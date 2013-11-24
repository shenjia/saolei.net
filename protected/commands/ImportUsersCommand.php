<?php
class ImportUsersCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        foreach (array('user', 'user_auth', 'user_info', 'user_scores', 'user_scores_nf', 'user_sig', 'user_stat') as $table) {
            Yii::app()->db->createCommand()->truncateTable($table);
        }
        
        $mysql = mysql_connect('127.0.0.1', 'local', 'locallocal');
        mysql_select_db('saolei');
        
        $skip_ids = array(    
            '8160', '6542', '2164', '2198', '2199', '2494', '2495',
            '2498', '2499', '2500', '2501', '497', '10074', '10212', '10217', '10669',
            '2175', '1902', '2044', '3179', '3173', '3188', '3191', '3200', '3205', '3206',
            '3773', '3830', '3979', '4159', '4373', '4606', '4709', '5383', '5532', '5090',
            '6211', '6230', '6035', '5197', '5674', '6494', '6922', '6721', '9182', '6750',
            '8566', '8571', '7376', '7392', '7566', '8083', '7906', '8379', '8380', '8381',
            '8279', '7718', '8885', '9894', '9901', '9380', '9028', '9843', '9607',
            '464', '855', '8572', '10055', '10424', '1313', '9643', '11050', '10928',
            '10927', '11278', '11295', '11385', '12157', '12169', '12203', '11424', '11483',
            '11518', '11392', '11645', '12272', '12299', '12300', '12286', '12287', '12304',
            '11787', '70', '2324', '8111', '1972', '6718', '2045', '2037', '5499', '5232', '5804',
            '8681', '8582', '9303', '11376', '11600', '3130'
        ); 
        
        $fileh = fopen(__DIR__.'/../../../database/mssql/player.txt', 'r');
        if ($fileh) {
            $line = fgets($fileh);
            $fields = explode("\t", $line);
            foreach ($fields as &$field) {
                $field = trim($field, "\'\\\r\n");
            }
            while ($line = fgets($fileh)) {
                $user = $this->parse($line, $fields);
                if (in_array($user['Player_Id'], $skip_ids)) continue;
                if ($user['Player_IsLive'] == 'False' ) continue;
                $this->insertUser($user);
                $this->insertUserInfo($user);
                $this->insertUserAuth($user);
                $this->insertUserStat($user);
                echo '.';
                //echo $user['Player_Id'] . ' : ' . $user['Player_Name_Chinese'] . PHP_EOL;
            }
            unset($line);
            fclose($fileh);
        }
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
    
    function insertUser($user) 
    {
        $sql = sprintf("insert user (id, chinese_name, english_name, area, sex, avatar, create_time) 
        					 values (%d, '%s', '%s', '%s', %d, '%s', %d)",
            $id = (int) $user['Player_Id'],
            $chinese_name = $user['Player_Name_Chinese'],
            $english_name = $user['Player_Name_English'],
            $area = $user['Player_Area'],
            $sex = intval($user['Player_Sex'] == 'True'),
            $avatar = intval(trim($user['Player_Image'])=='True'),
            $create_time = $this->timestamp($user['Player_Register_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) die('insert user error!' . $sql);
    }
    
    function insertUserInfo($user) 
    {
        if ($user['Player_Year'] != 0) {
            $birthday = strtotime($user['Player_Year'] . '-' . str_pad($user['Player_Month'], 2, '0', STR_PAD_LEFT) . '-01');
        } else {
            $birthday = 0;
        }
        $sql = sprintf("insert user_info (id, qq, nickname, birthday, mouse, pad, self_intro, interest, create_time) 
        					 values (%d, '%s', '%s', %d, '%s', '%s', '%s', '%s', %d)",
            $id = (int) $user['Player_Id'],
            $qq = $user['Player_QQ'],
            $nickname = addslashes($user['Player_Name_Net']),
            $birthday,
            $mouse = addslashes($user['Player_Mouse'] ? $user['Player_Mouse'] : '杂牌'),
            $pad = addslashes($user['Player_Pad'] ? $user['Player_Pad'] : '杂牌'),
            $self_intro = '',
            $interest = addslashes($user['Player_Interest'] == '无' ? '' : $user['Player_Interest']),
            $create_time = $this->timestamp($user['Player_Register_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) die('insert user info error!' . $sql);
    }
    
    function insertUserAuth($user) 
    {
        $salt = md5(rand());
        if ($user['Player_IsMaster'] == 'True') {
            $role = $user['Player_Id'] == 1 ? UserConfig::ROLE_ADMINISTRATOR : UserConfig::ROLE_MANAGER;    
        } else {
            $role = UserConfig::ROLE_PLAYER;
        }
        $sql = sprintf("insert user_auth (id, username, password, salt, role, create_time) 
        					 values (%d, '%s', '%s', '%s', %d, %d)",
            $id = (int) $user['Player_Id'],
            $username = $user['Player_Name'],
            $password = md5($user['Player_Password'] . $salt),
            $salt,
            $role,
            $create_time = $this->timestamp($user['Player_Register_Time']) 
        );
        $ret = mysql_query($sql);
        if (!$ret) die('insert user auth error!' . $sql);
    }
    
    function insertUserStat($user) 
    {
        $sql = sprintf("insert user_stat (id, login_time, login_times, login_ip, points, clicks, beg_videos, int_videos, exp_videos, create_time) 
        					 values (%d, '%s', %d, '%s', %d, %d, %d, %d, %d, %d)",
            $id = (int) $user['Player_Id'],
            $login_time = $this->timestamp($user['Player_Login_Time']),
            $login_times = 0,
            $login_ip = $user['Player_IP'],
            $points = 0,
            $clicks = (int) $user['Player_Click'],
            $beg_videos = 0,
            $int_videos = 0,
            $exp_videos = 0,
            $create_time = $this->timestamp($user['Player_Register_Time'])
        );
        $ret = mysql_query($sql);
        if (!$ret) die('insert user stat error!' . $sql);
    }
    
    function insertUserSig($user) 
    {
        $sql = sprintf("insert user_sig (user, signature, create_time) 
        					 values (%d, '%s', %d)",
            $user = (int) $user['Player_Id'],
            $signature = addslashes($user['Player_Text']),
            $create_time = $this->timestamp($user['Player_Register_Time'])
        );
        $ret = mysql_query($sql);
        if (!$ret) echo $user['Player_Id'] . ' : ' . $user['Player_Text'] . PHP_EOL;
        //if (!$ret) die('insert user stat error!' . $sql);
    }
    
    function timestamp($string) 
    {
        $parts = explode('.', $string);
        return strtotime($parts[0]);   
    }
}