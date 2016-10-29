<?php
namespace Home\Model;
use Think\Model;

class PayModel extends Model{
	
	public function payadd($id,$sn,$price,$uid,$type,$prepay_id=''){
		$data['uid']=$uid;
		$data['pid']=$id;
		$data['create_time']=NOW_TIME;
		$data['number']=$price;
		$data['order_id']=$sn;
		$data['type']=$type;
		$data['prepay_id']=$prepay_id;
		$info=M('shop_period')->table('__SHOP__ shop,__SHOP_PERIOD__ period')->field('shop.id as sid,shop.price,shop.buy_price,shop.status,shop.edit_price,period.no,period.number,period.state,period.jiang_num')->where('shop.id=period.sid and period.id='.$id)->find();
		if($info['state']>0){
			if($type>1){
				M('User')->where('id='.$uid)->setInc('black',$price);
			}
			$data['code']='FAIL';
			$data['msg']='您手慢了,该期已经准备开奖停止购买了!<br>系统已将购买金额自动充入余额。';
			$data['recharge']=1;
			$this->shop_order($data);
			$this->error=$data['msg'];
			return $sn;
		}
		$surplus=($info['price']-$info['number']);
		if($surplus>=$price){
			if($this->checkPrice($price,$uid) || $type>1){
				if($type=='1'){
					M('User')->where('id='.$uid)->setDec('black',$price);
				}
				$jiang_num=explode(',',$info['jiang_num']);
				$dataList = array('uid'=>$uid,'pid'=>$id,'create_time'=>$this->getMillisecond(),'number'=>$price,'order_id'=>$sn,'num'=>implode(',',array_slice($jiang_num,0,$price)));
				M('shop_record')->add($dataList);
				array_splice($jiang_num,0,$price);
				if(intval($surplus-$price)==0){
					if($info['edit_price']!=$info['price']){
            			M('Shop')->where('id='.$info['sid'])->save(array('price'=>$info['edit_price'],'edit_price'=>$info['edit_price']));
            		}
					$kaijiang_count=$this->kaijiangtime($id);
					M('shop_period')->where('id='.$id)->save(array('state' =>'1','number'=>$info['number']+$price,'kaijang_time'=>$this->kjtime(),'kaijiang_count'=>$kaijiang_count,'jiang_num'=>implode(',',$jiang_num),'end_time'=>$this->getMillisecond()));
					$period['jiang_num']=jiang_num($info['price']-1);
            		$period['sid']=$info['sid'];
            		$period['create_time']=NOW_TIME;
            		$period['state']=0;
            		$period['no']=$info['no']+1;
            		if($info['status']>0){
            			M('shop_period')->data($period)->add();
            		}
				}else{
					M('shop_period')->where('id='.$id)->save(array('number'=>$info['number']+$price,'jiang_num'=>implode(',',$jiang_num)));
				}
				$wid=M('user')->where('id='.$uid)->getField('wid');
				$data['code']='OK';
				$data['msg']='购买成功';
				$data['wid']=$wid;
				$this->shop_order($data);
				activity(4,$price,$uid);
				return $sn;
			}else{
				$this->error='您的余额不足请充值';
				return $sn;
			}
		}else{
			if($type>1){
				M('User')->where('id='.$uid)->setInc('black',$price);
				$data['msg']='您购买的数量大于剩余数量了!<br>系统已将购买金额自动充入余额。';
				$data['recharge']=1;
			}else{
				$data['msg']='您购买的数量大于剩余数量了!';
			}
			$data['code']='FAIL';
			$this->shop_order($data);
			$this->error=$data['msg'];
			return $sn;
		}
	}

	public function checkPrice($price,$uid){
		$black=M('User')->where('id='.$uid)->getField('black');
		if($black>=$price){
			return true;
		}else{
			return false;
		}
	}

	public function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());     
		return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);  
	}

	public function kaijiangtime($id){
		$kjtime_sum=0;
		$subQuery = M('shop_record')->order('create_time desc')->select(false);
		$kaijiang_time=M('shop_record')->field('uid,create_time,pid as shopid')->table($subQuery.' a')->group('uid')->order('create_time desc')->limit(50)->select();
		foreach ($kaijiang_time as $key => $value){
			$kjtime[$key]=$value;
			$kjtime[$key]['pid']=$id;
			$kjtime_sum+=intval(time_format(substr($value["create_time"],0,-3),'His000'))+intval(substr($value["create_time"],-3));
		}
		M('shop_kaijiang')->addAll($kjtime);
		return $kjtime_sum;
	}

	public function pay_result($sn){
		$map['order_id']=$sn;
    	$return['order']=M('shop_order')->where($map)->field('pid,code,msg,number,create_time')->find();
    	$num=M('shop_record')->where($map)->getField('num');
    	$return['record']=explode(',',$num);
    	$return['shop']=D('Shop')->detail($return['order']['pid']);
    	return $return;
  	}

  	public function recharge($uid,$sn,$price,$type,$recharge,$prepay_id=''){
  		M('User')->where('id='.$uid)->setInc('black',$price);
  		$data['uid']=$uid;
		$data['create_time']=NOW_TIME;
		$data['number']=$price;
		$data['order_id']=$sn;
		$data['type']=$type;
		$data['recharge']=$recharge;
		$data['prepay_id']=$prepay_id;
		$data['code']='OK';
		$data['msg']='充值成功';
		$this->shop_order($data);
		activity(5,$price,$uid);
		return $data;
  	}

  	public function kjtime(){
  		if(C('KJ_THIRD_PARTY')!=1){
	  		$kj_time=intval(NOW_TIME/300+1)*300+50;
  		}else{
  			$dtime=date('H',NOW_TIME);
	  		if($dtime>=10 && $dtime<22){
				$kj_time=intval(NOW_TIME/600+1)*600+50;
	  		}elseif($dtime<2 || $dtime>=22){
	  			$kj_time=intval(NOW_TIME/300+1)*300+50;
	  		}else{
	  			$kj_time=strtotime(date('Y-m-d 10:00:50',NOW_TIME));
	  		}
  		}
  		return $kj_time;	
  	}

  	public function shop_order($data){
  		$data['status']=1;
  		if($id=M('shop_order')->where(array('order_id'=>$data['order_id']))->getField('id')){
  			$data['id']=$id;
  			M('shop_order')->save($data);
  		}else{
  			M('shop_order')->add($data);
  		}
  	}
}
