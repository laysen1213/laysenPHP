<?php
// 路由类
namespace core\route;
class main{
	public $uri_1;
	public $uri_2;
	public function __construct(){
		$this->uri_1 = 'index';
		$this->uri_2 = 'index';
		if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
			$path = $_SERVER['REQUEST_URI'];
			$patharr = explode('/', trim($path,'/'));
			if(isset($patharr[0])){
				$this->uri_1 = $patharr[0];
				unset($patharr[0]);
			}
			if(isset($patharr[1])){
				$this->uri_2 = $patharr[1];
				unset($patharr[1]);
				$exp = explode("?", $this->uri_2);
				$this->uri_2 = $exp[0];
			}
		}
		
	}
}