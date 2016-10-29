<?php
/**
 * 阿里大鱼主调用类
 */
namespace Com\YunPay;

class YunPay
{
    /**
     * API请求地址
     */
    const API_URI = 'http://pay.yunpay.net.cn/i2eorder/yunpay/';

    protected $appId;
    protected $appKey;
    protected $appEmail;

    /**
     * 构造方法
     */
    public function __construct($appId = '', $appKey = '', $appEmail = '')
    {
        $this->appId    = $appId ?: C('YUN_PAY_ID');
        $this->appKey = $appKey ?: C('YUN_PAY_KEY');
        $this->appEmail    = $appEmail ?: C('YUN_PAY_EMAIL');
        $this->apiURI    = self::API_URI;
    }

    /**
     * 设置appId
     * @param string $value AppKey
     */
    public function setappId($value)
    {
        $this->appId = $value;
    }

    /**
     * 设置AppappKey
     * @param string $value appKey
     */
    public function setappKey($value)
    {
        $this->appKey = $value;
    }

    /**
     * 设置AppappKey
     * @param string $value appKey
     */
    public function setappEmail($value)
    {
        $this->appEmail = $value;
    }

    /**
     * 签名
     * @param  array $params 参数
     * @return string         
     */
    protected function sign($params)
    {
        foreach ($params as $pars) {
            $myparameter.=$pars;
        }
        $sign=md5($myparameter.'i2eapi'.$this->appKey);
        return $sign;
    }

    /**
     * 生成要请求的参数数组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    protected function RequestPara($para_temp) {
        $para_temp['sign'] = $this->sign($para_temp);
        return $para_temp;
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    public function RequestForm($para_temp, $method, $button_name) {
        //待请求参数数组
        $para = $this->RequestPara($para_temp);
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->apiURI."' accept-charset='utf-8' method='".$method."'>";
        while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        return $sHtml;
    }

    public function verifyReturn($i1, $i2,$i3) {
        $prestr = $i1.$i2.$this->appId.$this->appKey;
        $mysgin = md5($prestr);
        if($mysgin == $i3) {
            return true;
        }
        else {
            return false;
        }
    }
}