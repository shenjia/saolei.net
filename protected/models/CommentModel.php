<?php
require_once 'BaseModel.php';

class CommentModel extends BaseModel
{
    public function tableName() 
    {
        return 'comment';
    }
    
    public function relations()
    {
        return array(
            'video'  => array(self::BELONGS_TO, 'VideoModel', 'id'),
            'author' => array(self::BELONGS_TO, 'UserModel', 'user'),
        );  
    }
}