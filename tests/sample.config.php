<?php

require __DIR__.'/../autoload.php';

//载入配置，参数是字符串为单文件，数组为多文件；load在一个进程里只需载入一次
$config = $Lite->config->load(['app']); 

//获取配置变量
$config = $Lite->config->get('app');
//支持多级配置 .号分割，第二个参数可设默认值。
$appName = $Lite->config->get('app.name', 'LitePhp');

var_dump($config, $appName);