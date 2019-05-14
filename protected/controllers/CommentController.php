<?php
class CommentController extends Controller
{
    public $publicActions = array(
        'list'
    );
    
    public function actionPost($video, $content) 
    {
        $model = new CommentForm;
		
		if (isset($_POST['ajax']) && $_POST['ajax']==='comment-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['CommentForm'])) {
			$model->attributes=$_POST['CommentForm'];
			if ($model->validate() && $model->save()) {
				Flash::success('form/comment-form/success');
			} else {
			    Flash::error('form/comment-form/failed');
			}
		}	
    }
    
    
    public function actionMore($video, $cursor, $size = CommentConfig::PAGESIZE) 
    {
        if (Yii::app()->request->isAjaxRequest) {
    	    $comments = Comments::getlist($video, $cursor, $size);
    	    $items = '';
    	    foreach ($comments as $item) {
    	        $cursor = $item->id;
    	        $items .= $this->renderPartial('/comment/_cell', array('comment' => $item), true);
    	    }
    	    echo json_encode(array(
    	        'items' => $items,
    	        'cursor' => $cursor,
    	        'count' => count($comments)
    	    ));
	    }
    }
    
    
}