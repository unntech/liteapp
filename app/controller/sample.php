<?php

namespace LiteApp\controller;

use LiteApp\Controller;

class sample extends Controller
{
    public function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
    }


    public function test(){
        $title = 'Sample controller';
        $list = [
            'name'=>'Lite',
            'addr'=>'ShenZhen',
        ];

        $this->view('', compact('title', 'list'));
    }
}