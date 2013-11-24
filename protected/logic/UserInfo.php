<?php
require_once __DIR__.'/../models/UserInfoModel.php';

class UserInfo 
{
    public static function findById($userId) 
    {
        return UserInfoModel::model()->findByPk($userId);
    }
    
    public static function init($data) 
    {
        $info = new UserInfoModel();
        $info->id = $data['id'];
        $info->create_time = time();
        return $info->save();        
    }   
}