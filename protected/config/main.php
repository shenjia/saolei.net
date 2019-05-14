<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Saolei.net',
    'defaultController'=>'home',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.config.*',
		'application.logic.*',
		'application.models.*',
		'application.forms.*',
		'application.helpers.*',
		'application.components.*',
		'zii.widgets.CMenu'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'caseSensitive'=>false,
			'showScriptName'=>false,
			'rules'=>array(
				'page/<page:\w+>'=>'site/page/view/<page>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=saolei',
			'emulatePrepare' => true,
			'username' => 'saolei',
			'password' => 'aoeuaoeu',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
		    'class' => 'ErrorHandler',
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
                    'categories'=>'application.*',  
				),
				/*
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, trace',
                    'categories'=>'upyun.*',
					'logfile'=>'upyun.log'  
				),
				*/
				// uncomment the following to show log messages on web pages
				array(
					'class'=>'CWebLogRoute',
				    'categories' =>'system.db.*'
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
	),
);