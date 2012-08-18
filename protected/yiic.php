<?php

// change the following paths if necessary
$yiiBase=dirname(__FILE__).'/../yii';
$yiic=$yiiBase.'/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';
$buildCommands=realpath($yiiBase.'/build');

if( !$buildCommands ) {
    die("You must set valid Yii framework path. \nCurrent is \"" . $yiiBase . "\"\n");
}

defined('YII_BUILD_COMMANDS_PATH') or define('YII_BUILD_COMMANDS_PATH', $buildCommands);

require_once($yiic);
