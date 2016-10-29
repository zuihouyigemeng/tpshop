<?php
namespace Home\Controller;
use Com\WechatAuth;
use Com\Bankpay\Bankpay;
use Com\Alipay\AlipaySubmit;
use Com\Alipay\AlipayNotify;
use Com\YunPay\YunPay;
use Com\Paypal\PaypalSubmit;

class PayController extends WapController {

  public function _initialize(){
		parent::_initialize();
		if(!defined('UID') || UID==0){// 还没登录 跳转到登录页面
			$this->error('请先登录！',U('public/login'));
		}
	}
	
  public function index(){
		$this->assign('shop',D("Shop")->detail(I('pid',0,'intval')));
		$this->assign('black',D("User")->getBlack());
		$this->assign('price',abs(I('number',0,'intval')));
    $this->display($this->tplpath."pay.html");
  }
	
	public function create_sn(){
   		mt_srand((double)microtime() * 1000000 );
    	return date("YmdHis" ).str_pad( mt_rand( 1, 99999 ), 5, "0", STR_PAD_LEFT );
 	}

  public function yuepay($pid,$price){
    $price=$this->pay_check($pid,$price);
    if(!is_numeric($price)){
      $this->error($price);
    }
    $res = D('Pay')->payadd(intval($pid),$this->create_sn(),$price,UID,1);
      if($res !== false){
        $this->success('支付完成！',U('Pay/pay_result?sn='.$res));
      }else{
        $this->error(D('Pay')->getError());
      }
  }

  public function pay_wx($code,$price,$no){
    $this->assign('code_url',urlencode($code));
    $this->assign('price',$price/100);
    $this->assign('no',$no);
    $this->display($this->tplpath."pay_wx.html");
  }
  
  public function pay_result($sn){
    $payResult=D('Pay')->pay_result($sn);
    if(IS_AJAX){
      if($payResult['order']['code']){
        $this->success($payResult,U('Pay/pay_result?sn='.$sn));
      }
    }else{
      $this->assign('pay',$payResult);
      if($payResult['order']['pid']){
        $this->title="支付结果";
      }else{
        $this->title="充值结果";
      }
      $this->display($this->tplpath."pay_result.html");
    }
  }

  public function recharge(){
    $this->display($this->tplpath."recharge.html");
  }

  public function check_pay($price){
    $pid=I('pid',0,'intval');
    $price=$this->pay_check($pid,$price);
    if(!is_numeric($price)){
      $this->error($price);
    }else{
      $this->success('等待支付');
    }
  }

  protected function pay_check($pid,$price){
    $price=abs(intval($price));
    if(!is_numeric($price)){
      return '请输入数字';
    }
    if($price<=0){
      return '购买数量必须大于0';
    }
    if($pid>0){
      $info=M('shop_period')->table('__SHOP__ shop,__SHOP_PERIOD__ period')->field('shop.price,shop.ten,period.number')->where('shop.id=period.sid and period.id='.intval($pid))->find();
      $ten=M('ten')->where(array('id'=>$info["ten"],'status'=>1))->find();
      $unit=$info["ten"]?$ten['unit']:1;
      if($ten["restrictions"]){
        $user_num=M('shop_record')->where(array('uid'=>UID,'pid'=>intval($pid)))->sum('number');
        if(($price+$user_num)>($ten["restrictions_num"])){
          return '对不购买数量超过限购数量';
        }
      }
      if(($info['price']-$info['number'])==$price){
        return $price;
      }else{
        if($price%$unit==0){
          return $price;
        }else{
          return '对不购买数量错误';
        }
      }
    }else{
      return $price; 
    }
  }

  public function pay_weixin(){
    $price=$this->pay_check(I('pid'),I('price'));
    if(!is_numeric($price)){
      $this->error($price);
    }
    $price=$price*100;
    $sn=$this->create_sn();
    $options['mch_id'] = C('WX_PAY_MCHID');
    $options['key'] = C('WX_PAY_KEY');
    $options['body'] = C("WEB_SITE_TITLE");
    $options['attach'] = intval(I('pid',0,'intval')).'|'.UID;
    $options['out_trade_no'] = $sn;
    $options['total_fee'] = $price;
    $options['notify_url'] = C('WEB_URL').U('Home/Api/pay_weixin_notify');
    $options['trade_type'] = 'NATIVE';
    $auth = new WechatAuth(C('WX_APPID'), C('WX_APPSECRET'));
    $rmsg=$auth->unifiedOrder($options);
    $this->success(array(
      'return_msg'=>$rmsg['return_msg'],
      'trade_type'=>$rmsg['trade_type'],
      'code_url'=>$rmsg['code_url'],
      'parameters'=>$jsApiObj,
      'pid'=>I('pid'),
      'no'=>$sn,
      'price'=>$price
      ),U('Pay/pay_result?sn='.$sn));
  }

  public function pay_bank(){
    $price=$this->pay_check(I('pid'),I('price'));
    if(!is_numeric($price)){
      $this->error($price);
    }
    $sn=$this->create_sn();
    $bankpay = new Bankpay(C('BAND_PAY_MID'),C('BANK_PAY_KEY'),C('WEB_URL').U('Pay/pay_result?sn='.$sn));
    $options['v_oid'] = $sn;
    $options['v_amount'] = $price;
    $options['v_moneytype'] = 'CNY';
    $options['v_md5info'] = $bankpay->MakeSign($options);
    $options['pmode_id'] = I('pay_type');
    $options['remark1'] = intval(I('pid',0,'intval')).'|'.UID;
    $options['remark2'] = '[url:='.C('WEB_URL').U('Home/Api/pay_bank_notify').']';
    $html_text = $bankpay->postForm($options);
    echo $html_text;
  }

  public function pay_alipay($price){
    $price=$this->pay_check(I('pid'),I('price'));
    if(!is_numeric($price)){
      $this->error($price);
    }
    $sn=$this->create_sn();
    //建立请求
    $alipay_config['partner']   = C('ALI_PAY_PARTNER');
    $alipay_config['seller_email']  = C('ALI_PAY_SELLER_EMAIL');
    $alipay_config['key']     = C('ALI_PAY_KEY');
    $alipay_config['sign_type']    = strtoupper('MD5');
    $alipay_config['input_charset']= strtolower('utf-8');
    $alipay_config['cacert']    = getcwd().'\\cacert.pem';
    $alipay_config['transport']    = 'http';
    //构造要请求的参数数组，无需改动
    $parameter = array(
        "partner" => trim($alipay_config['partner']),
        "payment_type"  => '1',
        "notify_url"  => C('WEB_URL').U('Home/Api/pay_alipay_notify'), //返回地址
        "return_url"  => C('WEB_URL').U('Pay/pay_alipay_notify'), //异步返回地址
        "out_trade_no"  => $sn,
        "subject" => I('name'),
        "total_fee" => $price,
        "body"  => C("WEB_SITE_TITLE"),
        "show_url"  => C('WEB_URL').U('shop/index',array('id'=>I('pid'))), //商品地址
        "extra_common_param" => intval(I('pid')).'|'.UID,
        "anti_phishing_key" => '',
        "exter_invoke_ip" => get_client_ip(),
        "_input_charset"  => trim(strtolower($alipay_config['input_charset']))
    );
    $parameter["service"] = "create_direct_pay_by_user";
    $parameter["seller_email"] = trim($alipay_config['seller_email']);
    $alipaySubmit = new AlipaySubmit($alipay_config);
    $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
    echo $html_text;
  }

  public function pay_alipay_notify(){
    $alipay_config['partner']   = C('ALI_PAY_PARTNER');
    $alipay_config['seller_email']  = C('ALI_PAY_SELLER_EMAIL');
    $alipay_config['key']     = C('ALI_PAY_KEY');
    $alipay_config['sign_type']    = strtoupper('MD5');
    $alipay_config['input_charset']= strtolower('utf-8');
    $alipay_config['cacert']    = getcwd().'\\cacert.pem';
    $alipay_config['transport']    = 'http';
    $alipayNotify = new AlipayNotify($alipay_config);
    $verify_result = $alipayNotify->verifyReturn();
    if($verify_result) {
        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
          $this->redirect(U('Pay/pay_result?sn='.I('get.out_trade_no')));
        }
    }else {
      $this->error('校验失败',U('Pay/index'));
    }
  }

  public function pay_yun(){
    $price=$this->pay_check(I('pid'),I('price'));
    if(!is_numeric($price)){
      $this->error($price);
    }
    $sn=$this->create_sn();
    $parameter = array(
      "partner" => C('YUN_PAY_ID'),
      "seller_email"  => C('YUN_PAY_EMAIL'),
      "out_trade_no"  => $sn,
      "subject" => I('name'),
      "total_fee" => $price,
      "body"  => C("WEB_SITE_TITLE"),
      "nourl" => C('WEB_URL').U('Home/Api/pay_yunpay_notify'),
      "reurl" => C('WEB_URL').U('Pay/pay_yunpay_notify'),
      "orurl" => '',
      "orimg" => ''
    );
    $data= array(
      "uid"=>UID,
      "pid"=>intval(I('pid')),
      "create_time"=>NOW_TIME,
      "number"=>$price,
      "order_id"=>$sn,
      "type"=>5,
      "msg"=>'等待支付',
      "code"=>'FAIL',

    );
    M('shop_order')->add($data);
    $yunpay = new YunPay(C('YUN_PAY_ID'),C('YUN_PAY_KEY'),C('YUN_PAY_EMAIL'));
    $html_text = $yunpay->RequestForm($parameter,'get','确认');
    echo $html_text;
  }

  public function pay_pal(){
    $price=$this->pay_check(I('pid'),I('price'));
    if(!is_numeric($price)){
      $this->error($price);
    }
    $sn=$this->create_sn();
    $parameter = array(
        "cmd" => '_xclick',
        "business"  => C('PAY_PAL'),
        "item_name" => I('name'),
        "currency_code" => 'USD',
        "amount" => sprintf("%.2f", $price),
        "notify_url"  => C('WEB_URL').U('Home/Api/pay_paypal_notify'), 
        "return"  => C('WEB_URL').U('Pay/pay_result?sn='.$sn), //返回地址
        "invoice"  => $sn,
        "custom" => intval(I('pid')).'|'.UID,
        "lc" => 'CN'
    );
    $paypalSubmit = new PaypalSubmit();
    $html_text = $paypalSubmit->buildRequestForm($parameter);
    echo $html_text;
  }

  public function pay_yunpay_notify(){
    $yunpay = new YunPay(C('YUN_PAY_ID'),C('YUN_PAY_KEY'),C('YUN_PAY_EMAIL'));
    $verify_result = $yunpay->verifyReturn(I('i1'),I('i2'),I('i3'));
    if($verify_result) {
      $this->redirect(U('Pay/pay_result?sn='.I('i2')));
    }else {
      $this->error('校验失败',U('Pay/index'));
    }
  }
}