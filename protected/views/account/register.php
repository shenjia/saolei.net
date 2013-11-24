<div id="account_register" class="box">
	<h1><?php echo Yii::t( 'title', 'register' );?></h1>
	<?php $form=$this->beginWidget('Form', array(
		'id'=>'register-form',
		'action'=>'/account/register',
		'focus'=>array($model,'username'),
	)); ?>
	<table class="form" cellpadding="0" cellspacing="0">
		<tr>
			<td><?php echo $form->label($model,'username'); ?></td>
			<td>
				<div><?php echo $form->textField($model,'username'); ?></div>
				<?php echo $form->hint($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $form->label($model,'password'); ?></td>
			<td>
				<div><?php echo $form->passwordField($model,'password'); ?></div>
				<?php echo $form->hint($model,'password'); ?>
				<?php echo $form->error($model,'password'); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $form->label($model,'chinese_name'); ?></td>
			<td>
				<div><?php echo $form->textField($model,'chinese_name'); ?></div>
				<?php echo $form->hint($model,'chinese_name'); ?>
				<?php echo $form->error($model,'chinese_name'); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $form->label($model,'english_name'); ?></td>
			<td>
				<div><?php echo $form->textField($model,'english_name'); ?></div>
				<?php echo $form->hint($model,'english_name'); ?>
				<?php echo $form->error($model,'english_name'); ?>
			</td>
		</tr>
		<tr>
			<td><?php echo $form->label($model,'sex'); ?></td>
			<td><?php echo $form->radioButtonList($model,'sex',array('男'=>1,'女'=>0)); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->label($model,'area'); ?></td>
			<td class="middle"><?php echo $form->dropDownList($model,'area',Area::getList()); ?></td>
		</tr>
	</table>
	<hr class="down"/>
	<?php $this->widget('Button', array('name' => 'next', 'type' => 'submit', 'class' => 'active'));?>
	<?php $this->endWidget(); ?>
</div><!-- box -->
<script>
$(function(){
	app.form.init({ 
		'name' : 'register-form' 
	});
});
</script>