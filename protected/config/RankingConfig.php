<?php
class RankingConfig 
{
    const DEFAULT_LEVEL = 'sum';
    const DEFAULT_ORDER = 'time';
    const PAGESIZE = 20; 
    
    public static $levels = array(
        'beg', 'int', 'exp', 'sum'
    );
    
    public static $orders = array(
        'time', '3bvs'
    );
    
    public static $order_direction = array(
        'time' => 0,
        '3bvs' => 1
    );
}