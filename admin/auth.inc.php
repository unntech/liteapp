<?php

defined('IN_LitePhp') or exit('Access Denied');
$liAdminToken = get_cookie('LiAdmin');
if (empty($liAdminToken)) {
    LitePhp\LiHttp::redirect('index.php');
}

$auth = new \LiteApp\admin\auth();
$loginSuc = $auth->auth();
if (!$loginSuc) {
    LitePhp\LiHttp::redirect('index.php');
}
$activeMenu = $activeMenu ?? 1;
$auth->activeMenu($activeMenu);
if (!$auth->authNode($activeMenu)) {
    LitePhp\Template::message('无此权限，无法操作！', '错误提示');
}
if (isset($currentAuthNode) && !$auth->authNode($currentAuthNode)) {
    LitePhp\Template::message('无此权限，无法操作！', '错误提示');
}

$appName = $Lite->appName;
$curUser = $auth->curUser();
$curUserId = $auth->curUserId();

$title = $appName . '-' . $auth->nodeName($activeMenu);
$pageNum = config('admin.pageNum');
$navigationConfig = config('admin.navigation');
if (\LitePhp\LiComm::is_mobile()) {
    $navigationConfig = 'top';
}
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    $page = 1;
}
$pageStart = ($page - 1) * $pageNum;

$liCrypt = new LitePhp\LiCrypt(DT_KEY);
$jwt = ['sub' => $curUserId, 'node' => $activeMenu, 'exp' => time() + 18000];
$apiToken = $liCrypt->getToken($jwt);

$postStr = file_get_contents("php://input");
$postData = json_decode($postStr, true);
if (!empty($postData)) {
    $_jwt = $liCrypt->verifyToken($postData['token']);
    if ($_jwt === false) {
        \LiteApp\admin\response::error(2, 'TOKEN无效！');
    }
    if ($_jwt['sub'] != $curUserId) {
        \LiteApp\admin\response::error(4, '非当前登入用户！');
    }
    if (isset($postData['node'])) {
        if (!$auth->authNode($postData['node'])) {
            \LiteApp\admin\response::error(1, '无此权限，无法操作！');
        }
    }
    $isAjax = true;
} else {
    $isAjax = false;
}


//常用函数

function auth_nodeHref($id): string
{
    global $auth;
    return $auth->nodeHref($id);
}