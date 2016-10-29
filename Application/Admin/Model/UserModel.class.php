<?php
namespace Admin\Model;
use Think\Model;

class UserModel extends Model {

	protected $_validate = array(
		array('username', '5,30', '用户名长度必须在5-30个字符之间！', self::EXISTS_VALIDATE, 'length',self::MODEL_INSERT), //用户名长度不合法
		array('password', '6,30', '密码长度必须在6-30个字符之间！', self::EXISTS_VALIDATE, 'length'),
	);

	protected $_auto = array(
		array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function'),
		array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
		array('status', 'getStatus', self::MODEL_INSERT, 'callback'),
	);


	public function info($uid){
		$map['id'] = $uid;
		$map['status']=1;
		$info=$this->where($map)->field('id,nickname,black,create_time')->find();
		return $info;
	}
	
	public function edit(){
		if($data=$this->create()){
			unset($data['password']);
			return $this->save($data);
		} else {
			return $this->getError();
		}
	}
	
	public function Password(){
		if(!$data = $this->create()){
            return false;
        }
		if(I('post.password') !== I('post.repassword')){
            $this->error = '您输入的新密码与确认密码不一致！';
			return false;
        }
        $res = $this->save();
        return $res;
    }
	
	protected function getStatus(){
		return true;
	}
}