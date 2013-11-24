<?php
class RegisterForm extends CFormModel
{
	public $username;
	public $password;
	public $chinese_name;
	public $english_name;
	public $sex = 1;
	public $area;
	
	public function rules()
	{
		return array(
			array('username, password, chinese_name, english_name, area', 'required'),
			array('username', 'uniqueName'),
			array('username', 'email'),
			array('password', 'length', 'max' => 20, 'min' => 6),
			array('chinese_name', 'match', 'pattern' => '/^[\x{4e00}-\x{9fa5}]+$/u'),
			array('chinese_name', 'length', 'max' => 6, 'min' => 2),
			array('english_name', 'match', 'pattern' => '/^([A-Z][a-z]{0,5}\s?){2,6}$/'),
			array('english_name', 'matchchinese_name'),
			array('sex', 'boolean')
		);
	}
	
	public function uniqueName($attribute, $params)
	{
		if (UserAuth::findByName($this->username))
			$this->addError('username', Yii::t('user', 'username') . ' "' . $this->username . '"' . Yii::t('form/register-form', 'already_exists'));
	}
	
	public function matchchinese_name($attribute, $params)
	{
		if (count(explode(' ', $this->english_name)) !== mb_strlen($this->chinese_name, 'utf-8'))
			$this->addError('english_name', Yii::t('form/register-form', 'english_name_dont_match'));
	}
	
	public function createUser()
	{
	    $user = array();
		foreach ($this->attributeNames() as $attribute) {
			$user[ $attribute ] = $this->$attribute;
		}
		return User::register($user);
	}
	
	public function attributeLabels()
	{
		return array(
			'username' 		=> Yii::t('user', 'username'),
			'password' 		=> Yii::t('user', 'password'),
			'chinese_name' 	=> Yii::t('user', 'chinese_name'),
			'english_name' 	=> Yii::t('user', 'english_name'),
			'sex' 			=> Yii::t('user', 'sex'),
			'area' 	        => Yii::t('user', 'area'),
		);
	}
	
}