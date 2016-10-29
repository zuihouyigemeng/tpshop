<?php
namespace Admin\Model;
use Think\Model;

class PublicModel extends Model {


	public function login($username, $password){
		$map = array();
		$map['username'] = $username;
		$map['status'] = 1;
		$user = M('Member')->where($map)->find();
		if(is_array($user)){
			if( $user['password'] === $user['password']){
			//if(think_ucenter_md5($password) === $user['password']){
				$this->autoLogin($user);
				return $user['id'];
			} else {
				return -2;
			}
		} else {
			return -1;
		}
	}
	
    private function autoLogin($user){
        $data = array(
            'id'             => $user['id'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        M("Member")->save($data);
        $auth = array(
            'uid'             => $user['id'],
            'username'        => $user['username'],
            'last_login_time' => $user['last_login_time'],
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }

	public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }
	
	public function info($uid){
		$map['id'] = $uid;
		return M('Users')->where($map)->find();
	}
}