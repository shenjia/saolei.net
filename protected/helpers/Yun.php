<?php
require_once dirname( __FILE__ ) . '/../modules/upyun/upyun.class.php';
class Yun 
{
	private static $api;
	
	/**
	 * 获得api句柄
	 */
	public static function api()
	{
		if ( !isset( self::$api ) ) {
			self::$api = new UpYun( UPYUN_BUCKET, UPYUN_USERNAME, UPYUN_PASSWORD );
		}
		return self::$api;
	}

	/**
	 * 上传文件
	 * @param string $filepath
	 */
	public static function upload ( $filePath, $toPath )
	{
		try {
			Yii::trace( 'upload: ' . $filePath, 'upyun' );
			$file = fopen( $filePath, 'r' );
			self::api()->writeFile( $toPath, $file, true, array(
				UpYun::CONTENT_MD5 => md5_file( $filePath )
			) );
			fclose( $file );
			return true;
		} catch ( UpYunException $e ) {
			echo $toPath . ' ' . $e->getCode() . ' ' . $e->getMessage();die();
			Yii::log( $e->getCode() . ' ' . $e->getMessage(), 'error', 'upyun' );
			return false;
		}
	}
}