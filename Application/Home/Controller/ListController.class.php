<?php
namespace Home\Controller;
class ListController extends WapController{
    public function index(){
		$id=I('id',0,'intval');
		$p=I('p',0,'intval');
		$ten=I('ten',0,'intval');
		$order=I('order')?I('order'):"shop.hits|desc";
		if($id){
			$info=D("Category")->info($id);
		}
		$shop=D('Shop')->period($p,$id,20,$order,$ten);
		if(IS_AJAX){
    		$this->ajaxReturn($shop);
    	}else{
    		$this->cid=intval($info["id"]);
			$this->pid=$info["pid"];
			$this->ctitle=$info["title"];
			$this->list_webtitle=empty($info["meta_title"]) ? $this->web_title : $info["meta_title"];
			$this->list_keywords=empty($info["keywords"]) ? $this->web_keywords : $info["keywords"];
			$this->list_description=empty($info["description"]) ? $this->web_description :$info["description"];
			if($info["pid"]>0){
				$this->assign('pos',1);
			}else{
				$this->assign('pos',2);
			}
    		$this->assign('shop',$shop);
    		$this->display($this->tplpath."list.html");
    	} 
    }

    public function type(){
    	if(IS_AJAX){
    		$this->ajaxReturn(D('Category')->getTree());
    	}else{
    		return D('Category')->getTree();
    	}
    	
    }
}