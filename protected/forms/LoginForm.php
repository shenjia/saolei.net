<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array( 'username, password', 'required' ),
			// rememberMe needs to be a boolean
			array( 'rememberMe', 'boolean' ),
			// username needs to be existed
			array( 'username', 'shouldExists' ),
			// password needs to be authenticated
			array( 'password', 'authenticate' )
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t( 'user', 'username' ),
			'password' => Yii::t( 'user', 'password' ),
			'rememberMe' => Yii::t( 'form/login-form', 'rememberMe' ),
		);
	}

	/**
	 * Check the user.
	 */
	public function shouldExists($attribute,$params)
	{
		if (!UserAuth::findByName($this->username))
			$this->addError('username', Yii::t('form/login-form','user_not_exists'));
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if (!UserAuth::checkPassword($this->username, $this->password))
			$this->addError('password', Yii::t('form/login-form','wrong_password'));
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 dayss
			$user = UserModel::model()->findByPk($this->_identity->id);
			Yii::app()->user->login($this->_identity,$duration);
			User::increaseLoginTimes($this->_identity->id);
			return true;
		}
		else
			return false;
	}
}
