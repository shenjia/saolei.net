<?php
class VideoController extends Controller
{
    public $defaultAction = 'list';
	public $publicActions = array( 
		'list', 'download', 'view' 
	);
	
	public function actionReview($id = null, $status = VideoConfig::STATUS_NORMAL) 
	{
	    // review videos list
	    if (is_null($id)) {
	        $pages = new Pages(Video::count('status=' . $status));
	        $videos = Video::findByStatus($status, $pages->offset, $pages->limit);
	        $ids = Value::getFields($videos, 'id');
	        $this->render('/video/reviewList', array(
	            'pages' => $pages,
	            'status' => $status,
	            'videos' => Video::getListInfo($ids)
	        ));
    	    
	    } 
	    // review one video
	    else {
	        $stat = VideoStat::getStat($id);
	        Review::video(User::getCurrentId(), $id, $status)
    	        ? Flash::success('video/review_success_' . $status, 'app.refresh()')
    	        : Flash::error('video/review_failed');
	    } 
	}
	
	public function actionView($id)
	{
	    $video = Video::getFullInfo($id);
        $this->layout = 'two_columns';	    
	    $this->title = Yii::t('video', $video->level) . Format::score_time($video->scores['time']) . '秒' . ($video->info->noflag ? 'NF' : '')
	                 . ' | ' . $video->author->chinese_name;
	    if ($video) {
	        $video->stat->uniqueAction('click');
            $this->render('view', array(
                'video' => $video,
                'comments' => Comment::getList($id, 0, CommentConfig::TOP_NUMBER)
            ));	        
	    } else {
	        throw new CHttpException(404);   
	    }
	}
		
	public function actionList($level = 'all', $order = 'id', $author = null)
	{
	    if (!in_array($order, array('id', 'time', '3bvs'))) $order = 'id';
	    $user = User::findById($author);
	    if (!$user) $author = null;
	    
	    if ($level == 'all') {
	        $order = 'id';
	        $pages = new Pages(Video::count($user ? 'user=' . intval($author) : ''));
	        $videos = Video::listRecentVideos($author, $pages->offset, $pages->limit);
	        $ids = Value::getFields($videos, 'id');
	    } else {
	        $pages = new Pages(VideoScores::count($level, $author));
	        $scores = VideoScores::listHighScores($level, $author, $order, $pages->offset, $pages->limit);
	        $ids = Value::getFields($scores, 'id');
	    }
	    $this->title = $author ? $user->chinese_name . '的' . Yii::t('video', $level) . '录像'
	                           : Yii::t('video', $level) . '录像';
	    $this->render('/video/list', array(
		    'pages'  => $pages,
		    'videos' => Video::getListInfo($ids), 
		    'level'  => $level,
		    'order'  => $order,
	        'user'   => $user
		));
	}
	
	public function actionUpload ( )
	{
		if ($_POST['name']) {
		    Video::upload(User::getCurrentId());   
		}
		$this->render('upload');
	}
	
	public function actionDownload ( $id )
	{
	    $video = Video::getFullInfo($id);
	    $video->stat->uniqueAction('download');
	    header("Content-Disposition: attachment; filename= \"" . $video->filename . "\"");
        header("Content-Type: application/octet-stream");
        header('X-Accel-Redirect: ' . VIDEO_PATH . $video->info->filepath); 
        header("X-Accel-Buffering: yes");
        header("X-Accel-Limit-Rate :" . DOWNLOAD_LIMIT_RATE);
        //header("Accept-Ranges: none");//单线程 限制多线程
	}
}
