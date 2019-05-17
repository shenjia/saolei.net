<?php
class TestCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        $sql = sprintf("select count(*) as count from user where id=%d",39);
        echo $sql . PHP_EOL;
        
        $ret = Yii::app()->db->createCommand($sql)->queryScalar();
        var_dump($ret);

        
    }
}