<?php

defined('IN_LitePhp') or exit('Access Denied');

return[
    'ENVIRONMENT'   => 'DEV',  // 'DEV', 'PRO'
    'APP_DEBUG'     =>  true,  //生产环境：ENVIRONMENT 设为 PRO, APP_DEBUG 设为 false
    'name' => 'LiteApp',
    'authkey' => 'LitePhp_185622a8f4e2c72a9f75f8f5b8099259',
    'cookie_pre' => 'Lite',
    'cookie_path' => '/',
    'cookie_domain' => '',
    'template' => 'default',
    'skin' => 'default',
];