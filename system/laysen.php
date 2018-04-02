<?php
namespace laysen;
class main{
	public $uri_1;
	public $uri_2;
	public function run(){
		// 辅助函数
		include PATH . 'system/function.php';
		
		// 控制器类
		include PATH . 'system/controller.php';

		new \core\controller\LS_Controller();

		// 路由类
		include PATH . 'system/route.php';
		$route = new \core\route\main();

		$this->uri_1 = $route->uri_1;
		$this->uri_2 = $route->uri_2;
		// // 控制器类
		include CONTROLLER . '/' .$this->uri_1 . '.php';
		$uri = '\\' . $this->uri_1 . '\\' . $this->uri_1;

		// 数据库类
		include PATH . 'system/mysql.php';

		// 视图
		$view = new $uri();
		$display = $this->uri_2;
		$view->$display();

	}
}