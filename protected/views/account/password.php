<ul id="account_password">
	<li class="main">
        <div class="box">
        	<?php $this->renderPartial('/account/_header', array('user' => $user));?>
        	<?php $form=$this->beginWidget('Form', array(
        		'id'=>'password-form',
        		'action'=>'/account/password',
        		'focus'=>array($model,'password'),
        	)); ?>
        	<table class="form" cellpadding="0" cellspacing="0">
        		<tr>
        			<th><?php echo $form->label($model,'password'); ?></th>
        			<td>
        				<div><?php echo $form->passwordField($model,'password',array('size'=>25)); ?></div>
        				<?php echo $form->hint($model,'password');?>
        				<?php echo $form->error($model,'password');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'new'); ?></th>
        			<td>
        				<div><?php echo $form->passwordField($model,'new',array('size'=>25)); ?></div>
        				<?php echo $form->hint($model,'new');?>
        				<?php echo $form->error($model,'new');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'new_repeat'); ?></th>
        			<td>
        				<div><?php echo $form->passwordField($model,'new_repeat',array('size'=>25)); ?></div>
        				<?php echo $form->hint($model,'new_repeat');?>
        				<?php echo $form->error($model,'new_repeat');?>
        			</td>
        		</tr>
        		<tr>
        			<th></th>
        			<td>
        			    <?php $this->widget('Button', array('type' => 'submit', 'name' => 'submit', 'class' => 'active'));?>
        			</td>
        		</tr>
        	</table>
        	<?php $this->endWidget(); ?>
        </div>
	</li>
	<li class="sidebar">
		<?php $this->renderPartial('/user/_infoCell', array('user' => $user))?>
	</li>
</ul>
<script>
$(function(){
	app.form.init({ 
		'name' : 'password-form' 
	});
});
</script>