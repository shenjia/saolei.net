<ul id="account_profile">
	<li class="main">
        <div class="box">
        	<?php $this->renderPartial('/account/_header', array('user' => $user));?>
        	<?php $form=$this->beginWidget('Form', array(
        		'id'=>'profile-form',
        		'action'=>'/account/profile',
        		'focus'=>array($model,'status'),
        	)); ?>
        	<table class="form" cellpadding="0" cellspacing="0">
        		<tr>
        			<th><?php echo $form->label($model,'self_intro');?></th>
        			<td>
        				<div><?php echo $form->textArea($model,'self_intro',array('style'=>'width:300px','rows'=>4));?></div>
        				<?php echo $form->hint($model,'self_intro');?>
        				<?php echo $form->error($model,'self_intro');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'interest');?></th>
        			<td>
        				<div><?php echo $form->textArea($model,'interest',array('style'=>'width:300px','rows'=>3));?></div>
        				<?php echo $form->hint($model,'interest');?>
        				<?php echo $form->error($model,'interest');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'qq')?></th>
        			<td>
        				<div><?php echo $form->textField($model,'qq',array('size'=>UserConfig::INFO_QQ_LIMIT, 'maxlength'=>UserConfig::INFO_QQ_LIMIT));?></div>
        				<?php echo $form->hint($model,'qq');?>
        				<?php echo $form->error($model,'qq');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'nickname')?></th>
        			<td>
        				<div><?php echo $form->textField($model,'nickname',array('size'=>UserConfig::INFO_NICKNAME_LIMIT, 'maxlength'=>UserConfig::INFO_NICKNAME_LIMIT));?></div>
        				<?php echo $form->hint($model,'nickname');?>
        				<?php echo $form->error($model,'nickname');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'mouse')?></th>
        			<td>
        				<div><?php echo $form->textField($model,'mouse',array('size'=>UserConfig::INFO_MOUSE_LIMIT, 'maxlength'=>UserConfig::INFO_MOUSE_LIMIT));?></div>
        				<?php echo $form->hint($model,'mouse');?>
        				<?php echo $form->error($model,'mouse');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'pad')?></th>
        			<td>
        				<div><?php echo $form->textField($model,'pad',array('size'=>UserConfig::INFO_PAD_LIMIT, 'maxlength'=>UserConfig::INFO_PAD_LIMIT));?></div>
        				<?php echo $form->hint($model,'pad');?>
        				<?php echo $form->error($model,'pad');?>
        			</td>
        		</tr>
        		<tr>
        			<th><?php echo $form->label($model,'birth_day'); ?></th>
        			<td class="middle">
        				<div>
        					<?php echo $form->dropDownList($model,'birth_year',Value::getRangeList(Time::getYear(),1900,true)); ?>
        					<?php echo $form->dropDownList($model,'birth_month',Value::getRangeList(1,12,true)); ?>
        					<?php echo $form->dropDownList($model,'birth_day',Value::getRangeList(1,31,true)); ?>
        				</div>
        				<?php echo $form->error($model,'birth_day'); ?>
        			</td>
        		</tr>
        		<tr>
        			<th></th>
        			<td>
        			    <?php $this->widget('Button', array('name' => 'save', 'type' => 'submit', 'class' => 'active'));?>
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
		'name' : 'profile-form' 
	});
});
</script>