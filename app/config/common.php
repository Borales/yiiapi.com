<?php
return array(
	'basePath' => realPath(__DIR__ . '/..'),
	'aliases' => array(
		'vendor' => 'application.lib.vendor'
	),
	'import' => array(
		'application.components.*',
	),
	'components' => array(
		'cache' => array(
			'class' => 'CFileCache'
		),
	),
	'params' => array(
		// php configuration
		'php.defaultCharset' => 'utf-8',
		'php.timezone'       => 'UTC',
	)
);