<?php
class HomeController extends Controller
{
    public $publicActions = array( 
		'index', 'morenews'
	);
	
    public function actionIndex()
	{
	    $top_scores = UserScores::getHighScores('sum', 'time', false, 0, HomeConfig::TOP_NUMBER);
	    $top_ids = Value::getFields($top_scores, 'id');
	    
	    $this->layout = 'two_columns';
		$this->render('index', array(
		    'news'    => News::getRecentNews(null, null, 0, HomeConfig::NEWS_NUMBER),
		    'newbies' => News::getRecentNews(NewsConfig::TYPE_NEWBIE, null, null, HomeConfig::NEWBIE_NUMBER),
		    'top'     => User::getListInfo($top_ids)
		));
	}
	
}