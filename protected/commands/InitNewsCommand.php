<?php
class InitNewsCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        Yii::app()->db->createCommand()->truncateTable('news');
        Yii::app()->db->createCommand()->truncateTable('user_scores');
        Yii::app()->db->createCommand()->truncateTable('user_scores_nf');
        $videos = VideoModel::model()->iterate(array(
            'order' => 'id asc'
        ));
        foreach ($videos as $video) {
            VideoScores::insertVideo($video);
            echo '.';
        }
    }
}