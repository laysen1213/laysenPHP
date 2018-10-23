<?php
// 路由类
namespace system;
use system\config;
class route{
	public static $uri_1 = 'blog';
	public static $uri_2 = 'index';
	public static $uri_3 = 'index';
	public static $uri_length = 1;
	public function index(){
		$conf = config::get('route');
		self::$uri_1 = $conf['uri_1'];
		self::$uri_2 = $conf['uri_2'];
		self::$uri_3 = $conf['uri_3'];
		if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
			$path = $_SERVER['REQUEST_URI'];
			$exp = explode("?", $path);
			$patharr = explode('/', trim($exp[0],'/'));
			$length = count($patharr);
			self::$uri_length = $length >= 3 ? 3 : 1;
			if(isset($patharr[0]) && !empty($patharr[0])){
				self::$uri_1 = $patharr[0];
				unset($patharr[0]);
			}
			if(isset($patharr[1]) && !empty($patharr[1])){
				self::$uri_2 = $patharr[1];
				unset($patharr[1]);
			}

			if(isset($patharr[2]) && !empty($patharr[2])){
				self::$uri_3 = $patharr[2];
				unset($patharr[2]);
			}
		}
	}
}