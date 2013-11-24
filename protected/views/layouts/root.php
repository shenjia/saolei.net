<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo Yii::app()->language; ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="format-detection" content="telephone=no" />
	<base target="_self"> 
	<title><?php echo CHtml::encode( empty($this->pageTitle) ? Yii::t( 'nav', 'site') : $this->pageTitle );?></title>
	<?php Resource::loadCss( 'app' ); ?>
	<?php Resource::loadJs( 'app-min' ); ?>
	<script>
	var app = {
		'servers' : {
			'resource' : '<?php echo RESOURCE_SERVER; ?>'
		}
	}
	</script>
</head>
<body>
<?php echo $content; ?>
</body>
</html>  
<?php 
if ( Yii::app()->user->isGuest ) Javascript::register( array( 'loginKey' => 'app.registerLoginKey();' ) );
if ( Yii::app()->user->hasFlash( 'flash' ) ) {
	$flash = Yii::app()->user->getFlash( 'flash' );
	$callback = Yii::app()->user->getFlash( 'flash_callback' );
	Javascript::register( array( 'flash' => "app.flash('{$flash}',null,function(){{$callback}});" ) );
}
?>
