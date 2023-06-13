<?php

require '../autoload.php';
$activeMenu = 7;
require 'auth.inc.php';

$qUserId = isset($_GET['userid']) ? intval($_GET['userid']) : '';
if($qUserId <= 0){ $qUserId = '';}
$today = getdate($DT_TIME);
$starTime = isset($_GET['date']) ? strtotime($_GET['date']) : mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
$endTimes = $starTime;
$endTime = $starTime + 86400;

if(isset($_GET['toolbarSearch']) || isset($_GET['toolbarExport'])){
    $starTime = strtotime($_GET['begdatetime']);
    $endTimes = strtotime($_GET['enddatetime']);
    $endTime = $endTimes + 86400;
}

$where[] = ['addtime'=>['>=', $starTime]];
$where[] = ['addtime'=>['<', $endTime]];
if(!empty($qUserId)){
    $where[] = ['admin_id ' => $qUserId];
}
$pageTotal = $Lite->db->table($auth->tableAdmin.'_log')->where($where)->count();
$list = $Lite->db->table($auth->tableAdmin.'_log')->where($where)->order('id desc')->limit([$pageStart, $pageNum])->select()->toArray();

if(isset($_GET['toolbarExport'])){ //导出
    $res = $Lite->db->table($auth->tableAdmin.'_log')->where($where)->order('id desc')->select(true);
    $list = [];
    while ($r = $res->fetch_assoc()){
        $list[] = [
            (int)$r['id'],
            (int)$r['admin_id'],
            $r['nickname'],
            $r['url'],
            $r['title'],
            $r['content'],
            $r['ip'],
            date('Y-m-d H:i:s', $r['addtime']),
        ];
    }
    $excel = new \LiteApp\admin\XlsWriter();
    $excel->fileName('管理员日志')->header(['ID','管理员ID','管理员','操作页面','标题','内容','IP','时间'])->data($list)->export();
    exit(0);
}

$CSS = ['admin'];
include LitePhp\Template::load('adminlog', 'admin');