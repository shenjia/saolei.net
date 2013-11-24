<div id="top10" class="box">
	<a href="/ranking" target="_blank"><h2>十大元帅</h2></a>
	<hr class="down"/>
	<table cellpadding="0" cellspacing="0" class="table full">
	<?php foreach ($users as $user):?>
	<tr>
		<td class="rank"><em><?= ++$rank ?></em></td>
		<td class="user"><?php $this->renderPartial('/user/_avatarCell', array('user' => $user, 'link' => true))?></td>
		<td><?php $this->renderPartial('/common/title', array('title' => $user->title, 'link' => true))?></td>
	</tr>
	<?php endforeach;?>
	</table>
</div>