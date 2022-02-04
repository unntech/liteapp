<?php

require __DIR__.'/../autoload.php';

$ga = new LitePhp\GoogleAuthenticator(); //谷歌验证器示例
$secret = $ga->createSecret();
$qrCodeUrl = $ga->getQRCodeGoogleUrl('phplite.unn.tech', $secret, 'PHPLite @ UNN.tech '); //第一个参数是"标识",第二个参数为"安全密匙SecretKey" 生成二维码信息

$code = '123456';
$checkResult = $ga->verifyCode($secret, $code); //验证输入谷歌验证器是否正确

var_dump($secret, $checkResult);
echo "<br>\n";
echo '<img src="'.$qrCodeUrl.'">';