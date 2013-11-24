<ul id="home">
	<li class="main">
        <div id="news" class="box">
        	<h1>雷界动态1</h1>
        	<table cellpadding="0" cellspacing="0" class="table">
        	<?php foreach ($news as $item) {
        	    $this->renderPartial('/news/_cell', array('news' => $item));
        	}?>
			</table>
			<?php
			if (count($news) == HomeConfig::NEWS_NUMBER) $this->renderPartial('/common/more', array(
        	    'id' => 'news_loader',
        	    'url' => '/news/more',
        	    'container' => '#news .table',
        	    'cursor' => $item->id,
        	    'pagesize' => HomeConfig::NEWS_PAGECOUNT
        	));
        	?>
        </div>
    </li>
    <li class="sidebar">
    	<?php $this->renderPartial('/home/_newbies', array('newbies' => $newbies))?>
    	<?php $this->renderPartial('/home/_top', array('users' => $top))?>
	</li>
</ul>