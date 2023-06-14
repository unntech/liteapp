<?php

namespace LiteApp\admin;

class auth
{
    protected $DT_TIME, $DT_IP, $db;
    public $tableAdmin = 'admin';
    public $loginSuccess = false, $user = ['id' => 0];
    public $menu = [], $node = [];
    const status = ['正常', '禁用', '锁定'];
    public $adminTag = ['标准用户', '超级管理员', '自定义'];


    public function __construct()
    {
        global $DT_TIME, $DT_IP, $Lite;
        $this->DT_TIME = $DT_TIME;
        $this->DT_IP = $DT_IP;
        $this->db = $Lite->db;
    }

    public function __destruct()
    {

    }

    public function isLogin(): bool
    {
        return $this->loginSuccess;
    }

    public function curUser(): array
    {
        return $this->user;
    }

    public function curUserId(): int
    {
        return $this->user['id'];
    }

    public function getMenu(): array
    {
        return $this->menu;
    }

    public function getNode(): array
    {
        return $this->node;
    }

    /**
     * 验证节权权限
     * @param int $authId
     * @return bool
     */
    public function authNode(int $authId): bool
    {
        if ($authId == 0) {
            return true;
        }
        return array_key_exists($authId, $this->node);
    }

    public function activeMenu($id)
    {
        if (isset($this->node[$id]) && $this->node[$id]['pid'] > 0) {
            $this->menu[$this->node[$id]['pid']]['menuShow'] = true;
        }

    }

    public function nodeName($id): string
    {
        if ($id == 0) {
            return config('app.name');
        }
        return $this->node[$id]['title'] ?? '';
    }

    public function nodeHref($authId): string
    {
        return $this->node[$authId]['href'] ?? '';
    }

    public function auth(): bool
    {
        $liAdminToken = get_cookie('LiAdmin');
        $liCrypt = new \LitePhp\LiCrypt(DT_KEY);
        $verify = $liCrypt->verifyToken($liAdminToken);
        if ($verify === false) {
            return false;
        } else {
            $this->logined = true;
            $user = $this->db->table($this->tableAdmin)->where(['id' => $verify['sub']])->selectOne();
            $this->user = [
                'id'        => $user['id'],
                'username'  => $user['username'],
                'nickname'  => $user['nickname'],
                'status'    => $user['status'],
                'login_num' => $user['login_num'],
                'admin'     => $user['admin'],
                'params'    => empty($user['params']) ? [] : json_decode($user['params'], true),
            ];

            //获取菜单权限
            if ($user['admin'] == 1) {
                $res = $this->db->table($this->tableAdmin . '_node')->where(['is_menu' => 1, 'status' => 1])->order('sort')->select(true);
            } else {
                $authIds = $this->db->get_value("SELECT GROUP_CONCAT(rules) FROM `{$this->tableAdmin}_auth` WHERE id IN ({$user['auth_ids']})");
                $res = $this->db->table($this->tableAdmin . '_node')->where(['is_menu' => 1, 'status' => 1, 'id' => ['IN', explode(',', $authIds)]])->order('sort')->select(true);
            }
            $node = [];
            while ($r = $res->fetch_assoc()) {
                if (empty($r['node'])) {
                    $href = "javascript:treeviewopen({$r['id']});";
                } else {
                    if ($_rpos = strrpos($r['node'], '#')) {
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
                $authIds = $this->db->get_value("SELECT GROUP_CONCAT(rules) FROM `{$this->tableAdmin}_auth` WHERE id IN ({$user['auth_ids']})");
                $res = $this->db->table($this->tableAdmin . '_node')->where(['status' => 1, 'id' => ['IN', explode(',', $authIds)]])->order('sort')->select(true);
            }
            $node = [];
            while ($r = $res->fetch_assoc()) {
                if (empty($r['node'])) {
                    $href = "javascript:void(0);";
                } else {
                    if ($_rpos = strrpos($r['node'], '#')) {
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
            return true;
        }

    }

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
            $ga = new \LitePhp\GoogleAuthenticator(); //谷歌验证器示例
            $_check_google = $ga->verifyCode($user['authenticator'], $authenticator);
            if (!$_check_google) {
                return (object)['errcode' => 4, 'msg' => '动态码二次验证失败'];
            }
        }

        //登入成功，写入登入日志
        $this->db->query("UPDATE {$this->tableAdmin} SET login_num = login_num + 1 WHERE id = {$user['id']}");
        $content = json_encode(['username' => $username, 'authenticator' => $authenticator]);
        $this->adminLog(
            [
                'admin_id' => $user['id'],
                'nickname' => $user['nickname'],
                'url'      => $_SERVER['REQUEST_URI'],
                'title'    => '登入成功',
                'content'  => $content,
            ]
        );

        $liCrypt = new \LitePhp\LiCrypt(DT_KEY);
        $jwt = ['sub' => $user['id'], 'exp' => $this->DT_TIME + 86400];
        $token = $liCrypt->getToken($jwt);
        set_cookie('LiAdmin', $token);
        return (object)['errcode' => 0, 'msg' => '登入成功！'];
    }

    public function password(string $value, $salt = ''): string
    {
        return sha1($value . $salt);
    }

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

    public function getAdminUser($id)
    {
        $row = $this->db->table($this->tableAdmin)->where(['id' => $id])->selectOne();
        $row['auths'] = empty($row['auth_ids']) ? [] : $this->getAdminAuths($row['auth_ids']);
        $row['params'] = empty($row['params']) ? [] : json_decode($row['params'], true);
        return $row;
    }

    public function getAdminAuth($id)
    {
        return $this->db->table($this->tableAdmin . '_auth')->where(['id' => $id])->selectOne();
    }

    public function getAdminAuths($ids = null): array
    {
        if (empty($ids)) {
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

    public function getAdminNode($id)
    {
        return $this->db->table($this->tableAdmin. '_node')->where(['id'=>$id])->selectOne();
    }

    public function presentation($activeMenu){
        $presentation = json_decode(get_cookie('presentation'), true);
        if(empty($presentation)){
            $presentation = [];
        }
        if($activeMenu > 0){
            $presentation[$activeMenu] = [
                'title'=>$this->nodeName($activeMenu),
                'href'=>$_SERVER['REQUEST_URI'],
                'hit'=> $this->DT_TIME,
            ];
        }
        if(count($presentation) > 12){
            foreach ($presentation as $k=>$v){
                $_hit[$k] = $v['hit'];
            }
            asort($_hit);
            $k = array_key_first($_hit);
            unset($presentation[$k]);
        }
        set_cookie('presentation', json_encode($presentation));
        return $presentation;
    }
}