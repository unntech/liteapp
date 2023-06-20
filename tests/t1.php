<?php
require __DIR__.'/../autoload.php';



$config = $Lite->config->get('app');

$where[] = ['addtime'=>1];
$where[] = ['addtime'=>2];
echo $Lite->db->table('admin_log')->alias('a')->where(['addtime'=>4])->where($where)->fields(['id','admin_id','addtime'])->fetchSql()->select();
var_dump($Lite->db->getOptions(), DT_ROOT);

$excel = new \LiteApp\admin\XlsWriter('.');
$a = $excel->reader('../runtime/export/202306/12/1.xlsx');
/*
foreach ($a as $k=>$v){
    $s = 'bi bi-' . substr($v[0],0, -4);
    $Lite->db->table('icons')->insert(['name'=>$s]);
}
*/
// LitePhp\Template::message('这是一个提示示例', '错误提示');

try{
    ffff();
}catch (Throwable $e){
    echo $e->getMessage();

}
