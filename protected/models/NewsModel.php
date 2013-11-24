<?php
require_once 'BaseModel.php';

class NewsModel extends BaseModel
{
    public function tableName() 
    {
        return 'news';
    }
    
    public function relations()
    {
        return array(
            'author' => array(self::BELONGS_TO, 'UserModel', 'user'),
        );  
    }
    
    public function getDetails() 
    {
        return json_decode($this->details_data);        
    }
    
    public function setDetails($details)
    {
        $this->details_data = json_encode($details);
    }
    
}