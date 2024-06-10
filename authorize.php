<?php
/**
 * 获取API接口secret
 * 简单示例，生产环境根据自己的应该需求编写自己的生成和保存规则
 */
require 'autoload.php';

use LitePhp\Redis;
use LitePhp\SnowFlake;

$req = json_decode(file_get_contents("php://input"), true);
$sub = $req['head']['uuid'] ?? uniqid();

$apiBase = new LiteApp\api\ApiBase();
/**---
   按需求验证请求身份，验证通过后给予核发TOKEN

if(!empty($req['head']['uuid'])){
    if(empty($req['bodyEncrypted'])){
        $apiBase->error(401,'授权请求数据必须RSA加密');
    }

    $d = $apiBase->bodyDecrypt($req['bodyEncrypted']);
    if($d['uuid'] != $req['head']['uuid']){
        $apiBase->error(401, '授权请求数据错误！');
    }
}

-------*/

$pass = true; //假设验证通过

if($pass){
    $Aes = new LitePhp\LiCrypt(DT_KEY);
    $exp = $DT_TIME + 7200;
    $jwt = ['sub'=>$sub, 'exp'=>$exp];
    $key = $_SERVER['UNIQUE_ID'] ?? uniqid() . dechex(SnowFlake::generateParticle());
    $token = $Aes->getToken($jwt);
    $data = [
        'secret'        =>  $token,
        'expires_in'    =>  ($exp - 30) * 1000, //过期时间按毫秒适应APP前端
        'sessid'        =>  $key,
        'signType'      =>  'MD5',
    ];

    //服务端保存secret值
//    if(!empty($req['head']['uuid'])){
//        $Lite->set_redis();
//        Redis::set('uuid:'.$req['head']['uuid'], $token, 7200);
//    }

    $apiBase->success($data);

}else{

    $apiBase->error(401, '授权失败');
}

