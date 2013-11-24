<?php
class Value
{
	/**
	 * 为变量定义默认值（注意：$default一定会求值，如果第二个参数是函数且需要短路运算，不要调用此方法）
	 * @param mixed $var
	 * @param mixed $default
	 * @example Value::setDefault( $arr[ 'key' ], 'default' );
	 */
	public static function setDefault ( &$var, $default ) 
	{
		if ( !isset( $var ) ) $var = $default;
	}
	
	/**
	 * 获取变量的值，定义默认值（注意：$default一定会求值，如果第二个参数是函数且需要短路运算，不要调用此方法）
	 * @param mixed $var
	 * @param mixed $default
	 * @example Value::get( $arr[ 'key' ], 'default' );
	 */
	public static function get ( &$var, $default )
	{
		return isset( $var ) ? $var : $default;
	}
	
	/**
	 * 如变量不存在则定义默认值，如已存在则附加值
	 * @param mixed $var
	 * @param mixed $append
	 * @param mixed $seperator
	 */
	public static function append ( &$var, $append, $seperator = ' ' )
	{
		if ( isset( $var ) ) {
			if ( is_string( $var ) ) $var = $var . $seperator . $append;
			if ( is_numeric( $var ) ) $var = $var + $append;
			if ( is_array( $var ) ) array_push( $var, $append );
		} else {
			$var = $append;
		}
	}
	
	/**
	 * 从数组中取出某个元素并将其删除
	 * @param array $array
	 * @param string $key
	 */
	public static function pickFrom ( &$array, $key )
	{
		$value = $array[ $key ];
		unset( $array[ $key ] );
		return $value;
	}
	
	/**
	 * 如果变量不是array，则返回一个包裹它的Array
	 * @param mixed $var
	 */
	public static function toArray ( &$var )
	{
		if ( !is_array( $var ) ) $var = array( $var );
	}
	
	/**
	 * 检查变量是否为null
	 * @param mixed
	 */
	public static function exists ()
	{
    	$params = func_get_args();
    	foreach ( $params as $param ) {
    		if ( $param == null ) return false;
    	} 
    	return true;
	}
	
	/**
	 * 交换两个变量的内容
	 * @param $mixed $a
	 * @param $mixed $b
	 */
	public static function swap ( &$a, &$b )
	{
		list( $a, $b ) = array( $a, $b );
	}
	
	/**
	 * 生成序列
	 * @param number $from
	 * @param number $to
	 * @param boolean $null
	 */
	public static function getRangeList ( $from, $to, $null = false )
	{	
		$range = $null ? array( null => null ) : array();
		$step = ( $from < $to ) ? 1 : -1;
		$count = abs( $from - $to ) + 1;
		while ( $count-- ) {
			$range[ $from ] = $from;
			$from += $step;
		}
		return $range;
	}
	
	/**
	 * 获得所有对象的指定属性
	 * @param array $data
	 */
	public static function getFields(&$data, $field) 
	{
	    if (!is_array($data) || empty($data)) return array();
	    $function = create_function('&$object', 'return $object->' . $field . ';');
	    return array_map($function, $data);
	}
	
	/**
	 * 按id序列排序
	 * @param array $ids
	 */
	public static function orderByIds(&$ids) 
	{
    	return 'FIELD(`t`.`id`,'.implode(',', $ids).')';	    
	}
	
}