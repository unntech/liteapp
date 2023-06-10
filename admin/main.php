<?php

require '../autoload.php';
$activeMenu = 1;
require 'auth.inc.php';


$CSS = ['admin'];
include LitePhp\Template::load('main', 'admin');