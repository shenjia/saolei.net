<?php
class ProfileForm extends CFormModel
{
    public $qq;
    public $nickname;
    public $mouse;
    public $pad;
    public $interest;
	public $self_intro;
	public $birth_day;
	public $birth_month;
	public $birth_year;
	
	public function rules()
	{
		return array(
			array( 'qq', 'length', 'encoding' => 'utf-8', 'max' => UserConfig::INFO_QQ_LIMIT),
			array( 'nickname', 'length', 'encoding' => 'utf-8', 'max' => UserConfig::INFO_NICKNAME_LIMIT),
			array( 'mouse', 'length', 'encoding' => 'utf-8', 'max' => UserConfig::INFO_MOUSE_LIMIT),
			array( 'pad', 'length', 'encoding' => 'utf-8', 'max' => UserConfig::INFO_PAD_LIMIT),
			array( 'self_intro', 'length', 'encoding' => 'utf-8', 'max' => UserConfig::INFO_SELF_INTRO_LIMIT),
			array( 'interest', 'length', 'encoding' => 'utf-8', 'max' => UserConfig::INFO_INTEREST_LIMIT),
			array( 'birth_day', 'checkdate' ),
		);
	}
	
	public function checktext()
	{
		$overflow = mb_strlen( $this->self_intro, 'utf-8' ) - self::STATUS_LIMIT;
		if ( $overflow > 0 )
			$this->addError( 'self_intro', Yii::t( 'form/profile-form', '超出<em>' . $overflow . '</em>个字' ) );	
	}
	
	public function checkdate()
	{
		if ( !$this->birth_day || !$this->birth_month || !$this->birth_year ) {
			$this->birth_day = $this->birth_month = $this->birth_year = null;
		} else if ( !checkdate( $this->birth_month, $this->birth_day, $this->birth_year ) ) {
			$this->addError( 'birth_day', Yii::t( 'form/profile-form', 'invalid_date' ) );
		} else if ( Time::date2time( $this->birth_month . '/' . $this->birth_day . '/' . $this->birth_year ) > time() ) {
			$this->addError( 'birth_day', Yii::t( 'form/profile-form', 'invalid_date' ) );
		}
	}
	
	public function saveToDb()
	{
	    if ($info = UserInfo::findById(User::getCurrentId())) {
    	    foreach ($this->attributeNames() as $attribute) {
    			if (isset($info[$attribute])) $info[$attribute] = $this->$attribute; 
    		} 
    		if ($this->birth_day && $this->birth_month && $this->birth_year) {
    		    $info->birthday = Time::date2time($this->birth_month . '/' . $this->birth_day . '/' . $this->birth_year);
    		}
    		$info->save();
	    }
	}
	
	public function attributeLabels()
	{
		return array(
		    'qq'			=> Yii::t('user', 'qq'),
		    'nickname'		=> Yii::t('user', 'nickname'),
		    'mouse'			=> Yii::t('user', 'mouse'),
		    'pad'			=> Yii::t('user', 'pad'),
			'self_intro'    => Yii::t('user', 'self_intro'),
			'interest'      => Yii::t('user', 'interest'),	
			'birth_day'		=> Yii::t('user', 'birthday'),
		);
	}
	
}