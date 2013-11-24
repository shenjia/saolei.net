<?php
require_once 'BaseModel.php';
class VideoModel extends BaseModel
{
    public function tableName() 
    {
        return 'video';
    }
    
    public function relations()
    {
		return array(
		    'author'   => array(self::BELONGS_TO, 'UserModel', 'user'),
		    'reviewer' => array(self::BELONGS_TO, 'UserModel', 'review_user'),
			'info'     => array(self::HAS_ONE, 'VideoInfoModel', 'id'),
			'stat'     => array(self::HAS_ONE, 'VideoStatModel', 'id')
		);	
    }
    
    public function getScores()
    {
        return array(
            'time' => $this->info->real_time,
            '3bvs' => ($this->info->board_3bv > VideoConfig::MIN_3BV_FOR_3BVS ? '' : '-')
                    . intval($this->info->board_3bv * 1000000 / $this->info->real_time) 
        );
    }
    
    public function getFilename() 
    {
        $pathinfo = pathinfo($this->info->filepath);
        return '' 
            . '#' . $this->author->id
            . ' ' . $this->author->english_name
            . ' - ' .ucfirst($this->level) 
            . ' ' . Format::score_time($this->scores['time']) . 's' . ($this->info->noflag ? ' NF' : '')
            . ' - 3BV ' . $this->info->board_3bv . ''
            . ' (' . date('Y-m-d', $this->create_time) . ')'
            . '.' . $pathinfo['extension'];     
    }    
}