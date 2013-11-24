<?php
class InitDistributionCommand extends CConsoleCommand
{
    public function actionIndex($args) 
    {
        Distribution::init();
        var_dump(Distribution::get('sum_time'));
    }
}