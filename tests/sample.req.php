<?php

require __DIR__.'/../autoload.php';

$req = new LiteApp\api\ApiBase();
$data = [
    'head'  =>  [
        'app' => 'IOS',
        'unique_id' => $_SERVER['UNIQUE_ID'],
        'token' => 'hjdp6DnmCZWgQCSzQ43d1Eo7YhES2VZ5I86OOY/L1nY7kqn7gSGdO4SLMg9HIUppdGEEdVz7HyFB1F3eb600A98DqoqcJQrIEis1DmKLLbKQJCtXbi', //用户登入token
        'timestamp' => $DT_TIME,
    ],
    'body'  => [
        'order_id'=>123,
        'money'=>1001.23,
    ],
    'signType'=>'RSA',
    'encrypted'=>true,
];

$data = $req->request($data);
var_dump($data);
$request = json_encode($data);
var_dump($request);