<?php
namespace Home\Model;
use Think\Model;

class PublicModel extends Model {

	public function login($username, $password){
		$map = array();
		if(is_numeric($username)){
			$map['phone'] = $username;
		}else{
			$map['username'] = $username;
		}
		$map['status'] = 1;
		$user = M('User')->where($map)->find();
		if(is_array($user)){
			if(think_ucenter_md5($password) === $user['password']){
				if($user['activation']>0){
					$this->autoLogin($user);
					return array($user['id'],$user['ucid']);
				}else{
					return array(-3,$user['ucid']);
				}
			} else {
				return array(-2,$user['ucid']);
			}
		} else {
			return array(-1,$user['ucid']);
		}
	}

    public function autoLogin($user){
        $data = array(
            'id'             => $user['id'],
            'login_time' => NOW_TIME,
            'login_ip'   => get_client_ip(1)
        );
        M("User")->save($data);
        $auth = array(
            'uid'             => $user['id'],
            'username'        => $user['nickname']
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }

	public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }
	
	public function reg(){
		if($data=D('User')->create()){
			$data['nickname']=$data['username'];
			if(is_numeric($data['username'])){
				$data['phone']=$data['username'];
				unset($data['username']);
			}
			$city=$this->getCity(get_client_ip());
			$data['country']=$city['country'];
			$data['province']=$city['region'];
			$data['city']=$city['city'];
			$data['headimgurl']='/Picture/Head/user-icon.png';
			return M('User')->add($data);
		} else {
			$this->error=D('User')->getError();
			return false;
		}
	}

	public function getCity($ip){
		$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
		$ipinfo=json_decode(file_get_contents($url),true);
		if($ipinfo->code=='1'){
			return false;
		}
		return $ipinfo['data'];
	}
	
	public function info($uid){
		$map['id'] = $uid;
		return M('User')->where($map)->find();
	}
}