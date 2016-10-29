<?php
namespace Com\Bankpay;

class Bankpay{
    private $payURL = 'https://tmapi.jdpay.com/PayGate?encoding=UTF-8';

    public function __construct($v_mid,$key,$v_url){
        if($v_mid && $key && $v_url){
            $this->v_mid     = $v_mid;
            $this->v_url = $v_url;
            $this->key = $key;
        } else {
            throw new \Exception('缺少参数 v_mid 和 key 和 url!');
        }
    }

    public function postForm($para){
        $para['v_mid']=$this->v_mid;
        $para['v_url']=$this->v_url;
        $sHtml = "<form id='bankpaysubmit' name='bankpaysubmit' action='".$this->payURL."' method='post'>";
        while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        } 
        $sHtml = $sHtml."</form>";
        $sHtml = $sHtml."<script>document.forms['bankpaysubmit'].submit();</script>";  
        return $sHtml;
    }
     /*
     * 随机字符串
     */
    public function getNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign($values,$return=null){
        if($return){
            $string = $values['v_oid'].$values['v_pstatus'].$values['v_amount'].$values['v_moneytype'].$this->key;
        }else{
            $string = $values['v_amount'].$values['v_moneytype'].$values['v_oid'].$this->v_mid.$this->v_url.$this->key;
        }
        
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

    public function CheckSign($data){
        if(!array_key_exists('v_md5str',$data)){
            return false;
        }
        if($this->MakeSign($data,true)!=$data['v_md5str']){
            return false;
        }
        return true;
    }
}
