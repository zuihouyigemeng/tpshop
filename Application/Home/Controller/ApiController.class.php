<?php
namespace Home\Controller;
use Think\Controller;
use Com\WechatAuth;
use Com\Alipay\AlipayNotify;
use Com\Bankpay\Bankpay;
use Com\YunPay\YunPay;
use Com\Paypal\PaypalSubmit;

class ApiController extends Controller{

	protected function _initialize(){
		$config =   S('DB_CONFIG_DATA');
		if(!$config){
			$config =  config_lists();
			S('DB_CONFIG_DATA',$config);
		}
		C($config);
	}

    public function pay_weixin_notify(){
        $auth  = new WechatAuth(C('WX_APPID'), C('WX_APPSECRET'));
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data=$auth->FromXml($xml);
        if(M('shop_order')->where(array('prepay_id'=>$data['transaction_id']))->getField('id')){
            echo "fail";
            return false;
        }
        if($data['return_code']=='SUCCESS' && $auth->CheckSign($data,C('WX_PAY_KEY'))){
            $order_id=$data['out_trade_no'];
            $price=$data['total_fee']/100;
            $attach=explode('|',$data['attach']);
            $pid=$attach[0];
            $uid=$attach[1];
            if(!empty($pid)){
                D('Pay')->payadd($pid,$data['out_trade_no'],$price,$uid,2,$data['transaction_id']);
            }else{
                D('Pay')->recharge($uid,$data['out_trade_no'],$price,2,1,$data['transaction_id']);
            }
            echo "success";
        }else{
            echo "fail";
        }
    }

    public function pay_alipay_notify(){
        if(M('shop_order')->where(array('prepay_id'=>I('post.trade_no')))->getField('id')){
            echo "fail";
            return false;
        }
        $alipay_config['partner']   = C('ALI_PAY_PARTNER');
        $alipay_config['seller_email']  = C('ALI_PAY_SELLER_EMAIL');
        $alipay_config['key']     = C('ALI_PAY_KEY');
        $alipay_config['sign_type']    = strtoupper('MD5');
        $alipay_config['input_charset']= strtolower('utf-8');
        $alipay_config['cacert']    = getcwd().'\\cacert.pem';
        $alipay_config['transport']    = 'http';
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $transaction_id=I('post.trade_no');
                $order_id=I('post.out_trade_no');
                $price=I('post.total_fee');
                $attach=explode('|',I('post.extra_common_param'));
                $pid=$attach[0];
                $uid=$attach[1];
                if(!empty($pid)){
                    D('Pay')->payadd($pid,$order_id,$price,$uid,3,$transaction_id);
                }else{
                    D('Pay')->recharge($uid,$order_id,$price,3,1,$transaction_id);
                }
            }
            echo "success";
        }else{
            echo "fail";
        }
    }

    public function pay_bank_notify(){
        $data['v_oid']     =I('post.v_oid');
        $data['v_pmode']   =I('post.v_pmode');
        $data['v_pstatus'] =I('post.v_pstatus');
        $data['v_amount']  =I('post.v_amount');
        $data['v_moneytype']  =I('post.v_moneytype');
        $data['remark1']   =I('post.remark1');
        $data['v_md5str']  =I('post.v_md5str');
        if(M('shop_order')->where(array('prepay_id'=>$data['v_oid']))->getField('id')){
            echo "error";
            return false;
        }
        $bankpay = new Bankpay(C('BAND_PAY_MID'),C('BANK_PAY_KEY'),U('Pay/pay_result?sn='.I('post.v_oid')));
        $verify_result = $bankpay->CheckSign($data);
        if($verify_result) {
            if ($data['v_pstatus']=="20") {
                $attach=explode('|',$data['remark1']);
                $pid=$attach[0];
                $uid=$attach[1];
                if(!empty($pid)){
                    D('Pay')->payadd($pid,$data['v_oid'],$data['v_amount'],$uid,4,$data['v_oid']);
                }else{
                    D('Pay')->recharge($uid,$data['v_oid'],$data['v_amount'],4,1,$data['v_oid']);
                }
            }     
            echo "ok";
        }else{
            echo "error";
        }
    }

    public function pay_yunpay_notify(){
        $yunpay = new YunPay(C('YUN_PAY_ID'),C('YUN_PAY_KEY'),C('YUN_PAY_EMAIL'));
        $verify_result = $yunpay->verifyReturn(I('i1'),I('i2'),I('i3'));
        $order = M('shop_order')->field('uid,pid')->where(array('order_id'=>I('i2')))->find();
        if($verify_result) {
            if($order['pid']){
                D('Pay')->payadd($order['pid'],I('i2'),I('i1'),$order['uid'],5,I('i4'));
            }else{
                D('Pay')->recharge($order['uid'],I('i2'),I('i1'),5,1,I('i4'));
            }
        }else {
            echo "验证失败";
        }
    }

    public function pay_paypal_notify(){
        $paypal = new PaypalSubmit();
        $res = $paypalSubmit->validate_ipn($_POST);
        if(strcmp($res, 'VERIFIED') == 0){
            $attach=explode('|',$_POST['custom']);
            $pid=$attach[0];
            $uid=$attach[1];
            if(!empty($pid)){
                D('Pay')->payadd($pid,$_POST['invoice'],intval($_POST['mc_gross']),$uid,6,$_POST['txn_id']);
            }else{
                D('Pay')->recharge($uid,$_POST['invoice'],intval($_POST['mc_gross']),6,1,$_POST['txn_id']);
            }
        }elseif(strcmp($res,'INVALID') === 0){
            echo 'fail';
        }
    }
}