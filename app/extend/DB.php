<?php

namespace LiteApp\extend;

class DB extends \LitePhp\Db
{
    /**
     * 构造方法
     * @access public $i 为配置文件db列表里的第几个配置
     */
    public static function Create($icfg, $i=0, $new = false)
    {
        $cfg = $icfg['connections'][$i];
        $dbt = $cfg['database'];
        switch($dbt){
            case 'mysqli':
                $db = new MySQLi($cfg);
                break;
            case 'sqlsrv':
                $db = new SqlSrv($cfg);
                break;
            case 'mongodb':
                $db = new MongoDB($cfg);
                break;
            default :
                $db = false;
        }
        if(!$new) self::$db = $db;
        return $db;
    }
}