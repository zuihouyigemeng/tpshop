<?php
namespace Admin\Controller;

class ActivityController extends WebController {
	
    public function index(){
        //获取列表数据
        $Activity =   M('Activity')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Activity);
		Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('_list', $list);
        $this->meta_title = '用户活动';
        $this->display();
    }

    /**
     * 新增活动
     */
    public function add(){
        $this->meta_title = '新增活动';
        $this->assign('data',null);
        $this->display('edit');
    }

    /**
     * 编辑活动
     */
    public function edit(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Activity')->field(true)->find($id);
        $this->assign('data',$data);
        $this->meta_title = '编辑活动';
        $this->display('edit');
    }

    /**
     * 更新活动
     */
    public function save(){
        $res = D('Activity')->update();
        if(!$res){
            $this->error(D('Activity')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }
	
	public function del(){
		$id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $res = D('Activity')->remove($id);
        if($res !== false){
            $this->success('删除活动成功！');
        }else{
            $this->error('删除活动失败！');
        }
    }
	
	/**
     * 上传图片
     */
    public function uploadPicture(){
        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = 5*1024*1024;
		$pic_upload = C('PICTURE_UPLOAD');
        $pic_upload['maxSize'] = false;
        $pic_upload['autoSub'] = false;
        $pic_upload['rootPath'] = './Picture/Activity/';
        $pic_upload['saveName'] = '';
        $info = $Picture->wxupload(
            $_FILES,
            $pic_upload,
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );
        if($info){
            $return['status'] = 1;
            $return = array_merge($info['download'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }
        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }
}
