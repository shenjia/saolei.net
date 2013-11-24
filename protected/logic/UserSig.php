<?php
require_once __DIR__.'/../models/UserSigModel.php';

class UserSig
{
    public static function register($userId, $signature) 
    {
        $sig = UserSigModel::model()->findByAttributes(array('signature' => $signature));
        
        // new signature, register
        if (!$sig) {
            $sig = new UserSigModel();
            $sig->user = (int) $userId;
            $sig->signature = $signature;
            $sig->create_time = time();
            return $sig->save();
        }
        // old signature, check user
        else {
            return (int) $sig->user == (int) $userId;
        }
    }
}