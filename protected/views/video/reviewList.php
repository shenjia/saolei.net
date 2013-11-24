<div id="video_list_header" class="box">
    <h1><?php echo Yii::t( 'title', 'review' );?></h1>
    <div class="filters">
	<?php $this->widget('Switcher', array(
	    'name'    => 'videoListMode',
	    'param'   => 'model',
	    'cases'   => array('detail', 'thumb', 'list'),
	    'default' => 'detail' 
	));?>
	<?php
	$this->widget('Tabs', array(
	    'tabs' => array(
	        'status' => array(
	            VideoConfig::STATUS_NORMAL   => '待审核',
	            VideoConfig::STATUS_REVIEWED => '已通过',
	            VideoConfig::STATUS_BANNED   => '已屏蔽'
	        )
	    ),
	    'options' => array(
    		'status' => $status,
    	)
	)); 
	?>
	</div>
</div>
<?php $mode = Yii::app()->user->getState('videoListMode'); ?>
<div id="video_list" class="ranking_list order_by_<?= $order ?> mode_<?= $mode ?>">
<?php 
if ( !empty($videos) ) {
    foreach ($videos as $video) {
        echo CHtml::openTag('a', array('href' => '/video/' . $video->id, 'class' => 'list_link', 'target' => '_blank'));
        $this->renderPartial('/video/_' . $mode . 'Cell', array('video' => $video));
        echo CHtml::closeTag('a');
    }
}
$this->widget('Pager', array('pages' => $pages));
if ($status == VideoConfig::STATUS_NORMAL) {
    Javascript::register(array('remove_from_list' => '
    	$("#video_list").delegate(".list_link", "click", function(){ $(this).hide(); return true });
    '));
}
?>
</div>