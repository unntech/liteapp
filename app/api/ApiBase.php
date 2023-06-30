<?php

namespace LiteApp\api;

use LiteApp\app;

class ApiBase extends app
{
    protected array $PATH_INFO, $GET, $POST;
    protected array $postData;
    protected string $secret = '';
    protected $rsa;

    public function __construct(){
        parent::__construct();
        $this->init_request_data();
    }

    public function __call($name, $arguments) {
        //方法名$name区分大小写

        $this->error(400, "调用方法：{$name} 不存在");
    }

    protected function init_request_data()
    {
        $requestPath = isset($_SERVER['PATH_INFO']) ? explode('/',$_SERVER['PATH_INFO']) : [];
        unset($requestPath[0], $requestPath[1], $requestPath[2]);
        $this->PATH_INFO = array_values($requestPath);
        $this->GET = $_GET;
        $this->POST = $_POST;
        $_str = file_get_contents("php://input");
        $_arr = json_decode($_str, true);

        /*  //如果需要安全验证，要求必须有签名才可以请求，也可以公共接口不要求，管理接口要求，那就把这个限制放至ApiAdmin里
        if($_arr === false || !isset($_arr['signType']) || !in_array($_arr['signType'], ['MD5', 'SHA256', 'RSA'])){
            $this->error(400, '无请求数据或无效 signType！', ['request'=>$_arr]);
        }
        //*/

        if($_arr){
            $check = $this->verifySign($_arr);
            if($check == false){
                $this->error(405, '数据验签失败！', ['request'=>$_arr]);
            }
        }

        $this->postData = $_arr ?? [];

        $this->secret = DT_KEY;  //生产环境需自定义通讯密钥，根据请求传入的参数更新此secret值
    }

    /**
     * 接口数据输出
     * @param array $data
     * signType 提供 MD5、SHA256、RSA，验签时json encode增加中文不转unicode和不转义反斜杠两个参数
     * @return void
     */
    public function response(array $data)
    {
        $data = $this->request($data);

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
        exit(0);
    }

    public function success(array $data, $errcode = 0, $msg = 'success')
    {
        $signType = $data['signType'] ?? "NONE";
        $encrypted = $data['encrypted'] ?? false;
        unset($data['signType'], $data['encrypted']);
        $ret = [
            'head'  => [
                'errcode' => $errcode,
                'msg'     => $msg,
                'unique_id' => $_SERVER['UNIQUE_ID'],
                'timestamp' => $this->DT_TIME,
            ],
            'body'  => $data,
            'signType'  => $signType,
            'encrypted' => $encrypted,
        ];
        $this->response($ret);
    }

    public function error($errcode = 0, $msg = 'fail', $data = ['void'=>null])
    {
        $signType = $data['signType'] ?? "NONE";
        $encrypted = $data['encrypted'] ?? false;
        unset($data['signType'], $data['encrypted']);
        $ret = [
            'head'  => [
                'errcode' => $errcode,
                'msg'     => $msg,
                'unique_id' => $_SERVER['UNIQUE_ID'],
                'timestamp' => $this->DT_TIME,
            ],
            'body'  => $data,
            'signType'  => "NONE",
            'encrypted' => false,
        ];
        $this->response($ret);
    }

    /**
     * 接口方法运行入口
     * @param $path
     * @return mixed|null
     */
    public function run($path = '')
    {
        $requestPath = isset($_SERVER['PATH_INFO']) ? explode('/',$_SERVER['PATH_INFO']) : [];
        if(!empty($requestPath[1]) && !empty($requestPath[2])){
            $action = $requestPath[1];
            $func = $requestPath[2];
            $newClass = "\\LiteApp\\api\\controller\\";
            if(!empty($path)){
                $newClass .= $path . "\\" ;
            }
            $newClass .= $action;
            try{
                $filename = DT_ROOT. '/app/api/controller/';
                if(!empty($path)){
                    $filename .= $path . '/';
                }
                $filename .= $action . '.php';
                if(file_exists($filename)){

                    $api = new $newClass();

                    $api->$func();

                    return $api;

                }else{
                    $this->error(404,'接口不存在！');
                }

            }catch(Exception $e){
                $emsg = $e->getMessage();
                $this->error(417, $emsg);
            }
        }else{
            $this->error(400, '无效请求');
        }

        return null;
    }

    /**
     * 请求数据生成签名
     * @param array $data
     * signType 提供 MD5、SHA256、RSA，验签时json encode增加中文不转unicode和不转义反斜杠两个参数
     * @return string
     */
    public function request(array $data) : array
    {
        if(($data['encrypted'] === true || $data['signType'] == 'RSA') && empty($this->rsa)){
            $rsaKey = config('app.rsaKey');
            $this->rsa = new \LitePhp\LiRsa($rsaKey['pub'], $rsaKey['priv'], false, $rsaKey['private_key_bits'] );
            $this->rsa->SetThirdPubKey($rsaKey['thirdPub']);
        }
        if($data['encrypted'] === true){
            $_enda = $this->rsa->encrypt(json_encode($data['body']));
            if($_enda === false) {   //加密失败
                $data['encrypted'] = false;
                $data['bodyEncrypted'] = '';
            }else{
                $data['bodyEncrypted'] = $_enda;
                $data['body'] = ['data'=>'encrypted'];
            }
        }else{
            $data['bodyEncrypted'] = '';
        }
        if(isset($data['signType']) && $data['signType'] != 'NONE') {
            $head = $data['head'];
            ksort($head);
            $body = $data['body'];
            ksort($body);
            $signString = json_encode($head,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . $data['bodyEncrypted'] . $this->secret;
            switch($data['signType']){
                case 'MD5':
                    $sign = strtoupper(md5($signString));
                    $data['sign'] = $sign;
                    break;
                case 'SHA256':
                    $sign = strtoupper(hash("sha256", $signString));
                    $data['sign'] = $sign;
                    break;
                case 'RSA':
                    $sign = $this->rsa->sign($signString);
                    $data['sign'] = $sign;
                    break;
            }
        }

        return $data;
    }

    /**
     * 验签
     * @param array $data
     * @return bool
     */
    public function verifySign(array &$data) : bool
    {
        if(($data['encrypted'] === true || $data['signType'] == 'RSA') && empty($this->rsa)){
            $rsaKey = config('app.rsaKey');
            $this->rsa = new \LitePhp\LiRsa($rsaKey['pub'], $rsaKey['priv'], false, $rsaKey['private_key_bits'] );
            $this->rsa->SetThirdPubKey($rsaKey['thirdPub']);
        }
        $dataSign = $data['sign'] ?? 'NONE';
        $verify = false;
        if(isset($data['signType']) && $data['signType'] != 'NONE'){
            $head = $data['head'];
            ksort($head);
            $body = $data['body'];
            ksort($body);
            $signString = json_encode($head,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . $data['bodyEncrypted'] . $this->secret;
            switch($data['signType']){
                case 'MD5':
                    $sign = strtoupper(md5($signString));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'SHA256':
                    $sign = strtoupper(hash("sha256", $signString));
                    if($dataSign == $sign){
                        $verify = true;
                    }
                    break;
                case 'RSA':
                    $verify = $this->rsa->verifySign($signString, $dataSign);
                    break;
                default:

            }
        }else{
            $verify = true;
        }
        if($data['encrypted'] === true && $verify == true){
            $data['body'] = json_decode($this->rsa->decrypt($data['bodyEncrypted']), true);
        }

        return $verify;
    }

}