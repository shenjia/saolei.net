<?php
class AccountController extends Controller
{
	public $defaultAction = 'center';
	public $publicActions = array( 
		'register', 'login', 'logout'
	);
	
	/**
	 * 我的资料
	 */
	public function actionCenter()
	{
	    $this->layout = 'two_columns';
		$this->render( '/account/center', array(
			'user' => User::getCurrentUser()
		));
	}
	
	/**
	 * 账户升级
	 */
	public function actionUpgrade() 
	{
	    $user = User::getCurrentUser();
	    if (!$user->auth->needUpgrade) $this->redirect('/account');
	
		$model = new UpgradeForm;
		$model->area = $user->area;
		
		if (isset($_POST['ajax']) && $_POST['ajax']==='upgrade-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if (isset($_POST['UpgradeForm'])) {
			$model->attributes=$_POST['UpgradeForm'];
			if ($model->validate()) {
			    $user->auth->username = $model->username;
			    $user->auth->save();
			    Yii::app()->user->setState('username', $model->username);
    			Flash::success('form/upgrade-form/success', "app.go('/account')");
			}
		}
		
	    $this->layout = 'two_columns';
		$this->render( '/account/upgrade', array(
		    'model' => $model,
			'user' => $user
		)); 
	}
	
	
	/**
	 * 用户注册
	 */
	public function actionRegister()
	{
		$model = new RegisterForm;
		
		if (isset($_POST['ajax']) && $_POST['ajax']==='register-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['RegisterForm'])) {
			$model->attributes=$_POST['RegisterForm'];
			if ($model->validate()) {
			    if ($model->createUser()) {
    				$login = new LoginForm;
    				$login->username = $model->username;
    				$login->password = $model->password;
    				$login->rememberMe = true;
    				$login->login();
    				Flash::success('form/register-form/success', "app.go('/page/help')");
			    } else {
			        Flash::error('form/register-form/fail');
			    }			
			}
		}
		$this->render( '/account/register', array('model'=>$model) );
	}
	
	/**
	 * 用户登陆
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		if( isset($_POST['ajax']) && $_POST['ajax']==='login-form' )
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			if ($model->validate() && $model->login()) {
			    filter_var($model->username, FILTER_VALIDATE_EMAIL)
			        ? $this->redirect(Yii::app()->user->returnUrl)
			        : $this->redirect('/account/upgrade');
			}
		}

		$this->render( '/account/login', array( 'model' => $model ) );
	}
	
	/**
	 * 退出登陆
	 */
	public function actionLogout()
	{
	    $url = Yii::app()->user->returnUrl ? Yii::app()->user->returnUrl : Yii::app()->homeUrl;
		Yii::app()->user->logout();
		$this->redirect( $url );
	}
	
	/**
	 * 修改资料
	 */
	public function actionProfile()
	{
		$model = new ProfileForm;
		
		$model = $this->fillProfile( $model );

		if( isset($_POST['ajax']) && $_POST['ajax']==='profile-form' )
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['ProfileForm']))
		{
			$model->attributes=$_POST['ProfileForm'];
			if($model->validate()) {
				$model->saveToDb();
				Flash::success('form/profile-form/success');
			}
		}

		$this->layout = 'two_columns';
		$this->render( '/account/profile', array( 
			'model' => $model,
		    'user' => User::getCurrentUser()
		) );			
	}

	
	/**
	 * 修改密码
	 */
	public function actionPassword()
	{
		$model = new PasswordForm;
		
		if (isset($_POST['ajax']) && $_POST['ajax']==='password-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['PasswordForm'])) {
			$model->attributes=$_POST['PasswordForm'];
			if($model->validate()) {
				$model->saveToDb();
				Flash::success('form/password-form/success');
			}
		}

		$this->layout = 'two_columns';
		$this->render( '/account/password', array( 
			'model' => $model,
		    'user' => User::getCurrentUser()
		) );			
	}
	
	/**
	 * 读取资料
	 * @param array $model
	 */
	private function fillProfile($model)
	{
	    $user = User::getCurrentUser();
		foreach ($model->attributeNames() as $attribute) {
			if (isset($user->info->$attribute)) {
			     $model[$attribute] = $user->info->$attribute;
			}
			
		}
		if ($user->info->birthday) {
		    $model->birth_day   = date('j', $user->info->birthday);
		    $model->birth_month = date('n', $user->info->birthday);
		    $model->birth_year  = date('Y', $user->info->birthday);
		}
		return $model;
	}
	
}