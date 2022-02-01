<?php

defined('IN_LitePhp') or exit('Access Denied');

return[
    // 数据库连接配置信息
    'connections'     => [
           [
            'database'          => 'mysqli',
            // 服务器地址
            'hostname'          => '127.0.0.1',
            // 数据库名
            'dbname'            => 'litephp',
            // 用户名
            'username'          => 'litephp',
            // 密码
            'password'          => 'litepwd',
            // 端口
            'hostport'          => 3306,
            // 数据库编码默认采用utf8
            'charset'           => 'utf8mb4'
           ] ,
        
           [
            'database'          => 'sqlsrv',
            // 服务器地址
            'hostname'          => '127.0.0.1',
            // 数据库名
            'dbname'            => 'db',
            // 用户名
            'username'          => 'user',
            // 密码
            'password'          => 'psw',
            // 端口
            'hostport'          => 1433,
            
            'charset'           => ''
           ] ,
        ]
];