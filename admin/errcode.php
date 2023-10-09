<?php

require '../autoload.php';
$title = "错误代码汇编";

$errcode = [
    1   => '无此权限，无法操作！',
    2   => 'TOKEN无效！',
    3   => '签名错误！',
    4   => '非当前登入用户',
    5   => '登入超时',
    9   => '非法无效请求',
    400 => '无效请求',
    401 => 'Unauthorized',
    402 => '支付请求错误',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    417 => 'Expectation Failed',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    509 => 'Bandwidth Limit Exceeded',
    1000=> '查无记录',
    1001=> '操作处理失败',
];

include LitePhp\Template::load('errcode', 'admin');