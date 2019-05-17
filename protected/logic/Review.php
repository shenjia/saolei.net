<?php
require_once __DIR__.'/../logic/UserAuth.php';
require_once __DIR__.'/../logic/Video.php';
require_once __DIR__.'/../logic/UserScores.php';
require_once __DIR__.'/../logic/VideoScores.php';
require_once __DIR__.'/../models/VideoModel.php';

class Review 
{
    public static function video($userId, $videoId, $status = VideoConfig::STATUS_REVIEWED) 
    {
        // check role
        $role = UserAuth::getRole($userId);
        if (!UserAuth::isManager($role)) throw new FlashException('auth/failed');
        
        // check video
        $video = Video::getFullInfo($videoId);
        if (!$video) throw new FlashException('video/not_exist');
        if ($video->stat->downloads == 0) throw new FlashException('notice:video/review_blind');
        
        // check status
        if (!in_array($status, VideoConfig::$statuses)) throw new FlashException('video/invalid_status');

        // only administrator can review again
        if ($video->review_user && $role != UserConfig::ROLE_ADMINISTRATOR) throw new FlashException('auth/failed');
        
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (!VideoModel::model()->updateByPk($videoId, array(
            	'status' => $status,
                'review_user' => $userId,
                'review_time' => time(),
                'update_time' => time()
            )))  throw new Exception('update video status failed');
            switch ($status) {
                case VideoConfig::STATUS_BANNED:
                    if (!VideoScores::removeVideo($video)) throw new Exception('remove video scores failed');
                    break;
                case VideoConfig::STATUS_REVIEWED;
                    if (!VideoScores::insertVideo($video)) throw new Exception('insert video scores failed');
                    break;
                default:
                    break;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            Logger::warning('review failed', 100, array('message' => $e->getMessage()));
            $transaction->rollback();
            return false;
        }
    }
}