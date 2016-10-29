<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model{

	protected $_validate = array(
	    array('oldpassword', 'require', '请输入原密码！', self::EXISTS_VALIDATE, 'regex',self::MODEL_UPDATE),
	    array('password', '6,30', '密码长度必须在6-30个字符之间！', self::EXISTS_VALIDATE, 'length')
  	);

  	protected $_auto = array(
	    array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function'),
	    array('create_time', NOW_TIME, self::MODEL_INSERT),
	    array('login_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
	    array('status', '1')
  	);

	public function info($id, $field = true){
		$map = array();
		if(is_numeric($id)){
			$map['id'] = $id;
		} else {
			$map['username'] = $id;
		}
		$user=M('User')->field($field)->where($map)->find();
		$user['headimgurl']=completion_pic($user['headimgurl']);
		return $user;
	}

	 public function update(){
        if(!$data = $this->create()){
            return false;
        }
        $data['id']=UID;
		unset($data['password']);
        $res = $this->save($data);
        return $res;
    }
	
	public function password(){
		if(!$data = $this->create()){
            return false;
        }
		if(I('post.password') !== I('post.repassword')){
            $this->error = '您输入的新密码与确认密码不一致！';
			return false;
        }
		if(!$this->verifyUser(UID, I('post.oldpassword'))){
			$this->error = '验证出错：密码不正确！';
			return false;
		}
        $this->id=UID;
        $res = $this->save();
        return $res;
    }

	public function getBlack(){
		return M('User')->where("id=".UID)->getField('black');
	}
	
	public function records($id,$p,$state){
		$subQuery = M('shop_record')->field(true)->where('uid='.$id)->order('create_time desc')->select(false);
		if(is_numeric($state)){
			$where_state=' and period.state='.$state;
		}
		$period = M('shop_period')->table($subQuery.' record,__SHOP_PERIOD__ period')->field('period.*')->where('record.pid=period.id'.$where_state)->group('record.pid')->page($p,$num)->order('period.state asc,record.create_time desc')->select();
		$subQuery = M('shop_record')->distinct(true)->field('pid')->where('uid='.$id)->select(false);
		$count = M('shop_period')->alias('period')->where('id in '.$subQuery.$where_state)->count();
		if($period){
			foreach ($period as $k=>$v){
				$info = M('shop')->field(true)->find($v["sid"]);
				$list[$k]= D('Shop')->overChange($info,$v);
				$list[$k]['user']=$this->userChange($v);
				$list[$k]['user']['count']=M('shop_record')->where("uid=".$v['uid']." and pid=".$v["id"])->sum('number');
				$list[$k]['count']=M('shop_record')->where("uid=".$id." and pid=".$v["id"])->sum('number');
				$list[$k]['lookurl']=U('User/looknum?id='.$v['id'].'&uid='.$id);
				$list[$k]['userurl']=U('User/user?id='.$v['uid']);
			}
		}
		return $list;
	}

	public function lottery($id,$p){
		$map['state']=2;
		if($id){
			$map['uid']=$id;
		}
		$period = M('shop_period')->where($map)->field(true)->page($p.',20')->order('end_time desc')->select();
		if($period){
			foreach ($period as $k=>$v){
				$info = M('shop')->field(true)->find($v["sid"]);
				$list[$k]= D('Shop')->overChange($info,$v);
				$list[$k]['user']=$this->userChange($v);
				$list[$k]['count']=M('shop_record')->where("uid=".$id." and pid=".$v["id"])->sum('number');
			}
		}
		return $list;
	}

	public function displays($p,$id,$num=20,$sid=''){
		if($id){$sql=' and shared.uid='.$id;}
		if($sid){$sql.=' and period.sid='.$sid;}
		$shared = M('shop_shared')->table('__SHOP_SHARED__ shared,__SHOP_PERIOD__ period') ->field('shared.id,shared.pic,shared.thumbpic,shared.content,shared.create_time,period.uid,period.number,period.no,period.kaijang_num,period.kaijang_time,period.id as pid,period.sid')->where('shared.pid=period.id'.$sql)->page($p,$num)->order('shared.create_time desc')->select();
		if($shared){
			foreach ($shared as $k=>$v){
				$list[$k] = $this->userChange($v,'displays');
				$list[$k]['shared_id']=$v['id'];
				$list[$k]['pic']=explode(',',str_replace('/Picture',C('web_url').'/Picture',$v['pic']));
				$list[$k]['thumbpic']=explode(',',str_replace('/Picture',C('web_url').'/Picture',$v['thumbpic']));
				$list[$k]['content']=$v['content'];
				$list[$k]['count']=M('shop_record')->where("pid=".$v["pid"]." and uid=".$v['uid'])->sum('number');
			}
			return $list;
		}
	}

	public function displays_more($id){
		$shared = M('shop_shared')->table('__SHOP_SHARED__ shared,__SHOP_PERIOD__ period') ->field('shared.id,shared.pic,shared.content,period.uid,period.number,period.no,period.kaijang_num,period.kaijang_time,period.id as pid,period.sid')->where('shared.pid=period.id and shared.id='.$id)->order('shared.id desc')->find();
		if($shared){
			$list = $this->userChange($shared,'displays');
			$list['pic']=explode(',',str_replace('/Picture',C('web_url').'/Picture',$shared['pic']));
			$list['content']=$shared['content'];
			$list['shop']=D('Shop')->detail($list["pid"]);
			$list['count']=M('shop_record')->where("uid=".$list['uid']." and pid=".$list["pid"])->sum('number');
			return $list;
		}
	}

	public function shared_update($pid){
		$rules = array(     
			array('content','require','晒单内容不能为空！')
		);
		if (!$data=M('shop_shared')->validate($rules)->create()){
			$this->error = M('shop_shared')->getError();
			return false;
		}
		$pic = array_unique((array)$data['pic']);
        if ( empty($pic) ) {
            $this->error='至少要有一张晒单图片!';
            return false;
        }
		$data['pid']=$pid;
		$data['uid']=UID;
		$data['pic']=implode(',',$data['pic']);
		$data['thumbpic']=implode(',',$data['thumbpic']);
		$data['create_time']=NOW_TIME;
        $res = M('shop_shared')->add($data);
        M('shop_period')->where('id='.$pid." and uid=".UID)->setField("shared",0);
		activity(6,$res,UID);
        return $res;
    }

    public function announced($p,$pid=null){
    	if($pid){
    		$period = M('shop_period')->where(array('id'=>$pid,'state'=>array('gt',0)))->field(true)->order('state asc,kaijang_time desc')->select();
    	}else{
    		$period = M('shop_period')->where('state>0')->field(true)->page($p.',20')->order('state asc,kaijang_time desc')->select();
    	}
		if($period){
			foreach ($period as $k=>$v){
				$info = M('shop')->field(true)->find($v["sid"]);
				$user_name=get_user_name($v["uid"]);
				$list[$k]= D('Shop')->overChange($info,$v);
				$list[$k]['user']=is_numeric($user_name)?substr_replace($user_name,'****',3,4):$user_name;
				$list[$k]['user_pic']=get_user_pic($v["uid"]);
				$list[$k]['count']=M('shop_record')->where("uid=".$v["uid"]." and pid=".$v["id"])->sum('number');
			}
		}
		return $list;
    }

	public function address_info($id, $field = true){
		$map = array();
		$map['uid']=UID;
		if(is_numeric($id)){
			$map['id'] = $id;
		} else {
			$map['nickname'] = $id;
		}
		return M('shop_address')->field($field)->where($map)->find();
	}
	
	public function address(){
		$map = array();
		$map['uid']=UID;
		return M('shop_address')->field(true)->where($map)->select();
	}

	public function address_default($uid){
		return M('shop_address')->where('uid='.$uid)->save(array('default' => 0 ));
	}
	
	public function address_update(){
		$rules = array(     
			array('nickname','require','联系人不能为空！'),
			array('tel','require','联系电话不能为空！'),
			array('province','require','请选择所在城市！'),
			array('city','require','请选择所在城市！'),
			array('address','require','请填写收货地址！'),
		);
		if (!$data=M('shop_address')->validate($rules)->create()){
			$this->error = M('shop_address')->getError();
			return false;
		}
		$data['uid']=UID;
		if($data['default']==1){
			$this->address_default($data['uid']);
		}
        if(empty($data['id'])){
            $res = M('shop_address')->add($data);
        }else{
            $res = M('shop_address')->save($data);
        }
        return $res;
    }

    public function recharge_list($p,$paydate){
    	if($paydate==1){
    		$where=" and FROM_UNIXTIME(create_time,'%Y-%m-%d')=DATE_FORMAT(now(),'%Y-%m-%d')";
    	}elseif($paydate==2){
    		$where=" and YEARWEEK(FROM_UNIXTIME(create_time,'%Y-%m-%d')) = YEARWEEK(now())";
    	}elseif($paydate==3){
    		$where=" and FROM_UNIXTIME(create_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    	}
    	if($p){
    		$list=M('shop_order')->where('type>1 and status=1 and uid='.UID.$where)->page($p.',20')->order('id desc')->select();
    	}else{
    		$list=M('shop_order')->where('type>1 and status=1 and uid='.UID.$where)->order('id desc')->select();
    	}
    	if($list){
			foreach ($list as $k=>$v){
				$data[$k]['number']=$v['number'];
				$data[$k]['order_id']=$v['order_id'];
				$data[$k]['code']=$v['code']=='OK'?"已付款":"未付款";
				$data[$k]['recharge']=$v['recharge']==1?"充值":"购买";
				$data[$k]['paytype']=get_recharge($v['type']);
				$data[$k]['time']=time_format($v['create_time'],'Y-m-d H:i:s');
			}
		}
    	return $data;
    }

    public function userChange($val,$type=''){
		$map=array('status'=>1,'id'=>$val['uid']);
		$user=M('user')->field('id,nickname,province,city,headimgurl')->where($map)->find();
		$data["id"]=$user["id"];
		$data["name"]=is_numeric($user["nickname"])?substr_replace($user["nickname"],'****',3,4):$user["nickname"];
		$data["address"]=$user["province"].$user["city"];
		$data["img"]=completion_pic($user["headimgurl"]);
		$data['user_url']=url_change("user/user",array("id"=>$user["id"],"name"=>'user'));
		switch ($type) {
			case 'record':
				$number=D('Shop')->user_num($val["uid"],$val["pid"]);
				$data["number"]=$number;
				$data["count"]=count($number);
				$data["time"]=time_format(substr($val["create_time"],0,-3),'Y-m-d H:i:s').'.'.substr($val["create_time"],-3);
				break;
			case 'history':
				$data["state"]=$val["state"];
				$data["no"]=$val["no"];
				$data["pid"]=$val["id"];
				$data["url"]=url_change("shop/over",array("id"=>$val["id"],"name"=>'shop'));
				$data["number"]=$val["number"];
				$data["kaijang_num"]=$val["kaijang_num"];
				$data["time"]=time_format($val["kaijang_time"]);
				break;
			case 'displays':
				$data["uid"]=$val["uid"];
				$data["sid"]=$val["sid"];
				$data["pid"]=$val["pid"];
				$data["no"]=$val["no"];
				$data["shop_name"]=get_shop_name($val["sid"]);
				$data["url"]=url_change("user/displays_more",array("id"=>$val["id"],"name"=>'user'));
				$data["number"]=$val["number"];
				$data["kaijang_num"]=$val["kaijang_num"];
				$data["time"]=time_format($val["kaijang_time"]);
				$data['shared_time']=time_format($val['create_time']);
				break;
			default:
				break;
		}
		return $data;
	}

	protected function verifyUser($uid, $password_in){
		$password = $this->getFieldById($uid, 'password');
		if(think_ucenter_md5($password_in) === $password){
			return true;
		}
		return false;
	}
}
