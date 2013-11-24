<?php
class WebUser extends CWebUser
{
    protected $states = array(
        'username'     => '',
        'chinese_name' => '',
        'english_name' => '',
        'role'         => UserConfig::ROLE_UNKNOWN
    );
    
    public $loginRequiredAjaxResponse = ';';
    
    public $loginUrl = '/account/login';
    
	public function init()
	{
		parent::init();
		foreach ($this->states as $key => $value) {
    		if ( !$this->hasState( $key ) ) {
    			$this->setState( $key, $value );	
    		}
		}
	}	
}