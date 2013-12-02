<?php
require_once __DIR__.'/../models/UserScoresModel.php';
require_once __DIR__.'/../models/UserScoresNFModel.php';
require_once __DIR__.'/../logic/VideoScores.php';

class UserScores 
{
    protected static $order_direct = array(
        'time' => 'asc',
        '3bvs' => 'desc'
    );
    
    public static function getDistributionScore($level, $order, $offset = 0, $direct = null) 
    {
        $field = $level . '_' . $order;
        $score = UserScoresModel::model()->find(array(
            'condition' =>  $field . ' > 0',
            'select'    => 'id, ' . $field,
            'order'     => $field . ' ' . ($direct ? $direct : self::$order_direct[$order]),
            'offset'    => $offset,
            'limit'     => 1
        ));
        return $score->$field;
    }
    
    public static function getHighScores($level, $order, $nf, $offset, $limit) 
    {
        $class = self::getClassName($nf);
        $field = $level . '_' . $order;
        return $class::model()->findAll(array(
            'condition' =>  $field . ' > 0',
            'select'    => 'id, ' . $field,
            'order'     => $field . ' ' . self::$order_direct[$order],
            'offset'    => $offset,
            'limit'     => $limit
        ));
    }
    
    public static function count($level, $order, $nf = false) 
    {
        $class = self::getClassName($nf);
        return $class::model()->count($level . '_' . $order . ' > 0');
    }
    
    public static function init($userId, $nf = false) 
    {
        $class = self::getClassName($nf);
        $scores = $class::model()->findByPk($userId);
        if (!$scores) {
            $scores = new $class();
            $scores->id = $userId;
            $scores->create_time = time();
        }
        foreach (VideoConfig::$levels as $level) {
            if ($score = VideoScores::getHighScore($userId, $level, 'time', $nf)) {
                $scores->updateScore($level, 'time', $score->video);                              
            } else {
                $scores->resetScore($level, 'time');
            }
            if ($score = VideoScores::getHighScore($userId, $level, '3bvs', $nf)) {
                $scores->updateScore($level, '3bvs', $score->video);                              
            } else {
                $scores->resetScore($level, '3bvs');
            }
        }
        $scores->updateSumScores();
        return $scores->save();
    }

    public static function removeVideo(&$video, $nf = false) 
    {
        $class = self::getClassName($nf);
        $scores = $class::model()->findByPk($video->user);
        if (!$scores) {
            $scores = new $class();
            $scores->id = $video->user;
            $scores->create_time = time();
        }
        // update time scores
        if ($scores->{$video->level . '_time_video'} == $video->id) {
            if ($video->id == VideoScores::getHighScore($video->user, $video->level, 'time')) {
                $scores->updateScore($video->level, 'time', $video);                              
            } else {
                $scores->resetScore($video->level, 'time');
            }
        }
        // update 3bvs scores
        if ($scores->{$video->level . '_3bvs_video'} == $video->id) {
            if ($video->id == VideoScores::getHighScore($video->user, $video->level, '3bvs')) {
                $scores->updateScore($video->level, '3bvs', $video);                              
            } else {
                $scores->resetScore($video->level, '3bvs');
            }
        }
        // update sum scores
        $scores->updateSumScores();
        
        return $scores->save();       
    }

    public static function insertVideo(&$video, $nf = false) 
    {
        $class = self::getClassName($nf);
        $scores = $class::model()->findByPk($video->user);
        if (!$scores) {
            $scores = new $class();
            $scores->id = $video->user;
            $scores->create_time = time();
        }
        $original = $scores->attributes;
        // update time scores
        if ($scores->{$video->level . '_time'} == 0 || $video->scores['time'] < $scores->{$video->level . '_time'}) {
            $scores->updateScore($video->level, 'time', $video);
        }
        // update 3bvs scores
        if ($scores->{$video->level . '_3bvs'} == 0 || $video->scores['3bvs'] > $scores->{$video->level . '_3bvs'}) {
            if ($video->scores['3bvs'] > 0) {
                $scores->updateScore($video->level, '3bvs', $video);
            }
        }
        // update sum scores and save
        $scores->updateSumScores();
        if (!$scores->save()) return false;
        
        // no news for NF
        if ($nf) return true;
        
        // publish newbie news
        if (($original['sum_time'] == 0) && ($scores->sum_time > 0)) {
            News::publish(NewsConfig::TYPE_NEWBIE, $video->author, null, array(), $video->review_time);
        } 
        // publish person record news
        else {
            foreach (VideoConfig::$levels as $level) {
                foreach (RankingConfig::$orders as $order) {
                    $score = $level . '_' . $order;
                    if ($scores->{$score . '_video'} == $video->id) {
                        News::publish(NewsConfig::TYPE_PERSON_RECORD, $video->author, $video->id, array(
                            'lv' => $level,
                            'od' => $order,
                            'or' => $original[$score],
                            'cr' => $scores->$score,
                            'nf' => intval($nf)
                        ), $video->create_time);    
                    }
                }
            }        
        }
        return true;
    }

    protected static function getClassName($nf = false)
    {
        return 'UserScores' . ($nf ? 'NF' : '') . 'Model';
    }
}