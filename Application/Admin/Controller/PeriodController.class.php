<?php
namespace Admin\Controller;

class PeriodController extends WebController {

    public function index(){
    	if(I('type')==1){
    		$map['uid']=array('LT',101500);
    	}elseif(I('type')==2){
    		$map['uid']=array('GT',101500);
    	}
    	if(I('express')==1){
    		$map['express_no']=array('NEQ','NULL');
    	}elseif(I('express')==2){
    		$map['express_no']=array('exp',' is NULL');
    	}
    	if(isset($_GET['keyword'])){
    		if(is_numeric($_GET['keyword'])){
    			$map['uid']=I('keyword');
    		}else{
    			$where['name']  = array('like', '%'.I('keyword').'%');
            	$map['sid']=array('IN',M('shop')->where($where)->getField('id',true));
    		}
        }
        $list   = $this->lists('shop_period',$map,$order='kaijang_time desc',$rows=0,$base = array('state'=>2),$field=true);
		foreach ($list as $k=>$v){
			$shop=M('shop')->where('id='.$v['sid'])->field('name,buy_url')->find();
			$list[$k]['no']=$v['no'];
			$list[$k]['kaijang_time']=$v['kaijang_time'];
			$list[$k]['kaijang_num']=$v['kaijang_num'];
			$list[$k]['name']=$shop['name'];
			$list[$k]['buy_url']=$shop['buy_url'];
			$list[$k]['user']=D('User')->info($v['uid']);
		}
        $this->assign('_list', $list);
        $this->meta_title = '中奖列表';
        $this->display();
    }
	
	public function edit($id){
		if(IS_POST){
			if(false !==D('Period')->update()){
                $this->success('商品发货成功！');
            } else {
                $this->error(D('Period')->getError());
            }
		}else{
			$info=D('Period')->express($id);
            $this->assign('info',$info);
			$this->meta_title = '修改用户';
			$this->display();
		}
	}
	public function shared($id){
		if(IS_POST){
			if(false !==D('Period')->shared($id)){
                $this->success('晒单发货成功！');
            } else {
                $this->error(D('Period')->getError());
            }
		}else{
			$this->meta_title = '用户晒单';
			$this->display();
		}
	}

	public function user($pid,$uid){
		
		$this->assign('num_list',D('Period')->user_num($uid,$pid));
		$this->record($uid);
		$this->period($uid);
		$this->display();
	}

	private function record($id){
		$map['uid']=$id;
		$list   = $this->lists('shop_record',$map,$order='id desc',$rows=0,$base = '',$field=true);
		foreach ($list as $k=>$v){
			$period=M('shop_period')->where('id='.$v['pid'])->field('sid,no,state,kaijang_time,kaijang_num')->find();
			$shop=M('shop')->where('id='.$period['sid'])->field('name,price')->find();
			$list[$k]['no']=$period['no'];
			$list[$k]['state']=$period['state'];
			$list[$k]['kaijang_time']=$period['kaijang_time'];
			$list[$k]['kaijang_num']=$period['kaijang_num'];
			$list[$k]['name']=$shop['name'];
			$list[$k]['price']=$shop['price'];
		}
        $this->assign('record_list', $list);
	}
	
	private function period($id){
		$map['uid']=$id;
		$list   = $this->lists('shop_period',$map,$order='kaijang_time desc',$rows=0,$base = '',$field=true);
		foreach ($list as $k=>$v){
			$shop=M('shop')->where('id='.$v['sid'])->field('name,price')->find();
			$list[$k]['no']=$v['no'];
			$list[$k]['kaijang_time']=$v['kaijang_time'];
			$list[$k]['kaijang_num']=$v['kaijang_num'];
			$list[$k]['name']=$shop['name'];
			$list[$k]['price']=$shop['price'];
		}
        $this->assign('period_list', $list);
	}
}