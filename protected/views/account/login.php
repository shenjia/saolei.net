<div id="account_login" class="box">
	<h1><?php echo Yii::t( 'title', 'login' );?></h1>
	<?php $model->rememberMe=true;?>
	<?php $form=$this->beginWidget('Form', array(
		'id'=>'login-form',
		'action'=>'/account/login',
		'focus'=>array($model,'username'),
	)); ?>
		<table class="form" cellpadding="0" cellspacing="0">
			<tr>
				<td><?php echo $form->label($model,'username'); ?></td>
				<td>
					<div><?php echo $form->textField($model,'username',array('size'=>25)); ?></div>
					<?php echo $form->hint($model,'username');?>
					<?php echo $form->error($model,'username');?>
				</td>
			</tr>
			<tr>
				<td><?php echo $form->label($model,'password'); ?></td>
				<td>
					<div><?php echo $form->passwordField($model,'password',array('size'=>25)); ?></div>
					<?php echo $form->hint($model,'password');?>
					<?php echo $form->error($model,'password');?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo $form->checkBox($model,'rememberMe'); ?></td>
			</tr>
		</table>
		<?php echo CHtml::hiddenField('type',$type);?>
	<hr class="down"/>
	<?php 
	$this->widget('Buttons', array( 
	    'options' => array('class' => 'left'),
	    'buttons' => array(
	        array('name' => 'login', 'type' => 'submit', 'class' => 'active'),
	        array('name' => 'register', 'click' => 'app.go(\'/account/register\');'),
	        array('name' => 'forget', 'class' => 'disabled')
	    )
	) );
	?>
	<?php $this->endWidget(); ?>
</div>
<script>
$(function(){
	app.form.init({
		'name' : 'login-form',
	});
	app.resizePop();
});
</script>