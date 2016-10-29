<?php
namespace Home\Controller;
class NewsController extends WapController{
    public function index($id){
    	$map['status']=1;
    	$map['display']=1;
        $map['category']=intval($id);
    	$news=M('News')->where($map)->select();
    	$this->assign('news',$news);
        $this->display($this->tplpath."notice.html");
    }

    public function more($id){
    	$news=D('News')->detail(intval($id));
    	$this->assign($news);
        $this->display($this->tplpath."notice_more.html");
    }
}