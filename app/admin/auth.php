<?php

namespace LiteApp\admin;

use LiteApp\app;
use LitePhp\GoogleAuthenticator;
use LitePhp\Template;
use LitePhp\Redis;

class auth extends app
{
    const menuNodeCache = false;  //菜单权限列表是否缓存，生产环境建议开启，需要配置redis参数
    const NonceId = '';
    public $tableAdmin = 'admin';
    public $loginSuccess = false;
    public $user = ['id' => 0];
    public $menu = [];
    public $node = [];
    const status = ['正常', '禁用', '锁定'];
    public $adminTag = ['标准用户', '超级管理员', '自定义'];

    use \LiteApp\traits\crypt;

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {

    }

    /**
     * 是否登入状态
     * @return bool
     */
    public function isLogin(): bool
    {
        return $this->loginSuccess;
    }

    /**
     * 当前登入用户信息
     * @return int[]
     */
    public function curUser(): array
    {
        return $this->user;
    }

    /**
     * 当前登入用户ID
     * @return int
     */
    public function curUserId(): int
    {
        return $this->user['id'];
    }

    /**
     * 获取菜单列表
     * @return array
     */
    public function getMenu(): array
    {
        return $this->menu;
    }

    /**
     * 获取权限节点列表
     * @return array
     */
    public function getNode(): array
    {
        return $this->node;
    }

    /**
     * 验证节权权限
     * @param int $authId
     * @param bool $tag 为true时，如果验证无权则直接退出
     * @return bool
     */
    public function authNode(int $authId, bool $tag = false): bool
    {
        if ($authId == 0) {
            return true;
        }
        $auth =  array_key_exists($authId, $this->node);
        if($tag && $auth == false){
            $this->message('对不起，您无权限进行此操作！', '错误提示');
        }
        return $auth;
    }

    /**
     * 设置激活菜单项
     * @param $id
     * @return void
     */
    public function activeMenu($id)
    {
        if (isset($this->node[$id]) && $this->node[$id]['pid'] > 0) {
            $this->menu[$this->node[$id]['pid']]['menuShow'] = true;
        }

    }

    /**
     * 获取节点名
     * @param $id
     * @return string
     */
    public function nodeName($id): string
    {
        if ($id == 0) {
            return config('app.name');
        }
        return $this->node[$id]['title'] ?? '';
    }

    /**
     * 获取节点链接
     * @param $authId
     * @return string
     */
    public function nodeHref($authId): string
    {
        return $this->node[$authId]['href'] ?? '';
    }

    /**
     * 登入鉴权
     * @return bool
     */
    public function auth(): bool
    {
        $userid = session('admin.id');
        if(empty($userid)){
            return false;
        }
        $liAdminToken = get_cookie('LiAdmin'.self::NonceId);
        $verify = $this->verifyToken($liAdminToken);
        if ($verify === false || $userid != $verify['sub']) {
            return false;
        } else {
            $this->loginSuccess = true;
            $user = $this->db->table($this->tableAdmin)->where(['id' => $verify['sub']])->selectOne();
            $this->user = [
                'id'        => (int)$user['id'],
                'username'  => $user['username'],
                'nickname'  => $user['nickname'],
                'status'    => $user['status'],
                'login_num' => $user['login_num'],
                'auth_ids'  => empty($user['auth_ids']) ? '0' : $user['auth_ids'],
                'authPrivs' => empty($user['auth_priv']) ? [] : explode(',', $user['auth_priv']),
                'admin'     => $user['admin'],
                'params'    => empty($user['params']) ? [] : json_decode($user['params'], true),
            ];

            $this->updateMenuNode();

            return true;
        }

    }

    /**
     * 登入
     * @param $username
     * @param $passwd
     * @param $authenticator
     * @return object
     */
    public function login($username, $passwd, $authenticator = '')
    {
        $username = $this->db->removeEscape($username);
        $user = $this->db->table($this->tableAdmin)->where(['username' => $username])->selectOne();
        if (!$user) {
            return (object)['errcode' => 1, 'msg' => '用户不存在！'];
        }
        if ($user['status'] != 0) {
            return (object)['errcode' => 2, 'msg' => '账号已被禁用或已删除'];
        }
        if ($this->password($passwd) != $user['psw']) {
            return (object)['errcode' => 3, 'msg' => '密码输入有误'];
        }
        if (!empty($user['authenticator'])) {
            $ga = new GoogleAuthenticator(); //谷歌验证器示例
            $_check_google = $ga->verifyCode($user['authenticator'], $authenticator);
            if (!$_check_google) {
                return (object)['errcode' => 4, 'msg' => '动态码二次验证失败'];
            }
        }

        $user['id'] = (int)$user['id'];
        $this->loginSuccess = true;
        $this->user = [
            'id'        => $user['id'],
            'username'  => $user['username'],
            'nickname'  => $user['nickname'],
            'status'    => $user['status'],
            'login_num' => $user['login_num'],
            'auth_ids'  => empty($user['auth_ids']) ? '0' : $user['auth_ids'],
            'authPrivs' => empty($user['auth_priv']) ? [] : explode(',', $user['auth_priv']),
            'admin'     => $user['admin'],
            'params'    => empty($user['params']) ? [] : json_decode($user['params'], true),
        ];

        //登入成功，写入登入日志
        $this->db->query("UPDATE {$this->tableAdmin} SET login_num = login_num + 1 WHERE id = {$user['id']}");
        $content = json_encode(['username' => $username, 'authenticator' => $authenticator]);
        $this->aLog('登入成功', $content);

        $this->updateMenuNode(true);

        $jwt = ['sub' => $user['id'], 'exp' => $this->DT_TIME + 86400];
        $token = $this->getToken($jwt);
        set_cookie('LiAdmin'.self::NonceId, $token);
        session('admin', ['id'=>$user['id'], 'username'=>$user['username']]);
        return (object)['errcode' => 0, 'msg' => '登入成功！', 'token'=>$token];
    }

    /**
     * 获取用户的菜单和节点权限，生产环境建议缓存起来，不用每次读库
     * @param $tag 为true时强制更新
     * @return void
     */
    protected function updateMenuNode($tag = false)
    {
        if(self::menuNodeCache){
            self::$Lite->set_redis();
        }
        $user = $this->user;
        if(!$tag && self::menuNodeCache){  //不强制更新先偿试读缓存
            $_g = true;
            $_c = Redis::get('adminMenu'.self::NonceId.'_'.$user['id']);
            if(!empty($_c)){
                $this->menu = json_decode($_c, true);
            }else{
                $_g = false;
            }
            $_c = Redis::get('adminNode'.self::NonceId.'_'.$user['id']);
            if(!empty($_c)){
                $this->node = json_decode($_c, true);
            }else{
                $_g = false;
            }
            if($_g){
                return;
            }
        }

        $_aIds = $this->db->get_value("SELECT GROUP_CONCAT(rules) FROM `{$this->tableAdmin}_auth` WHERE id IN ({$user['auth_ids']})");
        $authIds = empty($_aIds) ? [] : explode(',', $_aIds);
        $authIds = array_merge($authIds, $this->user['authPrivs']);
        //获取菜单权限
        if ($user['admin'] == 1) {
            $res = $this->db->table($this->tableAdmin . '_node')->where(['is_menu' => 1, 'status' => 1])->order('pid, sort desc')->select(true);
        } else {
            $res = $this->db->table($this->tableAdmin . '_node')->where(['is_menu' => 1, 'status' => 1, 'id' => ['IN', $authIds]])->order('pid, sort desc')->select(true);
        }
        $node = [];
        while ($r = $res->fetch_assoc()) {
            if (empty($r['node'])) {
                $href = "javascript:treeviewopen({$r['id']});";
            } else {
                if (strpos($r['node'], '@') === 0){
                    $_url = substr($r['node'], 1);
                    $href = '/route.php/'.$_url;
                } elseif ($_rpos = strrpos($r['node'], '#')) {
                    $action = substr($r['node'], $_rpos + 1);
                    $_url = substr($r['node'], 0, $_rpos);
                    $href = '/' . $_url . '.php?action=' . $action . '&';
                } elseif ($_rpos = strrpos($r['node'], '@')) {
                    $href = "javascript:ajaxviewopen({$r['id']});";
                } else {
                    $href = '/' . $r['node'] . '.php';
                }
            }
            //$href = empty($r['node']) ? "javascript:treeviewopen({$r['id']});" : '/'.$r['node'].'.php';
            if ($r['pid'] == 0) {
                $node[$r['id']] = ['id' => $r['id'], 'node' => $r['node'], 'title' => $r['title'], 'sort' => $r['sort'], 'icon' => $r['icon'], 'href' => $href];
            } else {
                $node[$r['pid']]['sub'][$r['id']] = ['id' => $r['id'], 'node' => $r['node'], 'title' => $r['title'], 'sort' => $r['sort'], 'icon' => $r['icon'], 'href' => $href];
            }
        }
        $this->menu = $node;

        //获取权限节点
        if ($user['admin'] == 1) {
            $res = $this->db->table($this->tableAdmin . '_node')->where(['status' => 1])->order('sort')->select(true);
        } else {
            $res = $this->db->table($this->tableAdmin . '_node')->where(['status' => 1, 'id' => ['IN', $authIds]])->order('sort')->select(true);
        }
        $node = [];
        while ($r = $res->fetch_assoc()) {
            if (empty($r['node'])) {
                $href = "javascript:void(0);";
            } else {
                if (strpos($r['node'], '@') === 0){
                    $_url = substr($r['node'], 1);
                    $href = '/route.php/'.$_url;
                } elseif ($_rpos = strrpos($r['node'], '#')) {
                    $action = substr($r['node'], $_rpos + 1);
                    $_url = substr($r['node'], 0, $_rpos);
                    $href = '/' . $_url . '.php?action=' . $action . '&';
                } elseif ($_rpos = strrpos($r['node'], '@')) {
                    $href = "javascript:void(0);";
                } else {
                    $href = '/' . $r['node'] . '.php';
                }
            }
            //$href = empty($r['node']) ? "javascript:void(0);" : '/'.$r['node'].'.php';
            $node[$r['id']] = ['id' => $r['id'], 'node' => $r['node'], 'title' => $r['title'], 'sort' => $r['sort'], 'icon' => $r['icon'], 'pid' => $r['pid'], 'href' => $href];
        }
        $this->node = $node;

        if(self::menuNodeCache){
            Redis::set('adminMenu'.self::NonceId.'_'.$user['id'], json_encode($this->menu), 7200);
            Redis::set('adminNode'.self::NonceId.'_'.$user['id'], json_encode($this->node), 7200);
        }
    }

    /**
     * 生成密码哈希
     * @param string $value
     * @param $salt
     * @return string
     */
    public function password(string $value, $salt = ''): string
    {
        return sha1($value . $salt);
    }

    /**
     * 写入管理员操作日志
     * @param $title
     * @param $content
     * @return
     */
    public function aLog($title, $content = '')
    {
        return $this->adminLog(
            [
                'admin_id' => $this->user['id'],
                'nickname' => $this->user['nickname'],
                'url'      => $_SERVER['REQUEST_URI'],
                'title'    => $title,
                'content'  => $content,
            ]
        );
    }

    public function adminLog(array $data)
    {
        $data['ip'] = $this->DT_IP;
        $data['addtime'] = $this->DT_TIME;
        return $this->db->table($this->tableAdmin . '_log')->insert($data);
    }

    /**
     * 读取管理员用户表信息
     * @param $id
     * @return array|false
     */
    public function getAdminUser($id)
    {
        $row = $this->db->table($this->tableAdmin)->where(['id' => $id])->selectOne();
        $row['auths'] = empty($row['auth_ids']) ? [] : $this->getAdminAuths($row['auth_ids']);
        $row['params'] = empty($row['params']) ? [] : json_decode($row['params'], true);
        $row['authPrivs'] = empty($row['auth_priv']) ? [] : explode(',', $row['auth_priv']);
        return $row;
    }

    /**
     * 读取角色权限信息
     * @param $id
     * @return array|false
     */
    public function getAdminAuth($id)
    {
        return $this->db->table($this->tableAdmin . '_auth')->where(['id' => $id])->selectOne();
    }

    /**
     * 读取所有角色权限表数据
     * @param $ids
     * @return array
     */
    public function getAdminAuths($ids = null): array
    {
        if (is_null($ids)) {
            $res = $this->db->table($this->tableAdmin . '_auth')->fields(['id', 'title'])->where(['status' => 1])->select(true);
        } else {
            $res = $this->db->table($this->tableAdmin . '_auth')->fields(['id', 'title'])->where(['id' => ['IN', explode(',', $ids)]])->select(true);
        }
        $ret = [];
        while ($r = $res->fetch_assoc()) {
            $ret[$r['id']] = $r['title'];
        }
        return $ret;
    }

    /**
     * 读取节点信息
     * @param $id
     * @return array|false
     */
    public function getAdminNode($id)
    {
        return $this->db->table($this->tableAdmin. '_node')->where(['id'=>$id])->selectOne();
    }

    /**
     * 获取当用活动页列表
     * @param int $activeMenu
     * @return array|mixed
     */
    public function presentation(int $activeMenu): array
    {
        $maxPresentation =config('admin.presentation', 10);
        $presentation = json_decode(get_cookie('presentation'.$this->user['id']), true);
        if(empty($presentation)){
            $presentation = [];
        }
        if($activeMenu > 0 && !isset($_GET['toolbarExport'])){
            $presentation[$activeMenu] = [
                'title'=>$this->nodeName($activeMenu),
                'href'=>$_SERVER['REQUEST_URI'],
                'hit'=> $this->DT_TIME,
            ];
        }
        if(count($presentation) > $maxPresentation){
            foreach ($presentation as $k=>$v){
                $_hit[$k] = $v['hit'];
            }
            asort($_hit);
            $k = array_key_first($_hit);
            unset($presentation[$k]);
        }
        set_cookie('presentation'.$this->user['id'], json_encode($presentation));
        return $presentation;
    }

    public function removePresentation(int $id): array
    {
        $presentation = json_decode(get_cookie('presentation'.$this->user['id']), true);
        if(empty($presentation)){
            $presentation = [];
        }
        unset($presentation[$id]);
        set_cookie('presentation'.$this->user['id'], json_encode($presentation));
        return $presentation;
    }

    /**
     * 通过访问的URL自动获取节点ID
     * @return int
     */
    public function activeMenuFormScriptName(): int
    {
        $_nodeCode = substr($_SERVER['SCRIPT_NAME'], 1);
        $_i = strrpos($_nodeCode, '.');
        $_nodeCode = $_i === false ? $_nodeCode : substr($_nodeCode, 0, $_i);
        $row = $this->db->table($this->tableAdmin . '_node')->fields(['id','pid','node'])->where(['node'=>$_nodeCode, 'is_menu'=>1])->selectOne();
        return $row ? $row['id'] : 1;
    }
    public function activeMenuFormPathInfo(): int
    {
        $_nodeCode = substr($_SERVER['PATH_INFO'], 1);
        $_nodeCode = '@'.$_nodeCode;
        $row = $this->db->table($this->tableAdmin . '_node')->fields(['id','pid','node'])->where(['node'=>$_nodeCode])->selectOne();
        return $row ? $row['id'] : 1;
    }

    /**
     * 输出错误信息提示
     * @param string $promptMessage
     * @param string|null $msgTitle
     * @param array $param
     * @return void
     */
    public function message(string $promptMessage, string $msgTitle = null, array $param = [])
    {
        global $activeMenu;
        if(isset($param['activeMenu'])) $activeMenu = $param['activeMenu'];
        $admin_dir = 'admin';
        $DT_TIME = $this->DT_TIME;
        $title = $appName = app::$Lite->appName;
        $curUser = $this->curUser();
        $presentation = $this->presentation($activeMenu);

        include Template::load('message_thin', 'admin');
        exit(0);
    }

    public function checkGoogleAuth($uid, $authenticator): bool
    {
        $user = $this->getAdminUser($uid);
        $ga = new GoogleAuthenticator(); //谷歌验证器示例
        return $ga->verifyCode($user['authenticator'], $authenticator);
    }

    /**
     * 验证Post Ajax 数据请求合法apiToken 和 node
     * @param $postData
     * @return bool
     */
    public function verifyAjaxToken($postData): bool
    {
        $_jwt = $this->verifyToken($postData['apiToken']);
        if ($_jwt === false) {
            response::error(2, 'TOKEN无效！');
        }
        if ($_jwt['sub'] != $this->user['id']) {
            response::error(4, '非当前登入用户！');
        }
        if (isset($postData['node'])) {
            if(!is_numeric($postData['node'])){
                response::error(1, '无效权限，无法操作！', $postData);
            }
            if (!$this->authNode($postData['node'])) {
                response::error(1, '无此权限，无法操作！');
            }
        }
        return true;
    }
}