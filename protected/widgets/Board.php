<?php
class Board extends CWidget
{
    public $id;
    public $level;
    public $board;
    public $class;
    public $zoomable = false;
    public $size = 8; 
    
    public function init()
    {
        if (!isset($this->id)) $this->id = uniqid(); 
    }
    
    public function run()
    {
        $this->render('board/board');
        Javascript::register(array($this->id => "app.drawBoard('$this->id');"));
    }
}