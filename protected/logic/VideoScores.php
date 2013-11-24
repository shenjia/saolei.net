<?php
require_once __DIR__.'/../models/VideoScoresBegModel.php';
require_once __DIR__.'/../models/VideoScoresIntModel.php';
require_once __DIR__.'/../models/VideoScoresExpModel.php';
require_once __DIR__.'/../models/VideoScoresBegNFModel.php';
require_once __DIR__.'/../models/VideoScoresIntNFModel.php';
require_once __DIR__.'/../models/VideoScoresExpNFModel.php';
require_once __DIR__.'/../logic/UserScores.php';

class VideoScores 
{
    protected static $condition = array(
        'id'   => '1=1',
        'time' => 'score_time > 0',
        '3bvs' => 'score_3bvs > 0'
    );
    
    protected static $order_field = array(
        'id'   => 'id',
        'time' => 'score_time',
        '3bvs' => 'score_3bvs'
    );
    
    protected static $order_direct = array(
        'id'   => 'desc',
        'time' => 'asc',
        '3bvs' => 'desc'
    );
    
    public static function insertVideo(&$video) 
    {
        $updated = self::update(self::getClassName($video->level), $video);
        if (!$updated) return false;
        UserScores::insertVideo($video);
        
        if ($video->info->noflag) {
            $updated_nf = self::update(self::getClassName($video->level, true), $video);
            if (!$updated) return false;
            UserScores::insertVideo($video, true);
        }
        
        return true;
    }
    
    public static function removeVideo(&$video) 
    {
        $class = self::getClassName($video->level);
        if ($class::model()->findByPk($video->id)) {
            $removed = $class::model()->deleteByPk($video->id);
            if (!$removed) return false;
        }
        $updated = UserScores::removeVideo($video);
        if (!$updated) return false;
        
        if ($video->info->noflag) {
            $class_nf = self::getClassName($video->level, true);
            if ($class_nf::model()->findByPk($video->id)) {
                $removed_nf = $class_nf::model()->deleteByPk($video->id);
                if (!$removed_nf) return false;
            }
            $updated_nf = UserScores::removeVideo($video, true);
            if (!$updated_nf) return false;
        }
        return true;
    }
    
    public static function getHighScore($userId, $level, $order, $nf = false)
    {
        $class = self::getClassName($level, $nf);
        return $class::model()->find(array(
            'condition' => self::$condition[$order] . ' and user=' . intval($userId),
            'select'    => 'id, ' . self::$order_field[$order],
            'order'     => self::$order_field[$order] . ' ' . self::$order_direct[$order]
        ));
    }
    
    public static function listHighScores($level, $userId = null, $order, $offset = 0, $limit = PAGECOUNT) 
    {
        $class = self::getClassName($level);
        $field = self::$order_field[$order];
        return $class::model()->findAll(array(
            'condition' => self::$condition[$order] . ($userId ? ' and user=' . intval($userId) : ''),
            'select'    => 'id, ' . $field,
            'order'     => $field . ' ' . self::$order_direct[$order],
            'offset'    => $offset,
            'limit'     => $limit
        ));
    }
    
    public static function count($level, $userId = null) 
    {
        $class = self::getClassName($level);
        return $class::model()->count($userId ? 'user=' . intval($userId) : '');
    }
    
    public static function update($class, &$video) 
    {
        $scores = $class::model()->findByPk($video->id);
        if (!$scores) {
            $scores = new $class();
            $scores->id = $video->id;
            $scores->user = $video->user;
            $scores->create_time = $video->create_time;
        }
        $scores->score_time = $video->scores['time']; 
        $scores->score_3bvs = max($video->scores['3bvs'], 0);
        return $scores->save();        
    }
    
    protected static function getClassName($level, $nf = false)
    {
        return 'VideoScores' . ucfirst($level) . ($nf ? 'NF' : '') . 'Model';
    }
}
