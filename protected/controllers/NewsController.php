<?php
class NewsController extends Controller
{
	public function actionMore($cursor, $user = null, $size = PAGECOUNT) 
	{
	    if (Yii::app()->request->isAjaxRequest) {
    	    $news = News::getRecentNews(null, $user, $cursor, $size);
    	    $items = '';
    	    foreach ($news as $item) {
    	        $cursor = $item->id;
    	        $items .= $this->renderPartial('/news/_cell', array('news' => $item), true);
    	    }
    	    echo json_encode(array(
    	        'items' => $items,
    	        'cursor' => $cursor,
    	        'count' => count($news)
    	    ));
	    }
	}
}