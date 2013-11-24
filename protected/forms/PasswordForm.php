<?php
class PasswordForm extends CFormModel
{
	public $password;
	public $new;
	public $new_repeat;
	
	public function rules()
	{
		return array(
			array('password, new, new_repeat', 'required'),
			array('password', 'authenticate'),
			array('new', 'length', 'max'=>20, 'min'=>6),
			array('new_repeat', 'repeat'),
		);
	}
	
	public function authenticate()
	{
		if (!UserAuth::checkPassword(Yii::app()->user->username, $this->password)) {
			$this->addError('password', Yii::t('form/password-form', 'wrong_password'));
		}
	}
	
	public function repeat()
	{
		if ($this->new !== $this->new_repeat)
			$this->addError('new_repeat', Yii::t('form/password-form', 'not_repeat'));
	}
	
	public function saveToDb()
	{
		UserAuth::setPassword(Yii::app()->user->username, $this->new);
	}
	
	public function attributeLabels()
	{
		return array(
			'password' 		=> Yii::t('form/password-form', 'password'),
			'new' 			=> Yii::t('form/password-form', 'new'),
			'new_repeat'	=> Yii::t('form/password-form', 'new_repeat'),
		);
	}
	
}