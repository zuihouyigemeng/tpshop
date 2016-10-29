<?php
namespace Admin\Controller;
use Think\Controller;

class StatisticsController extends WebController {
    public function index($starttime='',$endtime=''){
		$income=D('Statistics')->income($starttime,$endtime);
		$this->assign('income',$income);
    	$this->meta_title="盈利统计";
		$this->display();
    }

    public function order($starttime='',$endtime='',$recharge=''){
    	$order=D('Statistics')->order($starttime,$endtime,$recharge);
    	$this->assign('order',$order);
    	$this->meta_title="订单统计";
		$this->display();
    }
}