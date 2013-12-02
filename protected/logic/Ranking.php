<?php
require_once __DIR__.'/../models/UserScoresModel.php';


class Ranking 
{
    public static function getPostion($userId, $level, $order) 
    {
        $field = $level . '_' . $order;
        $score = UserScoresModel::model()->findByPk($userId);
        if ($score->$field == 0) return -1;
         
        $operator = ($order == 'time') ? ' < ' : ' > ';
        return UserScoresModel::model()->count(
            $field . $operator . $score->$field . ' and ' . $field . ' > 0' 
        );
    }
    
    public static function getPage($userId, $level, $order, $limit) 
    {
        $position = self::getPostion($userId, $level, $order);
        if ($position < 0 ) return -1;
        $offset = $position - ($position % $limit);
        while ($users = self::users($level, $order, false, $offset, $limit)) {
            foreach ($users as $u) {
                if ($u->id == $userId) {
                    return $offset / $limit + 1;
                }
            }
            $offset += $limit;
        }
        return -1;
    }

    public static function users($level, $order, $nf, $offset, $limit) 
    {
        return UserScores::getHighScores($level, $order, $nf, $offset, $limit);
    }
    
}