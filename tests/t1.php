<?php

require __DIR__.'/../autoload.php';



$config = $Lite->config->get('app');

echo ip2long('10.100.112.3');
echo long2ip(174354435);

\LitePhp\Template::message('这是一个提示示例', '错误提示');
