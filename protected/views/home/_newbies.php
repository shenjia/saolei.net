<div id="newbie" class="box">
	<h2>入伍新兵</h2>
	<hr class="down"/>
	<table cellpadding="0" cellspacing="0" class="table full">
	<?php foreach ($newbies as $newbie):?>
	<tr>
		<td class="user"><?php $this->renderPartial('/user/_avatarCell', array('user' => $newbie->author, 'link' => true))?></td>
	    <td><?php $this->renderPartial('/common/title', array('title' => Assess::title($newbie->user_score), 'link' => true));?></td>
	</tr>
	<?php endforeach;?>
	</table>
</div>