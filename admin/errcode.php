<?php

require '../autoload.php';
$title = "错误代码汇编";

$errcode = [
    1   => '无此权限，无法操作！',
    2   => 'TOKEN无效！',
    3   => '签名错误！',
    4   => '非当前登入用户',
    5   => '登入超过',
    9   => '非法无效请求',
    1000=> '查无记录',
    1001=> '操作处理失败',
];

include LitePhp\Template::load('errcode', 'admin');