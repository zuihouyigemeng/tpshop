<?php
namespace Home\Controller;

class UserController extends WapController{

  public function userlogin(){
    if(!defined('UID') || UID==0){// 还没登录 跳转到登录页面
      $this->error('请先登录！',U('public/login'));
    }
  }

  public function _before_index(){
    $this->userlogin();
  }
  public function index(){
	  $user=D('User')->info(UID);
    if(IS_AJAX){
      $this->ajaxReturn($user);
    }else{
	   $this->assign('user',$user);
      $this->display($this->tplpath."user.html");
    }
  }

  public function user($id,$type=''){
  	$user=D('User')->info($id);
  	$this->assign('user',$user);
  	if($type=='lottery'){
  		$lottery=D('User')->lottery($id,$p);
  		$this->assign('lottery',$lottery);
  	}elseif($type=='displays'){
  		$displays=D('User')->displays($p,$id);
  		$this->assign('displays',$displays);
  	}else{
  		$records=D('User')->records($id,$p);
  		$this->assign('records',$records);
  	}
      $this->display($this->tplpath."user_her".($type?"_":"").$type.".html");
  }

  public function ajax_user($id){
    $this->ajaxReturn(D('User')->info($id,'id,nickname,phone,sex,province,city,country,headimgurl,create_time,black'));
  }

  public function guide(){
    $this->display($this->tplpath."guide.html");
  }

  public function _before_password(){
    $this->userlogin();
  }
  public function password(){
    if(false !== D('User')->password()){
      $this->success('密码修改成功！');
    } else {
      $error = D('User')->getError();
      $this->error(empty($error) ? '未知错误！' : $error);
    }
  }

  public function _before_bing_phone_app(){
    $this->userlogin();
  }
  public function bing_phone_app(){
    if(M('user')->where(array('phone'=>I('phone')))->getField('id')){
      $this->error('该手机已绑定！');
    }
    if(M('user')->where(array('id'=>UID))->setField('phone',I('phone'))){
      $this->success('手机绑定成功！');
    }else{
      $this->error('手机绑定失败！');
    }
}

  public function records($p=1,$state='',$uid=''){
  	$uid=$uid?$uid:UID;
		$records=D('User')->records($uid,$p,$state);
		if(IS_AJAX){
			$this->ajaxReturn($records);
		}else{
			$this->assign('records',$records);
      $this->display($this->tplpath."records.html");
		}
  }

  public function lottery($p=1,$uid=''){
   	$uid=$uid?$uid:UID;
   	$lottery=D('User')->lottery($uid,$p);
   	if(IS_AJAX){
   		$this->ajaxReturn($lottery);
   	}else{
   		$this->assign('lottery',$lottery);
      $this->display($this->tplpath."lottery.html");
   	}	
  }

  public function displays($uid='',$p=1,$sid=''){
  	$displays=D('User')->displays($p,$uid,'',$sid);
  	if(IS_AJAX){
   		$this->ajaxReturn($displays);
   	}else{
   		$this->assign('displays',$displays);
      	$this->display($this->tplpath."displays.html");
   	}		
  }

  public function displays_more($id){
  	$displays=D('User')->displays_more($id);
    if(IS_AJAX){
      $this->ajaxReturn($displays);
    }else{
      $this->assign($displays);
      $this->display($this->tplpath."displays_more.html");
    }
  }

  public function _before_shared(){
    $this->userlogin();
  }
  public function shared($pid){
    if(M('shop_period')->where('id='.$pid." and uid=".UID." and shared=1")->getField("id")){
    	if(IS_POST){
    		$res = D('User')->shared_update($pid);
  			if(false !== $res){
          $this->success('晒单成功，您获得1'.C("WEB_CURRENCY").'！',U('User/displays'));
        } else {
          $error = D('User')->getError();
          $this->error(empty($error) ? '未知错误！' : $error);
        }
    	}else{
    		$this->display($this->tplpath."shared.html");
    	}
    }else{
    	$this->error('请不要乱走！',U('Index/index'));
    }
  }

  public function announced($p=1,$pid=null){
  	$announced=D('User')->announced($p,$pid);
  	if(IS_AJAX){
   		$this->ajaxReturn($announced);
   	}else{
   		$this->assign('announced',$announced);
      $this->display($this->tplpath."announced.html");
   	}
  }

  public function looknum($id){
    $uid=I('uid')?I('uid'):UID;
    $record = D('Shop')->user_num($uid,$id);
    if(IS_AJAX){
      $this->ajaxReturn($record);
    }else{
      $period=M('shop_period')->where('id='.$id)->field('sid,no')->find();
      $shop=M('Shop')->where('id='.$period['sid'])->field(true)->find();
      $this->assign('period',$period);
      $this->assign('shop',$shop);
      $this->assign('record',$record);
      $this->display($this->tplpath."user_look_num.html");
    }
  }
  
  public function _before_address_info(){
    $this->userlogin();
  }
  public function address_info($id){
    $info=D('User')->address_info($id);
    $this->ajaxReturn($info);
  }

  public function _before_address(){
    $this->userlogin();
  }
	public function address(){
		$address=D('User')->address();
    if(IS_AJAX){
      $this->ajaxReturn($address);
    }else{
      $this->assign('address',$address);
      $this->display($this->tplpath."address.html");
    }
  }
	
  public function _before_address_add(){
    $this->userlogin();
  }
	public function address_add(){
		if(IS_POST){
			$res = D('User')->address_update();
			if(false !== $res){
         	$this->success('新增成功！', U('address'));
      } else {
          $error = D('User')->getError();
          $this->error(empty($error) ? '未知错误！' : $error);
      }
		}else{
			$user=D('User')->info(UID);
			$this->assign('user',$user);
			$this->assign('cart',I('get.cart'));
			$this->meta_title = '新增地址';
			$this->display($this->tplpath."address_add.html");
		}
  }
	
  public function _before_address_edit(){
    $this->userlogin();
  }
	public function address_edit($id){
		if(IS_POST){
			 if(false !== D('User')->address_update()){
          $this->success('编辑成功！', U('address'));
        } else {
          $error = D('User')->getError();
          $this->error(empty($error) ? '未知错误！' : $error);
        }
		}else{
			$info=D('User')->address_info($id);
			$user=D('User')->info(UID);
			$this->assign('info',$info);
			$this->assign('user',$user);
			$this->meta_title = '编辑地址';
			$this->display($this->tplpath."address_add.html");
		}
  }
	
  public function _before_address_del(){
    $this->userlogin();
  }
	public function address_del(){
    $id = array_unique((array)I('id',0,'intval'));
    if ( empty($id) ) {
        $this->error('请选择要操作的数据!');
    }
    $map = array('id' => array('in', $id));
    if(M('shop_address')->where($map)->delete()){
        $this->success('删除成功');
    } else {
        $this->error('删除失败！');
    }
  }

  public function _before_address_default(){
    $this->userlogin();
  }
  public function address_default($id){
    D('User')->address_default(UID);
		M('shop_address')->where('id='.$id)->setField('default',1);
	}
  public function _before_set_username(){
    $this->userlogin();
  }
  public function set_username(){
    M('user')->where('id='.UID)->setField('nickname',I('nickname'));
    $auth = array(
      'uid'             => UID,
      'username'        => I('nickname')
    );
    session('user_auth', $auth);
    session('user_auth_sign', data_auth_sign($auth));
  }

  public function _before_recharge_list(){
    $this->userlogin();
  }
  
  public function recharge_list($p='',$paydate=''){
    $list=D('User')->recharge_list($p,$paydate);
    if(IS_AJAX){
      $this->ajaxReturn($list);
    }else{
      $this->assign('list',D('User')->recharge_list());
      $this->display($this->tplpath."recharge_list.html");
    }
  }
}