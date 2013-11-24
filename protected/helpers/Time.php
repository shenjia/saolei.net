<?php
class Time
{
	const NEVER = 3153600000;
	
    const SECONDS_PER_SECOND = 1;
    const SECONDS_PER_MINUTE = 60;
	const SECONDS_PER_HOUR   = 3600;
	const SECONDS_PER_DAY    = 86400;
	const SECONDS_PER_WEEK   = 604800;
	const SECONDS_PER_MONTH  = 2592000;
	const SECONDS_PER_YEAR   = 31536000;
	
	const MINUTES_PER_HOUR = 60;
	const MINUTES_PER_DAY = 1440;
	const MINUTES_PER_WEEK = 10080;
	const MINUTES_PER_MONTH = 43200;
	const MINTUES_PER_YEAR = 525600;
	
	const HOURS_PER_DAY = 24;
	const HOURS_PER_WEEK = 168;
	const HOURS_PER_MONTH = 720;
	const HOURS_PER_YEAR = 8760;
	
	const DAYS_PER_WEEK = 7;
	const DAYS_PER_MONTH = 30;
	const DAYS_PER_YEAR = 365;
	
	const WEEKS_PER_MONTH = 4;
	const WEEKS_PER_YEAR = 52;
	
	const MONTHS_PER_YEAR = 12;
	
	public static $unitNames = array( 
		'year'   => '年', 
		'month'  => '月', 
		'day'    => '天', 
		'hour'   => '小时', 
		'minute' => '分钟', 
		'second' => '秒' 
	);
	public static $unitCodes = array(
		'year'   => 'Y',
		'month'  => 'm',
		'day'	 => 'd',
		'hour' 	 => 'H', 
		'minute' => 'i',
		'second' => 's',
	);
	public static $weekdays = array( '日', '一', '二', '三', '四', '五', '六' );
	
	/**
	 * 显示相对时间
	 * @param timpstamp $time
	 * @param number $max
	 */
	public static function opposite ( $time, $max = self::SECONDS_PER_DAY, $format = 'Y年n月j日 H:i' )
	{
	    $now = time();
	    
	    if ( abs( $time - $now ) > $max ) return date( $format, $time );
	    
	    if ( $time > $now ) {
	        list( $now, $time ) = array( $time, $now );
	        $direction =  '后';
	    } else {
	        $direction = '前';
	    }
	    
		$min_dis = floor( ( $now - $time ) / self::SECONDS_PER_MINUTE );
		$hor_dis = floor( ( $now - $time ) / self::SECONDS_PER_HOUR );
		$day_dis = floor( ( $now - $time ) / self::SECONDS_PER_DAY );
		$wek_dis = floor( ( $now - $time ) / self::SECONDS_PER_DAY / self::DAYS_PER_WEEK );
		$mon_dis = ( date( "Y", $now ) * 12 + date( "m", $now ) ) - ( date( "Y" , $time ) * 12 + date( "m", $time ) );
		if ( $day_dis < date( "t", $now ) ) $mon_dis = 0;
		$yer_dis = date( "Y", $now ) - date( "Y", $time );
		$this_year_days = self::DAYS_PER_YEAR;
		if ( date( "L", $now ) ) $this_year_days++; 
		if ( $day_dis < $this_year_days ) $yer_dis = 0;
		
		if ( $yer_dis ) return $yer_dis . '年' . $direction;
        if ( $mon_dis ) return $mon_dis . '个月' . $direction;
        if ( $wek_dis ) return $wek_dis . '周' . $direction;
        if ( $day_dis ) return $day_dis . '天' . $direction;
        if ( $hor_dis ) return $hor_dis . '小时' . $direction;
        if ( $min_dis ) return $min_dis . '分钟' . $direction;
		
		return "刚刚";
	}
	
	/**
	 * 将时间戳转换为日期
	 * @param timestamp $time
	 */
	public static function time2date ( $time )
	{
		return date( 'm/d/Y', $time );
	}
	
	/**
	 * 将日期转换为时间戳
	 * @param string $date
	 */
	public static function date2time ( $date )
	{
		list( $month, $day, $year ) = explode( '/', $date );
		return mktime( 0, 0, 0, $month, $day, $year ); 
	}

	/**
	 * 根据秒数获得时间段的长度
	 * @param number $seconds
	 */
	public static function length( $seconds )
	{
		$length = '';
		foreach ( self::$unitNames as $unit => $name ) {
			$unit_seconds = constant( 'self::SECONDS_PER_' . strtoupper( $unit ) );
			if ( $amount = floor( $seconds / $unit_seconds ) ) {
				$seconds -= $amount * $unit_seconds;
				$length .= $amount . $name;
			}
		}
		return $length; 
	}
	
	/**
	 * 修改时间
	 * @param timestamp $time
	 * @param string $unit
	 * @param number $fix
	 */
	public static function fix ( $time, $unit, $fix )
	{
		$date = array();
		foreach ( self::$unitCodes as $unit => $code ) {
			$date[ $key ] = date( $code, $time );
		}
		$date[ $type ] += $fix;
		return mktime( 
			$date[ 'hour' ],
			$date[ 'minute' ],
			$date[ 'second' ],
			$date[ 'month' ],
			$date[ 'day' ],
			$date[ 'year' ]
		);
	} 
	
	/**
	 * 获得指定时间之前的某天
	 * @param timestamp $time
	 * @param number $days
	 */
	public static function prevDay ( $time, $days = 1 )
	{
		return self::fix( $time, 'day', - $days );
	} 
	
	/**
	 * 获得指定时间之后的某天
	 * @param timestamp $time
	 * @param number $days
	 */
	public static function nextDay ( $time, $days = 1 )
	{
		return self::fix( $time, 'day', $days );
	}

	/**
	 * 取得指定时间的前一天
	 * @param timestamp $time
	 */
	public static function yesterday ( $time )
	{
		return self::prevDay( $time, -1 );
	}
	
	/**
	 * 获得指定时间的后一天
	 * @param timestamp $time
	 */
	public static function tomorrow ( $time )
	{
		return self::nextDay( $time, 1 );
	}
	
	/**
	 * 获得某天是星期几
	 * @param timestamp $time
	 */
	public static function getWeekDay ( $time )
	{
		if ( is_string( $time ) ) $time = strtotime( $time );
		return self::$weekdays[ date( 'w', $time ) ];	
	}
	
	/**
	 * 获得指定时间的年份
	 * @param timestamp $time
	 */
	public static function getYear ( $time = null ) 
	{
		$date = getdate( Value::get( $time, time() ) );
		return $date[ 'year' ];
	}
	
	/**
	 * 获得指定时间的月份
	 * @param timestamp $time
	 */
	public static function getMonth ( $time = null ) 
	{
		$date = getdate( Value::get( $time, time() ) );
		return $date[ 'mon' ];	
	}
	
	/**
	 * 获得指定时间的天数
	 * @param timestamp $time
	 */
	public static function getDay ( $time = null )
	{
		$date = getdate( Value::get( $time, time() ) );
		return $date[ 'mday' ];
	}
	
	/**
	 * 获得指定时间的小时
	 * @param timestamp $time
	 */
	public static function getHour ( $time = null )
	{
		$date = getdate( Value::get( $time, time() ) );
		return $date[ 'hours' ];
	}
	
	/**
	 * 获得指定时间的分钟
	 * @param timestamp $time
	 */
	public static function getMinute ( $time = null )
	{
		$date = getdate( Value::get( $time, time() ) );
		return $date[ 'minutes' ];
	}
	
	/**
	 * 获得指定时间的秒数
	 * @param timestamp $time
	 */
	public static function getSecond ( $time = null )
	{
		$date = getdate( Value::get( $time, time() ) );
		return $date[ 'seconds' ];
	}
}