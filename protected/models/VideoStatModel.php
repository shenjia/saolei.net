<?php 
class VideoStatModel extends BaseModel
{
    protected static $unique_actions = array('click', 'download');
    
    public function tableName() 
    {
        return 'video_stat';
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