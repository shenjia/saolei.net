<?php
class TestCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        var_dump(Ranking::getPage(5138, 'beg', 'time', 2));
        var_dump(Ranking::getPage(6786, 'beg', 'time', 2));
        echo 'test' . PHP_EOL;
    }
}