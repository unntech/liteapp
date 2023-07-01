<?php

namespace LiteApp\api;

use LiteApp\api\ApiBase;

class ApiAdmin extends ApiBase
{
    protected $uid;

    public function __construct(){
        parent::__construct();
        $this->initialize();
    }

    public function initialize()
    {
        /*  //如果需要安全验证，要求必须有签名才可以请求，如果ApiBase里init_request_data已经启用，那这里就不要重复验证
        if(!isset($this->postData['signType']) || !in_array($this->postData['signType'], ['MD5', 'SHA256', 'RSA'])){
            $this->error(400, '无请求数据或无效 signType！', ['request'=>$this->postData]);
        }
        //*/

        //验证接口权限等初始化过程
        $this->uid = 10001;

        /* ---  鉴权不成功则退出
        $notAllow = true;
        if($notAllow){
            $this->error(401, 'Unauthorized');
        }
        */
    }
}