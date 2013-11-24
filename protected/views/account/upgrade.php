<ul id="account_upgrade">
	<li class="main">
        <div class="box">
        	<?php $this->renderPartial('/account/_header', array('user' => $user));?>
        	<?php $form=$this->beginWidget('Form', array(
        		'id'=>'upgrade-form',
        		'action'=>'/account/upgrade',
        		'focus'=>array($model,'username'),
        	)); ?>
        	<p>本站的账户系统已经升级，新的账户系统要求使用邮箱登陆，之后找回密码将会把密码直接发送到您填写的邮箱<em>（旧版通过回答问题找回密码的方案已废弃）</em>。</p>
        	<p>此外，旧版中的“所在地区”已经更改为“<?= Yii::t('user', 'area')?>”，用以日后将用户分划到固定的地区建立排行，请谨慎选择。<em>（一经设定将不能再更改）</em>。</p>
        	<table class="form" cellpadding="0" cellspacing="0">
        		<tr>
        			<th><?php echo $form->label($model,'username'); ?></th>
        			<td>
        				<div><?php echo $form->textField($model,'username',array('size'=>40)); ?></div>
        				<?php echo $form->hint($model,'username');?>
        				<?php echo $form->error($model,'username');?>
        			</td>
        		</tr>
        		<tr>
        			<td><?php echo $form->label($model,'area'); ?></td>
        			<td class="middle"><?php echo $form->dropDownList($model,'area',Area::getList()); ?></td>
        		</tr>
        		<tr>
        			<th></th>
        			<td>
        			    <?php $this->widget('Button', array('name' => 'submit', 'type' => 'submit', 'class' => 'active'));?>
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
		'name' : 'upgrade-form' 
	});
});
</script>