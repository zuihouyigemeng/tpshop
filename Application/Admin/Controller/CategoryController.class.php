<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 用户首页控制器
 */
class CategoryController extends WebController {

	public function index(){
		$tree = D('Category')->getTree(0,'id,title,sort,pid,display,status');
		$this->assign('tree', $tree);
		C('_SYS_GET_CATEGORY_TREE_'.UID, true); //标记系统获取分类树模板
		$this->meta_title = '栏目管理';
        $this->display();
	}

	/* 编辑栏目 */
    public function edit($id = null, $pid = 0){
        $Category = D('Category');
        if(IS_POST){ //提交表单
            if(false !== $Category->update()){
                $this->success('编辑成功！');
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if($pid){
                /* 获取上级栏目信息 */
                $cate = $Category->info($pid, 'id,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级栏目不存在或被禁用！');
                }
            }
            /* 获取栏目信息 */
            $info = $id ? $Category->info($id) : '';
            $this->assign('info',       $info);
            $this->assign('category',   $cate);
			$this->meta_title = '栏目修改';
            $this->display();
        }
    }
	
	 /* 新增栏目 */
    public function add($pid = 0){
        $Category = D('Category');
        if(IS_POST){ //提交表单
            if(false !== $Category->update()){
                $this->success('新增成功！');
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if($pid){
                /* 获取上级栏目信息 */
                $cate = $Category->info($pid, 'id,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级栏目不存在或被禁用！');
                }
            }
            /* 获取栏目信息 */
            $this->assign('category', $cate);
            $this->meta_title = '新增栏目';
            $this->display('edit');
        }
    }
	
	/**
     * 删除一个栏目
     */
    public function remove(){
        $cate_id = I('id');
        if(empty($cate_id)){
            $this->error('参数错误!');
        }
        //判断该栏目下有没有子栏目，有则不允许删除
        $child = M('Category')->where(array('pid'=>$cate_id))->field('id')->select();
        if(!empty($child)){
            $this->error('请先删除该栏目下的子栏目');
        }
        //判断该栏目下有没有内容
		$document_list = M('Shop')->where(array('category'=>$cate_id))->field('id')->select();
        if(!empty($document_list)){
            $this->error('请先删除该栏目下的商品');
        }
        //删除该栏目信息
        $res = M('Category')->delete($cate_id);
        if($res !== false){
            $this->success('删除栏目成功！');
        }else{
            $this->error('删除栏目失败！');
        }
    }

        /**
     * 操作分类初始化
     * @param string $type
     */
    public function operate($type = 'move'){
        //检查操作参数
        if(strcmp($type, 'move') == 0){
            $operate = '移动';
        }elseif(strcmp($type, 'merge') == 0){
            $operate = '合并';
        }else{
            $this->error('参数错误！');
        }
        $from = intval(I('get.from'));
        empty($from) && $this->error('参数错误！');

        //获取分类
        $map = array('status'=>1, 'id'=>array('neq', $from));
        $list = M('Category')->where($map)->field('id,pid,title')->order('pid asc,id asc,sort asc')->select();
        $Tree = new \Org\Tree;
        $this->assign('type', $type);
        $this->assign('operate', $operate);
        $this->assign('from', $from);
        $this->assign('list', $Tree->tree($list));
        $this->meta_title = $operate.'分类';
        $this->display();
    }

    /**
     * 移动分类
     */
    public function move(){
        $to = I('post.to');
        $from = I('post.from');
        $Model = M('Category');
        $res = $Model->where(array('id'=>$from))->setField('pid', $to);
        if($res !== false){
            $this->success('分类移动成功！');
        }else{
            $this->error('分类移动失败！');
        }
    }

    /**
     * 合并分类
     */
    public function merge(){
        $to = I('post.to');
        $from = I('post.from');
        $Model = M('Category');
        //合并文档
        $res = M('Shop')->where(array('category'=>$from))->setField('category', $to);
        if($res){
            //删除被合并的分类
            $Model->delete($from);
            $this->success('合并分类成功！');
        }else{
            $this->error('合并分类失败！');
        }

    }
	
	/**
     * 显示栏目树，仅支持内部调
     * @param  array $tree 栏目树
     */
    public function tree($tree = null){
        C('_SYS_GET_CATEGORY_TREE_'.UID) || $this->_empty();
        $this->assign('tree', $tree);
        $this->display('Category/tree');
    }
}
