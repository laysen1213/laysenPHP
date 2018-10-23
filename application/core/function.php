<?php
function json_return($arr = array()){
	echo json_encode($arr);exit;
}

function success_return($data = array()){
	json_return(array('ret' => 1 , 'msg' => 'success' , 'data' => $data));
}

function error_return($msg = 'error' , $ret = -1 , $data = array()){
	json_return(array('ret' => $ret , 'msg' => $msg , 'data' => $data));
}

/*
	递归 无限分类
*/
function recursive($list,$lv = 0 , $parents = 'pid',$cid = 0,$level = 0){
	$arr = array();
	if(!empty($list)){
		foreach($list as $v){
			if($v[$parents] == $cid){
				if($lv == 0 || $lv > 0 && $lv > $level){
					$v['lv'] = $level;
					$v['tx'] = str_repeat("---", $level);
					$v['cx'] = str_repeat("　", $level);
					$arr[] = $v;
					$arr = array_merge($arr,recursive($list,$lv,$parents,$v['id'],$level+1));
				}
			}	
		}
	}
	return $arr;
}

//获取在线IP
function getonlineip($format = 0) {
	$onlineip = '';
	if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	if ($format) {
		$ips = explode('.', $onlineip);
		for ($i = 0; $i < 3; $i++) {
			$ips[$i] = intval($ips[$i]);
		}
		return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
	} else {
		return $onlineip;
	}
}

function p($arr = array()){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

function C($key = ''){
	// 配置类
	$res = include PATH . 'system/config.php';
	if($key){
		return $res[$key];
	}
	return $res;
}

function Ipost($val = '' , $msg = ''){
	if(empty($val)){
		return $_POST;
	}
	$post = isset($_POST[$val])?htmlspecialchars($_POST[$val]):'';
	if(!empty($msg) && empty($post)){
		error_return($msg);
	}
	return $post;
}

function Iget($val = ''){
	return isset($_GET[$val])?$_GET[$val]:'';
}

function U($url = ''){
	return '/' . $url;
}

/**
 * PHP连接API的方法
 * $params 关联数组，key为接口获取参数的名字，val为传送给接口的值
 * $query 接口地址的后半部分，比如调用文档中用户注册的接口query为api/user/register
 */
function api_connect($url = '',$params = array() , $type = 'json'){
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
	curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	$result = curl_exec($ch); //运行curl
	if($type == 'json'){
		$result = json_decode($result , true);
	}
	curl_close($ch);
	return $result;
}

function page($file = ''){
	if($file){
		include PATH . VIEW . '/'.$file.'.html';
	}
}

/**
 * PHP连接API的方法
 * $params 关联数组，key为接口获取参数的名字，val为传送给接口的值
 * $query 接口地址的后半部分，比如调用文档中用户注册的接口query为api/user/register
 */
function weixin_api($url = ''){
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
	$result = json_decode(curl_exec($ch), true); //运行curl
	curl_close($ch);
	return $result;
}

/*
 * +------------------------------
 * 输出分页字符串
 * @param nowpage    	当前页码
 * @param nums    	        总条数
 * @param perpage    		每页多少条
 * @param $url
 * +------------------------------
 */
function setpage($page = 1, $perpage = 10, $nums = 0, $url = '', $param = '') {
	if($nums < 1){
		// 当总条数小于1时，将不显示分页
		return '';
	}

	$allpages = ceil($nums / $perpage);
	$allpages = $allpages <= 0 ? 1 : $allpages;
	if ($page > $allpages) {
		$page = $allpages;
	}
    $url = U($url);
    
    $arr_param = array();
	if (sizeof($_GET)) {
		foreach ($_GET as $k => $v) {
			if ($k != "page") {
				$arr_param[$k] = $v;
			}
		}
	}
	$arr_ret = array();
	if (!empty($page)) {
		$arr_param['page'] = $page;
	}
	foreach ($arr_param as $k => $v) {
	    if($k != 'page') {
	        $arr_ret[] = $k . "=" . $v;
	    }
	}
    $param = implode("&", $arr_ret);
    
	$strpage = '<div class="ui-pagination">';
	$strpage .= '<div class="">';
	$strpage .= '<ul>';
	if ($param != '') {
		$strpage .= "<li class='ui-page-index'> <a href='" . ($url) . "?" . $param . "&page=1' title='首页'> 首页 </a></li>";	
	}else{
		$strpage .= "<li class='ui-page-index'> <a href='" . ($url) . "?page=1' title='首页'> 首页 </a></li>";	
	}
	if ($param != '') {
		$strpage .= "<li class='ui-page-prev'> <a href='" . ($url) . "?" . $param . "&page=" . (($page - 1) <= 0 ? 1 : $page - 1) . "' title='上一页'> 上一页 </a></li>";
	} else {
		$strpage .= "<li class='ui-page-prev'> <a href='" . ($url) . "?page=" . (($page - 1) <= 0 ? 1 : $page - 1) . "' title='上一页'> 上一页 </a></li>";
	}
	$n = 4;
	$startP = $page - $n / 2;
	if ($startP < 1) {
		$startP = 1;
	}
	$endP = $startP + $n;
	if ($endP > $allpages) {
		$endP = $allpages;
	}
	for ($i = $startP; $i <= $endP; $i++) {
		if ($i == $page) {
			if ($param != '') {
				$strpage .= '<li class="active"><a href="' . ($url) . '?' . $param . '&page=' . $i . '">' . $i . '</a></li>';
			} else {
				$strpage .= '<li class="active"><a href="' . ($url) . '?page=' . $i . '">' . $i . '</a></li>';
			}
		} else {
			if ($param != '') {
				$strpage .= '<li><a href="' . ($url) . '?' . $param . '&page=' . $i . '">' . $i . '</a></li>';
			} else {
				$strpage .= '<li><a href="' . ($url) . '?page=' . $i . '">' . $i . '</a></li>';
			}
		}
	}

	if ($param != '') {
		$strpage .= "<li class='ui-page-next'><a href='" . ($url) . "?" . $param . "&page=" . (($page + 1) >= $allpages ? $allpages : $page + 1) . "' title='下一页'> 下一页 </a></li>";
	} else {
		$strpage .= "<li class='ui-page-next'><a href='" . ($url) . "?page=" . (($page + 1) >= $allpages ? $allpages : $page + 1) . "' title='下一页'> 下一页 </a></li>";
	}
	if ($param != '') {
		$strpage .= "<li class='ui-page-end'><a href='" . ($url) . "?".$param ."&page=" . $allpages . "' title='尾页' style='border-radius:0;'> 尾页 </a></li>";
	}else{
		$strpage .= "<li class='ui-page-end'><a href='" . ($url) . "?page=" . $allpages . "' title='尾页' style='border-radius:0;'> 尾页 </a></li>";
	}
	$strpage .= '</ul>';

	$strpage .= '<form class="ui-page-search">';
	$strpage .= '<input type="text" value="'.$page.'" class="page_href pageSearch" onkeyup="var _this = $(this);_this.val(_this.val().replace(/\D/g,1));if(parseInt(_this.val()) > '.$allpages.'){ _this.val('.$allpages.');}else if(parseInt(_this.val()) < 1){_this.val('.$page.');}"  name="page">';
	$strpage .= '<div class="pageCount"> 总计'.$nums.'条&nbsp;&nbsp;&nbsp;共'.$allpages.'页 </div>';
	$strpage .= '<input type="submit" value="跳转" class="mar15 pageLocation">';
	if ($param != '') {
		foreach($arr_param as $k => $v){
			if($k != 'page'){
				$strpage .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
			}
		}
		$strpage .= '<script>$(".page_href").keyup(function(){ var _this = $(this);_this.siblings("a").attr("href","'.($url).'?'.$param.'&page="+parseInt(_this.val())+"")})</script>';
	} else {
		$strpage .= '<script>$(".page_href").keyup(function(){ var _this = $(this);_this.siblings("a").attr("href","'.($url).'?page="+parseInt(_this.val())+"")})</script>';
	}
	$strpage .= '</form>';
	$strpage .= '</div>';
	$strpage .= '</div>';
	return $strpage;
}

function imgUrl($src = ''){
    return is_http().HTTP_HOST . $src;
}

function imgArr($src){
	if(empty($src)){
		return [];
	}
	$image = explode("|", $src);
	$arr = array();
	if($image){
		foreach($image as $k => $v){
			$arr[] = imgUrl($v);
		}
	}
	return $arr;
}

function is_http(){
	if(isset($_SERVER['HTTP_SCHEME'])){
		return $_SERVER['HTTP_SCHEME'] . '://';
	}
    if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
        return 'https://';
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
        return 'https://';
    }elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'){
        return 'https://';
    }
    return 'http://';
}

/**
 * 字符串截取
 * @param string $str 原始字符串
 * @param int    $len 截取长度（中文/全角符号默认为 2个单位，英文/数字为 1。例如：长度 12 表示 6 个中文或全角字符或 12 个英文或数字）
 * @param bool   $dot 是否加点（若字符串超过 $len 长度，则后面加 "..."）
 * @return string
 */
function my_truncate($str, $len, $suffix = '...') {
	$i = 0;
	$l = 0;
	$c = 0;
	$a = array();
	while ($l <= $len) {
		$t = substr($str, $i, 1);
		if (ord($t) >= 224) {
			$c = 3;
			$t = substr($str, $i, $c);
			$l += 2;
		} elseif (ord($t) >= 192) {
			$c = 2;
			$t = substr($str, $i, $c);
			$l += 2;
		} else {
			$c = 1;
			$l++;
		}
		$i += $c;
		$a[] = $t;
	}
	$sub = implode('', $a);
	if (strlen($str) > $len) {
		$sub .= $suffix;
	}
	return $sub;
}

// 清除html标签  type = true 截至字段后加...  type = false 不加...
function html_clear($detail = '' , $len = '' , $type = false){
	$detail = trim(strip_tags($detail));//转换为字符串
	$detail = str_replace(array(" ","&nbsp;","\t","　","\r\n"),"",$detail);//清除空格
	if($len){
		if($type == false){
			$detail = mb_substr($detail , 0 , $len,"utf-8");
		}else{
			$detail = my_truncate($detail , $len);
		}
	}
	return $detail;
}

// 判断用户头像
function userHeadimg($val = ''){
	$res = mb_substr($val , 0 , 4);
	if($res == 'http'){
		return $val;
	}
	return imgUrl($val);
}

function pro($params = '' , $id = 0){
	if(strpos($params,'|'.$id.'|') === false){
		return 2;
	}else{
		return 1;
	}
}

// 点赞收藏通用分割
function params_arr($params = '',$id = 0){
	if(strpos($params,'|'.$id.'|') === false){
		$res = ($params==''?'|':$params).$id.'|';
	}else{
		$res = str_replace('|'.$id.'|' , '|' , $params);
	}
	return $res;
}

function par($text = ''){
	$text = trim($text,'|');
	$arr = $text ? explode("|", $text) : [];
	return [
		'nums' => count($arr),
		'arr' => $arr
	];
}

function create_order_num($id) {
	$time = ceil(microtime(true) * 1000);
	if (strlen($id) < 7) {
		$start = pow(10, 7 - strlen($id));
		$end = $start * 10 - 1;
		$rnd = rand($start, $end);
		$id = $rnd . $id;
	} elseif (strlen($id) > 7) {
		$id = substr($id, -7, 7);
	}
	return $time . $id;
}

// 1.进行中 2.已结束  3.未开始
function hd_time($start_time = 0 , $end_time = 0){
	$status = 1;
    if($start_time > time()){
        $status = 3;// 活动未开始
    }
    if($end_time <= time()){
        $status = 2;// 活动已结束
    }
    return $status;
}

// 计算概率
function getRand($proArr) {
    $result = '';
    //概率数组的总概率精度   
    $proSum = array_sum($proArr);
    //概率数组循环   
    foreach ($proArr as $k => $v) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $v) {
            $result = $k;
            break;
        } else {
            $proSum -= $v;
        }
    }
    unset($proArr);
    return $result;
}

function arr_rush($arr = array(), $key = 'name'){
	if(is_array($arr) && !empty($arr)){
		$array = array();
		foreach($arr as $k => $v){
			if(isset($v['id']) && isset($v[$key])){
				$array[$v['id']] = $v[$key];
			}
		}
		return $array;
	}
	return false;
}