<?php

require '../autoload.php';

$title = "Bootstrap svg 图标库";

$res = $Lite->db->table('icons')->select(true);
$icons = [];
while ($r = $res->fetch_assoc()){
    $icons[$r['id']] = ['name'=>$r['name'], 'title'=>substr($r['name'], 6)];
}

include LitePhp\Template::load('icons', 'admin');