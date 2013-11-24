<?php
class VideoScoresModel extends BaseModel
{
    public function relations()
    {
		return array(
		    'video'   => array(self::BELONGS_TO, 'VideoModel', 'id'),
		);	
    }
}