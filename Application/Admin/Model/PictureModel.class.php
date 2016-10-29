<?php
namespace Admin\Model;
use Think\Model;
use Think\Upload;


class PictureModel extends Model{

    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    public function upload($files, $setting, $driver = 'Local', $config = null){

        $setting['callback'] = array($this, 'isFile');
		$setting['removeTrash'] = array($this, 'removeTrash');
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);

        if($info){ 
            foreach ($info as $key => &$value) {
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }
                $value['path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename'];
				if($this->create($value) && ($id = $this->add())){
                    $value['id'] = $id;
                } else {
                    unset($info[$key]);
                }
            }
            return $info;
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    public function wxupload($files, $setting, $driver = 'Local', $config = null){
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);

        if($info){
            foreach ($info as $key => &$value) {
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }
                $value['path'] = $setting['rootPath'].$value['savepath'].$value['savename'];
            }
            return $info;
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
		$map = array('md5' => $file['md5'],'sha1'=>$file['sha1']);
        return $this->field(true)->where($map)->find();
    }
	
	public function removeTrash($data){
		$this->where(array('id'=>$data['id'],))->delete();
	}
}