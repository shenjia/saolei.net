<ul class="tabs">
	<?php foreach ( $items as $item => $title ):?>
	<li id="li_<?= $name ?>_<?= $item ?>" <?php if ( $item == $this->options[ $name ] ):?>class="current"<?php endif;?>>
		<a href="<?= $this->getUrl( $name, $item ) ?>"><?= $title ?></a>
	</li>
	<?php endforeach;?>
</ul>