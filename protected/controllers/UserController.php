<?php
class UserController extends Controller
{
	public function actionHome()
	{
		
	}
	
	public function actionDelete()
	{
		
	}
	
	public function actionProfile()
	{
		
	}
	
	public function actionBan()
	{
		
	}
	
	public function actionAccess()
	{
		
	}
	
	public function actionView($id)
	{
	    $user = User::getFullInfo($id);
        $this->layout = 'two_columns';	    
	    $this->title =  $user->chinese_name;
	    if ($user) {
	        $user->stat->uniqueAction('click');
            $this->render('view', array(
                'user' => $user,
                'news' => News::getRecentNews(null, $id, 0, UserConfig::NEWS_NUMBER)
            ));	        
	    } else {
	        throw new CHttpException(404);   
	    }
	}
	
	public function actionMoreNews($id, $cursor) 
	{
	    
	}
	
	
}
