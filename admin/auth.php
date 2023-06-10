<?php

require '../autoload.php';
$activeMenu = 9;
require 'auth.inc.php';

if($isAjax){  //ajax 提交
    if($postData['node'] == 13 && $postData['action'] == 'QUERY'){
        $qUser = $auth->getAdminAuth($postData['rowid']);
        if($qUser){
            \LiteApp\admin\response::success($qUser);
        }else{
            \LiteApp\admin\response::error(1000, '查无记录！');
        }
    }elseif($postData['node'] == 13 && $postData['action'] == 'EDIT') {
        $auth->aLog('编辑角色：'.$postData['rowid'], json_encode($postData));
        $data = ['title'=>$postData['username'], 'remark' => $postData['nickname'], 'status' => $postData['userstatus']];
        $res = $Lite->db->table($auth->tableAdmin.'_auth')->where(['id' => $postData['rowid']])->fields($data)->update();
        if ($res) {
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        } else {
            \LiteApp\admin\response::error(1001, '更新角色资料失败！');
        }
    }elseif($postData['node'] == 12 && $postData['action'] == 'ADD'){
        $auth->aLog('添加角色', json_encode($postData));
        $data = ['title'=>$postData['username'], 'remark' => $postData['nickname'], 'status' => $postData['userstatus']];
        $res = $Lite->db->table($auth->tableAdmin.'_auth')->insert($data);
        if ($res) {
            \LiteApp\admin\response::success(['id' => $res]);
        } else {
            \LiteApp\admin\response::error(1001, '添加用户失败！');
        }
    }elseif($postData['node'] == 14 && $postData['action'] == 'DELETE'){
        $auth->aLog('删除角色：'.$postData['rowid'], json_encode($postData));
        $res = $Lite->db->table($auth->tableAdmin.'_auth')->where(['id'=>$postData['rowid']])->fields(['status'=>-1])->update();
        if($res){
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        }else{
            \LiteApp\admin\response::error(1001, '删除角色失败！');
        }
    }else{
        \LiteApp\admin\response::error(9, '非法无效请求！');
    }
}

$pageTotal = $Lite->db->table($auth->tableAdmin.'_auth')->where(['status'=>['>=', 0]])->count();
$list = $Lite->db->table($auth->tableAdmin.'_auth')->where(['status'=>['>=', 0]])->limit([$pageStart, $pageNum])->select()->toArray();

$liCrypt = new LitePhp\LiCrypt(DT_KEY);
$jwt = ['node'=>$activeMenu, 'exp'=>time()+3600];
$apiToken = $liCrypt->getToken($jwt);

$CSS = ['admin'];
include LitePhp\Template::load('auth', 'admin');