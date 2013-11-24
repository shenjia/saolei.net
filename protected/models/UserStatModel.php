<?php
class UserStatModel extends BaseModel
{
    protected static $unique_actions = array('click', 'download');
    
    public function tableName() 
    {
        return 'user_stat';
    }
    
    public function countVideos($level) 
    {
        return $level == 'all' 
            ? intval($this->beg_videos) + intval($this->int_videos) + intval($this->exp_videos)
            : intval($this->{$level . '_videos'});
    }
    
    
    public function uniqueAction($action = 'click') 
    {
        if (in_array($action, self::$unique_actions)) {
            $ip = Request::getIP();
            $count_ip = $action . 'er';
            $count_num = $action . 's';
            if ($this->$count_ip != $ip) {
                $this->$count_ip = $ip;
                $this->$count_num++;
                $this->save();    
            }
        }
    }
}