<?php
class UpgradeForm extends CFormModel
{
	public $username;
	public $area;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array( 'area', 'required' ),
			array( 'username', 'required' ),
			array( 'username', 'email' ),
			array( 'username', 'unique' ),
		);
	}
	
	public function unique() 
	{
	    if (UserAuth::findByName($this->username)) {
	        $this->addError('username', Yii::t('form/upgrade-form','already_exists'));
	    }
	}
	

	public function attributeLabels()
	{
		return array(
			'username' => Yii::t( 'user', 'username' ),
			'area' => Yii::t( 'user', 'area' )
		);
	}
}
