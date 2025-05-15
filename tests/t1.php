<?php
require __DIR__.'/../autoload.php';



$config = $Lite->config->get('app');

$where[] = ['addtime'=>1];
$where[] = ['addtime'=>['>=', 2]];
$where[] = ['addtime'=>['in',[123,456]]];
$where[] = ['addtime'=>['BETWEEN','123 AND 456']];
$where[] = "(id = 1 or id = 3)";
echo $Lite->db->table('admin_log')->alias('a')->where(['addtime'=>4])->where($where)->where("(id = 1 or id = 3)")->fields(['id','admin_id','addtime'])->fetchSql()->select();
var_dump($Lite->db->getOptions(), DT_ROOT);

//$excel = new \LiteApp\admin\XlsWriter('.');
//$a = $excel->reader('../runtime/export/202306/12/1.xlsx');
/*
foreach ($a as $k=>$v){
    $s = 'bi bi-' . substr($v[0],0, -4);
    $Lite->db->table('icons')->insert(['name'=>$s]);
}
*/
// LitePhp\Template::message('这是一个提示示例', '错误提示');
