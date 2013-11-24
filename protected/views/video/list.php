<div id="video_list_header" class="box">
	<h1>
	<?php if ($user) {
	    $this->renderPartial('/user/_avatarCell', array('user' => $user, 'class' => 'author', 'link' => true));
	    ?><ins>的<?= Yii::t('video', $level)?>录像</ins><?php 
	} else {
	    echo Yii::t('title', 'video');
	}?> 
	</h1>
	<?php $this->widget('Button', array(
		'name' => 'uploadVideo', 'url' => '/video/upload', 
		'class' => 'active', 'display' => User::isLogin() && !$user
	));?>
	<div class="filters">
	<?php
	$this->widget('Tabs', array(
	    'tabs' => array(
	    	'level',
	        'order' => $level == 'all' ? array() : array(
	            'id'   => '按上传时间排列',
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
	<?php $this->widget('Switcher', array(
	    'name'    => 'videoListMode',
	    'param'   => 'model',
	    'cases'   => array('detail', 'thumb', 'list'),
	    'default' => 'detail' 
	));?>
</div>
<?php $mode = Yii::app()->user->getState('videoListMode'); ?>
<div id="video_list" class="ranking_list order_by_<?= $order ?> mode_<?= $mode ?>">
<?php 
if ( !empty($videos) ) {
	foreach ($videos as $video) {
	    echo CHtml::openTag('a', array('href' => '/video/' . $video->id, 'target' => '_blank'));
		$this->renderPartial('/video/_' . $mode . 'Cell', array('video' => $video));
		echo CHtml::closeTag('a');
	}
}
$this->widget('Pager', array('pages' => $pages));
?>
</div>