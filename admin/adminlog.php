<?php

require '../autoload.php';
$activeMenu = 7;
require 'auth.inc.php';


$pageTotal = $Lite->db->table($auth->tableAdmin.'_log')->count();
$list = $Lite->db->table($auth->tableAdmin.'_log')->order('id desc')->limit([$pageStart, $pageNum])->select()->toArray();

$CSS = ['admin'];
include LitePhp\Template::load('adminlog', 'admin');