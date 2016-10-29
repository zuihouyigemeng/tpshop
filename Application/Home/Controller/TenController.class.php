<?php
namespace Home\Controller;

class TenController extends WapController{

    public function index(){
    	$ten=I('ten',0,'intval');
		$p=I('p',0,'intval');
		$order=I('order')?I('order'):"shop.hits|desc";
		$shop=D('Shop')->period($p,'',20,$order,$ten);
		if(IS_AJAX){
    		$this->ajaxReturn($shop);
    	}else{
    		if($ten){
				$info=D("Ten")->info($ten);
			}
			$this->ctitle=$info["title"];
			$this->ten_webtitle=empty($info["meta_title"]) ? $this->web_title : $info["meta_title"];
			$this->ten_keywords=empty($info["keywords"]) ? $this->web_keywords : $info["keywords"];
			$this->ten_description=empty($info["description"]) ? $this->web_description :$info["description"];
			if($info["pid"]>0){
				$this->assign('pos',1);
			}else{
				$this->assign('pos',2);
			}
    		$this->assign('shop',$shop);
    		$this->display($this->tplpath."ten.html");
    	}
    }

    public function ten(){
        return D('ten')->ten();
    }
}