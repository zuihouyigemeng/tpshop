<?php
namespace Admin\Controller;

class UserController extends WebController {

    public function index(){
    	$map = array();
        if(isset($_GET['keyword'])){
        	if(is_numeric($_GET['keyword'])){
        		$map['id'] = I('keyword');
        	}else{
        		$map['nickname'] = array('like', '%'.I('keyword').'%');
        	}
        }
        $list   = $this->lists('User', $map);
        $this->assign('_list', $list);
        $this->meta_title = '用户列表';
        $this->display();
    }


    public function password($id){
		 if(IS_POST){
			$res   =  D('User')->password();
			if($res  !== false){
				$this->success('修改密码成功！',U('index'));
			}else{
				$this->error(D('users')->getError());
			}
		 }else{
		 	$nickname = M('User')->where("id=".$id)->getField('nickname');
			$this->assign('nickname', $nickname);
			$this->meta_title = '修改密码';
			$this->display();
		 }
    }
	
	public function edit($id){
		if(IS_POST){
			$uid = D('User')->edit();
			if(is_numeric($uid)){
                $this->success('用户修改资料成功！',U('index'));
            } else {
                $this->error(D('User')->getError());
            }
		}else{
			$info=D('User')->info($id);
            $this->assign('info',$info);
			$this->meta_title = '修改用户';
			$this->display();
		}
	}
	
    public function del(){
        $id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $id) );
        if(M('User')->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
	
	public function record($id){
		$map['uid']=$id;
		$list   = $this->lists('shop_record',$map,$order='',$rows=0,$base = '',$field=true);
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
        $this->assign('_list', $list);
        $this->display();
	}
	
	public function period($id){
		$map['uid']=$id;
		$list   = $this->lists('shop_period',$map,$order='',$rows=0,$base = '',$field=true);
		foreach ($list as $k=>$v){
			$shop=M('shop')->where('id='.$v['sid'])->field('name,price')->find();
			$list[$k]['no']=$v['no'];
			$list[$k]['kaijang_time']=$v['kaijang_time'];
			$list[$k]['kaijang_num']=$v['kaijang_num'];
			$list[$k]['name']=$shop['name'];
			$list[$k]['price']=$shop['price'];
			$list[$k]['number']=M('shop_record')->where('uid='.$id.' and pid='.$v['id'])->sum('number');
		}
        $this->assign('_list', $list);
        $this->display();
	}

	public function pay($id){
		$map['uid']=$id;
		$map['type']=array('gt',1);
		$list = $this->lists('shop_order',$map,$order='',$rows=0,$base = '',$field=true);
        $this->assign('_list', $list);
        $this->display();
	}
	public function activity($id){
		$map['user_id']=$id;
		$list = $this->lists('activity_log',$map,$order='',$rows=0,$base = '',$field=true);
        $this->assign('_list', $list);
        $this->display();
	}
}