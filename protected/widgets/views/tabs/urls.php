<ul class="tabs">
	<?php foreach ( $items as $title => $url ):?>
	<li id="li_<?= ++$i ?>" <?php if ( $current == $i ):?>class="current"<?php endif;?>>
		<a href="<?= $url ?>"><?= $title ?></a>
	</li>
	<?php endforeach;?>
</ul>