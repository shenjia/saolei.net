<?php
class NewsConfig 
{
    const TYPE_NOTICE        = 0;
    
    const TYPE_NEWBIE        = 10;
    
    const TYPE_PERSON_RECORD = 20;
    const TYPE_AREA_RECORD   = 21;
    const TYPE_NATION_RECORD = 22;
    
    const TYPE_VIDEO         = 30;
    const TYPE_ARTICLE       = 40;
    
    const PAGESIZE = 20;
    
    public static $types = array(
        self::TYPE_NOTICE        => 'notice',
        self::TYPE_NEWBIE        => 'newbie',
        self::TYPE_PERSON_RECORD => 'person_record',
        self::TYPE_AREA_RECORD   => 'area_record',
        self::TYPE_NATION_RECORD => 'nation_record',
        self::TYPE_VIDEO         => 'video',
        self::TYPE_ARTICLE       => 'article'
    );
}