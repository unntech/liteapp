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
    public $config, $db, $redis;
    
    public function __construct()
    {
        $this->config = new \LitePhp\Config(DT_ROOT . "/config/");
        $this->config->load(['app', 'db']);
        $this->db = \LitePhp\Db::Create($this->config->get('db'));
    }
    
    public function set_db($i=0){
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
    
}