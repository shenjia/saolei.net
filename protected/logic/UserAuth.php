<?php
require_once __DIR__.'/../models/UserAuthModel.php';
 
class UserAuth
{
    public static function getRole($id = null) 
    {
        if (is_null($id)) {
            return intval(Yii::app()->user->role);
        } else if ($auth = UserAuthModel::model()->findByPk($id)) {
            return (int) $auth->role;    
        } else {
            return UserConfig::ROLE_UNKNOWN;
        }
    }
    
    public static function isAdministrator($role = null) 
    {
        if (is_null($role)) $role = self::getRole();
        return $role == UserConfig::ROLE_ADMINISTRATOR;
    }
    
    public static function isManager($role = null) 
    {
        if (is_null($role)) $role = self::getRole();
        return $role == UserConfig::ROLE_ADMINISTRATOR
            || $role == UserConfig::ROLE_MANAGER;
    }
    
    public static function findByName($username) 
    {
        return UserAuthModel::model()->findByAttributes(array(
        	'username' => $username
        ));
    }
    
    public static function checkPassword($username, $password) 
    {
        if ($auth = self::findByName($username)) {
            return md5($password . $auth->salt) == $auth['password'];            
        } else {
            return false;
        }
    }
    
    public static function setPassword($username, $password) 
    {
        if ($auth = self::findByName($username)) {
            $auth->password = md5($password . $auth->salt);
            return $auth->save();
        } else {
            return false;
        }
    }
    
    public static function init($data) 
    {
        $auth = new UserAuthModel();
        $auth->id = $data['id'];
        $auth->username = $data['username'];
        $auth->password = md5($data['password'] . $auth->salt);
        $auth->create_time = time();
        return $auth->save();
    }
}