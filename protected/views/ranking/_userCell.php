<div class="user_cell box">
	<span class="rank">No.<em><?= $rank ?></em></span>
	<span class="user"><?php $this->renderPartial('/user/_avatarCell', array('user' => $user))?></span>
	<?php $this->renderPartial('/common/title', array('title' => $user->title))?>
	<span class="level"><?= Yii::t('video', $level) ?></span>
	<?php $this->renderPartial('/common/score_' . $order, array( 
    	'score' => $user->scores->score($level, $order),
        'date'  => $user->scores->date($level, $order)
	))?>
</div>