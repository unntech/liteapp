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

function session($name = '', $value = '')
{
    if(empty(\LitePhp\Session::$session_id)){
        $opt = config('session');
        if($opt['save'] == 'redis'){
            if(empty(\LiteApp\app::$Lite->redis)){
                \LiteApp\app::$Lite->set_redis();
            }
            $opt['handle'] = \LiteApp\app::$Lite->redis;
        }
        \LitePhp\Session::start($opt);
    }

    if (is_null($name)) {
        // 清除
        \LitePhp\Session::clear();
    } elseif ('' === $name) {
        return \LitePhp\Session::get();
    } elseif (is_null($value)) {
        // 删除
        \LitePhp\Session::delete($name);
    } elseif ('' === $value) {
        // 获取
        return \LitePhp\Session::get($name);
    } else {
        // 设置
        \LitePhp\Session::set($name, $value);
    }
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

/**
 * 全局通用异常处理过程
 * @param Throwable $e
 * @return void
 */
function exception_handler(Throwable $e)
{
    $postDate = json_decode(file_get_contents("php://input"), true);
    if(!empty($postDate)){ // isAjax Json Request
        if (DT_DEBUG) {
            $data = ['request'=>$postDate, 'exception'=>$e, 'code'=>$e->getCode(),'message'=>$e->getMessage(), 'trace'=>$e->getTrace()];
        }else{
            $data = ['code'=>$e->getCode(),'message'=>$e->getMessage()];
        }
        echo json_encode($data);
    }else {
        if (DT_DEBUG) {
            $html = '<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"><title>HTTP 500</title><style>body{margin: 0 auto;} .header{background: #6c757d; color: #eee; padding: 50px 15px 30px 15px;line-height: 1.5rem} .msg{padding: 15px 15px;line-height: 1.25rem}</style></head><body>';
            $html .= '<div class="header"><h3>' . $e->getMessage() . '</h3>Code: ' . $e->getCode() . '<BR>File: ' . $e->getFile() . '<BR>Line: ' . $e->getLine() . '</div>';
            $html .= '<div class="msg">' . dv($e, false) . '</div>';
            $html .= '</body></html>';
            echo $html;
        } else {
            $msg = $e->getCode() . ': ' . $e->getMessage();
            $html = '<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"><title>HTTP 500</title><style>body{background-color:#444;font-size:16px;}h3{font-size:32px;color:#eee;text-align:center;padding-top:50px;font-weight:normal;}</style></head>';
            $html .= '<body><h3>' . $msg . '</h3></body></html>';
            echo $html;
        }
    }
}