<?php

require '../autoload.php';
$activeMenu = 0;
require 'auth.inc.php';

if(isset($_POST['save'])){
    if($_POST['newpassword'] != $_POST['cfmpassword']){
        $promptMessage = '您输入的两次新密码不一致，无法修改，请重设！';
        include LitePhp\Template::load('message', 'admin');
        exit(0);
    }
    $user = $auth->getAdminUser($auth->curUserId());
    if($auth->password($_POST['oldpassword']) != $user['psw']){
        $promptMessage = '您输入的旧密码不正确！';
        include LitePhp\Template::load('message', 'admin');
        exit(0);
    }
    $newpsw = $auth->password($_POST['newpassword']);
    $res = $Lite->db->table($auth->tableAdmin)->where(['id'=>$auth->curUserId()])->fields(['psw'=>$newpsw])->update();
    if($res){
        $auth->aLog('修改个人密码', json_encode($_POST));
        $promptMessage = '修改密码成功！';
        include LitePhp\Template::load('message', 'admin');
        exit(0);
    }
}

$profile = $auth->curUser();

$CSS = ['admin'];
include LitePhp\Template::load('editpsw', 'admin');