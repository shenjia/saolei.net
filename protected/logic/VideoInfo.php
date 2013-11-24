<?php
require_once __DIR__.'/../models/VideoInfoModel.php';

class VideoInfo 
{
    public static function init($data) 
    {
        $parsed = $data['parsed'];
        $info = new VideoInfoModel();
        $info->id = $data['id'];
        $info->filepath = $data['filepath'];
        $info->signature = $parsed['player'];
        $info->software = $parsed['program'];
        $info->version = $parsed['version'];
        $info->noflag = $parsed['noflag'];
        $info->board = $parsed['board'];
        $info->board_3bv = $parsed['3bv'];
        $info->real_time = intval($parsed['time'] * 1000);
        $info->create_time = time();
        return $info->save();        
    }
}
