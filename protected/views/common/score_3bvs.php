<span class="score_3bvs <?= $class ?>">
    3BV/s
    <?php if ($score > 0):?>
    	<?php if ($id):?><a href="/video/<?= $id ?>" target="_blank"><?php endif;?>
    		<em><?= Format::score_3bvs($score) ?></em>
    	<?php if ($id):?></a><?php endif;?>
    <?php elseif ($score < 0):?>
    	<del title="<?= Yii::t('video', 'why_uncount_3bvs')?>"><?= str_replace('-', '', Format::score_3bvs($score, true)) ?></del>
    <?php else:?>
    	<ins class="null" title="<?= Yii::t('video', 'why_null_record')?>">?</ins>
    <?php endif;?>
	<?php if ($date):?>
	(<?= Time::opposite($date, Time::NEVER)?>)
	<?php endif;?>
</span>