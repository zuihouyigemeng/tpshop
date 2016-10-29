<?php
namespace Home\Model;
use Think\Model;


class NewsModel extends Model{
	public function detail($id){
		$info = $this->field(true)->find($id);
		if(!(is_array($info) || 1 !== $info['status'] || 1 !== $info['display'])){
			$this->error = '商品被禁用或已删除！';
			return false;
		}
		return $info;
	} 
}
