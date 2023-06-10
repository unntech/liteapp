<?php

require '../autoload.php';
$activeMenu = 4;
require 'auth.inc.php';


$CSS = ['admin'];
include LitePhp\Template::load('setting', 'admin');