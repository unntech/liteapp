<?php

require '../../autoload.php';
$activeMenu = 0;
require '../auth.inc.php';

$title = "ECharts";

$CSS = ['admin'];
include LitePhp\Template::load('echarts', 'admin/example');