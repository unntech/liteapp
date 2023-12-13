<?php

namespace LiteApp\api\controller;

use LiteApp\api\ApiBase;
use LitePhp\Image;
use Throwable;

class wangeditor extends ApiBase
{
    protected $maxWidth = 1200;

    public function __construct(){
        parent::__construct();
        $this->initialize();
    }

    public function initialize()
    {
        $jwt = $this->verifyToken($_POST['token']);
        if($jwt === false){
            $this->error(2, 'TOKEN无效！');
        }
    }

    //请求处理函数，按需添加编写
    public function test(){
        $data = [
            'title'=>'This is a testing.',
            'GET'=>$this->GET,
            'postData' => $this->postData,

            'signType'=> $this->postData['signType'] ?? 'NONE',
            'encrypted'=>$this->postData['encrypted'] ?? false,
        ];

        $this->success($data,0, "调用方法：test 成功");
    }

    public function uploadImage()
    {
        if(strpos($_FILES['upload']['type'], 'image/') !== 0){
            $this->error(1001, '上传的文件只支持 png,jpeg,gif');
        }
        $subject = $_POST['cls'] ?? '';
        if($image = Image::open($_FILES['upload']['tmp_name'])){
            /*  处理图片及保存至S3对象存储示例
            $ext = $image->type();
            $mime = $image->mime();
            if($image->width() > $this->maxWidth){
                try {
                    $image->thumb(640, $image->height())->save($_FILES['upload']['tmp_name']);
                }catch (Throwable $e){
                    if(DT_DEBUG) echo $e->getMessage();
                    $this->error(1001, '上传图片处理失败');
                }
            }
            $image->free();
            $s3 = new MinioS3();
            $fn = $s3->uploadFile($_FILES['upload']['tmp_name'], $subject, $ext, $mime);
            if($fn){
                $this->success([
                    'url'   =>  $fn['ObjectURL'],
                    'alt'   =>  $_FILES['upload']['name'],
                ]);
            }
            */
            $this->success([
                'url'   =>  'https://apis.zhisg.com/lite/lite-logo.png',
                'alt'   =>  $_FILES['upload']['name'],
            ]);
        }
        $this->error(1001, '上传图片失败！');
    }

    public function uploadVideo()
    {
        if(strpos($_FILES['upload']['type'], 'video/') !==0){
            $this->error(1001, '上传的文件只支持 mov,mpeg,mp4,avi', $_FILES['upload']);
        }
        /* 处理及保存至S3对象存储示例
        $subject = $_POST['cls'] ?? '';
        $s3 = new MinioS3();
        $ext = substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1);
        $fn = $s3->uploadFile($_FILES['upload']['tmp_name'], $subject, $ext, $_FILES['upload']['type']);
        if($fn){
            $this->success([
                'url'   =>  $fn['ObjectURL'],
            ]);
        }
        */
        $this->success([
            'url'   => 'https://apis.zhisg.com/lite/lite-logo.png',
        ]);

        $this->error(1001, '上传视频失败！');
    }

    public function success(array $data = [], int $errcode = 0, string $msg = 'success')
    {
        $ret = [
            'errno' => $errcode,
            'data'  => $data,
            'message'=>$msg,
        ];
        $this->response($ret);
    }

    public function error(int $errcode = 0, string $msg = 'fail', array $data = ['void'=>null])
    {
        $ret = [
            'errno' => $errcode,
            'message' => $msg,
            'data'  =>$data,
        ];
        $this->response($ret);
    }
}