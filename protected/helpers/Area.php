<?php
class Area
{
	public static $list = array(
		'Beijing',
		'Tianjin',
		'Hebei',
		'Shanxi',
		'Inner Mongolia',
		'Liaoning',
		'Jilin',
		'Heilongjiang',
		'Shanghai',
		'Jiangsu',
		'Zhejiang',
		'Anhui',
		'Fujian',
		'Jiangxi',
		'Shandong',
		'Henan',
		'Hubei',
		'Hunan',
		'Guangdong',
		'Guangxi',
		'Hainan',
		'Chongqing',
		'Sichuan',
		'Guizhou',
		'Yunnan',
		'Tibet',
		'Shaanxi',
		'Gansu',
		'Qinghai',
		'Ningxia',
		'Xinjiang',
		'Hongkong',
		'Macau',
		'Taiwan',
		'Abroad'
	); 
	
	/**
	 * 返回地区列表
	 */
	public static function getList()
	{
		$list = array();
		foreach ( self::$list as $area ) {
			$name = Yii::t( 'area', $area );
			$list[ $name ] = $name; 
		}
		return $list;
	}
}