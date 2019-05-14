<?php
require_once __DIR__.'/protected/config/define.php';

$yii = __DIR__.'/protected/vendors/yii/yii.php';
$config = __DIR__.'/protected/config/main.php';

require_once($yii);
Yii::createWebApplication($config)->run();
