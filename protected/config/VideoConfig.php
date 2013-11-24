<?php
class VideoConfig 
{
    const STATUS_BANNED   = 0;
    const STATUS_NORMAL   = 10;
    const STATUS_REVIEWED = 20;
    
    const MIN_3BV_FOR_3BVS = 4;
    
    public static $statuses = array(
        self::STATUS_BANNED,
        self::STATUS_NORMAL,
        self::STATUS_REVIEWED
    );
    
    public static $levels = array(
        'beg', 'int', 'exp'
    );
    
    public static $level_min_3bv = array(
        'beg' => 2,
        'int' => 30,
        'exp' => 100
    );
}