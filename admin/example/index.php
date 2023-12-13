<?php

require '../../autoload.php';
$activeMenu = 0;
require '../auth.inc.php';

//多个分页条处理方式
$pageTotal1 = 100;
$page1 = isset($_GET['page1']) ? intval($_GET['page1']) : 1;
if ($page1 < 1) {  $page1 = 1; }
$pageStart1 = ($page1 - 1) * $pageNum;

$pageTotal2 = 200;
$page2 = isset($_GET['page2']) ? intval($_GET['page2']) : 1;
if ($page2 < 1) {  $page2 = 1; }
$pageStart2 = ($page2 - 1) * $pageNum;

$pageTotal3 = 300;
$page3 = isset($_GET['page3']) ? intval($_GET['page3']) : 1;
if ($page3 < 1) {  $page3 = 1; }
$pageStart3 = ($page3 - 1) * $pageNum;




$CSS = ['admin'];
include LitePhp\Template::load('index', 'admin/example');
