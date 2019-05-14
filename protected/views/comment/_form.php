<?php 
$model = new CommentForm();
$model->video = $video;
$model->user = User::getCurrentId();
$form = $this->beginWidget('Form', array(
	'id'=>'comment-form',
	'action'=>'/comment/post',
)); ?>
<table class="form" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<div><?php echo $form->textArea($model,'content',array(
				'style'=>'width:420px; margin-right: 15px',
				'rows'=>4
			));?></div>
			<?php echo $form->hint($model,'content');?>
			<?php echo $form->error($model,'content');?>
		</td>
		<td>
		    <?php $this->widget('Button', array('name' => 'comment', 'type' => 'submit'));?>
		</td>
	</tr>
</table>
<?php $this->endWidget(); ?>