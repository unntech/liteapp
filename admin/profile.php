<?php

require '../autoload.php';
$activeMenu = 0;
require 'auth.inc.php';

if (isset($_POST['save'])) {
    $res = $Lite->db->table($auth->tableAdmin)->where(['id' => $auth->curUserId()])->fields(['nickname' => $_POST['nickname']])->update();
    if ($res) {
        $postSuccess = true;
        $auth->aLog('修改个人资料', json_encode($_POST));
    }
}
$profile = $auth->getAdminUser($curUserId);
$rulesNames = $auth->getAdminAuths($profile['auth_ids']);
$rulesString = implode(',', $rulesNames);
$CSS = ['admin'];
include LitePhp\Template::load('profile', 'admin');