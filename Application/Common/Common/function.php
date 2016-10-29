<?php

function is_login(){
    $user = session('user_auth');
    if (empty($user)){
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

function data_auth_sign($data) {
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data);
    $code = http_build_query($data);
    $sign = sha1($code);
    return $sign;
}

function think_ucenter_md5($str, $key = 'HXyiyuanhuanlego'){
	return '' === $str ? '' : md5(sha1($str) . $key);
}

function check_verify($code, $id = 1){
    ob_clean();
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

function config_lists(){
	$data   = M('Config')->field('type,name,value')->select();
	$config = array();
	if($data && is_array($data)){
		foreach ($data as $value) {
			$config[$value['name']] = config_parse($value['type'], $value['value']);
		}
	}
	return $config;
}


function config_parse($type, $value){
	switch ($type) {
		case 3:
			$array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
			if(strpos($value,':')){
				$value  = array();
				foreach ($array as $val) {
					list($k, $v) = explode(':', $val);
					$value[$k]   = $v;
				}
			}else{
				$value =    $array;
			}
			break;
	}
	return $value;
}

function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    $tree = array();
    if(is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}

function url_change($model,$params,$createl=false){
	unset($params['name']);
	$reurl = U($model,$params);
	return $reurl;
}


function get_cover($cover_id, $field = null){
    if(empty($cover_id)){
        return false;
    }
    $picture = M('Picture')->where(array('status'=>1))->getById($cover_id);
    if($picture){
        $picture['path'] = __ROOT__.$picture['path'];
    }
    return empty($field) ? $picture : completion_pic($picture[$field]);
}

function completion_pic($url){
    if(strpos($url,"http://") === 0){
        return $url;
    }else{
        return C("WEB_URL").$url;
    }
}

function get_category_name($cid = 0){
    $info = M('Category')->field('title')->find($cid);
    if($info !== false && $info['title'] ){
        $name = $info['title'];
    } else {
        $name = '';
    }
    return $name;
}

function get_ten_unit($cid = 0){
    $info = M('Ten')->field('unit')->find($cid);
    return $info['unit'];
}

function get_ten_name($cid = 0){
    $info = M('Ten')->field('title')->find($cid);
    if($info !== false && $info['title'] ){
        $name = $info['title'];
    } else {
        $name = '';
    }
    return $name;
}

if(!function_exists('array_column')){
    function array_column(array $input, $columnKey, $indexKey = null) {
        $result = array();
        if (null === $indexKey) {
            if (null === $columnKey) {
                $result = array_values($input);
            } else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        return $result;
    }
}

function jiang_num($num){
    $numbers = range(10000001,$num+10000001);
    shuffle($numbers);
    return implode(',',$numbers);
}

function get_shop_name($id){
    return M('Shop')->where('id='.$id)->getField('name');
}

function get_user_name($id){
	return M('User')->where('id='.$id)->getField('nickname');
}

function get_user_pic($id){
    return completion_pic(M('User')->where('id='.$id)->getField('headimgurl'));
}

function sendMail($to, $title, $content){
    import('Com.PHPMailer.PHPMailerAutoload');
    $mail = new \PHPMailer();
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
    $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to,"尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return($mail->Send());
}

function activity($type,$record_id = null, $user_id = null){
    $activity=M('Activity')->field('name')->where('type='.$type)->select();
    foreach((array)$activity as $value){
        activity_log($value['name'],$record_id,$user_id);
    }
}

function activity_log($activity = null,$record_id = null, $user_id = null){
    if(empty($activity) || empty($record_id)){
        return '参数不能为空';
    }
    $activity_info = M('Activity')->getByName($activity);
    if($activity_info['status'] != 1){
        return '该活动被禁用或删除';
    }

    $data['type']      =   $activity_info['type'];
    $data['activity_id']      =   $activity_info['id'];
    $data['user_id']        =   $user_id;
    $data['activity_ip']      =   ip2long(get_client_ip());
    $data['record_id']      =   $record_id;
    $data['create_time']    =   NOW_TIME;

    if(!empty($activity_info['log'])){
        if(preg_match_all('/\[(\S+?)\]/', $activity_info['log'], $match)){
            $log['user']    =   $user_id;
            $log['record']  =   $record_id;
            $log['time']    =   NOW_TIME;
            $log['data']    =   array('user'=>$user_id,'record'=>$record_id,'time'=>NOW_TIME);
            foreach ($match[1] as $value){
                $price = explode('=', $value);
                if(isset($price[1])){
                    $prices = explode('|', $price[1]);
                    if(isset($prices[1])){
                        $data[$price[0]] = call_user_func($prices[1],$log[$prices[0]]);
                    }else{
                        $data[$price[0]] = is_numeric($price[1])?$price[1]:$log[$price[1]];
                    }
                }else{
                    $param = explode('|', $value);
                    if(isset($param[1])){
                        $replace[] = call_user_func($param[1],$log[$param[0]]);
                    }else{
                        $replace[] = $log[$param[0]];
                    }
                }
            }
            $data['remark'] =   str_replace($match[0], $replace, $activity_info['log']);
        }else{
            $data['remark'] =   $activity_info['log'];
        }
    }else{
        $data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'];
    }

    if(!empty($activity_info['rule']) && $activity_info['end_time']>=NOW_TIME){
        $rules = parse_activity($activity, $user_id,$record_id);
        $res = execute_activity($rules, $activity_info['id'], $user_id);
        if($res){
             M('ActivityLog')->add($data);
        }
    }
}


function parse_activity($activity = null,$self,$relf){
    if(empty($activity)){
        return false;
    }
    if(is_numeric($activity)){
        $map = array('id'=>$activity);
    }else{
        $map = array('name'=>$activity);
    }

    $info = M('Activity')->where($map)->find();
    if(!$info || $info['status'] != 1){
        return false;
    }

    $rules = $info['rule'];
    $rules = str_replace(array('{$self}','{$relf}'), array($self,$relf), $rules);
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key=>&$rule){
        $rule = explode('|', $rule);
        foreach ($rule as $k=>$fields){
            $field = empty($fields) ? array() : explode(':', $fields);
            if(!empty($field)){
                $return[$key][$field[0]] = $field[1];
            }
        }
        if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
            unset($return[$key]['cycle'],$return[$key]['max']);
        }
    }
    return $return;
}

function execute_activity($rules = false, $activity_id = null, $user_id = null){
    if(!$rules || empty($activity_id) || empty($user_id)){
        return false;
    }

    $return = true;
    foreach ($rules as $rule){
        $map = array('activity_id'=>$activity_id, 'user_id'=>$user_id);
        if($rule['ip']){
            $map['activity_ip'] = ip2long(get_client_ip());
        }
        $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
        $exec_count = M('ActivityLog')->where($map)->count();
        if($exec_count >= $rule['max']){
            $return = false;
            continue;
        }
        $Model = M(ucfirst($rule['table']));
        $field = $rule['field'];
        $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));
        if(!$res){
            $return = false;
        }
    }
    return $return;
}

function activity_mod($price){
    return floor($price/100)*5;
}

function union_price($price,$buy_price,$num){
    return (float)substr(sprintf("%.3f",($price-$buy_price)*($num/$price)/2),0,-1);
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 */
function check_document_position($pos = 0, $contain = 0){
    if(empty($pos) || empty($contain)){
        return false;
    }

    //将两个参数进行按位与运算，不为0则表示$contain属于$pos
    $res = $pos & $contain;
    if($res !== 0){
        return true;
    }else{
        return false;
    }
}