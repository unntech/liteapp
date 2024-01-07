<?php

namespace LiteApp\controller\member;

use LiteApp\Controller;

class user extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
    }


    public function info(){
        $title = 'Sample member user info';
        $list = [
            'name'=>'Lite',
            'addr'=>'ShenZhen',
        ];

        $this->view('sample/test', compact('title', 'list'));
    }
}