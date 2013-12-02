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
	    $pages->pageSize = RankingConfig::PAGESIZE;
	    $ranking = Ranking::users($level, $order, false, $pages->offset, $pages->limit);
	    $ids = Value::getFields($ranking, 'id');
	    
		$this->render('index', array(
		    'pages' => $pages,
		    'users' => User::getListInfo($ids),
		    'level' => $level,
		    'order' => $order
		));
	}
	
	public function actionWhereAmI($id, $level = RankingConfig::DEFAULT_LEVEL, $order = RankingConfig::DEFAULT_ORDER) 
	{
	    $level = strtolower($level);
	    $order = strtolower($order);
	    if (!in_array($order, RankingConfig::$orders)) $order = RankingConfig::DEFAULT_ORDER;
	    if (!in_array($level, RankingConfig::$levels)) $level = RankingConfig::DEFAULT_LEVEL;
	    $page = Ranking::getPage($id, $level, $order, RankingConfig::PAGESIZE);
	    Javascript::location('?level=' . $level . '&order=' . $order .'&page=' . $page . '#id_' . $id);
	}
	
	public function actionActive()
	{
		
	}
}
