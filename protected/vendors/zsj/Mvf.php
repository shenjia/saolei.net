<?php
require_once __DIR__.'/../../config/define.php';
require_once __DIR__.'/../../helpers/Logger.php';
class Mvf
{
	/**
	 * 时间修正1秒
	 * @var number
	 */
	const SCORE_FIX = 1;
	
	/**
	 * 录像级别
	 */
	public static $levels = array(
		1 => 10, //beg
		2 => 40, //int
		3 => 99  //exp
	);

	/**
	 * 各级别雷区占用字节
	 */
	public static $spaces = array(
		10 => 25, //beg
		40 => 85, //int
		99 => 203 //exp
	);
	
	/**
	 * 当前文件路径
	 */
	public static $filepath;
	
	/**
	 * 从mvf文件中解析录像数据
	 * @param string $filepath
	 */
	public static function parse ( $filepath )
	{
	    self::$filepath = $filepath;
		$raw = file_get_contents( $filepath );
		
		/*
		$year	= self::parseNumber( $raw, 76, 2 );
		$month	= self::parseNumber( $raw, 74, 1 );
		$day	= self::parseNumber( $raw, 75, 1 );
		$hour	= self::parseNumber( $raw, 78, 1 );
		$minute = self::parseNumber( $raw, 79, 1 );
		$second = self::parseNumber( $raw, 80, 1 );
		*/
		
		$parsed = array(
			//'create_time'   => strtotime( $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second ),
			'level' 		=> $level = self::$levels[ self::parseNumber( $raw, 81, 1 ) ],
		    'version'		=> '0.97', 
			'real_time'     => self::parseNumber( $raw, 83, 2 ) + self::parseNumber( $raw, 85, 1 ) / 100 + self::SCORE_FIX,
			'model'			=> self::parseNumber( $raw, 82, 1 ),
			'total_3bv'		=> self::parseNumber( $raw, 86, 2 ),
			'finish_3bv'	=> self::parseNumber( $raw, 88, 2 ),
			'click_left'	=> self::parseNumber( $raw, 90, 2 ),
			'click_double'	=> self::parseNumber( $raw, 92, 2 ),
			'click_right'	=> self::parseNumber( $raw, 94, 2 ),
			'signature'		=> substr( $raw, 97 + self::$spaces[ $level ], self::parseNumber( $raw, 96 + self::$spaces[ $level ], 1) )
		);
		return self::verify( $parsed );
	}
	
	/**
	 * 检查解析的数据格式
	 * @param array $data
	 */
	public static function verify ( $data )
	{
	    //级别异常
	    if ( !in_array( $data[ 'level' ], self::$levels ) ) {
	        Logger::warning('invalid level!', 100, array('filepath' => self::$filepath, 'level' => $data['level']));
	        return false;
	    }
	    //非classical模式
	    if ( $data[ 'model' ] != 1 ) {
	        Logger::warning('not classical mode!', 100, array('filepath' => self::$filepath, 'model' => $data['model']));
	        return false;
	    }
	    //标示错误
	    if ( !$data[ 'signature' ] ) {
	        Logger::warning('no signatrue!', 100, array('filepath' => self::$filepath));
	        return false;   
	    }
	    //未完成游戏
	    if ( $data['total_3bv'] != $data['finish_3bv'] ) {
	        Logger::warning('not finished yet!', 100, array('filepath' => self::$filepath, '3bv' => $data['finish_3bv'] . '/' . $data['total_3bv']));
	        return false;   
	    }
	    return array(
	        'level'       => $data['level'],
	        'real_time'   => intval($data['real_time'] * 1000),
	        'board_3bv'   => $data['total_3bv'],
	        'signature'   => $data['signature'],
	        'flag'        => $data['click_right'] > 0,
	        'software'    => SOFTWARE_CLONE,
	        'version'	  => '0.97'
	    );
	}

	/**
	 * 从指定位置取数字
	 * @param string $raw
	 * @param number $location
	 * @param number $length
	 */
	private static function parseNumber ( $raw, $location, $length)
	{
		return hexdec(bin2hex(substr($raw, $location, $length)));
	}
}