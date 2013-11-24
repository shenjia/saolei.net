<?php
require_once __DIR__.'/../models/VideoModel.php';
require_once __DIR__.'/../logic/VideoScores.php';
require_once __DIR__.'/../logic/VideoStat.php';
require_once __DIR__.'/../logic/User.php';

class Video
{
    public static function findByStatus($status, $offset, $limit) 
    {
        return VideoModel::model()->findAll(array(
            'condition' => 'status=' . $status,
            'select'    => 'id',
            'order'     => 'id ' . ($status == VideoConfig::STATUS_NORMAL ? 'asc' : 'desc'),
            'offset'    => $offset, 
            'limit'     => $limit
        ));
    }
    
    public static function getFullInfo($id) 
    {
        return VideoModel::model()->with('author', 'reviewer', 'info', 'stat')->findByPk($id);
    }
    
    public static function getListInfo($ids) 
    {
        return empty($ids) ? array() : VideoModel::model()->with('author', 'info', 'stat')->findAllByPk($ids, array(
            'order' => Value::orderByIds($ids)
        ));
    }
    
    public static function listRecentVideos($userId = null, $offset = 0, $limit = PAGECOUNT) 
    {
        return VideoModel::model()->findAll(array(
            'condition' => $userId ? 'user=' . intval($userId) : '',
            'select'    => 'id',
            'order'     => 'id desc',
            'offset'    => $offset, 
            'limit'     => $limit
        ));
    }
    
    public static function count($condition = '') 
    {
        return VideoModel::model()->count($condition);
    }
    
    public static function upload($userId) 
    {
        // get upload file
        $uploader = new Uploader(UPLOAD_TEMP_PATH);
        $filePath = $uploader->getUploadFile();
        if (!$filePath) {
            Logger::warning('file not exists!', 100, array('file' => $filePath));
            $uploader->error(103, '上传失败');   
        }

        // check md5 hash if exists
        $hash = md5(file_get_contents($filePath));
        if (Video::hashExists($hash)) {
            Logger::warning('already uploaded!', 100, array('uploader' => $userId, 'hash' => $hash));
            $uploader->error(104, '此录像已被上传过');  
        } 

        // get parsed info
        $parsed = Parser::parse($filePath);
        if (is_int($parsed)) $uploader->error(105, Yii::t('error', $parsed));
        
        // check 3bv
        if ($parsed['3bv'] < VideoConfig::$level_min_3bv[$parsed['level']]) {
            Logger::warning('3bv too small!', 100, array('uploader' => $userId, 'level' => $parsed['level'], '3bv' => $parsed));
            $uploader->error(105, '该录像的3BV小于' . VideoConfig::$level_min_3bv[$parsed['level']]);   
        }
        
        // check signature
        if (!UserSig::register($userId, $parsed['player'])) {
            Logger::warning('wrong signature!', 100, array('uploader' => $userId, 'sign' => $parsed['player']));
            $uploader->error(106, '该录像标示已被他人注册');
        }
        
        // save video
        $data = array(
            'user' => $userId,
            'filepath' => $filePath,
            'level' => $parsed['level'],
            'hash' => $hash,
            'parsed' => $parsed
        );
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (!Video::saveFile($data))   throw new Exception('save video failed');
            if (!Video::init($data))       throw new Exception('init video failed');
            if (!VideoInfo::init($data))   throw new Exception('init video info failed');
            if (!VideoStat::init($data))   throw new Exception('init video stat failed');
            $transaction->commit();
            $uploader->success();
        } catch (Exception $e) {
            Logger::warning('insert video failed', 100, array('message' => $e->getMessage()));
            $transaction->rollback();
            $uploader->error(106, '录像保存失败');
        }
    }   

    public static function hashExists($hash) 
    {
        return (boolean) VideoModel::model()->findByAttributes(array('hash' => $hash));
    }
    
    public static function saveFile(&$data) 
    {
        $targetDir = VIDEO_PATH . '/' . date('Y/m/d');
        if (!file_exists($targetDir)) {
            @mkdir($targetDir, 0755, true);
        } 
        $filePath = '/' . date('Y/m/d') . '/' . $data['hash'] . '.avf';
        $copyed = @copy($data['filepath'], VIDEO_PATH . $filePath);
        $data['filepath'] = $filePath;
        if (!$copyed){
            Logger::warning('save video failed', 100, array('from' => $data['filepath'], 'to' => UPLOAD_PATH . $filePath));
            return false;
        }
        return true;
    }
    
    public static function init(&$data) 
    {
        $video = new VideoModel();
        $video->level = $data['level'];
        $video->user = $data['user'];
        $video->hash = $data['hash'];
        $video->status = VideoConfig::STATUS_NORMAL;
        $video->create_time = time();
        if ($result = $video->save()) {
            $data['id'] = $video->id;
        }
        return $result;
    }
}