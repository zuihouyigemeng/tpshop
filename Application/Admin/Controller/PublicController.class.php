<?php
namespace Admin\Controller;
use Think\Controller;
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
		$this->webpath=__ROOT__."/";
		$this->webtitle=C("WEB_SITE_TITLE");
		$this->weblogo=C("WEB_LOGO");
		$this->keywords=C("WEB_SITE_KEYWORD");
		$this->description=C("WEB_SITE_DESCRIPTION");
		$this->icp=C("WEB_SITE_ICP");
		$this->weburl=C("WEB_URL");
		$this->webname=C("WEB_NAME");
	}
	
	public function login($username = null, $password = null){
        if(IS_POST){
            $uid = D('Public')->login($username, $password);
            if(0 < $uid){
                $this->success('登录成功！', U('Index/index'));
            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break;
                }
                $this->error($error);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                $this->display();
            }
        }
    }
	
	/* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Public')->logout();
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        } else {
            $this->redirect('login');
        }
    }
	
    public function getpass(){
        if(IS_POST){
            D('Public')->logout();
            $this->success('退出成功！', U('login'));
        } else {
            $this->display();
        }
    }
}
