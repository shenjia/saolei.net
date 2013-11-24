<div id="newbie" class="box">
	<h2>入伍新兵</h2>
	<table cellpadding="0" cellspacing="0" class="table full">
	<?php foreach ($newbies as $newbie):?>
	<tr>
		<td class="user">
		    <?php $this->renderPartial('/user/_avatarCell', array('user' => $newbie->author, 'gender' => 'small', 'link' => true))?>
		    <?php $this->renderPartial('/common/title', array('title' => Assess::title($newbie->user_score), 'link' => true));?>
		</td>
	    <td class="time"><?= Time::opposite($newbie->create_time, Time::NEVER)?></td>
	</tr>
	<?php endforeach;?>
	</table>
</div>