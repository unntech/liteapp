<?php

require __DIR__.'/../autoload.php';

//第一个参数 密钥；第二个参数 向量，为空则自动生成； 第三个参数加密方式，默认aes-128-cfb
$liCrypt = new LitePhp\LiCrypt(DT_KEY);

//获取可用加密方式
$cipher = $liCrypt -> getCipher();

//改变加密方式
//$liCrypt -> setCipher('AES-128-CBC');

//改变密钥和向量，第一个参数 密钥；第二个参数 向量，为空则自动生成；
//$liCrypt -> setKey(DT_KEY);

//获取Token
$jwt = ['sub'=>'abc', 'exp'=>time()+1000];
$token = $liCrypt->getToken($jwt);
var_dump($token);
//验证Token，不通过返回 false, $liCrypt->err 错误代码
$verify = $liCrypt->verifyToken($token);
var_dump($verify);
