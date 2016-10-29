<?php
namespace Home\Controller;
use Think\Storage;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class PictureController extends WapController {
    /**
     * 上传图片
     */
    public function uploadPicture(){
        header("Access-Control-Allow-Origin: *");
        /* 返回标准数据 */
        $return  = array('jsonrpc' => 2.0, 'id' => 'id');
        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
		$pic_upload = C('PICTURE_UPLOAD');
        $info = $Picture->upload(
            $_FILES,
            $pic_upload,
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );
        if($info){
            $return['result'] = null;
            $return['path'] = $info['file']['path'];
            $return['thumbpath'] = $info['file']['thumbpath'];
        } else {
            $return['error'] = $arrayName = array('code' => 100,'message' => $Picture->getError());
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    public function uploadWx($media_id){
        $return=D('Picture')->mediaGetUrl($media_id);
        $this->ajaxReturn($return);
    }

    public function uploadHeadPicture(){
        header('Access-Control-Allow-Origin: *');
        /* 返回标准数据 */
        $return  = array('jsonrpc' => 2.0, 'id' => 'id');
        /* 调用文件上传组件上传文件 */
        $uid=UID?UID:I('uid');
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $pic_upload = C('PICTURE_UPLOAD');
        $pic_upload['autoSub'] =false;
        $pic_upload['saveName'] ='user_haed_'.$uid;
        $pic_upload['saveExt'] ='png';
        $pic_upload['rootPath'] ='./Picture/Head/';
        $info = $Picture->uploadhead(
            $_FILES,
            $pic_upload,
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );
        if($info){
            $return['result'] = null;
            $return['path'] = $info['file']['path'];
        } else {
            $return['error']   = $arrayName = array('code' => 100,'message' => $Picture->getError());
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    public function cropImg($crop){
        $return  = array('status' => 1, 'info' => '头像裁剪成功', 'path' => '');
        if(!isset($crop) && empty($crop)){
            $return['status']= 0;
            $return['info']= '参数错误！';
        }
        $info = D('Picture')->cropImg($crop);
        $return['path'] = $info;
        $this->ajaxReturn($return);
    }

    public function uploadHeadPictureApp(){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', I('b64imac'), $result)){
            $type = $result[2];
            $new_file = './Picture/Head/'.uniqid().'.'.$type;
            if (Storage::put($new_file, base64_decode(str_replace($result[1], '', I('b64imac'))))){
                $return['status'] = 1;
                $return['path'] = $this->web_url.substr($new_file, 1);
                M('user')->where('id='.UID)->setField('headimgurl',substr($new_file, 1));
            }else{
                $return['status'] = 0;
            }
            $this->ajaxReturn($return);
        }
    }

    public function removeup(){
        D('Picture')->removeup();
    }

    public function preview(){
        // header("Access-Control-Allow-Origin: *");
        $DIR = 'Picture/Preview';
        // Create target dir
        if (!file_exists($DIR)) {
            @mkdir($DIR);
        }

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        if ($cleanupTargetDir) {
            if (!is_dir($DIR) || !$dir = opendir($DIR)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "未能打开临时目录。"}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and is not the current file
                if (@filemtime($tmpfilePath) < time() - $maxFileAge) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        $src = file_get_contents('php://input');

        if (preg_match("#^data:image/(\w+);base64,(.*)$#", $src, $matches)) {

            $previewUrl = sprintf(
                "%s://%s%s",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['HTTP_HOST'],
                $_SERVER['REQUEST_URI']
            );
            $previewUrl = str_replace("index.php?s=Home/Picture/preview.html", "", $previewUrl);


            $base64 = $matches[2];
            $type = $matches[1];
            if ($type === 'jpeg') {
                $type = 'jpg';
            }

            $filename = md5($base64).".$type";
            $filePath = $DIR.DIRECTORY_SEPARATOR.$filename;

            if (file_exists($filePath)) {
                die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
            } else {
                $data = base64_decode($base64);
                file_put_contents($filePath, $data);
                die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
            }

        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "一个recoginized源码"}}');
        }

    }
}