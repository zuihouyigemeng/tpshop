<?php
namespace Home\Model;
use Think\Model;
use Think\Upload;
use Think\Storage;
use Think\Image;
/**
 * 图片模型
 * 负责图片的上传
 */

class PictureModel extends Model{
	/**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'Local', $config = null){
        /* 上传文件 */
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);

        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }
                $value['path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename'];	//在模板里的url路径
                $image = new \Think\Image();
                $image->open($setting['rootPath'].$value['savepath'].$value['savename']);
                // $image->water('./Public/Home/images/logo.png',\Think\Image::IMAGE_WATER_CENTER)->save($setting['rootPath'].$value['savepath'].$value['savename']);
                $image->thumb(120, 120,\Think\Image::IMAGE_THUMB_FIXED)->save($setting['rootPath'].$value['savepath']."thumb_".$value['savename']);
                $value['thumbpath'] = substr($setting['rootPath'], 1).$value['savepath']."thumb_".$value['savename'];
            }
            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    public function mediaGetUrl($media_id){
        $token = session("token");
        if($token){
            $auth = new \Com\WechatAuth(C('WX_APPID'), C('WX_APPSECRET'), $token);
        } else {
            $auth  = new \Com\WechatAuth(C('WX_APPID'), C('WX_APPSECRET'));
            $token = $auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }
        $media = $auth->mediaGetUpload($media_id);
        $return=json_decode( $media, true);
        if($return["errcode"] == 42001  || $return['errcode'] == 40001){
            session("token", null);
            return $this->mediaGetUrl($media_id);
        }
        $savename=uniqid().'.jpg';
        $path='./Picture/Shared/'.date('Y-m-d').'/';
        \Think\Storage::put($path.$savename,$media);
        $image = new \Think\Image();
        $image->open($path.$savename);
        $image->water('./Public/Home/images/logo.png',\Think\Image::IMAGE_WATER_CENTER)->save($path.$savename);
        $image->thumb(120, 120,\Think\Image::IMAGE_THUMB_FIXED)->save($path."thumb_".$savename);
        $value['path'] = substr($path, 1).$savename;
        $value['thumbpath'] = substr($path, 1)."thumb_".$savename;
        return $value;
    }

    public function uploadhead($files, $setting, $driver = 'Local', $config = null){
        /* 上传文件 */
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);

        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }
                $value['path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename']; //在模板里的url路径
            }
            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    public function cropImg($params){
        $Image = new Image();
        $Image->open("./Picture/Head/user_haed_".UID.".png");
        $params = explode(',', $params);
        $pdir="./Picture/Head/".uniqid().'.png';
        $Image->crop($params[2],$params[3],$params[0],$params[1])->save($pdir);
        Storage::unlink("./Picture/Head/user_haed_".UID.".png");
        $pdir=substr($pdir, 1);
        $headimgurl=M('user')->where('id='.UID)->getField('headimgurl');
        if($headimgurl!='/Picture/Head/user-icon.png'){
            Storage::unlink(".".$headimgurl);
        }
        M('user')->where('id='.UID)->setField('headimgurl',$pdir);
        return $pdir;
    }

    public function removeup(){
        Storage::unlink("./Picture/Head/user_haed_".UID.".png");
    }
}