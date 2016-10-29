<?php
namespace Admin\Model;
use Think\Model;

class WebMenuModel extends Model {

	public function getPath($id){
		$path = array();
		$nav = $this->where("id={$id}")->field('id,pid,title')->find();
		$path[] = $nav;
		if($nav['pid'] >0){
			$path = array_merge($this->getPath($nav['pid']),$path);
		}
		return $path;
	}
}