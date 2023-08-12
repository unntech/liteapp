<?php

require 'autoload.php';
$title = 'LiteApp @ UNN.tech';

$a = array('loop 1','loop 2','loop 3');

$ga = new LitePhp\GoogleAuthenticator(); //谷歌验证器示例
$secret = $ga->createSecret();
$qrCodeUrl = $ga->getQRCodeGoogleUrl('liteapp.unn.tech', $secret, 'LiteApp @ UNN.tech '); //第一个参数是"标识",第二个参数为"安全密匙SecretKey" 生成二维码信息

$n = 123456789.363;

$Lite->setCookie('KEY','VALUES');
$c = $Lite->getCookie('KEY');

$CSS = ['index'];
include LitePhp\Template::load('index');
