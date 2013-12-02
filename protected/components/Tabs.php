<?php
class Tabs extends CWidget
{
	public $type = 'params';    //导航类型（params / urls）
	public $current = 1;        //默认选择（从1开始计算）
	public $tabs;               //导航列表，可传名称或array
	public $options;            //默认参数，如不提供则从Controler->options获取
	public $ignoreParams = array(
	    'page'
	);

	public static $level = array(
	    'all' => '全部',
		'beg' => '初级',
		'int' => '中级',
		'exp' => '高级'
	);
	
	public static $unit = array(
		'day'   => '日',
		'week'  => '周',
		'month' => '月',
		'total' => '全部'
	);

	public function init()
	{
		if ( !isset( $this->options ) ) {
			$this->options = $this->controller->actionParams;
		}
	}

	public function run()
	{
		foreach ( $this->tabs as $name => $items ) {
			//如果传入的是tabs名称，则尝试从controller->tabs及Tabs中读取内容
			if ( is_numeric( $name ) ) {
				$name = $items;
				$items = $this->getItems( $name );
			}
			//如果没有可选项，隐藏tabs
			if (!empty($items)) {
    			$this->render( 'tabs/' . $this->type, array(
    				'name' => $name,
    				'items' => $items,
    				'current' => $this->current
    			) );
			}
		}
	}

	protected function getUrl ( $name, $item )
	{
	    $params = URL::parseParams(Yii::app()->request->querystring);
	    $params = array_merge( $params, $this->options, array( $name => $item ) );
	    foreach ($this->ignoreParams as $name) {
	        if (isset($params[$name])) unset($params[$name]);   
	    }
		$route = $this->controller->action->id == $this->controller->defaultAction 
		    ? $this->controller->id
		    : $this->controller->route;
		return '/' . $route . '?' . Request::mergeParams( $params );
	}

	protected function getItems ( $name )
	{
		//尝试从controller->tabs中读取
		if ( isset( $this->controller->tabs ) ) $items = $this->controller->tabs[ $name ];
		//尝试从Tabs中读取
		if ( !$items ) $items = Tabs::$$name;
		return $items;
	}
}