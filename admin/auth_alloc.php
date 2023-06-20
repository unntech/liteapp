<?php

require '../autoload.php';
$activeMenu = 9;
$currentAuthNode = 15;
require 'auth.inc.php';
use \LiteApp\admin\Tree;
$id = $_GET['id'] ?? 0;

$ruleName = $auth->getAdminAuth($id);
if(empty($ruleName) || $ruleName['status'] != 1){
    $promptMessage = '角色组不存在或状态不正常，无法分配权限！';
    include LitePhp\Template::load('message', 'admin');
    exit(0);
}

$list = $Lite->db->table($auth->tableAdmin.'_node')->where(['status'=>['>=', 0]])->order('sort DESC, id ASC')->select()->toArray('id');
Tree::instance()->init($list);
$ruleList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');

if(isset($_POST['savebtn'])){
    $rulesCheck = $_POST['rulesCheck'] ?? [];
    if(empty($rulesCheck)){
        $rules_ids = '';
    }else{
        $rules_ids = implode(',', $rulesCheck);
    }
    $res = $Lite->db->table($auth->tableAdmin.'_auth')->where(['id'=>$id])->fields(['rules'=>$rules_ids])->update();
    $auth->aLog('角色分配权限：'.$id, json_encode($rulesCheck));
    $ruleName = $auth->getAdminAuth($id);
}

$curAuthIds = explode(',', $ruleName['rules']);
foreach ($ruleList as $k=>$v){
    if(in_array($v['id'], $curAuthIds)){
        $ruleList[$k]['check'] = true;
    }else{
        $ruleList[$k]['check'] = false;
    }
}

//$menuNode = ['节点', '菜单'];
$menuNode = ['<i class="bi bi-nut"></i>', '<i class="bi bi-list-ul"></i>'];

$listTreeArr = [];
foreach ($list as $k=>$v){
    $listTreeArr[$k]['p'] = Tree::instance()->getParentsIds($k);
    $listTreeArr[$k]['c'] = Tree::instance()->getChildrenIds($k);
}


$CSS = ['admin'];
include LitePhp\Template::load('auth_alloc', 'admin');