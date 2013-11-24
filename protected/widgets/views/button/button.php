<button
    <?php if ( $this->id ):?>
    id="<?= $this->id ?>"
    <?php endif; ?>
    type="<?= $this->type ?>"
    name="<?= $this->name ?>"
    onclick="<?php $this->render( 'button/onclick' )?>"
	<?php foreach ( $this->property as $key => $value ) echo ' ' . $key . '="' . $value . '" ';?>
	class="<?php $this->render( 'button/class' ) ?>"
   ><?= $this->title ?><?php if ( $this->badge ):?>(<?= $this->badge ?>)<?php endif;?>
</button>
