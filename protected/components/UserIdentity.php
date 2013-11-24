<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$auth = UserAuth::findByName( $this->username );
		
		if ( $auth === null ) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else {
			if ( $auth['password'] !== md5( $this->password . $auth[ 'salt' ] ) ) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} else {
				$this->setState('id', $auth->id); 
				$this->setState('role', $auth->role);  
				$this->setState('chinese_name', $auth->user->chinese_name); 
				$this->setState('english_name', $auth->user->english_name);
				$this->errorCode = self::ERROR_NONE;
			}
		}
		
		return !$this->errorCode;
	}
	
	public function getId()
	{
	    return $this->getState('id');
	}
}