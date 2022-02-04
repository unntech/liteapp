<?php

require __DIR__.'/../autoload.php';

//生成RSA公私钥
$unnrsa = new LitePhp\LiRsa();
$c = $unnrsa->createKey();
var_dump($c);

$pubKey = $c['pub'];
$priKey = $c['priv'];

$unnrsa = new LitePhp\LiRsa( $pubKey, $priKey );
$unnrsa->SetThirdPubKey($pubKey);
$a = '测试RSA2';
//生成RSA签名
$sign = $unnrsa->sign( $a );
//验证RSA签名
$y = $unnrsa->verifySign( $a, $sign, $pubKey );
var_dump( $sign, $y );

$arr = array('order'=>'20200826001','money'=>200);
//生成RSA签名数据数组
$arr = $unnrsa->signArray($arr);
//验证RSA签名数组
$y = $unnrsa->verifySignArray($arr);
var_dump($arr,$y);
//RSA加密
$x = $unnrsa->encrypt( $a );
//RSA解密
$y = $unnrsa->decrypt( $x );
var_dump( $x, $y );

