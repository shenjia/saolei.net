<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    const TITLE_SPLITTER = ' | ';
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();
	
	public $publicActions = array( '*' );
	
	public $pageTitle = '';
	
	public $skipTitles = array();
	
	public function init()
	{
		Language::init();
		$this->generateTitle();
	}
	
	/**
	 * 校验是否需要登录
	 * @see CController::beforeAction()
	 */
	protected function beforeAction ( $action )
	{
		if ( in_array( '*', $this->publicActions ) ) return true;
		
		if ( in_array( $action->getId(), $this->publicActions ) ) return true;
		
		if ( Yii::app()->user->isGuest ) {
		    if ( Yii::app()->request->isAjaxRequest ) {
		        $callback = 'location.href="' . Yii::app()->user->loginUrl . '";';
		        Flash::error('auth/login_timeout', $callback);
		    }
		    Yii::app()->user->loginRequired();
		}
	    return true;
	}
	
	/**
	 * 根据url生成网页标题
	 */
	public function generateTitle ( $path_sep = '/' )
	{
		$path_info = Yii::app()->request->pathinfo;
		$title = Yii::t( 'title', 'site' );
		
		if ( !empty( $path_info ) ) {
			$items = explode( $path_sep, $path_info );
			foreach ( $items as $item ) {
			    if ( !in_array( $item, $this->skipTitles ) ) {
				    $title = Yii::t( 'title', $item ) . self::TITLE_SPLITTER . $title;
			    } 
			}
		}
		
		$this->pageTitle = $title;
	}	

	/**
	 * 手动指定标题
	 */
	public function setTitle($title)
	{
	    $titles = is_array($title) ? $title : array($title);
	    $subtitle = '';
	    foreach ($titles as $title) {
	        $subtitle .= Yii::t('title', $title) . self::TITLE_SPLITTER; 
	    }
		$this->pageTitle = $subtitle . Yii::t( 'title', 'site' );	
	}

	/**
	 * 取得当前标题
	 */
	public function getTitle()
	{
		return str_replace(self::TITLE_SPLITTER . Yii::t( 'title', 'site' ), '', $this->pageTitle);	
	}

	/**
	 * 取得参数
	 */
    public function getActionParams()
	{
		return array_merge( $_GET, $_POST );
	}
}