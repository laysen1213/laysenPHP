<?php
namespace application\core;
use system\controller;
use application\core\My_model;
use system\lib\uploadFile;

class Admin_controller extends Controller{
	public function __construct(){
		parent::__construct();
		$this->files = new uploadFile();
	}

	protected function row($dao = '' , $where_arr = [] , $field = "*" , $order = "id desc"){
		$res = My_model::row($dao , $where_arr , $field , $order);
		return $res;
	}

	protected function alls($dao = '' , $where_arr = [] , $field = "*" , $order = "rank asc,id desc"){
		$res = My_model::all($dao , $where_arr , $field , $order);
		return $res;
	}

	// 分页
	protected function lists($dao = '' , $where_arr = [] , $field = '*' , $order = 'rank asc,id desc'){
		$page = Ipost('page') ? Ipost('page') : 1;
		$data = My_model::page($dao , $where_arr , $field , $page , 10 , $order);
		return $data;
	}

	protected function edit($dao = '' , $where_arr = [] , $params = []){
		$res = My_model::edit($dao , $where_arr , $params);
		return $res;
	}

	protected function delete($dao = '' , $where_arr = []){
		$res = My_model::delete($dao , $where_arr);
		return $res;
	}

	protected function setSubmit($dao = '' , $params = []){
		$id = Ipost('id');
		if($id){
			$res = My_model::edit($dao , ['id' => $id] , $params);
		}else{
			$params['ctime'] = time();
			$res = My_model::add($dao , $params);
		}
		success_return();
	}

	protected function cate_page($dao = '' , $where_arr = [] , $grade = 2 , $order = 'rank asc,id desc'){
		$res = My_model::all($dao , $where_arr , "*" , $order);
		$data['list'] = recursive($res , $grade);
		return $data;
	}
}
