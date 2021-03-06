<?php
class Navigator extends CMenu
{
	/**
	 * Checks whether a menu item is active.
	 * This is done by checking if the currently requested URL is generated by the 'url' option
	 * of the menu item. Note that the GET parameters not specified in the 'url' option will be ignored.
	 * @param array $item the menu item to be checked
	 * @param string $route the route of the current request
	 * @return boolean whether the menu item is active
	 */
	protected function isItemActive($item,$route)
	{
	    $url = trim( $item['url']['0'], '/' );
	    $url = $url ? $url : Yii::app()->defaultController . '/index';
		return ! ( stripos( $route, $url ) === false );
	}
}