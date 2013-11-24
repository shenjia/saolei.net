<?php
require_once __DIR__.'/../config/define.php';
require_once __DIR__.'/../helpers/Logger.php';
class Avf
{
	/**
	 * 时间修正1秒
	 * @var number
	 */
	const SCORE_FIX = 0;
	
	/**
	 * 录像级别
	 */
	public static $levels = array(
		0 => 10, //beg
		1 => 40, //int
		2 => 99  //exp
	);
	
	/**
	 * 当前文件路径
	 */
	public static $filepath;
	
	/**
	 * 从avf文件中解析录像数据
	 * @param string $filepath
	 */
    public static function parse ( $filepath )
    {
        self::$filepath = $filepath;
        $contents = file_get_contents( $filepath );
    
        //Get the string in the video file that has game information
        preg_match("(\[[0123]\|)", $contents, $match, PREG_OFFSET_CAPTURE);
        $start = $match[0][1]; 
        $end = strpos($contents, ']');
        $piece = substr($contents, $start+1, $end-$start-1);
        $line = explode('|', $piece);
        $lines = explode("\r", $contents); 
        
        $parsed = array(
            'level'   => self::$levels[ $line[0] ],
            'version' => (substr($contents, 1, 1) == 1) ? '<=0.49' : trim(explode(' ', end($lines))[2], '.'),
            'real_time' => str_replace(',', '.', substr($piece, strrpos($piece, 'T') + 1)) - 1,
            'total_3bv' => substr($piece, strpos($piece, 'B') + 1, strpos($piece, 'T') - strpos($piece, 'B') - 1),
            'signature' => $lines[count($lines)-2] 
        );
        return self::verify( $parsed );
    }
    
    /**
	 * 检查解析的数据格式
	 * @param array $data
	 */
	public static function verify ( $data )
	{
	    if ( !in_array( $data[ 'level' ], self::$levels ) ) {
            Logger::warning('invalid level!', 100, array('filepath' => self::$filepath, 'level' => $vidlevel));
	        return false;
        }
	    return $data;
	}
    
}