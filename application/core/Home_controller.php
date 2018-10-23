<?php
namespace application\core;
use system\controller;
// PC控制器
class Home_Controller extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign('plug' , '/public/blog/');
	}
}
