<?php
function json_return($arr = array()){
	echo json_encode($arr);exit;
}

function p($arr = array()){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

function post($val = ''){
	return isset($_POST[$val])?$_POST[$val]:'';
}

function get($val = ''){
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
function api_connect($query = '',$params = array()){
	$url = API . "/Home/" . $query;
	$params['myPostKey'] = md5("myNameIsLaysen@1213~");//curl加密秘钥
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
	curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	$result = json_decode(curl_exec($ch), true); //运行curl
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