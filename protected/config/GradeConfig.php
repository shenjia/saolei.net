<?php
class GradeConfig 
{
    public static $grades = array(
        'SSS', 'SS', 'S', 'A', 'B', 'C', 'D', 'E', 'F'
    );
    
    public static $percents = array(
        100, 90, 80, 70, 60, 50, 35, 15, 5
    );
    
    public static $fixed_grades = array(
        'SSS', 'SS', 'S'
    );
    
    public static $distribution = array(
        'SSS' => 1, 
        'SS'  => 7, 
        'S'   => 21,
        'A'   => 0.05,
        'B'   => 0.14,
        'C'   => 0.28,
        'D'   => 0.51,
        'E'   => 0.89,
        'F'   => 1.00 //not actually use
    );
}