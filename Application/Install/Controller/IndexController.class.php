<?php
namespace Install\Controller;
use Think\Controller;
use Think\Storage;

class IndexController extends Controller{
    //安装首页
    public function index(){
		$msg = '已经成功安装了，请不要重复安装!';
        if(Storage::has('./Data/install.lock')){
            $this->error($msg);
        }
        $this->display();
    }

    //安装完成
    public function complete(){
        $step = session('step');

        if(!$step){
            $this->redirect('index');
        } elseif($step != 3) {
            $this->redirect("Install/step{$step}");
        }

        // 写入安装锁定文件
        Storage::put('./Data/install.lock', 'lock');
        if(!session('update')){
            //创建配置文件
            $this->assign('info',session('config_file'));
        }
        session('step', null);
        session('error', null);
        $this->display();
    }
}
