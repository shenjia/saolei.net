<?php 
$distribution = Distribution::get('title');
$total = Distribution::get('size');
$create_time = Distribution::get('create_time');
$title_count = count(TitleConfig::$titles);
if ($user = User::getCurrentUser()) {
    $current = $user->title;
}
?>
<div id="titles_page" class="text box">
    <h1><?= Yii::t('title', 'titles') ?></h1>
    <h2>(<?= date('Y年n月j日', $create_time); ?>颁布)</h2>
    <table cellpadding="0" cellspacing="0" class="table">
    	<tr>
    		<th>级别</th>
    		<th>军衔</th>
    		<th class="tal">要求</th>
    		<th>编制</th>
    		<th></th>
    	</tr>
    <?php foreach (TitleConfig::$titles as $i => $title):?>
    	<tr <?php if ($current == $title):?>class="highlight"<?php endif;?>>
    		<td class="tac"><?= $title_count - $i ?></td>
    		<td>
        	<?php $this->renderPartial('/common/title', array('title' => $title));?>
        	</td>
    		<td>SUM <em><?= Format::score_time($distribution[$i]) ?></em> 秒</td>
        	<td>
        	<em><?php
        	$pre_title = TitleConfig::$titles[$i - 1];
        	// first fixed title, return directly
        	if ($i == 0) {
        	    echo TitleConfig::$distribution[$title];
        	} 
        	// other fixed titles, return the gap with the pre title
        	else if (in_array($title, TitleConfig::$fixed_titles)) {
        	    echo TitleConfig::$distribution[$title] - TitleConfig::$distribution[$pre_title];
        	}
        	// first rate title, return size directly 
        	else if ($i == count(TitleConfig::$fixed_titles)) {
        	    echo intval(TitleConfig::$distribution[$title] * intval($total));
        	} 
        	// last rate title, return gap with the population
        	else if ($i == $title_count - 1) {
        	    echo $total - intval(TitleConfig::$distribution[$pre_title] * intval($total));
        	} 
        	// normal rate title, return gap with pre title
        	else {
        	    echo intval(TitleConfig::$distribution[$title] * intval($total)) 
        	       - intval(TitleConfig::$distribution[$pre_title] * intval($total));
        	}
        	?></em>人
        	</td>
        	<td><?php if ($current == $title):?><span class="green">我的军衔</span><?php endif;?></td>
        </tr>
    <?php endforeach;?>
    </table>
    
    <hr/>    
    <h2>说明</h2>
    <p>1、以上军衔的要求计算的是玩家的<em>总时间成绩</em>	。</p>
    <p>2、当你的成绩达到相应的要求，系统将自动为你颁发相应的军衔。</p>
    <p>3、以上军衔制度共计编制<em><?= $total ?></em>人。
    由于扫雷玩家在不断增多，大家的水平也在不断进步，新制度发布后一段时间，部分军衔的持有人数就会超过编制。所以系统会定期对编制进行调整以恢复平衡，届时部分玩家的军衔会发生起伏。</p>
</div>
