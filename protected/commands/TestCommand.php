<?php
class TestCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        var_dump(Yii::app()->db->password);die('----------------------');
        echo 'test' . PHP_EOL;
    }
}