<?php

require '../autoload.php';
$activeMenu = 3;
require 'auth.inc.php';
use \LiteApp\admin\Tree;

$list = $Lite->db->table($auth->tableAdmin.'_node')->where(['is_menu'=>1, 'status'=>['>=', 0]])->order('sort DESC, id ASC')->select()->toArray();
Tree::instance()->init($list);
$list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');

$CSS = ['admin'];
include LitePhp\Template::load('menu', 'admin');