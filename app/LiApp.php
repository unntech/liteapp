<?php

namespace LiteApp;

class LiApp
{
    /**
     * @var \LitePhp\mysqli | \LitePhp\sqlsrv | \LitePhp\mongodb
     */
    public static $db;
    public static $DT_TIME;
    public static $DT_IP;
    public static $appName;
    public static $domain;

    public static function initialize()
    {
        global $Lite, $DT_IP;
        self::$appName = $Lite->appName;
        self::$db = $Lite->db;
        self::$DT_TIME = $Lite->DT_TIME;
        self::$DT_IP = $DT_IP;
        self::$domain = $Lite->config->get('app.domain');
    }

}