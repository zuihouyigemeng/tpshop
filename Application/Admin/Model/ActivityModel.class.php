<?php
namespace Admin\Model;
use Think\Model;
 
class ActivityModel extends Model {

    protected $_validate = array(
        array('name', 'require', '活动标识必须', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '/^[a-zA-Z]\w{0,39}$/', '标识不合法', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '标识已经存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,80', '标题长度不能超过80个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('remark', 'require', '活动描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('remark', '1,140', '活动描述不能超过140个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
		array('end_time', 'getEndTime', self::MODEL_BOTH,'callback'),
    );

    public function update(){
        $data = $this->create($_POST);
        if(empty($data)){
            return false;
        }
        if(empty($data['id'])){
            $id = $this->add();
            if(!$id){
                $this->error = '新增活动出错！';
                return false;
            }
        } else {
            $status = $this->save();
            if(false === $status){
                $this->error = '更新活动出错！';
                return false;
            }
        }
        return $data;
    }
	
	public function remove($id = null){
		$map = array('id' => array('in', $id));
		return $this->where($map)->delete();
	}
	
	protected function getEndTime(){
        $end_time    =   I('post.end_time');
        return $end_time?strtotime($end_time):NOW_TIME;
    }
}
