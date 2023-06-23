<?php

defined('IN_LitePhp') or exit('Access Denied');

return [
    'save'   => 'redis',  // redis, file
    'handle' => null,     // redis句柄
    'path'   => '/tmp/',  // file时SESSION数据保存路径
];