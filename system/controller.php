<?php
namespace system;
class Controller{
	public $assign;
	public $model;
	public function __construct(){
		header("Content-type:text/html;charset=utf-8");
	}

	// 渲染数据
	protected function assign($key , $value){
		$this->assign[$key] = $value;
	}

	// 渲染模板
	protected function display($file = '' , $data = array()){
		extract($this->assign);
		// extract($data);
		include './' . APP . '/' .VIEW . '/' . $file . '.php';
	}
}

