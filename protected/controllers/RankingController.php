<?php
class RankingController extends Controller
{
	public function actionIndex($level = RankingConfig::DEFAULT_LEVEL, $order = RankingConfig::DEFAULT_ORDER)
	{
	    $level = strtolower($level);
	    $order = strtolower($order);
	    if (!in_array($order, RankingConfig::$orders)) $order = RankingConfig::DEFAULT_ORDER;
	    if (!in_array($level, RankingConfig::$levels)) $level = RankingConfig::DEFAULT_LEVEL;
	    
	    $pages = new Pages(UserScores::count($level, $order));
	    $scores = UserScores::getHighScores($level, $order, false, $pages->offset, $pages->limit);
	    $ids = Value::getFields($scores, 'id');
	    
		$this->render('index', array(
		    'pages' => $pages,
		    'users' => User::getListInfo($ids),
		    'level' => $level,
		    'order' => $order
		));
	}
	
	public function actionTime()
	{
		
	}
		
	public function action3BVS()
	{
		
	}
	
	public function actionActive()
	{
		
	}
}
