<?php

defined('IN_LitePhp') or exit('Access Denied');

return [
    'save'   => 'file',  // redis, file  生产环境建议用redis
    'handle' => null,     // redis句柄
    'path'   => '/tmp/',  // file时SESSION数据保存路径
];