<?php

namespace LiteApp\traits;

use LitePhp\LiCrypt;

trait crypt
{
    /**
     * @var LiCrypt
     */
    protected $trait_jwt;

    public function construct()
    {
        $this->trait_jwt = new LiCrypt(DT_KEY);
    }

    public function getToken(array $jwt, bool $needSign = false)
    {
        if (empty($this->trait_jwt)) {
            $this->construct();
        }
        return $this->trait_jwt->getToken($jwt, $needSign);
    }

    public function verifyToken(string $Token, bool $needSign = false)
    {
        if (empty($this->trait_jwt)) {
            $this->construct();
        }
        return $this->trait_jwt->verifyToken($Token, $needSign);
    }

    public function jencrypt($arr, $key = '', $iv = '')
    {
        if (empty($this->trait_jwt)) {
            $this->construct();
        }
        return $this->trait_jwt->jencrypt($arr, $key, $iv);
    }

    public function jdecrypt($ciphertext, $key = '', $iv = '')
    {
        if (empty($this->trait_jwt)) {
            $this->construct();
        }
        return $this->trait_jwt->jdecrypt($ciphertext, $key, $iv);
    }
}