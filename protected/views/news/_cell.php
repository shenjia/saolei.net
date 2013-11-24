<tr>
	<td><?php $this->renderPartial('/news/_' . NewsConfig::$types[$news->type], array('news' => $news))?></td>
	<td><?= Time::opposite($news->create_time, Time::SECONDS_PER_DAY, 'Y-n-j')?></td>
</tr>