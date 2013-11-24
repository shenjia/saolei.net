<?php
require_once __DIR__.'/../models/UserStatModel.php';

class UserStat
{
    public static function init($data) 
    {
        $stat = new UserStatModel();
        $stat->id = $data['id'];
        $stat->create_time = time();
        return $stat->save();        
    }   
}