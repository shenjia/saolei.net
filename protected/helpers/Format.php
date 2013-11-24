<?php
class Format 
{
    public static function score_time($score, $negative = false) 
    {
        if ($score <= 0 && !$negative) return '';
        return sprintf("%.2f", $score / 1000);
    }
    
    public static function score_3bvs($score, $negative = false)
    {
        if ($score <= 0 && !$negative) return '';
        return sprintf("%.3f", $score / 1000);
    }
}