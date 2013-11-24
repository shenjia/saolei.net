<ul id="user_view">
	<li class="main">
        <div class="info box">
        	<?php $this->renderPartial('/common/avatar', array('user' => $user, 'size' => 192))?>
			<h1><?= $user->chinese_name ?></h1>
			<h2>(<?= $user->english_name ?>)</h2>
			<span class="gender big <?= $user->sex ? 'male' : 'female'?>"></span>
			<?php $this->renderPartial('/common/title', array('title' => $user->title, 'link' => true))?>
			<table class="scores" cellpadding="0" cellspacing="0">
    			<?php foreach (RankingConfig::$levels as $level):?>
				<tr>
					<th><?= Yii::t('video', $level); ?></th>
					<td>                    	
                    	<?php
                		$user->scores 
                		    ? $this->renderPartial('/common/score_time', array( 
                    	    	'id'    => $user->scores->video($level, 'time'),
                    	    	'score' => $user->scores->score($level, 'time'),
                    	        'date'  => $user->scores->date($level,  'time'),
                		        'class' => $level
                    		))
                    		: $this->renderPartial('/common/score_time');
                        ?>
            		</td>
            		<td>
            			<?php 
            			$user->scores 
            			    ? $this->renderPartial('/common/score_3bvs', array( 
                    	    	'id'    => $user->scores->video($level, '3bvs'),
                    	    	'score' => $user->scores->score($level, '3bvs'),
                    	        'date'  => $user->scores->date($level,  '3bvs'),
            			    	'class' => $level
                    		))
                    		: $this->renderPartial('/common/score_3bvs');
            			?>
            		</td>
            	</tr>
            	<?php endforeach;?>
            </table>
        </div>
        <div class="profile box">
			<h2>个人资料</h2>
			<span class="id">ID.<em><?= $user->id ?></em></span>
			<hr>
			<table class="form">
				<?php foreach (array('nickname', 'self_intro', 'interest', 'qq', 'mouse', 'pad') as $field) {
				    if ($user->info->$field) {
                        ?><tr>
                            <th><?php echo Yii::t('user', $field);?></th>
                            <td><?= CHtml::encode($user->info->$field);?></td>            
                        </tr><?php				        
				    }
				}?>
            </table>
        </div>
        <?php if (!empty($news)):?>
        <div id="news" class="box">
        	<h2>最新动态</h2>
        	<table cellpadding="0" cellspacing="0" class="table">
        	<?php 
        	foreach ($news as $item) {
        	    $this->renderPartial('/news/_cell', array('news' => $item));
        	}
        	?>
			</table>
			<?php
			if (count($news) == UserConfig::NEWS_NUMBER) $this->renderPartial('/common/more', array(
        	    'id' => 'news_loader',
        	    'url' => '/news/more?user=' . $user->id,
        	    'container' => '#news .table',
        	    'cursor' => $item->id,
			    'pagesize' => UserConfig::NEWS_PAGECOUNT
        	));
        	?>
        </div>
        <?php endif;?>
    </li>
    <li class="sidebar">
    	<?php $this->renderPartial('/user/_radar', array('user' => $user))?>
    	<?php if ($user->stat->countVideos('all') > 0) $this->renderPartial('/user/_videos', array('user' => $user));?>
	</li>
</ul>