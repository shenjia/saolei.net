<?php
class Request 
{
	/**
	 * 获取GET参数
	 */
	public static function getQuery( $name, $defaultValue = null )
	{
		return isset( $_GET[ $name ] ) ? $_GET[ $name ] : $defaultValue;
	}
	
	/**
	 * 获取POST参数
	 */
	public static function getPost( $name, $defaultValue = null )
	{
		return isset( $_POST[ $name ] ) ? $_POST[ $name ] : $defaultValue;
	}
	
	/**
	 * 获取GET或POST参数
	 */
	public static function getParam( $name, $defaultValue = null )
	{
		return isset( $_GET[ $name ] ) ? $_GET[ $name ] : ( isset( $_POST[ $name ] ) ? $_POST[ $name ] : $defaultValue );
	}
	
	/**
	 * 批量获取GET参数
	 */
	public static function getQuerys( $querys )
	{
		$func = create_function( '$name', 'return $_GET[ $name ];' );
		return array_map( $func, $querys );
	}
	
	/**
	 * 批量获取POST参数
	 */
	public static function getPosts( $posts )
	{
		$func = create_function( '$name', 'return $_POST[ $name ];' );
		return array_map( $func, $posts );
	}
	
	/**
	 * 批量获取GET或POST参数
	 */
	public static function getParams( $params )
	{
		$func = create_function( '$name', 'return isset( $_GET[ $name ] ) ? $_GET[ $name ] : $_POST[ $name ];' );
		return array_map( $func, $params );
	}
	
	/**
	 * 合并参数
	 */
	public static function mergeParams($params)
	{
    	$query = array();
    	foreach ( $params as $param => $value ) {
    		array_push( $query, $param . '=' . urlencode( $value ) );
    	}
    	return implode( '&', $query );
	}

	/**
	 * 获取客户端IP
	 */
	public static function getIP()
	{
	    if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) return self::getLastForwardIP();
		if ( isset( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) return $_SERVER[ 'HTTP_CLIENT_IP' ];
		if ( isset( $_SERVER[ 'REMOTE_ADDR' ] ) ) return $_SERVER[ 'REMOTE_ADDR' ];
	    return '0.0.0.0';
	}
	
	/**
	 * 获取经过格式检查的客户端IP
	 */
	public static function getSafeIP()
	{
		$ip = self::getIP();		
		//用正则过滤非法数据
		preg_match( "/[\d\.]{7,15}/", $ip, $match );
		return !empty( $match[ 0 ] ) ? $match[ 0 ] : '0.0.0.0';
	}
	
	/**
	 * 从代理IP序列中获取真实IP
	 */
	private static function getLastForwardIP()
	{
        $ips = explode( ',', $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] );
        foreach ( $ips as $ip ) {
            $ip = trim( $ip );
            if ( $ip !== 'unknown' ) return $ip;
        }
	}
}
