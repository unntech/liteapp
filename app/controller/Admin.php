<?php

namespace LiteApp\controller;

require DT_ROOT. '/app/admin/common.php';

use LiteApp\Controller;
use LitePhp\LiHttp;
use LitePhp\Template;
use LiteApp\admin\auth;

class Admin extends Controller
{
    /**
     * @var auth
     */
    protected $auth;
    protected $appName, $curUser, $curUserId;
    protected $activeMenu=0, $currentAuthNode =0;

    use \LiteApp\traits\crypt;

    public function __construct(){
        parent::__construct();
        $this->auth();
    }

    public function view(string $template = '', array $vars = [], array $CSS = [])
    {
        if ($this->title == $this->appName){
            $this->title = $this->appName . '-' . $this->auth->nodeName($this->activeMenu);
        }
        $vars['pageNum'] = config('admin.pageNum');
        $vars['navigationConfig'] = $this->curUser['params']['navigation'] ?? config('admin.navigation');

        if (\LitePhp\LiComm::is_mobile()) {
            $vars['navigationConfig'] = 'top';
        }
        $vars['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if ($vars['page'] < 1) {
            $vars['page'] = 1;
        }
        $vars['pageStart'] = ($vars['page'] - 1) * $vars['pageNum'];
        $jwt = ['sub' => $this->curUserId, 'node' => $this->activeMenu, 'exp' => time() + 18000];
        $vars['apiToken'] = $this->getToken($jwt);
        $vars['presentation'] = $this->auth->presentation($this->activeMenu);
        $vars['navigatorSiderFlag'] = $_COOKIE['navigatorSiderFlag'] ?? 0;
        $vars['auth'] = $this->auth;
        $vars['activeMenu'] = $this->activeMenu;
        $vars['curUser'] = $this->curUser;
        $vars['appName'] = $this->appName;
        parent::view($template, $vars, $CSS);
    }

    private function auth()
    {
        $liAdminToken = get_cookie('LiAdmin'. auth::NonceId);
        if (empty($liAdminToken)) {
            LiHttp::redirect('/admin/index.php');
        }
        $this->auth = new auth();
        $loginSuc = $this->auth->auth();
        if (!$loginSuc) {
            LiHttp::redirect('/admin/index.php');
        }
        $this->activeMenu = $activeMenu ?? $this->auth->activeMenuFormPathInfo();
        $this->auth->activeMenu($this->activeMenu);
        if (!$this->auth->authNode($this->activeMenu)) {
            Template::message('无此权限，无法操作！', '错误提示');
        }
        $this->appName = self::$Lite->appName;
        $this->curUser = $this->auth->curUser();
        $this->curUserId = $this->auth->curUserId();
    }

    /**
     * 根据activeMenu和currentAuthNode验证权限
     * @return void
     */
    protected function author()
    {
        if (!$this->auth->authNode($this->activeMenu)) {
            Template::message('无此权限，无法操作！', '错误提示');
        }
        if (isset($this->currentAuthNode) && !$this->auth->authNode($this->currentAuthNode)) {
            Template::message('无此权限，无法操作！！', '错误提示');
        }
    }
}