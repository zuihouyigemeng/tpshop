<?php
namespace Home\Controller;
use Com\Wechat;
use Com\WechatAuth;

class ActivityController extends WapController {

    public function index(){
		$list=D('Activity')->lists();
		$this->assign('list',$list);
		$this->display($this->tplpath."activity.html");
	}
	
	public function activity($id){
		$this->assign('ticket',$ticket);
		$this->activity_tpl($id,'web_index_tpl');
	}
	
	public function lists($id){
		$this->activity_tpl($id,'web_index_tpl');
	}
	
	public function content($id){
		$this->activity_tpl($id,'web_content_tpl');
	}
	
	public function ticket(){
		$webtoken = session("webtoken");
		if($webtoken){
            $this->auth = new WechatAuth(C('WX_APPID'), C('WX_APPSECRET'), $webtoken);
        } else {
            $this->auth  = new WechatAuth(C('WX_APPID'), C('WX_APPSECRET'));
            $webtoken = $this->auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("webtoken", $token['access_token']);
        }
		$data=$this->auth->qrcodeCreate(1,604800);
		if($data['errcode']==42001 || $data['errcode']==40001){
			session("webtoken",null);
			$this->ticket();
		}
		return $this->auth->showqrcode($data['ticket']);
	}
	
	protected function activity_tpl($id,$tpl){
		$info=D('Activity')->detail($id);
		$list_log=D('Activity')->list_log($id,UID);
		if(IS_AJAX){
			$this->ajaxReturn(array('info'=>$info,'list'=>$list_log,'uid'=>UID));
		}else{
			$this->assign('info',$info);
			$this->assign('list_log',$list_log);
			$this->display($this->tplpath.$info[$tpl]);
		}
	}

	public function turntable_num(){
		$turntable=M('user')->where('id='.UID)->getField('turntable');
		if(IS_AJAX){
			$this->ajaxReturn(array('turntable' => $turntable,'uid'=>UID));
		}else{
			return $turntable;
		}
	}

	public function turntable_list(){
		return M('activity_log')->where('activity_id=59')->limit(50)->order('price desc')->select();
	}

	public function getTurntable(){
		return activity_log('share_turntable',UID,UID);
	}

	public function getPrize(){
		$turntable=M('user')->where('id='.UID)->getField('turntable');
		if($turntable<=0){
			$this->ajaxReturn(array('v' =>1,'turntable' =>0));
			return false;
		}
		$prize_arr=array(
			array('num'=>100,'v'=>1),
			array('num'=>50,'v'=>2),
			array('num'=>0,'v'=>2700),
			array('num'=>10,'v'=>20),
			array('num'=>1,'v'=>1000),
			array('num'=>20,'v'=>7),
			array('num'=>0,'v'=>2700),
			array('num'=>1,'v'=>1000),
			array('num'=>3,'v'=>70),
			array('num'=>0,'v'=>2500));
		$proSum=10000;
		foreach($prize_arr as $k=>$v){
			$randNum=mt_rand(1,$proSum);//随机数
			if($randNum<=$v['v']){
				activity_log('turntable_jl',$v['num'],UID);
				M('user')->where('id='.UID.' and turntable>0')->setDec('turntable');
				$this->ajaxReturn(array('v' =>$k,'turntable' => $turntable));
				break;
			}else{
				$proSum-=$v['v'];
			}
		}	
	}
}