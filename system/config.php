<?php
// 配置类
namespace system;
class config {
	static public $conf = [];
	static public function get($name = '' , $file = 'my_config'){
		if(isset(self::$conf[$file][$name])){
			return self::$conf[$name];
		}
		$file = PATH . APP . '/config/' . $file . '.php';
		if(is_file($file)){
			$conf = include $file;
			if(isset($conf[$name])){
				self::$conf[$file] = $conf;
				return $conf[$name];
			}else{
				die("没有该配置项");
			}
		}else{
			die("找不到配置文件：".$file);
		}
	}
}