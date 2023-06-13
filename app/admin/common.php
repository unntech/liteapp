<?php
defined('IN_LitePhp') or exit('Access Denied');

//Admin通用函数

function auth_nodeHref($id): string
{
    global $auth;
    return $auth->nodeHref($id);
}