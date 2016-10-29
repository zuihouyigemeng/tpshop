<?php
namespace Admin\Controller;

class NewsController extends WebController {

    public function index(){
        $map = array();
		if(isset($_GET['category'])){
            $map['category']  = I('category');
        }
		if(isset($_GET["order"])){
			$order=I('order')." ".I('type');
		}else{
			$order="id desc";
		}
        $list   =   $this->lists('News', $map ,$order);
        $this->assign('newslist', $list);
        $this->meta_title = '公告管理';
        $this->display();
    }

    public function edit($id = null){
        $News = D('News');
        if(IS_POST){
            if(false !== $News->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $News->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			$info=$News->info($id);
            $this->assign('info',       $info);
            $this->meta_title = '编辑公告';
            $this->display();
        }
    }

    public function add(){
        $News = D('News');
        if(IS_POST){
            if(false !== $News->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $News->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增公告';
            $this->display('edit');
        }
    }

    public function del(){
		$id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $res = D('News')->remove($id);
        if($res !== false){
            $this->success('删除公告成功！');
        }else{
            $this->error('删除公告失败！');
        }
    }
}
