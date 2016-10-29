<?php
namespace Admin\Model;
use Think\Model;
use Think\Storage;


class SliderModel extends Model{

    protected $_validate = array(
        array('title', 'require', '幻灯名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('cover_id', 'require', '幻灯图片不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('link', 'require', '幻灯连接不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
		array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
		array('create_time', 'getCreateTime', self::MODEL_BOTH,'callback'),
    );
	
    public function info($id, $field = true){
        $map = array();
        if(is_numeric($id)){
            $map['id'] = $id;
        }
		$info=$this->field($field)->where($map)->find();
		$info["picurl"]=get_cover($info["cover_id"],"path");
        return $info;
    }
	
    public function update(){
        $data = $this->create();
        if(!$data){
            return false;
        }
        if(empty($data['id'])){
            $res = $this->add();
        }else{
            $res = $this->save();
        }
        return $res;
    }
	
	public function remove($id = null){
		$map = array('id' => array('in', $id) );
		$list = $this->where($map)->field('pic')->select();
		foreach ($list as $key => $value) {
            Storage::unlink($value['pic']);
		}
		$res=$this->where($map)->delete();
		return $res;
	}

    protected function getCreateTime(){
        $create_time    =   I('post.create_time');
        return $create_time?strtotime($create_time):NOW_TIME;
    }
}
