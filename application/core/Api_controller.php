<?php
namespace application\core;
use system\controller;
use application\core\My_model;
class Api_Controller extends Controller{
	public $uid;
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	protected function init(){
		$this->uid = 1;
		$this->mobile_model = new My_model();
	}
}
