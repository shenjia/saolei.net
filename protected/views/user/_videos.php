<div class="videos box">
	<a href="/video?author=<?= $user->id?>" target="_blank"><h2>录像</h2></a>
	<span class="id">共<em><?= $user->stat->countVideos('all') ?></em>个</span>
	<?php
	$data = array();
	foreach (VideoConfig::$levels as $level) {
	    array_push($data, array(
	        'name' => Yii::t('video', $level),
	        'level' => $level, 
	        'y' => $user->stat->countVideos($level)
	    ));
	} 
	$this->widget('Chart', array(
	    'type' => 'pie',
	    'series' => array(array(
	        'name' => 'videos',
	        'data' => $data
	    )),
	    'legend' => 'hide',
	    'tooltip' => 'level',
	    'spacing' => 10
	));
	?>
</div>
<script>
var clickPie = function (e) {
    app.open('/video?author=<?= $user->id ?>&level=' + e.point.level);
}
</script>