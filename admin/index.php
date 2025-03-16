<?php
require '../autoload.php';

use LiteApp\admin\auth;

$title = $Lite->appName.'后台管理';
$action = $_GET['action'] ?? '';
$ref = $_GET['ref'] ?? 'main.php';

$emsg = '';
$username = '';

if($action == 'logout'){
    set_cookie("LiAdmin".auth::NonceId, '');
    $href = empty($_GET['href']) ? 'index.php' : $_GET['href'];
    LitePhp\LiHttp::redirect($href);
}

if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $_username = strtoupper($username);
    $password = $_POST['password'];
    $authic = $_POST['authenticator'];
    $auth = new auth();
    $login = $auth->login($_username, $password, $authic);
    if($login->errcode == 0){ //登入成功
        LitePhp\LiHttp::redirect($ref);
    }else{
        $emsg = $login->msg;
    }

}



include LitePhp\Template::load('index', 'admin');