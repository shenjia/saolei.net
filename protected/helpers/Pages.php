<?php
class Pages extends CPagination
{
	const DEFAULT_PAGE_SIZE = 20;
	
	public $validateCurrentPage = false;
	
	public function __construct ( $itemCount = 0 )
	{
		$this->setItemCount( $itemCount );
		$this->setPageSize( self::DEFAULT_PAGE_SIZE );
	}
}