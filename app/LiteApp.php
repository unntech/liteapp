<?php
namespace LiteApp;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiteApi 基础类
 */
class LiteApp
{
    /**
     * 配置参数
     */
    const VERSION = '1.0.4';
    public $config, $db, $redis;
    public $DT_TIME;
    public $appName;
    
    public function __construct()
    {
        $this->DT_TIME = time();
        $this->config = new \LitePhp\Config(DT_ROOT . "/config/");
        $this->config->load(['app', 'db']);
        $this->db = \LitePhp\Db::Create($this->config->get('db'));
        $this->appName = $this->config->get('app.name', 'LiteApp');
    }
    
    public function set_db($i=0){ //$i 为配置文件db列表里的第几个配置
        $this->config->load(['db']);
        $this->db = \LitePhp\Db::Create($this->config->get('db'), $i);
    }
    
    public function set_redis(){
        $this->config->load(['redis']);
        $this->redis = \LitePhp\Redis::Create($this->config->get('redis'));
    }
    
    public function alog($type, $log1='', $log2 = '', $log3 = '' ) {
        if(empty($this->db)){
            $this->set_db();
        }
        $SQLC = "INSERT INTO alog (type, log1,log2,log3) VALUES ('{$type}', '" . addslashes( $log1 ) . "','" . addslashes( $log2 ) . "','" . addslashes( $log3 ) . "')";
        $this->db->query( $SQLC );
        return $this->db->insert_id();
    }
    
    public function setCookie($var, $value = '', $time = 0) {
        $time = $time > 0 ? $this->DT_TIME + $time : (empty($value) ? $this->DT_TIME - 3600 : 0);
        $port = $_SERVER['REQUEST_SCHEME'] == 'https' ? 1 : 0;
        $var = $this->config->get('app.cookie_pre', 'Lite') . $var;
        return setcookie($var, $value, $time, $this->config->get('app.cookie_path'), $this->config->get('app.cookie_domain'), $port);
    }

    public function getCookie($var) {
        $var =  $this->config->get('app.cookie_pre', 'Lite') . $var;
        return isset($_COOKIE[$var]) ? $_COOKIE[$var] : '';
    }
    
    public function getRequestHeaders(){
        return \LitePhp\LiHttp::requestHeaders();
    }
    
    public function do_get($url, $aHeader = null){
        $res = \LitePhp\LiHttp::get($url, $aHeader);
        return $res;
        
    }
    
    public function do_post($url, $data=null, $aHeader = null){
        $res = \LitePhp\LiHttp::post($url, $data, $aHeader);
        return $res;
    }
    
}