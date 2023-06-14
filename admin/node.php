<?php

require '../autoload.php';
$activeMenu = 8;
require 'auth.inc.php';
use \LiteApp\admin\Tree;

if($isAjax){  //ajax 提交
    if($postData['node'] == 16 && $postData['action'] == 'QUERY'){
        $qUser = $auth->getAdminNode($postData['rowid']);
        if($qUser){
            \LiteApp\admin\response::success($qUser);
        }else{
            \LiteApp\admin\response::error(1000, '查无记录！');
        }
    }elseif($postData['node'] == 16 && $postData['action'] == 'EDIT') {
        $auth->aLog('编辑节点：'.$postData['rowid'], json_encode($postData));
        $data = ['pid'=>$postData['pid'], 'node' => $postData['nodename'], 'title' => $postData['title'], 'status'=>$postData['status'], 'is_menu'=>$postData['is_menu'], 'icon'=>$postData['icon'], 'sort'=>$postData['sort']];
        $res = $Lite->db->table($auth->tableAdmin.'_node')->where(['id' => $postData['rowid']])->fields($data)->update();
        if ($res) {
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        } else {
            \LiteApp\admin\response::error(1001, '更新节点资料失败！');
        }
    }elseif($postData['node'] == 19 && $postData['action'] == 'ADD'){
        $auth->aLog('添加节点', json_encode($postData));
        $data = ['pid'=>$postData['pid'], 'node' => $postData['nodename'], 'title' => $postData['title'], 'status'=>$postData['status'], 'is_menu'=>$postData['is_menu'], 'icon'=>$postData['icon'], 'sort'=>$postData['sort']];
        $res = $Lite->db->table($auth->tableAdmin.'_node')->insert($data);
        if ($res) {
            \LiteApp\admin\response::success(['id' => $res]);
        } else {
            \LiteApp\admin\response::error(1001, '添加节点失败！');
        }
    }elseif($postData['node'] == 19 && $postData['action'] == 'DELETE'){
        $auth->aLog('删除节点：'.$postData['rowid'], json_encode($postData));
        $res = $Lite->db->table($auth->tableAdmin.'_node')->where(['id'=>$postData['rowid']])->fields(['status'=>-1])->update();
        if($res){
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        }else{
            \LiteApp\admin\response::error(1001, '删除节点失败！');
        }
    }else{
        \LiteApp\admin\response::error(9, '非法无效请求！');
    }
}

$list = $Lite->db->table($auth->tableAdmin.'_node')->where(['status'=>['>=', 0]])->order('sort DESC, id ASC')->select()->toArray();
Tree::instance()->init($list);
$ruleList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');

$menuNode = ['<i class="bi bi-nut"></i>', '<i class="bi bi-list-ul"></i>'];

$CSS = ['admin'];
include LitePhp\Template::load('node', 'admin');