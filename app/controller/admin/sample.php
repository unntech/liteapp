<?php

namespace LiteApp\controller\admin;

use LiteApp\controller\Admin;

class sample extends Admin
{
    public function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
    }

    public function index()
    {
        $list = array('loop 1','loop 2','loop 3');
        $pageTotal = 180;
        $this->activeMenu = 23;
        $this->currentAuthNode = 22;
        $this->author();
        $this->view('', compact('list', 'pageTotal'), ['admin']);
    }

    public function api()
    {
        $this->currentAuthNode = 23;
        $this->author();
        $action = $this->postData['action'] ?? '';
        switch ($action){
            case 'TEST':
                $this->success($this->postData);
                break;
            default:
                $this->error(9, '非法无效请求！');
        }

    }
}