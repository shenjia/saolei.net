<div id="ranking_header" class="box">
	<h1><?php echo Yii::t( 'title', 'ranking' );?></h1>
	<div class="filters">
	<?php
	$this->widget('Tabs', array(
	    'tabs' => array(
	    	'level' => array(
	            'sum' => '总计',
	            'beg' => '初级',
	            'int' => '中级',
	            'exp' => '高级'
	        ),
	        'order' => array(
	            'time' => '按成绩排列',
	            '3bvs' => '按3BV/s排列' 
	        )
	    ),
	    'options' => array(
    		'level' => $level,
    	    'order' => $order
    	)
	)); 
	?>
	</div>
</div>
<div id="user_list" class="ranking_list">
<?php 
if ( !empty($users) ) {
    $rank = $pages->currentPage * $pages->pageSize + 1;
	foreach ($users as $user) {
	    $url = $level == 'sum' ? '/user/' . $user->id : '/video/' . $user->scores->video($level, $order);
	    echo CHtml::openTag('a', array('href' => $url, 'target' => '_blank'));
		$this->renderPartial('/ranking/_userCell', array(
			'user' => $user, 'level' => $level, 'order' => $order, 'rank' => $rank++
		));
		echo CHtml::closeTag('a');
	}
}
$this->widget('Pager', array('pages' => $pages));
?>
</div>