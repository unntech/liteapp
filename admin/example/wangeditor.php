<?php

require '../../autoload.php';
$activeMenu = 0;
require '../auth.inc.php';


$CSS = ['admin'];
include LitePhp\Template::load('wangeditor', 'admin/example');