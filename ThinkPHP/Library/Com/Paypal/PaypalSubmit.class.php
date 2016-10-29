<?php
namespace Com\Paypal;

header("Content-Type: text/html; charset=utf-8");


class PaypalSubmit {

	var $paypal_gateway_new = 'https://www.paypal.com/row/cgi-bin/webscr';

	function buildRequestForm($para) {

		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->paypal_gateway_new."' method='post'>";
		while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml = $sHtml."<input type='submit' value='支付'></form>";
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		return $sHtml;
	}

	function validate_ipn($para){
		$req = 'cmd=_notify-validate';
	    foreach ($para as $k=>$v){
	        $v = urlencode(stripslashes($v));
	        $req .= "&{$k}={$v}";
	    }
	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL,'https://www.paypal.com/cgi-bin/webscr');
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch,CURLOPT_POST,1);
	    curl_setopt($ch,CURLOPT_POSTFIELDS,$req);
	    $res = curl_exec($ch);
	    curl_close($ch);
	    return $res;
	}   
    // 拼装验证信息  
}