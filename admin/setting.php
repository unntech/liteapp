<?php

require '../autoload.php';
$activeMenu = 4;
require 'auth.inc.php';

if(isset($_POST['saveBtn'])){
    //do something
}

$CSS = ['admin'];
include LitePhp\Template::load('setting', 'admin');