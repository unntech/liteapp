<?php

require '../autoload.php';
$activeMenu = 0;
require 'auth.inc.php';

if($isAjax){
    switch ($postData['action']){
        case 'SETNAV':
            $profile = $auth->getAdminUser($curUserId);
            $newParams = $profile['params'];
            $newParams['navigation'] = $postData['nav'];
            $res = $Lite->db->table($auth->tableAdmin)->where(['id'=>$curUserId])->fields(['params'=>json_encode($newParams)])->update();
            if($res){
                \LiteApp\admin\response::success($newParams);
            }else{
                \LiteApp\admin\response::error(1001, '更新用户资料失败！');
            }
            break;
        case 'clearCache':
            if(LiteApp\admin\auth::menuNodeCache){
                $Lite->set_redis();
                \LitePhp\Redis::del('adminMenu'. LiteApp\admin\auth::NonceId.'_'.$curUserId);
                \LitePhp\Redis::del('adminNode'. LiteApp\admin\auth::NonceId.'_'.$curUserId);
            }
            \LiteApp\admin\response::success([]);
            break;
        default:
    }
}

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