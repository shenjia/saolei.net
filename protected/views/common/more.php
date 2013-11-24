<?php if (!isset($id)) $id = 'more_' . uniqid();?>
<div id="<?= $id ?>">
<?php 
$this->widget('Button', array(
	'name' => 'more', 
	'ajax' => $url . (strpos($url, '?') > 0 ? '&' : '?') . 'size=' . $pagesize . '&cursor=\' + $(this).data(\'cursor\') + \'',
    'dataType' => 'json', 
    'success' => '$(\'' . $container . '\').append(data.items);$(\'#' . $id . ' button\').data(\'cursor\', data.cursor);if(data.count<' . $pagesize . ')$(\'#' . $id . '\').hide()',
    'size' => 'small',
    'property' => array('data-cursor' => $cursor)
));
?>
</div>