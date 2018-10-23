<?php
namespace laysen;
class main{
	static public $classMap = array();
	static public function run(){
		// 辅助函数
		include PATH . APP . '/core/function.php';
		
		// 路由类
		$route =  '\\' . 'system' . '\\' . 'route';
		$route::index();
		$uri_1 = $route::$uri_1;
		$uri_2 = $route::$uri_2;
		$uri_3 = $route::$uri_3;
		$uri_length = $route::$uri_length;
		// 控制器类
		$uri = '\\' . APP . '\\' . CONTROLLER . '\\' .  ( $uri_length == 3 ? $uri_1 . '\\' . $uri_2 : $uri_1);
		$view = new $uri();
		// 视图类
		$display = $uri_length == 3 ? $uri_3 : $uri_2;
		$view->$display();
	}

	static public function load($class = 'index'){
		if(isset(self::$classMap[$class])){
			return true;
		}
		$class = str_replace('\\', '/', $class);
		$files = PATH . $class . '.php';
		if(is_file($files)){
			include $files;
			self::$classMap[$class] = $class;
		}else{
			die("未找到该文件：" . $files);
			return false;
		}
	}
}