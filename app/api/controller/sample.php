<?php

namespace LiteApp\api\controller;

use LiteApp\api\ApiBase;

class sample extends ApiBase
{
    public function __construct(){
        parent::__construct();
    }

    //请求处理函数，按需添加编写
    public function test(){
        $data = [
            'title'=>'This is a testing.',
            'GET'=>$this->GET,
            'postData' => $this->postData,

            'signType'=> $this->postData['signType'],
            'encrypted'=>$this->postData['encrypted'],
        ];

        $this->success($data,0, "调用方法：test 成功");
    }
}