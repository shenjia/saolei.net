<?php
class InitUserScoresCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        $users = UserModel::model()->iterate(array('select' => 'id'));
        foreach ($users as $user) {
            UserScores::init($user->id, false);
            UserScores::init($user->id, true);
            echo '.';    
        }
    }
}