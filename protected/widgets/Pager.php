<?php
class Pager extends CLinkPager
{
	public $header = '<div class="pager-container">';
	public $footer = '</div>';
	public $htmlOptions = array( 'class' => 'pager' );
	public $maxButtonCount = 7;
	public $cssFile = false;
	
	public function init()
	{
	    $this->firstPageLabel = 1;
	    $this->lastPageLabel = $this->getPageCount();
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$this->getId();
	}
	
	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons=array();

		// first page
		if ($beginPage > 0)
		    $buttons[]=$this->createPageButton($this->firstPageLabel,0,$this->firstPageCssClass,$currentPage<=0,false);
		if ($beginPage > 1)
		    $buttons[]=$this->createPageSplitter();

		// internal pages
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);

		// last page
		if ($endPage < $pageCount - 2)
		    $buttons[]=$this->createPageSplitter();
		if ($endPage < $pageCount - 1)
		    $buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,$this->lastPageCssClass,$currentPage>=$pageCount-1,false);

		return $buttons;
	}
	
	protected function createPageSplitter()
	{
	    return CHtml::tag('li', array('class' => 'splitter'), '...'); 
	}
}