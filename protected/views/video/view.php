<ul id="video_view">
	<li class="main">
        <div class="video_cell box">
			<h1>
            	<?= Yii::t('video', $video->level) ?>
            	<em><?= Format::score_time($video->scores['time']) ?></em>
            	秒
            	<?php if ($video->info->noflag):?>
            	<ins>NF</ins>
            	<?php endif;?>
			</h1>
			<h2>
			<span class="board_3bv">3BV<em><?= $video->info->board_3bv ?></em></span>
        	<?php $this->renderPartial('/common/score_3bvs', array('score' => $video->scores['3bvs']))?>
        	<span class="id">ID.<em><?= $video->id ?></em></span>
            </h2>
        	<div class="info">
            	<?php $this->widget('Board', array(
            	    'id'  	    => $video->id,
            	    'level'		=> $video->level,
            	    'board'     => $video->info->board,
            	    'size'      => $video->level == 'beg' ? 16 : 8,
            	    'zoomable'  => $video->level != 'beg'
            	))?>
            	<p>
            	<?php $this->renderPartial('/user/_avatarCell', array('user' => $video->author, 'class' => 'author', 'link' => true))?>
            	<span class="create_time">上传于<em><?= Time::opposite($video->create_time,  Time::SECONDS_PER_DAY) ?></em></span>
            	</p>
            	<p>
            	<?php if ($video->review_user): ?>
                	<?php $this->renderPartial('/user/_nameCell', array('user' => $video->reviewer, 'class' => 'reviewer', 'link' => true))?>
            		<span class="review_time">审核于<em><?= Time::opposite($video->review_time,  Time::SECONDS_PER_DAY) ?></em></span>
        		<?php endif;?>
        		<span class="status st<?= $video->status ?>"><?= Yii::t('video', 'status.' . $video->status) ?></span>
        		</p>
                <p>
                	<span class="software">软件<em><?= $video->info->software ?> <?= $video->info->version ?></em></span>
                	<span class="signature">签名<em><?= $video->info->signature ?></em></span>
                </p>
            	<hr>
            	<?php $this->widget( 'Button', array( 
            		'name' => 'open', 'class' => 'active',
            		'url' => VIDEO_PATH . $video->info->filepath, 'blank' => false
            	) ); ?>
            	<?php $this->widget( 'Button', array( 
            		'name' => 'download', 
            		'url' => '/video/download/' . $video->id, 'blank' => false
            	) ); ?>
                <p class="counters">
                	<span class="clicks">点击<em><?= $video->stat->clicks ?></em></span>
            		<span class="comments">评论<em><?= $video->stat->comments ?></em></span>
            		<span class="downloads">下载<em><?= $video->stat->downloads ?></em></span>
            	</p>
            	<?php $this->renderPartial('/video/_reviewPanel', array('video' => $video))?>
        	</div>
        </div>
        <div class="post box">
    	<?php if (User::isLogin()) $this->renderPartial('/comment/_form', array('video' => $video))?>
    	</div>
        <div class="comments">
			<?php 
			foreach ($comments as $comment) {
			    $this->renderPartial('/comment/_cell', array('comment' => $comment));
			}
			?>        	
        </div>
    </li>
    <li class="sidebar">
    	<?php $this->renderPartial('/user/_infoCell', array('user' => $video->author))?>
	</li>
</ul>