<?php

require '../autoload.php';
$activeMenu = 3;
require 'auth.inc.php';
use \LiteApp\admin\Tree;

if($isAjax){  //ajax 提交
    if($postData['node'] == 17 && $postData['action'] == 'QUERY'){
        $qUser = $auth->getAdminNode($postData['rowid']);
        if($qUser){
            \LiteApp\admin\response::success($qUser);
        }else{
            \LiteApp\admin\response::error(1000, '查无记录！');
        }
    }elseif($postData['node'] == 17 && $postData['action'] == 'EDIT') {
        $auth->aLog('编辑节点：'.$postData['rowid'], json_encode($postData));
        $data = ['pid'=>$postData['pid'], 'node' => $postData['nodename'], 'title' => $postData['title'], 'status'=>$postData['status'], 'icon'=>$postData['icon'], 'sort'=>$postData['sort']];
        $res = $Lite->db->table($auth->tableAdmin.'_node')->where(['id' => $postData['rowid']])->fields($data)->update();
        if ($res) {
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        } else {
            \LiteApp\admin\response::error(1001, '更新节点资料失败！');
        }
    }else{
        \LiteApp\admin\response::error(9, '非法无效请求！');
    }
}

$list = $Lite->db->table($auth->tableAdmin.'_node')->where(['is_menu'=>1, 'status'=>['>=', 0]])->order('sort DESC, id ASC')->select()->toArray();
Tree::instance()->init($list);
$list = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');

$topMenu = $Lite->db->table($auth->tableAdmin.'_node')->where(['is_menu'=>1, 'status'=>['>=', 0], 'pid'=>0])->order('sort DESC, id ASC')->select()->toArray();

$CSS = ['admin'];
include LitePhp\Template::load('menu', 'admin');