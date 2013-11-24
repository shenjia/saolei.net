<?php
class Timer
{
	const TIME_FORMAT = '%.3f'; 
	
	public $start;
	
	public function __construct()
	{
		$this->start = time() + microtime();
	}
	
	public function stop ( $output = true )
	{
		$seconds = sprintf( self::TIME_FORMAT, time() + microtime() - $this->start );
		if ( $output ) echo "\tCost " . $seconds . " seconds. ( " . date( 'Y-m-d H-i-s') . ' )' . PHP_EOL;
		return $seconds;	
	}
	
}