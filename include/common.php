<?php
defined('IN_LitePhp') or exit('Access Denied');

/**
 * 浏览器友好的变量输出
 *
 * @param  mixed        $var     变量
 * @param  boolean      $echo    是否输出 默认为True 如果为false 则返回输出字符串
 * @param  string|null  $label   标签 默认为空
 * @param  boolean      $strict  是否严谨 默认为true
 */
function dv($var, bool $echo = true, string $label = null, bool $strict = true)
{
    $label = (null === $label) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();    
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else {
        return $output;
    }
}

/**
 * 通过JS把PHP变量输出至 console.log
 * @param $output
 * @param bool $with_script_tags
 * @return void
 */
function console_log($output, bool $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function set_cookie($var, $value = '', $time = 0): bool
{
    return \LiteApp\app::$Lite->setCookie($var, $value, $time);
}

function get_cookie($var) {
    return \LiteApp\app::$Lite->getCookie($var);
}

function config(string $name = null, $default = null){
    $config = \LiteApp\app::$Lite->config;
    if (false !== strpos($name, '.')) {
        $v = explode('.',$name);
        $key = $v[0];
    }else{
        $key = $name;
    }
    if(!$config->exists($key)){
        $config->load($key);
    } 
    $r = $config->get($name, $default);
    
    return $r;
}

function pagination(int $count, int $pagenum = 0): string
{
    return \LiteApp\app::$Lite->pagination($count, $pagenum);
}

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}
