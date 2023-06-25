<?php

require '../../autoload.php';
$activeMenu = 0;
require '../auth.inc.php';

$list1 = ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'];

$CSS = ['admin'];
include LitePhp\Template::load('multiple_select', 'admin/example');