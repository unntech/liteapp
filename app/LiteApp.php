<?php
namespace LiteApp;

use LiteApp\extend\DB;
use LitePhp\LiHttp;

defined('IN_LitePhp') or exit('Access Denied');
/**
 * LiteApp 基础类
 */
class LiteApp
{
    /**
     * 配置参数
     */
    const VERSION = '1.1.13';
    public $config;
    /**
     * @var \LitePhp\mysqli | \LitePhp\sqlsrv | \LitePhp\mongodb
     */
    public $db;
    /**
     * @var \redis()
     */
    public $redis;
    public $DT_TIME;
    public $appName;
    
    public function __construct()
    {
        $this->DT_TIME = time();
        $this->config = new \LitePhp\Config(DT_ROOT . "/config/");
        $this->config->load(['app', 'db']);
        $this->appName = $this->config->get('app.name', 'LiteApp');
    }
    
    public function set_db($i=0){ //$i 为配置文件db列表里的第几个配置
        //$this->config->load(['db']);
        $this->db = DB::Create($this->config->get('db'), $i);
    }
    
    public function new_db($i=0){ //$i 为配置文件db列表里的第几个配置
        //$this->config->load(['db']);
        return DB::Create($this->config->get('db'), $i, true);
    }
    
    public function set_redis(bool $reconnect = false){
        if(empty($this->redis) || $reconnect) {
            $this->config->load(['redis']);
            $this->redis = \LitePhp\Redis::Create($this->config->get('redis'));
        }
    }

    public function alog(string $type, ?string $log1='', ?string $log2 = '', ?string $log3 = '' )
    {
        if(empty($this->db)){
            $this->set_db();
        }
        $log1 = empty($log1) ? '' : addslashes( $log1 ) ;
        $log2 = empty($log2) ? '' : addslashes( $log2 ) ;
        $log3 = empty($log3) ? '' : addslashes( $log3 ) ;
        $sql = "INSERT INTO alog (type, log1,log2,log3) VALUES ('{$type}', '" . $log1 . "','" . $log2 . "','" . $log3 . "')";
        $this->db->query( $sql );
        return $this->db->insert_id();
    }

    public function setCookie($var, $value = '', $time = 0): bool
    {
        $time = $time > 0 ? $this->DT_TIME + $time : (empty($value) ? $this->DT_TIME - 3600 : 0);
        $port = $_SERVER['REQUEST_SCHEME'] == 'https' ? true : false;
        $var = $this->config->get('app.cookie_pre', 'Lite') . $var;
        return setcookie($var, $value, $time, $this->config->get('app.cookie_path'), $this->config->get('app.cookie_domain'), $port);
    }

    public function getCookie($var) {
        $var =  $this->config->get('app.cookie_pre', 'Lite') . $var;
        return $_COOKIE[$var] ?? '';
    }
    
    public function getRequestHeaders(): array
    {
        return LiHttp::requestHeaders();
    }
    
    public function do_get($url, $aHeader = null)
    {
        return LiHttp::get($url, $aHeader);
    }
    
    public function do_post($url, $data=null, $aHeader = null)
    {
        return LiHttp::post($url, $data, $aHeader);
    }

    /**
     * 默认头像图标
     * @return string
     */
    public function defaultAvatar(): string
    {
        return DT_STATIC.'/default_avatar_bg.png';
    }

    /**
     * 输出分页HTML
     * @param int $count
     * @param int $pagenum
     * @param string $pageKey
     * @return string
     */
    public function pagination(int $count, int $pagenum = 0, string $pageKey = 'page'): string
    {
        if($pagenum <= 0){
            $pagenum = $this->config->get('admin.pageNum');
        }
        if($pagenum <= 0) $pagenum = 1;
        if(empty($count)) $count = 0;
        $p = ceil($count / $pagenum);
        //if($p ==1) return '';   //只有一页时，不显示分页条
        $a = '';
        foreach($_GET as $k=>$v){
            if($k != $pageKey ) $a.='&'.$k.'='.$v;
        }
        $curPage = isset($_GET[$pageKey]) ? intval($_GET[$pageKey]) : 1;
        if($curPage < 1) $curPage = 1;
        $ret = '<nav aria-label="Page navigation admin"><ul class="pagination pagination-sm"><li class="page-item';
        if($curPage == 1){
            $ret .= ' disabled';
        }
        $prePage = $curPage - 1;
        $ret .= '"><a class="page-link" href="?'.$pageKey.'='.$prePage.$a.'">Previous</a></li>';
        if($curPage > 10){ $bi = $curPage - 9; $pi = $bi + 30;}
        else{$bi = 1; $pi = 30;}
        if($pi > $p){ $pi = $p;}
        for($i=$bi; $i<=$pi; $i ++){
            if($curPage == $i){
                $ret .='<li class="page-item active" aria-current="page"><span class="page-link">'.$i.'</span></li>';
            }else{
                $ret .= '<li class="page-item"><a class="page-link" href="?'.$pageKey.'='.$i.$a.'">'.$i.'</a></li>';
            }
        }
        $ret .= '<li class="page-item';
        if($curPage == $p){
            $ret .= ' disabled';
        }
        $nextPage = $curPage + 1;
        $ret .= '"><a class="page-link" href="?'.$pageKey.'='.$nextPage.$a.'">Next</a></li><li class="page-item"><span class="page-link">共'.$count.'条记录</span></li></ul></nav>';
        return $ret;
    }
}