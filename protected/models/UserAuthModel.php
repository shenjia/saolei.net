<?php
class UserAuthModel extends BaseModel
{
    public function tableName() 
    {
        return 'user_auth';
    }

    public function relations() 
    {
        return array(
            'user' => array(self::BELONGS_TO, 'UserModel', 'id')
        );
    }
    
    public function init() 
    {
        $this->salt = md5(rand());
    }
    
    public function getNeedUpgrade()
    {
        return !filter_var($this->username, FILTER_VALIDATE_EMAIL);
    }
}