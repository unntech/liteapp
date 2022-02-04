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

$Lite = new LiteApp\LiteApp();
$version = LiteApp\LiteApp::VERSION;
$DT_TIME = time();
$DT_IP = LitePhp\LiComm::ip();
$title = $Lite->config->get('app.name', 'LitePhp');
\LitePhp\Template::init(DT_ROOT, $Lite->config->get('app.template', 'default'), DT_ROOT."/runtime/cache");

define('DT_KEY', $Lite->config->get('app.authkey', 'LitePhp'));
define('DT_SKIN', 'template/'.$Lite->config->get('app.template', 'default')."/skin");