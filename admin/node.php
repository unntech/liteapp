<?php

require '../autoload.php';
$activeMenu = 8;
require 'auth.inc.php';
use \LiteApp\admin\Tree;

$list = $Lite->db->table($auth->tableAdmin.'_node')->where(['status'=>['>=', 0]])->order('sort DESC, id ASC')->select()->toArray();
Tree::instance()->init($list);
$ruleList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');

$menuNode = ['<i class="bi bi-nut"></i>', '<i class="bi bi-list-ul"></i>'];

$CSS = ['admin'];
include LitePhp\Template::load('node', 'admin');