<?php

namespace LiteApp\admin;

class response
{
    public function __construct()
    {

    }

    public static function response(array $ret)
    {
        header("Access-Control-Allow-Origin: *");
        echo json_encode($ret);
        exit(0);
    }

    public static function success(array $data, $errcode = 0, $msg = 'success')
    {
        $ret = [
            'errcode' => $errcode,
            'msg'     => $msg,
            'data'    => $data,
        ];
        self::response($ret);
    }

    public static function error($errcode = 0, $msg = 'success')
    {
        $ret = [
            'errcode' => $errcode,
            'msg'     => $msg,
            'data'    => (object)[],
        ];
        self::response($ret);
    }
}