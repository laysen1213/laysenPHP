<?php
namespace application\core;
use system\model\mysql;
class My_model{
	public function __construct(){
	}

	// 查询
	public function row($dao , $where_arr = [] , $filed = '*' , $order = "id desc"){
		$result = mysql::db($dao)->where($where_arr)->order($order)->row($filed);
		return $result['data'];
	}

	// 分页
	public function page($dao , $where_arr = [] , $filed = '*' , $page = 1 , $perpage = 10 , $order = "id desc"){
		$result = mysql::db($dao)->where($where_arr)->order($order)->limit(($page-1)*$perpage,$perpage)->all($filed);
		$count = mysql::db($dao)->where($where_arr)->count();
		return ['list' => $result['data'] , 'nums' => $count];
	}

	// 分页列表
	public function scroll($dao , $where_arr = [] , $filed = '*' , $perpage = 10 , $order = "id desc"){
		$page = Ipost('page') ? Ipost('page') : 1;
		$result = mysql::db($dao)->where($where_arr)->order($order)->limit(($page-1)*$perpage,$perpage)->all($filed);
		return $result['data'];
	}

	// 分页列表
	public function rankScroll($dao , $where_arr = [] , $filed = '*' , $perpage = 10 , $order = "rank asc,id desc" ){
		$page = Ipost('page') ? Ipost('page') : 1;
		$result = mysql::db($dao)->where($where_arr)->order($order)->limit(($page-1)*$perpage,$perpage)->all($filed);
		return $result['data'];
	}

	// 列表
	public function all($dao , $where_arr = [] , $filed = '*' ,  $order = "id desc" ){
		$result = mysql::db($dao)->where($where_arr)->order($order)->all($filed);
		return $result['data'];
	}

	// 添加
	public function add($dao , $params = []){
		$result = mysql::db($dao)->add($params);
		if($result['data']){
			return ['ret' => 1 , 'msg' => 'success' , 'id' => $result['data']];
		}
		return ['ret' => -1 , 'msg' => 'error'];
	}

	// 修改
	public function edit($dao , $where_arr = '', $params = []){
		$result = mysql::db($dao)->where($where_arr)->edit($params);
		if($result['data']){
			return ['ret' => 1 , 'msg' => 'success'];
		}
		return ['ret' => -1 , 'msg' => 'error'];
	}

	public function set($dao , $where_arr , $set = []){
		$result = mysql::db($dao)->where($where_arr)->set($set);
		if($result['data']){
			return ['ret' => 1 , 'msg' => 'success'];
		}
		return ['ret' => -1 , 'msg' => 'error'];
	}

	// 查询数量
	public function count($dao , $where_arr = [] , $field = 'id'){
		$res = mysql::db($dao)->where($where_arr)->count($field);
		return $res;
	}

	// 删除
	public function delete($dao , $where_arr = []){
		$result = mysql::db($dao)->where($where_arr)->del();
		if($result['data']){
			return ['ret' => 1 , 'msg' => 'success'];
		}
		return ['ret' => -1 , 'msg' => 'error'];
	}

	// sql查询
	public function query($sql = '' , $type = 1){
		$result = mysql_query($sql);
		switch ($type) {
			case 2:
				$result = mysql_fetch_assoc($result);
				break;
			case 3:
				$arr = array();
				while($row = mysql_fetch_assoc($result)){
					$arr[] = $row;
					unset($row);
				}
				$result = $arr;
				unset($arr);
				break;
			default:
				# code...
				break;
		}
		return array('data' => $result , 'sql' => $sql);
	}
}