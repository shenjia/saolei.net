<?php
class Cookie
{
	const COOKIE_DURATION = 2592000; //60*60*24*30 = one month

	public static function set ( $name, $value, $duration = null )
	{
		try {
			$cookie = new CHttpCookie( $name, $value );
			$cookie->expire = time() + ( $duration != null ? $duration : self::COOKIE_DURATION );
			$cookie->domain = Yii::app()->request->serverName;
			Yii::app()->request->cookies[ $name ] = $cookie;
			return true;
				
		} catch( CException $e ){
			return false;
		}
	}

	public static function get ( $name )
	{
		$cookie = Yii::app()->request->cookies[ $name ];
		if ( $cookie ) {
			return $cookie->value;
		} else {
			return false;
		}
	}

}