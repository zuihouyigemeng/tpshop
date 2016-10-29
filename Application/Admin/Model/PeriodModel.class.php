<?php
namespace Admin\Model;
use Think\Model;

class PeriodModel extends Model {

	public function express($id){
		$info=M('shop_period')->field('express_name,express_no,uid')->where('id='.$id)->find();
		$info['address']=M('shop_address')->where('uid='.$info['uid'])->order('`default` desc')->select();
		return $info;
	}
	
	public function update(){
		$rules = array(
			array('express_name', 'require', '快递公司不能为空'),
			array('express_no', 'require', '快递单号不能为空'),
		);
		if($data=M('shop_period')->validate($rules)->create()){
			return M('shop_period')->save($data);
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}

	/* 更新用户晒单 */
	public function shared($pid){
		$rules = array(     
			array('content','require','晒单内容不能为空！')
		);
		if (!$data=M('shop_shared')->validate($rules)->create()){
			$this->error = M('shop_shared')->getError();
			return false;
		}
		$pic = array_unique((array)$data['pic']);
        if ( empty($pic) ) {
            $this->error='至少要有一张晒单图片!';
            return false;
        }
        $uid=M('shop_period')->where('id='.$pid)->getField('uid');
		$data['pid']=$pid;
		$data['uid']=$uid;
		$data['pic']=implode(',',$data['pic']);
		$data['thumbpic']=implode(',',$data['thumbpic']);
		$data['create_time']=NOW_TIME;
        $res = M('shop_shared')->add($data);
        M('shop_period')->where('id='.$pid." and uid=".$uid)->setField("shared",0);
        return $res;
    }

    public function user_num($uid,$pid){
		$variable=M('shop_record')->field('group_concat(num) as num')->where("uid=".$uid." and pid=".$pid)->group("uid,pid")->find();
		if($variable){
			return explode(',',$variable['num']);
		}
	}
}