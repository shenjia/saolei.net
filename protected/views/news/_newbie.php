<?php 
$this->renderPartial('/user/_avatarCell', array(
	'user' => $news->author, 'class' => 'author', 'link' => true
));
$this->renderPartial('/common/title', array('title' => Assess::title($news->user_score), 'link' => true));
?><em>正式入伍，开始扫雷生涯！</em>