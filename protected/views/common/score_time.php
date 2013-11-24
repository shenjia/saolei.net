<span class="score_time <?= $class ?>">
    <?php if ($score > 0):?>
    	<?php if ($id):?><a href="/video/<?= $id ?>" target="_blank"><?php endif;?>
    		<em><?= Format::score_time($score) ?></em>
    	<?php if ($id):?></a><?php endif;?>
    	秒
    <?php else:?>
    	<ins class="null" title="<?= Yii::t('video', 'why_null_record')?>">?</ins>
    <?php endif;?>
	<?php if ($noflag):?>
	<ins title="<?= Yii::t('video', 'whats_noflag')?>">NF</ins>
	<?php endif;?>
	<?php if ($date):?>
	(<?= Time::opposite($date, Time::NEVER)?>)
	<?php endif;?>
</span>