<?php
class URL 
{
    /**
     * 分析参数
     */
    public static function parseParams ( $query ) 
    {
        $query = trim($query);
        if ($query == '') return array();
        
        $params = array();
        $parts = explode('&', $query);
        foreach ($parts as $part) {
            $pair = explode('=', $part);
            $params[$pair[0]] = $pair[1];             
        }
        return $params;
    }
    
	/**
	 * 拼接参数
	 */
	public static function generateParams ( $params )
	{
		$pairs = array();
		foreach ( $params as $key => $value ) {
			array_push( $pairs, urlencode( $key ). '=' . urlencode( $value ) );
		}
		return implode( '&', $pairs );
	}
}