<?php
class Buttons extends CWidget
{
	public $options = array();  //按钮通用设置，将继承给每个按钮
	public $buttons = array();  //按钮列表

	public function init()
	{

	}

	public function run()
	{
		foreach ( $this->buttons as $button ) {
			$this->widget( 'Button', $button );
		}
	}
}