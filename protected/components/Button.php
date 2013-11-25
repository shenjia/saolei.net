<?php
class Button extends CWidget
{
    /* 属性 */
	public $id;                     //按钮id
	public $class;                  //样式名
	public $type;                   //type属性（button / submit）
	public $name;                   //按钮名
	public $property;               //其他html属性

	/* 展示 */
	public $size;                   //尺寸（small / normal / big）
	public $title;                  //按钮文案
	public $display;                //是否显示 (true / false)
	public $badge;                  //文案后的数字

	/* 行为 */
	public $url;                    //作为链接跳转
	public $click;                  //执行js代码
	public $ajax;                   //进行ajax调用
	public $data;                   //ajax调用时的参数
	public $dataType = 'script';    //ajax调用时返回数据的格式
	public $success;                //成功后的回调
	public $confirm;                //弹出确认框，如传入字符串则作为弹出文案
	public $blank;                  //是否在新窗口中打开

	/* 多态 */
	public $switch;                 //选择条件
	public $cases = array();        //供选择的项目
	
	/* 默认属性 */
	protected static $defaults = array(
	    'type'      => 'button',
	    'name'      => 'submit',
	    'property'  => array(),
	    'display'   => true,
	    'confirm'   => false,
	    'blank'     => true,
	    'success'   => '',
	);

	public function init()
	{
	    if (!isset($this->id)) $this->id = 'button_' . uniqid();

	    //如果为按钮组，先继承属性
		if (is_a($this->owner, 'Buttons')) {
			foreach ($this->owner->options as $key => $value) {
				if (!isset($this->$key)) $this->$key = $value;
			}
		}
		
		//如为多态按钮，按条件进行选择
		if (isset($this->switch)) {
			foreach ($this->cases[$this->switch] as $key => $value) {
				$this->$key = $value;
			}
		}
		
		//如未设定文案，则尝试按照name从messages中读取
		if (!isset($this->title)) {
		    $this->title = Yii::t('button', $this->name);
		}
		
		//赋予默认属性
		foreach (self::$defaults as $key => $value) {
		    if (!isset($this->$key)) $this->$key = $value;
		}
	}

	public function run()
	{
		if ( $this->display ) $this->render( 'button/button' );
	}
}