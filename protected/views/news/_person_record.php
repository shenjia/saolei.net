<?php
$formatter = 'score_' . $news->details->od; 
$this->renderPartial('/user/_avatarCell', array(
	'user' => $news->author, 'class' => 'author', 'link' => true
));
$this->renderPartial('/common/title', array('title' => Assess::title($news->user_score), 'link' => true));
?>刷新了<span class="record person"><?= Yii::t('video', $news->details->lv) . Yii::t('video', $news->details->od); ?></span>
<span class="original"><?= Format::$formatter($news->details->or)?></span>
<span class="increase"></span>
<a href="/video/<?= $news->reference ?>" target="_blank" class="score"><?= Format::$formatter($news->details->cr)?></a>