<?php
class TitleConfig 
{
    const LEVEL = 'sum';
    const ORDER = 'time';
    
    
    public static $titles = array(
        '大元帅', '元帅', '大将', '上将', '中将', '少将', '大校', '上校', '中校', '少校', '上尉', '中尉', '少尉', 
        '上士', '中士', '下士', '上等兵', '列兵'
    );
    
    public static $fixed_titles = array(
        '大元帅', '元帅', '大将', '上将', '中将', '少将'
    );
    
    public static $distribution = array(
        '大元帅' => 1,
        '元帅'   => 10,
        '大将'   => 30,
        '上将'   => 60,
        '中将'   => 100,
        '少将'   => 150,
        '大校'   => 0.03, 
        '上校'   => 0.07, 
        '中校'   => 0.12, 
        '少校'   => 0.18, 
        '上尉'   => 0.25, 
        '中尉'   => 0.33, 
        '少尉'   => 0.42, 
        '上士'   => 0.52, 
        '中士'   => 0.63, 
        '下士'   => 0.75, 
        '上等兵' => 0.88,
        '列兵'   => 1.00 // not actually use
    );
    
    public static $classes = array(
        '大元帅' => 'grand', 
        '元帅' => 'marshal', 
        '大将' => 'general', '上将' => 'general', '中将' => 'general', '少将' => 'general',
        '大校' => 'colonel', '上校' => 'colonel', '中校' => 'colonel', '少校' => 'colonel', 
        '上尉' => 'captain', '中尉' => 'captain', '少尉' => 'captain', 
        '上士' => 'sergeant', '中士' =>  'sergeant', '下士' => 'sergeant', 
        '上等兵' => 'private', '列兵' => 'private'
    );    
}