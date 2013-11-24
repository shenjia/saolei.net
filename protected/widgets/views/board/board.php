<div id="board_<?= $this->id ?>" 
class="board <?= $this->level ?> size<?= $this->size ?> <?= $this->class ?> <?= $this->zoomable ? 'zoomable' : '' ?>" 
data-board="<?= $this->board ?>" data-level="<?= $this->level ?>" data-size="<?= $this->size?>"
<?= $this->zoomable ? 'onclick="$(this).toggleClass(\'zoom\');"' : '';?>>
</div>