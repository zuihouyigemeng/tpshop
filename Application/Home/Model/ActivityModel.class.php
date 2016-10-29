<?php
namespace Home\Model;
use Think\Model;


class ActivityModel extends Model{
	public function detail($id){
		$info = $this->field(true)->find($id);
		return $info;
	}
	
	public function lists(){
		return $this->where('status=1 and display=1')->field('id,title,picurl,remark,icon,end_time')->order('id desc')->select();
	}
	
	public function list_log($id,$uid){
		return M('activity_log')->where('activity_id='.$id.' and user_id='.$uid)->select();
	}
}
