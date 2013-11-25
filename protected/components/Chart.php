<?php
class Chart extends CWidget
{
	public $id;                     //控件名称
	public $title = '';             //图表标题
	public $style = '';             //图表样式
	public $type = 'line';          //图表类型（line / area /...）参见highchart api
	public $polar = false;          //雷达图
	public $x = 'days';             //x轴类型（days / text / number）
	public $y = 'number';           //y轴类型（number / percent / text / minutes）
	public $tooltip = 'share';      //tip类型（percent / single / share / percent / cpa / minutes）
	public $series = array();       //数据序列
	public $days = array();         //日期列表
	public $plotBands;              //标记区域（周末）
	public $legend = 'bottom';      //说明栏位置（bottom / hide / floating）
	public $showFirstOnly = false;  //是否只显示第一序列
	public $spacing = 10;           //图表四周留白
	public $initScript = '';        //初始化JS
	public $background = '#3b3a32'; //背景颜色
	public $lineColor = '#666666';  //线条颜色
	public $colors = array(         //颜色顺序
	    '#97b42d',
	    '#3a84d5',
	    '#862316'
	);

	public function init()
	{
		if ( !isset( $this->id ) ) $this->id = uniqid( 'chart_' );
		if ( !is_array( $this->x ) ) $this->initAxis( $this->x );
		if ( !is_array( $this->y ) ) $this->initAxis( $this->y );
	}

	public function run()
	{
	    Resource::loadJs( 'highcharts' );
	    if ($this->polar) Resource::loadJs( 'highcharts-more' ); 
		$this->render( 'chart/chart' );
	}

	private function markWeekEnd ( $days )
	{
		$bands = array();
		foreach ( $days as $index => $day ) {
			$week_day = TimeHelper::getWeekDay( $day );
			if ( in_array( $week_day, array( '六', '日' ) ) ) {
				array_push( $bands, new plotBands( $index ) );
			}
		}
		return $bands;
	}

	private function initAxis ( &$axis )
	{
		$axis = ( $axis == 'days' ) ? array(
			'type' => $axis,
			'title' => '',
			'categories' => $this->days,
			'step' => 3,
			'plotBands' => $this->markWeekEnd( $this->days )
		) : array(
			'type' => $axis,
			'title' => '',
		);
	}
}

class plotBands {
	public $from;
	public $to;
	public $color = 'rgba(68, 170, 213, .2)';
	public function __construct ( $start ) {
		$this->from = $start - 0.5;
		$this->to = $start + 0.5;
	}
}