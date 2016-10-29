<?php
namespace Home\Model;
use Think\Model;

class TenModel extends Model{
	/**
	 * 获取分类详细信息
	 * @param  milit   $id 分类ID或标识
	 * @param  boolean $field 查询字段
	 * @return array     分类信息
	 */
	public function info($id, $field = true){
		/* 获取分类信息 */
		$map = array();
		$map['display']=1;
		$map['status']=1;
		if(is_numeric($id)){ //通过ID查询
			$map['id'] = $id;
		} else { //通过标识查询
			$map['title'] = $id;
		}
		return M('Category')->field($field)->where($map)->find();
	}

	public function ten($field = true){
        return M('Ten')->field($field)->order('sort')->select();
    }
}
