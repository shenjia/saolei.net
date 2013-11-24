<?php
$formatter = 'score_' . $news->details->od; 
$this->renderPartial('/user/_avatarCell', array(
	'user' => $news->author, 'class' => 'author', 'gender' => 'small', 'link' => true
));
$this->renderPartial('/common/title', array('title' => Assess::title($news->user_score), 'link' => true));
echo $news->details->or > 0 ? '刷新了' : '创造了';
?><span class="record person">个人<?= Yii::t('video', $news->details->lv) . Yii::t('video', $news->details->od); ?></span>
<?php if ($news->details->or > 0):?>
<span class="original"><?= Format::$formatter($news->details->or)?></span>
<span class="increase"></span>
<a href="/video/<?= $news->reference ?>" target="_blank" class="score"><?= Format::$formatter($news->details->cr)?></a>
<?php else:?>
<a href="/video/<?= $news->reference ?>" target="_blank" class="original"><?= Format::$formatter($news->details->cr)?></a>
<?php endif;?> 