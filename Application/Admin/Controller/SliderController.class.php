<?php
namespace Admin\Controller;

class SliderController extends WebController {

    public function index(){
        $list   =   $this->lists('Slider');
        $this->assign('list', $list);
        $this->meta_title = '幻灯管理';
        $this->display();
    }

    public function edit($id = null){
        $Slider = D('Slider');
        if(IS_POST){
            if(false !== $Slider->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $Slider->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			$info=$Slider->info($id);
            $this->assign('info',       $info);
            $this->meta_title = '编辑幻灯';
            $this->display();
        }
    }

    public function add(){
        $Slider = D('Slider');
        if(IS_POST){
            if(false !== $Slider->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $Slider->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增幻灯';
            $this->display('edit');
        }
    }

    public function del(){
		$id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $res = D('Slider')->remove($id);
        if($res !== false){
            $this->success('删除幻灯成功！');
        }else{
            $this->error('删除幻灯失败！');
        }
    }
	
	public function uploadPicture(){
        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
		$pic_upload = C('PICTURE_UPLOAD');
		$pic_upload['rootPath']="./Picture/Slider/";
		$pic_upload['autoSub']=false;
        $info = $Picture->upload(
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
