<?php
class Format 
{
    public static function score_time($score) 
    {
        return $score > 0 ? sprintf("%.2f", $score / 1000) : '';
    }
    
    public static function score_3bvs($score) 
    {
        return $score > 0 ? sprintf("%.3f", $score / 1000) : '';
    }
}