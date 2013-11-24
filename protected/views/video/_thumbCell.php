<div class="video_cell box">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<p>
				<span class="level"><?= Yii::t('video', $video->level) ?></span>
            	<?php $this->renderPartial('/common/score_time', array(
            	    'score' => $video->scores['time'],
            	    'noflag' => $video->info->noflag
            	))?>
            	<span class="board_3bv">3BV<em><?= $video->info->board_3bv ?></em></span> 
            	<?php $this->renderPartial('/common/score_3bvs', array(
            		'score' => $video->scores['3bvs']
            	))?>
            	<span class="status st<?= $video->status ?>"><?= Yii::t('video', 'status.' . $video->status) ?></span>
        	</p>
        	<br>
        	<p>
            	<?php 
            	$this->renderPartial('/user/_avatarCell', array(
            		'user' => $video->author, 'class' => 'author', 'link' => false
            	));
            	$this->renderPartial('/common/title', array('title' => $video->author->title));
            	?>
            	<span class="create_time">上传于<em><?= Time::opposite($video->create_time,  Time::SECONDS_PER_DAY, 'Y年n月j日') ?></em></span>
        		<span class="clicks">点击<em><?= $video->stat->clicks ?></em></span>
        		<span class="comments">评论<em><?= $video->stat->comments ?></em></span>
            </p>
            </td>
			<td class="right">
            	<?php $this->widget('Board', array(
            	    'id'    => $video->id,
            	    'level' => $video->level,
            	    'board' => $video->info->board,
            	    'size'  => $video->level == 'beg' ? 8 : 4
            	))?>
			</td>
		</tr>
    </table>
</div>