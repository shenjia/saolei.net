<?php
require_once __DIR__.'/../models/VideoStatModel.php';

class VideoStat 
{
    public static function getStat($videoId) 
    {
        return VideoStatModel::model()->findByPk($videoId);
    }

    public static function init($data) 
    {
        $parsed = $data['parsed'];
        $stat = new VideoStatModel();
        $stat->id = $data['id'];
        $stat->clicks = 0;
        $stat->comments = 0;
        $stat->create_time = time();
        return $stat->save();        
    }
}
