<?php
namespace core\controller;
class LS_Controller{
	public $assign;
	public function __construct(){
		$this->init();
	}
	
	private function init(){

	}

	// 渲染数据
	protected function assign($key , $value){
		$this->assign[$key] = $value;
	}

	// 渲染模板
	protected function display($file = ''){
		extract($this->assign);
		include VIEW . '/' . $file . '.html';
	}
}

// 首页控制器
class HOME_Controller extends \core\controller\LS_Controller{
	public function __construct(){
		parent::__construct();
		header("Content-type:text/html;charset=utf-8");
		// 数据库类
		$this->model = new \core\db\mysql();
		$this->assign('plug' , '/public/index/');
		$this->domain = 'http://m.91laysen.cn';
	}
}