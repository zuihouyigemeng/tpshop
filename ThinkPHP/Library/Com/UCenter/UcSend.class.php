<?php
namespace Com\UCenter;

class UcSend{

	private $UC_API;
	private $UC_APPID;
	private $UC_KEY;

	public function __construct($appapi, $appkey, $appid){
        if($appapi && $appkey && $appid){
            $this->UC_API   = $appapi.'/uc_server';
            $this->UC_KEY = $appkey;
            $this->UC_APPID = $appid;
        } else {
            throw new \Exception('缺少参数 UC_APPID 和 UC_API!');
        }
    }

	protected function uc_api_post($module, $action, $arg = array()) {
		$s = $sep = '';
		foreach($arg as $k => $v) {
			$k = urlencode($k);
			if(is_array($v)) {
				$s2 = $sep2 = '';
				foreach($v as $k2 => $v2) {
					$k2 = urlencode($k2);
					$s2 .= "$sep2{$k}[$k2]=".urlencode($v2);
					$sep2 = '&';
				}
				$s .= $sep.$s2;
			} else {
				$s .= "$sep$k=".urlencode($v);
			}
			$sep = '&';
		}
		$postdata = $this->uc_api_requestdata($module, $action, $s);
		return $this->uc_fopen2($this->UC_API.'/index.php', 500000, $postdata, '', TRUE, '', 20);
	}

	protected function uc_api_requestdata($module, $action, $arg='', $extra=''){
		$input = $this->uc_api_input($arg);
		$post = "m=$module&a=$action&inajax=2&release=20110501&input=$input&appid=".$this->UC_APPID.$extra;
		return $post;
	}

	protected function uc_api_input($data) {
		$s = urlencode(uc_authcode($data.'&agent='.md5($_SERVER['HTTP_USER_AGENT'])."&time=".time(), 'ENCODE', $this->UC_KEY));
		return $s;
	}

	protected function uc_fopen2($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
		$__times__ = isset($_GET['__times__']) ? intval($_GET['__times__']) + 1 : 1;
		if($__times__ > 2) {
			return '';
		}
		$url .= (strpos($url, '?') === FALSE ? '?' : '&')."__times__=$__times__";
		return $this->uc_fopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block);
	}

	protected function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
		$return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}

		if(function_exists('fsockopen')) {
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} elseif (function_exists('pfsockopen')) {
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} else {
			$fp = false;
		}

		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}

				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
	}

	public function uc_user_register($username, $password, $email, $questionid = '', $answer = '', $regip = '') {
		return call_user_func(array($this,'uc_api_post'), 'user', 'register', array('username'=>$username, 'password'=>$password, 'email'=>$email, 'questionid'=>$questionid, 'answer'=>$answer, 'regip' => $regip));
	}

	public function uc_user_login($username, $password, $isuid = 2, $checkques = 0, $questionid = '', $answer = '') {
		$isuid = intval($isuid);
		$return = call_user_func(array($this,'uc_api_post'), 'user', 'login', array('username'=>$username, 'password'=>$password, 'isuid'=>$isuid, 'checkques'=>$checkques, 'questionid'=>$questionid, 'answer'=>$answer));
		return xml_unserialize($return);
	}

	public function uc_user_synlogin($uid) {
		$uid = intval($uid);
		$return = $this->uc_api_post('user', 'synlogin', array('uid'=>$uid));
		return $return;
	}

	public function uc_user_synlogout() {
		$return = $this->uc_api_post('user', 'synlogout', array());
		return $return;
	}

	public function uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw = 1, $questionid = '', $answer = '') {
		return call_user_func(array($this,'uc_api_post'), 'user', 'edit', array('username'=>$username, 'oldpw'=>$oldpw, 'newpw'=>$newpw, 'email'=>$email, 'ignoreoldpw'=>$ignoreoldpw, 'questionid'=>$questionid, 'answer'=>$answer));
	}

	public function uc_user_delete($uid) {
		return call_user_func(array($this,'uc_api_post'), 'user', 'delete', array('uid'=>$uid));
	}

	public function uc_get_user($username, $isuid=0) {
		$return = call_user_func(array($this,'uc_api_post'), 'user', 'get_user', array('username'=>$username, 'isuid'=>$isuid));
		return xml_unserialize($return);
	}

	public function uc_user_getcredit($appid, $uid, $credit) {
		$return = call_user_func(array($this,'uc_api_post'), 'user', 'getcredit', array('appid'=>$appid, 'uid'=>$uid, 'credit'=>$credit));
		return $return;
	}

	public function uc_credit_exchange_request($uid, $from, $to, $toappid, $amount) {
		$uid = intval($uid);
		$from = intval($from);
		$toappid = intval($toappid);
		$to = intval($to);
		$amount = intval($amount);
		$return = call_user_func(array($this,'uc_api_post'),'credit', 'request', array('uid'=>$uid, 'from'=>$from, 'to'=>$to, 'toappid'=>$toappid, 'amount'=>$amount));
		return $return;
	}
}