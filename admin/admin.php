<?php

require '../autoload.php';
$activeMenu = 6;
require 'auth.inc.php';

if($isAjax){  //ajax 提交
    if($postData['node'] == 11 && $postData['action'] == 'QUERY'){
        $qUser = $auth->getAdminUser($postData['rowid']);
        if($qUser){
            unset($qUser['authenticator']);
            unset($qUser['psw']);
            \LiteApp\admin\response::success($qUser);
        }else{
            \LiteApp\admin\response::error(1000, '查无记录！');
        }
    }elseif($postData['node'] == 11 && $postData['action'] == 'EDIT') {
        $auth->aLog('编辑用户：'.$postData['rowid'], json_encode($postData));
        if($postData['useradmin'] == 1){$postData['useradmin'] = 0;}
        $data = ['nickname' => $postData['nickname'], 'status' => $postData['userstatus'], 'admin' => $postData['useradmin']];
        $data['username'] = strtoupper($Lite->db->removeEscape($postData['username']));
        if (!empty($postData['password'])) {
            $data['psw'] = $auth->password($postData['password']);
        }
        $data['auth_ids'] = implode(',', json_decode($postData['rules'], true));
        $res = $Lite->db->table($auth->tableAdmin)->where(['id' => $postData['rowid']])->fields($data)->update();
        if ($res) {
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        } else {
            \LiteApp\admin\response::error(1001, '更新用户资料失败！');
        }
    }elseif($postData['node'] == 10 && $postData['action'] == 'ADD'){
        $auth->aLog('添加用户', json_encode($postData));
        if($postData['useradmin'] == 1){$postData['useradmin'] = 0;}
        $data = ['nickname' => $postData['nickname'], 'status' => $postData['userstatus'], 'admin' => $postData['useradmin']];
        $data['username'] = strtoupper($Lite->db->removeEscape($postData['username']));
        if (empty($postData['password'])) {
            $postData['password'] = '123456';
        }
        $data['psw'] = $auth->password($postData['password']);
        $data['auth_ids'] = implode(',', json_decode($postData['rules'], true));
        $res = $Lite->db->table($auth->tableAdmin)->insert($data);
        if ($res) {
            \LiteApp\admin\response::success(['id' => $res]);
        } else {
            \LiteApp\admin\response::error(1001, '添加用户失败！');
        }
    }elseif($postData['node'] == 10 && $postData['action'] == 'DELETE'){
        $auth->aLog('删除用户：'.$postData['rowid'], json_encode($postData));
        if($postData['rowid'] == 1){
            \LiteApp\admin\response::error(1001, 'ID为1的超级管理员用户不可删除！');
        }
        $res = $Lite->db->table($auth->tableAdmin)->where(['id'=>$postData['rowid']])->fields(['status'=>-1])->update();
        if($res){
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        }else{
            \LiteApp\admin\response::error(1001, '删除用户失败！');
        }
    }elseif($postData['node'] == 18 && $postData['action'] == 'SECURE'){
        $auth->aLog('解绑动态码：'.$postData['rowid'], json_encode($postData));
        $res = $Lite->db->table($auth->tableAdmin)->where(['id'=>$postData['rowid']])->fields(['authenticator'=>''])->update();
        if($res){
            \LiteApp\admin\response::success(['id' => $postData['rowid']]);
        }else{
            \LiteApp\admin\response::error(1001, '解绑动态码失败！');
        }
    }else{
        \LiteApp\admin\response::error(9, '非法无效请求！');
    }
}

$qUsername = $_GET['username'] ?? '';
$qStatus = $_GET['status'] ?? -9;
if($qStatus >=0){
    $where['status'] = $qStatus;
}else{
    $where['status'] = ['>=', 0];
}
if($qUsername != ''){
    $where['username'] = ['LIKE',"%{$qUsername}%"];
}
$pageTotal = $Lite->db->table($auth->tableAdmin)->where($where)->count();
$res = $Lite->db->table($auth->tableAdmin)->where($where)->limit([$pageStart, $pageNum])->select(true);
$list = [];
while ($r = $res->fetch_assoc()){
    $r['statusName'] = LiteApp\admin\auth::status[$r['status']];
    $r['adminTag'] = $auth->adminTag[$r['admin']];
    if(empty($r['auth_ids'])){
        $r['authRules'] = '';
    }else{
        $_reu= $Lite->db->table($auth->tableAdmin.'_auth')->fields(['title'])->where(['id'=>['IN', explode(',', $r['auth_ids'])]])->select(true);
        $rules = [];
        while($v = $_reu->fetch_assoc()){
            $rules[] = $v['title'];
        }
        $r['authRules'] = implode(',',$rules);
    }

    $list[] = $r;
}

$rulesNames = $auth->getAdminAuths();


$CSS = ['admin'];
include LitePhp\Template::load('admin', 'admin');