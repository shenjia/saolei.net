<div class="user_info_cell box">
	<a href="/user/<?= $user->id ?>" class="name" target="_blank">
	    <?= CHtml::encode($user->chinese_name) ?>
	</a>
	<span class="gender big <?= $user->sex ? 'male' : 'female'?>"></span>
	<?php $this->renderPartial('/common/title', array('title' => $user->title, 'link' => true))?>
	<?php $this->renderPartial('/common/avatar', array('user' => $user, 'size' => 192))?>
	<?php if ($user->info->self_intro):?>
	<span class="intro"><?= CHtml::encode($user->info->self_intro) ?></span>
	<?php endif;?>
</div>