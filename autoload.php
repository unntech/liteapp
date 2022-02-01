<?php


define('DT_DEBUG', 1);  //0用于生产， 1开启调试开发模式
if(DT_DEBUG) {
	error_reporting(E_ALL);
	$debug_starttime = microtime(true);
} else {
	error_reporting(E_ERROR);
}


define('IN_LitePhp', true);
define('DT_ROOT', str_replace("\\", '/', dirname(__FILE__)));

require_once DT_ROOT . '/vendor/autoload.php';

$Lite = new \LiteApp\LiteApp();
$DT_TIME = time();
$title = $Lite->config->get('app.name', 'LitePhp');