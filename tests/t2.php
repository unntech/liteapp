<?php
require '../autoload.php';

use LitePhp\LiComm;
//use LitePhp\Exception;
//
//try {
//    throw new Exception("TEST");
//}catch (Exception $e){
//    $e->errorMessage();
//}

$v = LiComm::versionExplode('1.2.3-beta.1');
dv($v);
$c = LiComm::versionCompare('1.9.4', '1.9.3-beta.1');
dv($c);