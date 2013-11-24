<div class="switcher" id="<?= $this->id ?>">
	<?php foreach ($this->cases as $case):?>
	<a href="<?= $this->generateURL($case)?>" class="<?= $case?><?= ($case == $this->current) ? ' current' : ''?>"></a>
	<?php endforeach;?>
</div>