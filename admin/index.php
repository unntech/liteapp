<?php

require '../autoload.php';

$title = 'LiteApp Admin';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$ref = isset($_GET['ref']) ? $_GET['ref'] : 'main.php';

$emsg = '';
$username = '';

if($action == 'logout'){
    set_cookie("LiAdmin",'');
    LitePhp\LiHttp::redirect('index.php');
}

if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $_username = strtoupper($username);
    $password = $_POST['password'];
    $authic = $_POST['authenticator'];
    $auth = new \LiteApp\admin\auth();
    $login = $auth->login($_username, $password, $authic);
    if($login->errcode ==0){ //登入成功
        LitePhp\LiHttp::redirect($ref);
    }else{
        $emsg = $login->msg;
    }

}



include LitePhp\Template::load('index', 'admin');