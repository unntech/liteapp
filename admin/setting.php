<?php

require '../autoload.php';
$activeMenu = 4;
require 'auth.inc.php';

if(isset($_POST['saveBtn'])){
    echo 'post';
}

$CSS = ['admin'];
include LitePhp\Template::load('setting', 'admin');