<?php
class TestCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        $video = VideoModel::model()->find();
        var_dump($video->scores);die('----------------------');
        echo 'test' . PHP_EOL;
    }
}