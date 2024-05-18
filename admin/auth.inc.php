<?php
defined('IN_LitePhp') or exit('Access Denied');

use LiteApp\admin\auth;
use LitePhp\LiComm;
use LitePhp\LiHttp;

require DT_ROOT. '/app/admin/common.php';

$liAdminToken = get_cookie('LiAdmin'.auth::NonceId);
if (empty($liAdminToken)) {
    LiHttp::redirect('/admin/index.php');
}

$auth = new auth();
$loginSuc = $auth->auth();
if (!$loginSuc) {
    LiHttp::redirect('/admin/index.php');
}
$activeMenu = $activeMenu ?? $auth->activeMenuFormScriptName();
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
$navigationConfig = $curUser['params']['navigation'] ?? config('admin.navigation');

if (LiComm::is_mobile()) {
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

$isAjax = false;
$postStr = file_get_contents("php://input");
$postData = json_decode($postStr, true);
if (!empty($postData)) {
    $auth->verifyAjaxToken($postData);
    $isAjax = true;
}
if(isset($_POST['apiToken'])){
    $auth->verifyAjaxToken($_POST);
    $isAjax = true;
}

$presentation = $auth->presentation($activeMenu);
$navigatorSiderFlag = $_COOKIE['navigatorSiderFlag'] ?? 0;
