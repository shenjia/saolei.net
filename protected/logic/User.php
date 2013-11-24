<?php
require_once __DIR__.'/../models/UserModel.php';
require_once __DIR__.'/../logic/UserAuth.php';
require_once __DIR__.'/../logic/UserStat.php';
require_once __DIR__.'/../logic/UserInfo.php';
require_once __DIR__.'/../logic/UserScores.php';

class User
{
    public static function findById($userId) 
    {
        return UserModel::model()->findByPk($userId);
    }
    
    public static function isExists($userId) 
    {
        return (boolean) UserModel::model()->findByPk($userId,array(
            'select' => 'id'
        ));
    }
    
    public static function isLogin() 
    {
        return !Yii::app()->user->isGuest;
    }
    
    public static function getCurrentId() 
    {
        return Yii::app()->user->id;
    }
    
    public static function getCurrentUser() 
    {
        return self::getFullInfo(self::getCurrentId());
    }
    
    public static function getListInfo($ids) 
    {
        return empty($ids) ? array() : UserModel::model()->with('scores', 'scores_nf', 'stat')->findAllByPk($ids, array(
            'order' => Value::orderByIds($ids)
        )); 
    }
    
    public static function getFullInfo($id) 
    {
        return UserModel::model()->with('info', 'auth', 'scores', 'scores_nf', 'stat')->findByPk($id); 
    }
    
    public static function increaseLoginTimes($id) 
    {
        return UserStatModel::model()->updateCounters(
            array('login_times' => 1 ), 'id=:id', array(':id' => $id)
        );
    }
    
    public static function register($data) 
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (!User::init($data))         throw new Exception('init user failed');
            if (!UserAuth::init($data))     throw new Exception('init user auth failed');
            if (!UserInfo::init($data))     throw new Exception('init user info failed');
            if (!UserStat::init($data))     throw new Exception('init user stat failed');
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            return false;
        }   
    }
    
    public static function init(&$data) 
    {
        $user = new UserModel();
        foreach (array('chinese_name', 'english_name', 'area', 'sex') as $field) {
            $user->$field = $data[$field];
        }
        $user->create_time = time();
        if ($result = $user->save()) {
            $data['id'] = $user->id;
        }
        return $result;
    }
}