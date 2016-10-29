<?php
namespace Home\Model;
use Think\Model;

class ShopModel extends Model{

	public function detail($id){
		$info=M('shop_period')->table('__SHOP__ shop,__SHOP_PERIOD__ period')->field('shop.id as sid,shop.category,shop.name,shop.cover_id,shop.price,shop.status,shop.content,shop.display,shop.meta_title,shop.keywords,shop.description,shop.ten,period.number,period.no,period.id,period.state,period.kaijang_time')->where('shop.id=period.sid and period.id='.$id)->find();
		$info['count']=M('shop_period')->field('max(no) as maxno,min(no) as minno')->where('sid='.$info['sid'].' and state=2')->find();
		$info=$this->shopChange($info);
		if(!(is_array($info)) || 1 != $info['status'] || 1 != $info['display']){
			$this->error = '商品被下架或已删除！';
			return false;
		}
		return $info;
	}

	public function period($p,$cid=0,$num=20,$order='shop.hits|desc',$ten=0){
		if($cid){
			$category=' and shop.category='.$cid;
		}
		if($ten){
			$category.=' and shop.ten='.$ten;
		}
		$order=str_replace(array('|',':','%'),array(' ','.','/'),$order);
		$shop=M('shop_period')->table('__SHOP__ shop,__SHOP_PERIOD__ period')->field('shop.id as sid,shop.name,shop.cover_id,shop.price,shop.position,shop.ten,period.number,period.no,period.id')->where('shop.status=1 and shop.id=period.sid and shop.display=1 and period.state=0'.$category)->page($p,$num)->order($order)->select();
		if($shop){
			foreach ($shop as $k=>$v){
				$list[]=$this->shopChange($v);
			}
		}
		return $list;
	}
	
	public function over($id){
		$period = M('shop_period')->where("id=".$id)->field(true)->find();
		$info = $this->field(true)->find($period["sid"]);
		$info = D('Shop')->overChange($info,$period);
		$info['user']=D('User')->userChange($info);
		$info['user_no']=$this->user_num(UID,$info['pid']);
		$attend=$this->user_num($period["uid"],$period["id"]);
		$info['attend_no']=$attend;
		$info['count']=count($attend);
		return $info;
	}

	public function hits($id){
		$map['id']=$id;
		$this->where($map)->setInc('hits');
	}
	
	public function record($pid,$p){
		$map=array('pid'=>$pid);
		$record = M('shop_record')->where($map)->field('pid,uid,order_id,create_time')->group('uid')->order('id desc')->page($p.',20')->select();
		if($record){
			foreach ($record as $k=>$v){
				$userlist['list'][]=D('User')->userChange($v,'record');
			}
		}
		return $userlist;
	}

	public function history($sid,$p,$no=null){
		$map=array('sid'=>$sid,state=>2);
		if(!$no){
			$record = M('shop_period')->where($map)->field('id,uid,number,kaijang_time,state,no,kaijang_num')->order('id desc')->page($p.',20')->select();
		}else{
			$where='no'.$no;
			if(strstr($no,'>')){$order='id asc';}else{$order='id desc';}
			$record = M('shop_period')->where($map)->where($where)->field('id,uid,number,kaijang_time,state,no,kaijang_num')->order($order)->select();
		}
		if($record){
			foreach ($record as $k=>$v){
				$list[$k]=D('User')->userChange($v,'history');
				$list[$k]['count']=M('shop_record')->where("uid=".$v["uid"]." and pid=".$v["id"])->sum('number');
			}
		}
		return $list;
	}

	public function more($id){
		$more=$this->where('id='.$id)->field('content')->find();
		return stripslashes(htmlspecialchars_decode($more['content']));
	}

	public function user_num($uid,$pid){
		$variable=M('shop_record')->field('group_concat(num) as num')->where("uid=".$uid." and pid=".$pid)->group("uid,pid")->find();
		if($variable){
			return explode(',',$variable['num']);
		}
	}

	public function calculate($pid){
		$calculate['shop']=M('shop_period')->where('id='.$pid)->field('no,number,state,kaijang_num,kaijiang_count,kaijiang_ssc')->find();
		if(C('KJ_THIRD_PARTY')>0 or $calculate['shop']['state']==2){
			$kaijiang=M('shop_kaijiang')->table('__USER__ user,__SHOP_KAIJIANG__ kaijiang')->field('kaijiang.shopid,kaijiang.create_time,user.id,user.nickname')->where('kaijiang.pid='.$pid.' and user.id=kaijiang.uid')->order('kaijiang.create_time desc')->select();
			foreach ($kaijiang as $k=>$v){
				$shop=M('shop_period')->table('__SHOP__ shop,__SHOP_PERIOD__ period')->where('period.id='.$v['shopid'].' and shop.id=period.sid')->field('shop.name,period.no')->find();
				$list[$k]['nickname']=$v['nickname'];
				$list[$k]['user_id']=$v['id'];
				$list[$k]['create_time']=$v['create_time'];
				$list[$k]['create_date']=time_format(substr($v['create_time'],0,-3),'Y-m-d');
				$list[$k]['create_hour']=time_format(substr($v['create_time'],0,-3),'H:i:s').'.'.substr($v["create_time"],-3);
				$list[$k]['create_int']=time_format(substr($v['create_time'],0,-3),'His').substr($v["create_time"],-3);
				$list[$k]['name']=$shop['name'];
				$list[$k]['no']=$shop['no'];
				$list[$k]['pid']=$v['shopid'];
				$list[$k]['user_url']=url_change("user/user",array("id"=>$v['id'],"name"=>'user'));
				$list[$k]['shop_url']=url_change("shop/over",array("id"=>$v['shopid'],"name"=>'shop'));
			}
			$calculate['kjtime']=$list;
		}
		return $calculate;
	}

	public function shopChange($date,$type='shop'){
		$ten=M('ten')->where(array('id'=>$date["ten"],'status'=>1))->find();
		$date["number"]=$date["number"];
		$date["surplus"]=$date["price"]-$date["number"];
		$date["pid"]=$date["id"];
		$date["sid"]=$date["sid"];
		$date["ten_unit"]=$date["ten"]?$ten['unit']:1;
		$date["ten_name"]=$ten["title"];
		$date["ten_restrictions"]=$ten["restrictions"];
		$date["ten_restrictions_num"]=$ten["restrictions_num"];
		if($date["category"]){
			$date["cid"]=$date["category"];
			$date["ctitle"]=D('Category')->gettitle($date["category"]);
		}
		if($date["state"]==1){
			$date["kaijang_diffe"]=($date["kaijang_time"]-NOW_TIME)*1000;
		}
		$date["no"]=$date["no"];
		$date['jd']=floor($date["number"]/$date["price"]*100);
		$date["pic"]=get_cover($date["cover_id"],"path");
		$date["url"]=url_change($type."/index",array("id"=>$date["id"],"name"=>$type));
		$date["moreurl"]=url_change($type."/more",array("id"=>$date["sid"],"name"=>$type));
		if(UID){
			$date['user_no']=$this->user_num(UID,$date['id']);
			$date['no_count']=count($date['user_no']);
		}else{
			$date['no_count']=0;
		}
		unset($date["cover_id"],$date["kaijang_time"]);
		return $date;
	}

	public function overChange($date,$period){
		$date["number"]=$period["number"];
		$date["surplus"]=$date["price"]-$period["number"];
		$date["pid"]=$period["id"];
		$date["sid"]=$period["sid"];
		$date["cid"]=$date["category"];
		$date['ctitle']=D('Category')->gettitle($date["category"]);
		$date["uid"]=$period["uid"];
		$date["no"]=$period["no"];
		$date['jd']=floor($period["number"]/$date["price"]*100);
		$date["state"]=$period["state"];
		if(UID==$period["uid"] and $period["shared"]==1){
			$date["shared"]=$period["shared"];
		}
		$date["pic"]=get_cover($date["cover_id"],"path");
		if($period["state"]>0 && $period["state"]<3){
			$date["url"]=url_change("shop/over",array("id"=>$period["id"],"name"=>"shop"));
		}else{
			$date["url"]=url_change("shop/index",array("id"=>$period["id"],"name"=>"shop"));
		}
		$date["moreurl"]=url_change("shop/more",array("id"=>$period["sid"],"name"=>"shop"));
		if($period["state"]==2){
			$date["express_name"]=$period["express_name"];
			$date["express_no"]=$period["express_no"];
		}
		$date['price']=$date["price"];
		$date["kaijang_diffe"]=($period["kaijang_time"]-NOW_TIME)*1000;
		$date["kaijang_timing"]=$period["kaijang_time"];
		$date["kaijang_time"]=time_format($period["kaijang_time"],"m-d H:i");
		$date["kaijang_num"]=$period["kaijang_num"];
		$date["kaijiang_count"]=$period["kaijiang_count"];
		$date["kaijiang_ssc"]=$period["kaijiang_ssc"];
		$date["end_time"]=$period["end_time"];
		unset($date["status"],$date["display"],$date["cover_id"],$date["update_time"]);
		return $date;
	}
}
