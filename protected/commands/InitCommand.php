<?php
class InitCommand extends CConsoleCommand
{
    public function actionDistribution($args) 
    {
        Distribution::init();
        var_dump(Distribution::get('sum_time'));
        echo 'done.' . PHP_EOL;
    }
    
    public function actionNews($args) 
    {
        $this->truncateTables(array(
            'news', 'user_scores', 'user_scores_nf'
        ));
        $videos = VideoModel::model()->iterate(array(
            'order' => 'id asc'
        ));
        foreach ($videos as $video) {
            VideoScores::insertVideo($video);
            echo '.';
        }
        echo 'done.' . PHP_EOL;
    }
    
    public function actionUserScores($args) 
    {
        $users = UserModel::model()->iterate(array('select' => 'id'));
        foreach ($users as $user) {
            UserScores::init($user->id, false);
            UserScores::init($user->id, true);
            echo '.';    
        }
    }
    
    private function truncateTables($tables) {
        Value::toArray($tables);
        foreach ($tables as $table) {
            if (Yii::app()->db->createCommand()->truncateTable($table)) {
                echo 'Table [ ' . $table . ' ] was truncated.' . PHP_EOL;   
            }
        }
    }
}