<?php

namespace LiteApp\api\controller;

use LiteApp\api\ApiAdmin;

class sampleAdmin extends ApiAdmin
{
    public function __construct(){
        parent::__construct();
    }

    //请求处理函数，按需添加编写
    public function test(){
        $data = [
            'title'=>'This is a testing.',
            'GET'=>$this->GET,
            'PATH_INFO'=>$this->PATH_INFO,
            'postData' => $this->postData,
            'uid'   => $this->uid,

            'signType'=>'SHA256',
            'encrypted'=>false,
        ];

        $this->success($data,0, "调用Admin方法：test 成功");
    }
}