<?php

define('IN_LitePhp', true);
define('DT_ROOT', str_replace("\\", '/', dirname(__FILE__)));

require_once DT_ROOT . '/vendor/autoload.php';

$Lite = new LiteApp\LiteApp();

define('ENVIRONMENT', $Lite->config->get('app.ENVIRONMENT', 'DEV'));
define('DT_DEBUG', $Lite->config->get('app.APP_DEBUG', true));
if (DT_DEBUG) {
    error_reporting(E_ALL);
    $debug_starttime = microtime(true);
} else {
    error_reporting(E_ERROR);
}

$version = LiteApp\LiteApp::VERSION;
$DT_TIME = time();
$DT_IP = LitePhp\LiComm::ip();
$title = $Lite->config->get('app.name', 'LitePhp');
\LitePhp\Template::init(DT_ROOT, $Lite->config->get('app.template', 'default'), DT_ROOT . "/runtime/cache");

define('DT_KEY', $Lite->config->get('app.authkey', 'LitePhp'));
define('DT_SKIN', '/template/' . $Lite->config->get('app.template', 'default') . "/skin");
define('DT_STATIC', '/template/static');

require_once DT_ROOT . '/include/common.php';
