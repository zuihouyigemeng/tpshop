<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 用户首页控制器
 */
class TenController extends WebController {

	public function index(){
		$tree = D('Ten')->getTree(0,'id,title,sort,pid,display,status');
		$this->assign('tree', $tree);
		$this->meta_title = '专区管理';
        $this->display();
	}

    public function edit($id = null){
        $Ten = D('Ten');
        if(IS_POST){
            if(false !== $Ten->update()){
                $this->success('编辑成功！');
            } else {
                $error = $Ten->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $info = $id ? $Ten->info($id) : '';
            $this->assign('info',       $info);
			$this->meta_title = '专区修改';
            $this->display();
        }
    }
	
    public function add($pid = 0){
        $Ten = D('Ten');
        if(IS_POST){
            if(false !== $Ten->update()){
                $this->success('新增成功！');
            } else {
                $error = $Ten->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增专区';
            $this->display('edit');
        }
    }
	
    public function remove(){
        $cate_id = I('id');
        if(empty($cate_id)){
            $this->error('参数错误!');
        }
        $child = M('Ten')->where(array('pid'=>$cate_id))->field('id')->select();
        if(!empty($child)){
            $this->error('请先删除该专区下的子专区');
        }
		$document_list = M('Shop')->where(array('ten'=>$cate_id))->field('id')->select();
        if(!empty($document_list)){
            $this->error('请先删除该专区下的商品');
        }
        $res = M('Ten')->delete($cate_id);
        if($res !== false){
            $this->success('删除专区成功！');
        }else{
            $this->error('删除专区失败！');
        }
    }
}
