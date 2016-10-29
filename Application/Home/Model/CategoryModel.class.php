<?php
namespace Home\Model;
use Think\Model;

/**
 * 分类模型
 */
class CategoryModel extends Model{
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

	public function getTree($id = 0, $field = true){
        /* 获取当前分类信息 */
        if($id){
            $info = $this->info($id);
            $id   = $info['id'];
        }
        $list = $this->field($field)->order('sort')->select();
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);
        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }
        return $info;
    }
	
	public function getid(){
		$map['display']=1;
		$map['status']=1;
		return M('Category')->field(true)->where($map)->find();
	}

	public function gettitle($id){
		return M('Category')->where('id='.$id)->getField('title');

	}
}
