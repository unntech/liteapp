<?php
/**
 * 获取API接口secret
 * 简单示例，生产环境根据自己的应该需求编写自己的生成和保存规则
 */
require 'autoload.php';

use LitePhp\Redis;

/*---
   按需求验证请求身份，验证通过后给予核发TOKEN
        */

$pass = true; //假设验证通过
$apiBase = new LiteApp\api\ApiBase();
if($pass){
    $Aes = new LitePhp\LiCrypt(DT_KEY);
    $exp = $DT_TIME + 7200;
    $jwt = ['sub'=>123, 'exp'=>$exp];
    $key = $_SERVER['UNIQUE_ID'] ?? uniqid();
    $token = $Aes->getToken($jwt);
    $data = [
        'secret'        =>  $token,
        'expires_in'    =>  $exp,
        'sessid'        =>  $key,
    ];

    //服务端保存secret值
    //$Lite->set_redis();
    //Redis::set($key, $token, 7200);

    $apiBase->success($data);

}else{

    $apiBase->error(401, '授权失败');
}

