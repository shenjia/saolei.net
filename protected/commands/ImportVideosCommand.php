<?php
define('FROM_PATH', __DIR__.'/../../../history');
define('TO_PATH', __DIR__.'/../../videos');

class ImportVideosCommand extends CConsoleCommand
{
    private $tables = array(
        'video', 'video_comment', 'video_info', 'video_stat', 
        'video_scores_beg', 'video_scores_beg_nf',
        'video_scores_int', 'video_scores_int_nf',
        'video_scores_exp', 'video_scores_exp_nf',
        'video_scores_beg', 'video_scores_beg_nf',
    );
    
    public function actionIndex($args) 
    {
        foreach ($this->tables as $table) {
            Yii::app()->db->createCommand()->truncateTable($table);
        }
        
        $mysql = mysql_connect('127.0.0.1', Yii::app()->db->username, Yii::app()->db->password);
        mysql_select_db('saolei');
        
        $user_alias = array(
            '2027' => '2517'
        );
        
        $skip_ids = array( 
            '826', //time,
            '6989', //finish
            //duplicate
            '28569', '28638', '36081', '20984', '53135', '455', '13535', '21746', '22101', '21103', '18274', '34053', 
            '23838', '23667', '25656', '30417', '27527', '36672', '33584', '35003', '41629', '41450', '45562', '46855', 
            '52309', '47333', '59635', '24768', '57751', '72688', '62522', '61730', '63768', '62948', '62925', '68049',
            '64646', '71106', '64751', '80093', '77291', '81361', '78387',
            //wrong sign
            '12396',
            // missed file
            '81451', '78048', '67445', '67466', '67460'
        ); 
        
        $skip_authors = array(
            '6483', //aka
            '59', //aka
            '497', //aka
            '5319', //aka
            '5504', //block
            '6302', //block
            '6663', //aka
            '8485', //aka
            '1956', //aka
            '1456', //aka
            '1826', //aka
            '70', //aka
            '11772', //aka
            '2324', //aka
            '8111', //aka
            '855', //duplicate
            '3766', //aka
        );
        
        $problem_authors = array(
            '1150', //zhang die()
        );
        
        $skip_signs = array(
            'wangwei', //8799 : 6366
            'Zhang Fan', //1373 : 1295
            'lixiang', //6482 : 8405
            'zhouzhou', //3924 : 9150
        );
        
        $fileh = fopen(__DIR__.'/../../../database/mssql/video.txt', 'r');
        if ($fileh) {
            $line = fgets($fileh);
            $fields = explode("\t", $line);
            foreach ($fields as &$field) {
                $field = trim($field, "\'\\\r\n");
            }
            while ($line = fgets($fileh)) {
                $video = $this->parse($line, $fields);
                if (in_array($video['Video_Id'], $skip_ids)) continue;
                if (in_array($video['Video_Player'], $skip_authors)) continue;
                if (array_key_exists($video['Video_Player'], $user_alias)) {
                    $video['Video_Player'] = $user_alias[$video['Video_Player']];
                }
                if ($this->videoExists($video)) continue;
                if (!$this->checkUser($video['Video_Player'])) {
                    //error($video['Video_Id'] . "'s author " . $video['Video_Player'] . ' not exists');
                    continue;
                }
                if (isset($user_alias[$video['Video_Player']])) {
                    $video['Video_Player'] = $user_alias[$video['Video_Player']];
                }
                if ($video['Video_IsFreeze'] == 'True' ) continue;
                $filepath = FROM_PATH . $video['Video_Path'];
                if (file_exists($filepath)) {
                    $info = $this->get_video_info($filepath);
                    if (is_int($info)) {
                        if (!in_array($video['Video_Player'], $problem_authors)) {
                            $this->error($video['Video_Id'] . " parse failed. author [ " . $video['Video_Player'] . " ] " . $filepath);
                        }
                        continue;
                    }
                    if ($this->videoDuplicated($info)) {
                        $this->error($video['Video_Id'] . ' : already uploaded!! hash [' . $info['hash'] . ']');
                        continue;
                    }
                    /* 放过已上传的小3bv录像
                    if ((int)$info['3bv'] < VideoConfig::$level_min_3bv[$info['level']]) {
                        error($video['Video_Id'] . ' : 3bv too small!! level [' . $info['level'] . ']' . ' 3bv[' . $info['3bv'] . ']');
                        continue;   
                    }
                    */
                    if (!$this->insertUserSig($video, $info) && !in_array($info['player'], $skip_signs)) {
                        $this->error($video['Video_Id'] . ' : invalid signature!' . $info['player']);
                        continue;
                    }
                    //echo date('Y-m-d',$info['create_time']).' / '.$video['Video_Time'].PHP_EOL;
                    //echo $video['Video_Playero_Id'] . " ok!" . PHP_EOL;
                    //var_dump($video,$info);die();
                    $this->insertVideo($video, $info);
                    $this->insertVideoInfo($video, $info);
                    $this->insertVideoStat($video, $info);
                    $this->insertVideoScore($video, $info);
                    $this->increaseUserVideoStat($video, $info);
                    if ($info['noflag']) $this->insertVideoScore($video, $info, true);
                    echo '.';
                    continue;
                } else {
                    $this->error($video['Video_Id'] . " file missed. " . $filepath);
                    continue;
                }
            }
            unset($line);
            fclose($fileh);
        }
        echo 'done' . PHP_EOL;
    }
    
    function parse(&$line, &$fields) 
    {
        $data = array();
        $parts = explode("\t", $line);
        foreach ($fields as $i => $field) {
            //echo $field . ' : ' . $parts[$i] . PHP_EOL;
            $data[$field] = trim($parts[$i], "'");
        }
        return $data;
    }
    
    function insertVideo(&$video, &$info)
    {
        $sql = sprintf("insert video (id, level, user, hash, status, review_user, review_time, create_time) 
                             values (%d, '%s', %d, '%s', %d, %d, %d, %d)",
            $id = (int) $video['Video_Id'],
            $level = $info['level'],
            $user = (int) $video['Video_Player'],
            $hash = $info['hash'],
            $status = $video['Video_Check']=='True' ? VideoConfig::STATUS_REVIEWED : VideoConfig::STATUS_NORMAL,
            $review_user = $video['Video_Check'] == 'True' ? $video['Video_CheckBy'] : NULL,
            $review_time = $video['Video_Check'] == 'True' ? $this->timestamp($video['Video_CheckTime']) : NULL,
            $create_time = $this->timestamp($video['Video_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) $this->error('insert video error!' . $sql);
    }
    
    function insertVideoInfo(&$video, &$info)
    {
        $sql = sprintf("insert video_info (id, filepath, signature, software, version, noflag, board, board_3bv, real_time, create_time) 
                             values (%d, '%s', '%s', '%s', '%s', %d, '%s', %d, %d, %d)",
            $id = (int) $video['Video_Id'],
            $filepath = $this->copyVideoFile($video, $info),
            $signature = addslashes($info['player']),
            $software = $info['program'],
            $version = $info['version'],
            $noflag = $info['noflag'],
            $board = $info['board'],
            $board_3bv = $info['3bv'],
            $real_time = intval($info['time'] * 1000),
            $create_time = $this->timestamp($video['Video_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) $this->error('insert video info error!' . $sql);
    }
    
    function insertVideoScore(&$video, &$info, $nf = false)
    {
        $sql = sprintf("insert video_scores_%s (id, user, score_time, score_3bvs, create_time) 
                             values (%d, %d, %d, %d, %d)",
            $info['level'] . ($nf ? '_nf' : ''),
            $id = (int) $video['Video_Id'],
            $user = (int) $video['Video_Player'],
            $score_time = intval($info['time'] * 1000),
            $score_3bvs = ((int)$info['3bv'] >= VideoConfig::MIN_3BV_FOR_3BVS) ? intval(($info['3bv'] / $score_time) * 1000000) : 0,
            $create_time = $this->timestamp($video['Video_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) $this->error('insert video score error!' . $sql);
    }
    
    function insertVideoStat(&$video, &$info)
    {
        $sql = sprintf("insert video_stat (id, clicks, clicker, comments, create_time) 
                             values (%d, %d, '%s', %d, %d)",
            $id = (int) $video['Video_Id'],
            $clicks = (int) $video['Video_Click'],
            $clicker = $video['Video_Click_IP'],
            $comments = 0,
            $create_time = $this->timestamp($video['Video_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) $this->error('insert video stat error!' . $sql);
    }
    
    function insertUserSig(&$video, &$info) 
    {
        $sql = sprintf("select * from user_sig where signature='%s'", addslashes($info['player']));
        $ret = mysql_query($sql);
        $sig = mysql_fetch_array($ret);
        if ($sig != null) {
            if ($sig['user'] == (int) $video['Video_Player']) {
                return true;
            } else {
                return false;
            }
        } else {
            $sql = sprintf("insert user_sig (user, signature, create_time) 
            					 values (%d, '%s', %d)",
                $user = (int) $video['Video_Player'],
                $signature = addslashes($info['player']),
                $create_time = $this->timestamp($video['Video_Time'])
            );
            $ret = mysql_query($sql);
            if ($ret) {
                return true;
            } else {
                $this->error($video['Video_Id'] . ' : insert user_sig error!');
                return false;
            }
    
        }
    }
    
    function increaseUserVideoStat(&$video, &$info) 
    {
        $sql = sprintf("update user_stat set %s_videos=%s_videos+1 where id=%d",
                $info['level'], $info['level'], $video['Video_Player']);
        $ret = mysql_query($sql);
        if (!$ret) $this->error('increase use video stat error!' . $sql);
    }
    
    function videoExists($video)
    {
        $sql = sprintf("select count(*) as count from video where id=%d",
                        intval($video['Video_Id']));
        $ret = mysql_query($sql);
        $ass = mysql_fetch_assoc($ret);
        return $ass['count'] == '1';
    }
    
    function videoDuplicated($info)
    {
        $sql = sprintf("select count(*) as count from video where hash='%s'",
                        $info['hash']);
        $ret = mysql_query($sql);
        $ass = mysql_fetch_assoc($ret);
        return $ass['count'] == '1';
    }
    
    function copyVideoFile($video, $info)
    {
        $time = $this->timestamp($video['Video_Time']);
        $pathinfo = pathinfo($video['Video_Path']);
        $from = FROM_PATH . addcslashes($video['Video_Path'], ' ()');
        $dir = '/' . date('Y', $time) . '/' . date('m', $time) . '/' . date('d', $time);
        $file =  $info['hash'] . '.' . $pathinfo['extension'];
        if (!file_exists(TO_PATH . $dir)) {
            mkdir(TO_PATH . $dir, 0755, true);
        }
        $to = TO_PATH . $dir . '/' . $file;
        $result = `cp $from $to 2>&1`;
        if ($result) {
            $this->error($video['Video_Id'] . ' : ' . $result);
        }
        return $dir . '/' . $file;
    }
    
    function checkUser($id)
    {
        $sql = sprintf("select count(*) as count from user where id=%d", $id);
        $res = mysql_query($sql);
        $ass = mysql_fetch_assoc($res);
        return $ass['count'] == '1';
    }
    
    function timestamp($string) 
    {
        $parts = explode('.', $string);
        return strtotime($parts[0]);   
    }
    
    function get_video_info($filepath)
    {
        return Parser::parse($filepath);
    }
    
    function error($msg)
    {
        echo PHP_EOL . $msg . PHP_EOL;
    }
}