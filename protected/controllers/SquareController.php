<?php
class SquareController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');	
	}
	
	public function actionUser()
	{
		echo 'user';
	}
	
	public function actionActiveUser()
	{
		echo 'activeUser';
	}
	
	public function actionNewUser()
	{
		echo 'newUser';
	}
	
	public function actionHotUser()
	{
		echo 'hotUser';
	}
	
	public function actionVideo()
	{
		echo 'video';
	}
	
	public function actionNewVideo()
	{
		echo 'newVideo';
	}
	
	public function actionHotVideo()
	{
		echo 'hotVideo';
	}
}