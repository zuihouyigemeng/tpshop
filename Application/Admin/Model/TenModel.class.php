<?php
namespace Admin\Model;
use Think\Model;

class TenModel extends Model{

    protected $_validate = array(
        array('title', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('unit', 'number', '单位为数字', self::VALUE_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
		array('status', '1', self::MODEL_BOTH),
		array('display', '1', self::MODEL_INSERT),
    );


   public function info($id, $field = true){
        $map = array();
        if(is_numeric($id)){
            $map['id'] = $id;
        } else {
            $map['title'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }
	

    public function getTree($field = true){
        $list = $this->field($field)->order('sort')->select();
        return $list;
    }

    public function update(){
        $data = $this->create();
        if(!$data){
            return false;
        }
        if(empty($data['id'])){
            $res = $this->add($data);
        }else{
            $res = $this->save($data);
        }
        return $res;
    }
}
