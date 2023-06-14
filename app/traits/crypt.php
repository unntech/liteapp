<?php

namespace LiteApp\traits;

trait crypt{
    protected $trait_jwt;
    public function construct() {
        $this->trait_jwt = new \LitePhp\LiCrypt(DT_KEY);
    }
    public function getToken($arr) {
        if(empty($this->trait_jwt)){
            $this->construct();
        }
        $token = $this->trait_jwt -> getToken($arr);
        return $token;
    }

    public function verifyToken($token){
        if(empty($this->trait_jwt)){
            $this->construct();
        }
        $jwt = $this->trait_jwt -> verifyToken($token);
        return $jwt;
    }
}