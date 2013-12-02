<?php
class Pages extends CPagination
{
	const DEFAULT_PAGE_SIZE = 20;
	
	public $validateCurrentPage = false;
	public $ignoreParams = array();
	
	public function __construct ( $itemCount = 0 )
	{
		$this->setItemCount( $itemCount );
		$this->setPageSize( self::DEFAULT_PAGE_SIZE );
	}
	
	public function ignoreParam($param) 
	{
	    if (!in_array($param, $this->ignoreParams)) {
	        array_push($this->ignoreParams, $param);
	    }
	}
	
	public function createPageUrl($controller,$page)
	{
	    $params = URL::parseParams(Yii::app()->request->querystring);
	    if (is_array($this->params)) {
	        $params = array_merge( $params, $this->params );
	    }
	    foreach ($this->ignoreParams as $name) {
	        if (isset($params[$name])) unset($params[$name]);   
	    }
	    if ($page > 0) // page 0 is the default
			$params[$this->pageVar] = $page + 1;
		else
			unset($params[$this->pageVar]);
	    $route = Yii::app()->controller->action->id == Yii::app()->controller->defaultAction 
		    ? Yii::app()->controller->id
		    : Yii::app()->controller->route;
	    return '/' . $route . '?' . Request::mergeParams( $params );
	}
}