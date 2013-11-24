<?php
class Language
{
	public static $default = 'zh_cn';
	
	public static function init()
	{
		if ( $language = Cookie::get( 'language' ) ) {
			Yii::app()->language = $language;
		} else {
			Yii::app()->language = self::getFromBrowser();
		}
	}
	
	public static function get()
	{
		return Yii::app()->language;
	}
	
	public static function getFromBrowser()
	{
		$lang = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
		$lang = strtolower( str_replace( '-', '_', $lang[0] ) );
		return ( $lang == 'zh_cn' ) ? 'zh_cn' : 'en';
	}
	
	public static function set( $language = null )
	{
		Value::setDefault( $language, self::$default );
		Yii::app()->language = $language;
		Cookie::set( 'language', $language );
	}
	
	public static function another()
	{
		return ( Yii::app()->language == 'zh_cn' ) ? 'en' : 'zh_cn';
	}
			
}