<?php
defined('APP_CONFIG_NAME') or define('APP_CONFIG_NAME', 'main');

use Yiinitializr\Helpers\ArrayX;

// web application configuration
return ArrayX::merge(array(
	'name' => 'Unofficial Yii API Documentation',
	'sourceLanguage' => 'en',
	'language' => 'en',
	'defaultController' => 'doc',

	// path aliases
	'aliases' => array(

	),

	// application behaviors
	'behaviors' => array(),

	// controllers mappings
	'controllerMap' => array(),

	// application modules
	'modules' => array(),

	// application components
	'components' => array(

		'clientScript' => array(
			'packages'=>array(
				'jquery'=>array(
					'baseUrl'=>'//ajax.googleapis.com/ajax/libs/jquery/1/',
					'js'=>array('jquery.min.js'),
				)
			),
		),

		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				'/' => 'doc/index',
				'<name:\w+>' => 'doc/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'errorHandler' => array(
			'errorAction' => 'doc/error',
		)
	),
	// application parameters
	'params' => array(),
), require_once('common.php')
);