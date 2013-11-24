<?php
class File
{
	private static $cache;

	/**
	 * 解析文件路径
	 * @param string $path
	 */
	public static function pathinfo ( $path )
	{
		if ( !isset( self::$cache[ $path ] ) ) {
			self::$cache[ $path ] = pathinfo( $path );
		}
		return self::$cache[ $path ];
	}
	
	/**
	 * 获得文件名（包括扩展名）
	 * @param string $path
	 */
	public static function getFullname ( $path )
	{
		$pathinfo = self::pathinfo( $path );
		return $pathinfo[ 'basename' ];
	}
	
	/**
	 * 获得文件名
	 * @param string $path
	 */
	public static function getFilename ( $path )
	{
		$pathinfo = self::pathinfo( $path );
		return $pathinfo[ 'filename' ];
	}
	
	/**
	 * 获得扩展名
	 * @param string $path
	 */
	public static function getExtension ( $path )
	{
		$pathinfo = self::pathinfo( $path );
		return $pathinfo[ 'extension' ];
	}
}