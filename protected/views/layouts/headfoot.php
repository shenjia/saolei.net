<?php $this->beginContent('//layouts/root'); ?>
<div id="header">
	<div class="wrapper">
		<div class="logo"><a href="/"><h1><?php echo Yii::t( 'title', 'site' ); ?></h1><h2><?php echo Yii::t('title', 'beta')?></h2></a></div>
		<div class="nav">
			<?php
			$isLogin = User::isLogin(); 
			$isManager = UserAuth::isManager();
			$this->widget('Navigator',array( 
				'id'=>'header_nav',
				'items'=>array(
			
			        //首页
					array('label'=>Yii::t('title', 'index'), 	'url'=>array('/home')),
					
					//排行榜
					array('label'=>Yii::t('title', 'ranking'), 	'url'=>array('/ranking')),
					
					//录像
					array('label'=>Yii::t('title', 'video'), 	'url'=>array('/video'),  'visible'=>!$isLogin),
					array('label'=>Yii::t('title', 'video'), 	'url'=>array('/video'),  'visible'=>$isLogin,
						'itemOptions'=>array('class'=>'popMenu'),
						'submenuOptions'=>array('class'=>'menu'),
    					'items'=>array(
    						array('label'=>Yii::t('title', 'upload'),	'url'=>array('/video/upload')),
    						array('label'=>Yii::t('title', 'review'),	'url'=>array('/video/review'), 'visible'=>$isManager),
    				)),
					
					//array('label'=>Yii::t('title', 'bbs'), 		'url'=>array('/bbs')),
					
					//登录|个人中心
					array('label'=>Yii::t('title', 'login'), 	'url'=>array('/account/login'),  'visible'=>!$isLogin),
					array('label'=>Yii::app()->user->chinese_name, 'url'=>array('/account'), 'visible'=>$isLogin, 
						'itemOptions'=>array('class'=>'popMenu ' . (Yii::app()->user->isGuest ? '' : 'light')), 
						'submenuOptions'=>array('class'=>'menu'),
    					'items'=>array(
    						array('label'=>Yii::t('title', 'account'),	'url'=>array('/account')),
    						array('label'=>Yii::t('title', 'logout'),	'url'=>array('/account/logout')),
    					)), 
					array('label'=>Yii::t('button', 'anotherLanguage'), 'url'=>'javascript:app.switchLanguage("'.Language::another().'");', 'visible'=>false)
				),'activateParents'=>true
			)); ?>
		</div>
	</div>
</div>
<!-- 正文开始 -->
<?php echo $content;?>
<!-- 正文结束 -->
<div id="footer">
	<div class="wrapper">
	<p>
	Copyright &copy <?= date('Y') ?>
	Saolei.net
	</p>
	<a href="http://www.miibeian.gov.cn" target="_blank">陕ICP备08100290号</a>
	</div>
</div>
<?php $this->endContent(); ?>