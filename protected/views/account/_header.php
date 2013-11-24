<div class="nav">
	<a href="/account"><h1><?php echo Yii::t( 'title', 'account' );?></h1></a>
	<?php $this->widget('Navigator',array( 
		'id'=>'box_nav',
		'items'=>array(
	        array('label'=>Yii::t('title', 'upgrade'), 	'url'=>array('/account/upgrade'), 'visible'=>$user->auth->needUpgrade),
			array('label'=>Yii::t('title', 'profile'), 	'url'=>array('/account/profile')),
			array('label'=>Yii::t('title', 'password'), 'url'=>array('/account/password')),
		)
	)); ?>
</div>
<hr/>