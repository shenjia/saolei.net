<?php
class UserScoresModel extends BaseModel
{
    public function tableName() 
    {
        return 'user_scores';
    }
    
    public function relations()
    {
		return array(
		    'user'   => array(self::BELONGS_TO, 'UserModel', 'id'),
		);	
    }
    
    public function video($level, $score) 
    {
        $video = $level . '_' . $score . '_video';
        return isset($this->$video) ? $this->$video : null;
    }
    
    public function date($level, $score) 
    {
        if ($level == 'sum') {
            return $this->{'sum_' . $score} <= 0 ? null : max(
                $this->{'beg_' . $score . '_date'},
                $this->{'int_' . $score . '_date'},
                $this->{'exp_' . $score . '_date'} 
            );
        } else {
            $date = $level . '_' . $score . '_date';
            return isset($this->$date) ? $this->$date : null;
        }
        
    }
    
    public function score($level, $score, $raw = true) 
    {
        if ($raw) {
            return $this->{$level . '_' . $score};
        } else {
            $formatter = 'score_' . $score;
            return Format::$formatter($this->{$level . '_' . $score});
        }
    }
    
    public function resetScore($level, $score) 
    {
        $this->{$level . '_' . $score} = null;
        $this->{$level . '_' . $score . '_video'} = null;
        $this->{$level . '_' . $score . '_date'} = null;   
    }
    
    public function updateScore($level, $score, &$video) 
    {
        $this->{$level . '_' . $score} = $video->scores[$score];
        $this->{$level . '_' . $score . '_video'} = $video->id;
        $this->{$level . '_' . $score . '_date'} = $video->create_time;
    }
    
    public function updateSumScores() 
    {
        if ($this->beg_time > 0 && $this->int_time > 0 && $this->exp_time > 0) {
            $this->sum_time = $this->beg_time + $this->int_time + $this->exp_time;
        } else {
            $this->sum_time = 0;
        }
        if ($this->beg_3bvs > 0 && $this->int_3bvs > 0 && $this->exp_3bvs > 0) {
            $this->sum_3bvs = $this->beg_3bvs + $this->int_3bvs + $this->exp_3bvs;
        } else {
            $this->sum_3bvs = 0;
        }
    }
}