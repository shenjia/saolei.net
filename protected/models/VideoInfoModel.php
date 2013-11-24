<?php 
class VideoInfoModel extends BaseModel
{
    public function tableName() 
    {
        return 'video_info';
    }
    
    public function hashExists($hash) 
    {
        return (boolean) self::model()->findByAttributes(array('hash'=>$hash));
    }
}