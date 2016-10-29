<?php
namespace Home\Controller;
use Think\Controller;
use Com\WechatAuth;
use Com\Alidayu\AlidayuClient as Client;
use Com\Alidayu\Request\SmsNumSend;
/**
 * 用户首页控制器
 */
class PublicController extends Controller {

	 protected function _initialize(){
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =  config_lists();
            S('DB_CONFIG_DATA',$config);
        }
        C($config);
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
        $this->web_title=C("WEB_SITE_TITLE");
        $this->web_logo="/".C('TMPL_PATH')."/Web/images/".C("WEB_LOGO");
        $this->web_keywords=C("WEB_SITE_KEYWORD");
        $this->web_description=C("WEB_SITE_DESCRIPTION");
        $this->web_icp=C("WEB_SITE_ICP");
        $this->web_url=C("WEB_URL");
        $this->login_qq_appid=C('LOGIN_QQ_APPID');
        $this->login_wx_appid=C('LOGIN_WX_APPID');
		$this->web_path=__ROOT__."/";
        $this->tplpath="./".C('TMPL_PATH')."/Web/";
        $this->web_tplpath=$this->web_path.C('TMPL_PATH')."/Web/";
        C('CACHE_PATH',RUNTIME_PATH."/Cache/".MODULE_NAME."/Web/");
	}

    public function servertime(){
        $this->ajaxReturn(array('time'=>NOW_TIME));
    }

    public function islogin(){
        if(is_login()){
            $this->success('已经登录！');
        }else{
            $this->error('请先登录！');
        }
    }
	
	public function login($username = null, $password = null){
        if(IS_POST){
            $user = D('Public')->login($username, $password);
            if($user[0]>0){
                //启动用户登录类活动
                activity(2,$user[0],$user[0]);
                $this->success('登录成功！', U('Index/index'));
            } else { //登录失败
                switch($user[0]) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    case -3: $error = '用户还为激活！';
                    	session('username',$username);
                    	if(is_numeric($username)){
                    		$url=U('public/phone/');
                    	}else{
                    		$url=U('public/email/');
                    	}
                    	break;
                    default: $error = '未知错误！'; break;
                }
                $this->error($error,$url);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                $this->display($this->tplpath."login.html");
            }
        }
    }

    public function weixinlogin($code){
        $this->wechatLogin($code,C('LOGIN_WX_APPID'),C('LOGIN_WX_APPSECRET'));
    }

    protected function wechatLogin($code,$appid,$appsecret){
        $auth  = new WechatAuth($appid, $appsecret);
        $token=$auth->getAccessToken('code',$code);
        $map=array('unionid'=>$token['unionid'],'status'=>1);
        $user=M('User')->where($map)->field('id,nickname')->find();
        if($user){
            D('Public')->autoLogin($user);
            //启动用户登录类活动
            activity(2,$user['id'],$user['id']);
            if(isset($_GET['state'])){
                $this->success('登录成功！'.$user['id'], $this->web_url.U('Index/index',array("cookie"=>$_COOKIE['PHPSESSID'])));
            }else{
                $this->success('登录成功！', $this->web_url.U('Index/index'));
            }
        }else{
            $data = $auth->getUserInfo($token['unionid']);
            $data['password']=think_ucenter_md5($data['openid']);
            $data['login_ip']=ip2long(get_client_ip());
            $data['status']=1;
            $data['create_time']=NOW_TIME;
            $data['activation']=1;
            unset($data['language'],$data['privilege'],$data['openid']);
            $uid=M('User')->add($data);
            $auth = array(
                'uid'             => $uid,
                'username'        => $data['nickname']
            );
            session('user_auth', $auth);
            session('user_auth_sign', data_auth_sign($auth));
            if(session('uid')){
                activity(7,$uid,session('uid'));
                session('uid',null);
            }
            //启动用户注册活动
            activity(1,$uid,$uid);
            $this->success('用户注册成功！',$this->web_url.U('index/index'));
        }
    }

    public function qq_login(){
        import('Com.QqConnect.qqConnect');
        $qc = new \QC(C('LOGIN_QQ_APPID'),C('LOGIN_QQ_APPKEY'),C('WEB_URL').C('LOGIN_QQ_CALLBACK'));
        $qc ->set_state($_GET['wid']);
        $qc->qq_login();
    }
	
    public function qq_callback(){
        import('Com.QqConnect.qqConnect');
        $qc = new \QC(C('LOGIN_QQ_APPID'),C('LOGIN_QQ_APPKEY'),C('WEB_URL').C('LOGIN_QQ_CALLBACK'));
        $acs=$qc->qq_callback();
        $oid=$qc->get_openid();
        $map['qqopenid']=$oid;
        $map['status']=1;
        if(isset($_GET['state'])){
            $map['wid'] = intval($_GET['state']);
        }
        $user=M('User')->where($map)->field('id,nickname')->find();
        if(!$user){
            $qc = new \QC(C('LOGIN_QQ_APPID'),C('LOGIN_QQ_APPKEY'),C('WEB_URL').C('LOGIN_QQ_CALLBACK'),$acs,$oid);
            $ret = $qc->get_user_info();
            $data['nickname']=$ret['nickname'];
            $data['password']=think_ucenter_md5($map['qqopenid']);
            $data['qqopenid']=$oid;
            $data['headimgurl']=$ret['figureurl_qq_1'];
            $data['province']=$ret['province'];
            $data['city']=$ret['city'];
            $data['sex']=$ret['gender']=='男'?1:2;
            $data['login_ip']=ip2long(get_client_ip());
            $data['status']=1;
            $data['create_time']=NOW_TIME;
            $data['activation']=1;
            $uid=M('User')->add($data);
            $auth = array(
                'uid'             => $uid,
                'username'        => $ret['nickname']
            );
            session('user_auth', $auth);
            session('user_auth_sign', data_auth_sign($auth));
            if(session('uid')){
                activity(7,$uid,session('uid'));
                session('uid',null);
            }
            //启动用户注册活动
            activity(1,$uid,$uid);
            $filename=getcwd().'/Template/Web/images/login2.jpg';
            $qc->add_pic_t(array("content"=>"1元就可以有机会买到iphone 6手机 ".$this->web_url,"pic"=>"@{$filename}"));
            $this->success('用户注册成功！',$this->web_url.U('index/index'));
        }else{
            D('Public')->autoLogin($user);
            //启动用户登录类活动
            activity(2,$user['id'],$user['id']);
            $this->success('登录成功！',$this->web_url.U('index/index'));
        }
    }

	/* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Public')->logout();
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        }
    }
	
	public function reg(){
		if(!C('USER_ALLOW_REGISTER')){
            $this->error('注册已经关闭，请稍后注册~');
        }
		if(IS_POST){
             /* 检测验证码 TODO: */
            if(!check_verify(I("passcode"))){
                $this->error("验证码错误！");
            }
            if(!I('protocol')){
                $this->error('抱歉不同意服务协议无法注册！');
            }
			if(I("password") != I("repassword")){
                $this->error('密码和重复密码不一致！');
            }
            if(is_numeric(I('username'))){
            	$map['phone']=I('username');
            }else{
				$map['username']=I('username');
            }
            if(M('user')->where($map)->getField('id')){
            	$this->error('用户已经注册！');
            }
            if(!$this->check_name(I('username'))){
            	$this->error('请正确输入注册邮箱或手机号');
            }
			$uid = D('Public')->reg();
			if(is_numeric($uid)){
                session('username',I('username'));
                if(session('uid')){
                    activity(7,$uid,session('uid'));
                    session('uid',null);
                }
                //启动用户注册活动
                activity(1,$uid,$uid);
                if(is_numeric(I('username'))){
                    session('username',I('username'));
                    $this->success('用户注册成功！',U('Public/phone'));
                }else{
                    $this->jhMail($uid,I('username'),think_ucenter_md5(I('password')));
                    $this->success('用户注册成功！',U('Public/email'));
                }
            } else {
                $this->error(D('Public')->getError());
            }
		}else{
			$this->display($this->tplpath."reg.html");
		}
	}

    public function email($uid=null,$key=null){
        $map=array('id' => $uid, 'password'=>$key,'status'=>1);
        if(M('user')->where($map)->setField('activation',1)){
            session('username',null);
            $this->success('邮箱验证成功请登录！',U('Public/login'));
        }else{
            $this->assign('user',session('username'));
            $this->assign('uid',M('user')->where(array('username'=>session('username')))->getField('id'));
            $this->display($this->tplpath."email.html"); 
        }
    }

    public function phone($uid=null,$code=null){
        if(IS_POST){
            if($this->cell_code_check($code)){
                $map=array('id' => $uid,'status'=>1);
                if(M('user')->where($map)->setField('activation',1)){
                    session('cell_code', null);
                    session('username',null);
                    $this->success('手机验证成功请登录！',U('Public/login'));
                }
            }else{
                $this->error('手机验证失败！');
            }
        }else{
            $this->assign('uid',M('user')->where(array('phone'=>session('username')))->getField('id'));
            $this->display($this->tplpath."phone.html");
        }
    }

    public function sendcode($type='reg',$phone=''){
        $username=$phone?$phone:session('username');
        if(IS_GET){
            if($type=='reg'){
                $this->cellcode($username);
            }elseif($type=='bindcode'){
                $this->cellcode($username);
            }else{
                $this->pswcode($username);
            }
        }
    }
	
	public function forgetpwd($step=1){
        switch ($step) {
            case 1:
                if(IS_POST){
                    /* 检测验证码 TODO: */
                    if(!check_verify(I("passcode"))){$this->error("验证码错误！");}
                    if(I('username')){
                    	if(is_numeric(I('username'))){
                    		$map['phone']=I('username');
                    	}else{
                    		$map['username']=I('username');
                    	}
                    	$map['status']=1;
                        if($user=M('user')->field('id,username,password,phone')->where($map)->find()){
                            if(is_numeric(I('username'))){
                                session('username',$user['phone']);
                                $this->success('验证手机！',U('Public/forgetpwd/step/2/uid/'.$user['id']));
                            }else{
                                $this->pwdMail($user['id'],$user['username'],$user['password']);
                                session('username',$user['username']);
                                $this->success('邮箱发送成功！',U('Public/forgetpwd/step/2/uid/'.$user['id']));
                            }
                        }else{$this->error('没有该用户！');}
                    }else{$this->error('请输入用户名！');}
                }else{$this->display($this->tplpath."findpwd_1.html");}
                break;
            case 2:
                if(IS_POST){
                    if($this->cell_code_check(I('post.code'))){
                        $map=array('id' => I('post.uid'),'status'=>1);
                        $user=M('user')->field('id,password')->where($map)->find();
                        session('cell_code', null);
                        session('username',null);
                        $this->success('手机验证成功请重新设置密码！',U('Public/forgetpwd',array('step'=>3,'uid'=>$user['id'],'key'=>$user['password'])));
                    }else{
                        $this->error('手机验证失败！');
                    }
                }else{
                    $this->assign('uid',I('uid',0,'intval'));
                    $this->assign('username',session('username'));
                    if(is_numeric(session('username'))){
                        $this->display($this->tplpath."findpwd_2_phone.html");
                    }else{
                        $this->display($this->tplpath."findpwd_2.html");
                    }
                }
                break;
            case 3:
                if(IS_POST){
                    if(I("password") != I("repassword")){
                        $this->error('密码和重复密码不一致！');
                    }
                    if(strlen(I('password'))<6){
                        $this->error('密码长度必须大于5位数！');
                    }
                    $map=array('id'=>session('uid'),'password'=>session('password'));
                    session('[destroy]');
                    if(M('user')->where($map)->setField('password',think_ucenter_md5(I('repassword')))){
                        $this->success('密码修改成功！',U('Public/login'));
                    }else{
                        $this->error('请问你想做什么！',U('index/index'));
                    }
                }else{
                    $map=array('id'=>I('uid',0,'intval'), 'password'=>I('key'),'status'=>1);
                    if(M('user')->where($map)->getField('id')){
                        session('password',I('key'));
                        session('uid',I('uid',0,'intval'));
                        $this->display($this->tplpath."findpwd_3.html");
                    }else{
                        $this->error('请不要到你不该来的地方！',U('index/index'));
                    }
                }
                break;
        }
	}

	public function login_cookie(){
		return 'PHPSESSID='.cookie('PHPSESSID').';path='.cookie('path');
	}


    public function verify(){
        ob_clean();
        $config =   array(
        'useCurve'  => false,            // 是否画混淆曲线
        'useNoise'  => true,            // 是否添加杂
        'fontSize'  => 16,              // 验证码字体大小(px)
        'length'    => 4,               // 验证码位数
        );
        $verify = new \Think\Verify($config);
        $verify->entry(1);
    }
    public function againmail($uid){
        $map=array('id' => $uid,'status'=>1,'activation'=>0);
        $user=M('user')->field('id,username,password')->where($map)->find();
        if($user){
            if($this->jhMail($user['id'],$user['username'],$user['password'])){
                $this->success('发送成功请查收！');
            }else{
                $this->error('发送失败请从新发送！');
            }
        }
    }

    protected function jhMail($uid,$username,$password){
        $url=$this->web_url."/public/email/uid/".$uid."/key/".$password;
        return sendMail($username,'感谢您注册'.$this->web_title.'-帐号激活邮件','<div class="wrapper" style="margin: 20px auto 0; width: 500px; padding-top:16px; padding-bottom:10px;"><br style="clear:both; height:0"><div class="content" style="background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 2px 0 0; padding: 30px;"><p>您好: </p><p>感谢您注册 <a href="'.$this->web_url.'">'.$this->web_title.'</a></p><p style="border-top: 1px solid #DDDDDD;margin: 15px 0 25px;padding: 15px;">请点击以下链接激活并设置您的账号: <a href="'.$url.'" target="_blank">'.$url.'</a></p><p style="border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;"><p>请勿回复本邮件, 此邮箱未受监控, 您不会得到任何回复。</p><p>如果点击上面的链接无效，请尝试将链接复制到浏览器地址栏访问。</p></p></div></div>');
    }

    public function againpwd($uid){
        $map=array('id' => $uid,'status'=>1);
        $user=M('user')->field('id,username,password')->where($map)->find();
        if($user){
            if($this->pwdMail($user['id'],$user['username'],$user['password'])){
                $this->success('发送成功请查收！');
            }else{
                $this->error('发送失败请从新发送！');
            }
        }
    }

    protected function pwdMail($uid,$username,$password){
        $url=$this->web_url."/public/forgetpwd/step/3/uid/".$uid."/key/".$password;
        return sendMail($username,$this->web_title.'-密码找回','<div class="wrapper" style="margin: 20px auto 0; width: 500px; padding-top:16px; padding-bottom:10px;"><br style="clear:both; height:0"><div class="content" style="background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 2px 0 0; padding: 30px;"><p>您好: </p><p style="border-top: 1px solid #DDDDDD;margin: 15px 0 25px;padding: 15px;">您最近提出了密码重设请求。要完成此过程，请点按以下链接: <a href="'.$url.'" target="_blank">'.$url.'</a></p><p style="border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;"><p>如果您未提出此请求，可能是其他用户无意中输入了您的电子邮件地址，您的帐户仍然安全。</p><p>请勿回复本邮件, 此邮箱未受监控, 您不会得到任何回复。</p><p>如果点击上面的链接无效，请尝试将链接复制到浏览器地址栏访问。</p></p></div></div>');
    }

    /**
     * 获取随机位数数字
     * @param  integer $len 长度
     * @return string       
     */
    protected function randString($len = 6){
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }

    //手机验证码保存
    protected function cell_code($code){
        $session = array();
        $session['cell_code'] = $code;
        $session['cell_time'] = NOW_TIME;
        session('cell_code', $session);
    }

    protected function cellcode($cell){
        $client  = new Client;
        $request = new SmsNumSend;
        $code=$this->randString();
        $this->cell_code($code);
        $smsParams = array(
            'code'    => $code,
            'product' => C("WEB_SITE_TITLE")
        );
        // 设置请求参数
        $req = $request->setSmsTemplateCode('SMS_5235593')
            ->setRecNum($cell)
            ->setSmsParam(json_encode($smsParams))
            ->setSmsFreeSignName('注册验证')
            ->setSmsType('normal')
            ->setExtend('reg');
        $request=$client->execute($req);
        $this->ajaxReturn($request);
    }

    public function pswcode($cell){
        $client  = new Client;
        $request = new SmsNumSend;
        $code=$this->randString();
        $this->cell_code($code);
        $smsParams = array(
            'code'    => $code,
            'product' => C("WEB_SITE_TITLE")
        );
        // 设置请求参数
        $req = $request->setSmsTemplateCode('SMS_5235591')
            ->setRecNum($cell)
            ->setSmsParam(json_encode($smsParams))
            ->setSmsFreeSignName('变更验证')
            ->setSmsType('normal')
            ->setExtend('getpasd');
        $request=$client->execute($req);
        $this->ajaxReturn($request["alibaba_aliqin_fc_sms_num_send_response"]["result"]);
    }

    public function bindcode($cell){
        $client  = new Client;
        $request = new SmsNumSend;
        $code=$this->randString();
        $this->cell_code($code);
        $smsParams = array(
            'code'    => $code,
            'product' => C("WEB_SITE_TITLE")
        );
        // 设置请求参数
        $req = $request->setSmsTemplateCode('SMS_5235596')
            ->setRecNum($cell)
            ->setSmsParam(json_encode($smsParams))
            ->setSmsFreeSignName('身份验证验证码')
            ->setSmsType('normal')
            ->setExtend('getpasd');
        $request=$client->execute($req);
        $this->ajaxReturn($request["alibaba_aliqin_fc_sms_num_send_response"]["result"]);
    }

    protected function cell_code_check($code) {
        $session = session('cell_code');
        if(empty($code) || empty($session)) {
            return false;
        }
        // session 过期
        if(NOW_TIME - $session['cell_time'] > 1800) {
            session('cell_code', null);
            return false;
        }
        if($code == $session['cell_code']) {
            return true;
        }
        return false;
    }

    protected function check_name($username){
		if(is_numeric($username)){
			return (ereg("^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$",$username));
		}else{
			return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$username));
		}
	}

}
