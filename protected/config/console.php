<?php
require_once dirname(__FILE__).'/define.php';
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Saolei.net',
	// application components
	'import'=>array(
		'application.config.*',
		'application.logic.*',
		'application.models.*',
		'application.forms.*',
		'application.helpers.*',
		'application.components.*',
		'application.widgets.*',
		'zii.widgets.CMenu'
	),
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=saolei',
			'emulatePrepare' => true,
			'username' => 'saolei',
			'password' => 'aoeuaoeu',
			'charset' => 'utf8',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
	),
);