<?php

class SiteController extends Controller
{
    public $skipTitles = array( 'page' );
    
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if ($error = Yii::app()->errorHandler->error) {
	        $this->title = Yii::t('error', 'title');
	    	if (Yii::app()->request->isAjaxRequest) {
	    		echo $error['message'];
	    	} else {
	        	$this->render('error', $error);
	    	}
	    }
	}
	
	/**
	 * 切换语言
	 */
	public function actionSwitch()
	{
		$language = Request::getQuery( 'to', Language::getFromBrowser() );
		Language::set( $language );
		Javascript::refresh();
	} 

}