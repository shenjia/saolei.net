<?php
class Assess 
{
    public static function title($score) 
    {
        if ($score == 0) return '';
        
        $distribution = Distribution::get('title');
        $direction = RankingConfig::$order_direction[TitleConfig::ORDER];
        $position = self::getPosition($score, $distribution, $direction);
        
        return TitleConfig::$titles[$position];
    }
    
    
    public static function grade($level, $order, $score) 
    {
        if ($score == 0) return '?';
        
        $distribution = Distribution::get($level . '_' . $order);
        $direction = RankingConfig::$order_direction[$order];
        $position = self::getPosition($score, $distribution, $direction);
        
        // return SSS and F immediately
        if ($position == 0 || $position == count(GradeConfig::$grades) - 1) return GradeConfig::$grades[$position]; 
        
        // and + / - for SS S A B C D E
        $max_score = $distribution[$position - 1] - 1;
        $min_score = $distribution[$position];
        $range_score = abs($max_score - $min_score);
        
        $offset = abs($max_score - $score);
        $rate = $offset / $range_score;
        
        if ($rate > 0.6666) {
            return GradeConfig::$grades[$position] . '-';   
        } else if ($rate < 0.3333) {
            return GradeConfig::$grades[$position] . '+';
        } else {
            return GradeConfig::$grades[$position];
        }
    }
    
    public static function percent($level, $order, $score) 
    {
        if ($score == 0) return 0;
        
        $distribution = Distribution::get($level . '_' . $order);
        $direction = RankingConfig::$order_direction[$order];
        $position = self::getPosition($score, $distribution, $direction);
        if ($position == 0) return GradeConfig::$percents[$position];

        $max_percent = GradeConfig::$percents[$position - 1] - 1;
        $min_percent = GradeConfig::$percents[$position];
        $range_percent = $max_percent - $min_percent; 
        
        $max_score = $distribution[$position - 1] - 1;
        $min_score = $distribution[$position];
        $range_score = abs($max_score - $min_score);
        
        $offset = abs($max_score - $score);
        $fix = intval($offset * $range_percent / $range_score);
        
        return max($max_percent - $fix, 0);
    }
    
    private static function getPosition($score, $distribution, $direction) 
    {
        foreach ($distribution as $i => $threshold) {
            if ($direction) {
                if ($score >= $threshold) return $i;  
            } else {
                if ($score <= $threshold) return $i;
            }
        }
        return $i + 1;
    }
}