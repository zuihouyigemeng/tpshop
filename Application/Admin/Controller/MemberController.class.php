<?php
namespace Admin\Controller;
use Think\Storage;

class MemberController extends WebController {

	 /**
     * 管理员管理首页
     */
    public function index(){
        $list   = $this->lists('Member');
        $this->assign('_list', $list);
        $this->meta_title = '管理员列表';
        $this->display();
    }

    /**
     * 修改密码
     */
    public function password(){
		 if(IS_POST){ //提交表单
		 	//获取参数
			$res   =  D('Member')->password();
			if($res  !== false){
				$this->success('修改密码成功！',U('index'));
			}else{
				$this->error(D('Member')->getError());
			}
		 }else{
		 	$username = M('Member')->getFieldById(UID, 'username');
			$this->assign('username', $username);
			$this->meta_title = '修改密码';
			$this->display();
		 }
    }
	
	public function add(){
		if(IS_POST){
			if(I("password") != I("repassword")){
                $this->error('密码和重复密码不一致！');
            }
			if(M('Member')->where(array('username'=>I("username")))->getField('id')){
				$this->error('用户名已被占用！');
			}
			$uid = D('Member')->reg();
			if(is_numeric($uid)){
                $this->success('用户添加成功！',U('Index/index'));
            } else {
                $this->error(D('Member')->getError());
            }
		}else{
			$this->display();
		}
	}
	
	
	/**
     * 删除管理员
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $id) );
        if(M('Member')->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}