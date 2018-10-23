<?php
namespace system\model;
use system\config;
class Mysql{
	public function db($dao = ''){
		return new \system\model\Query($dao);
	}
}

class Query{
	private static $conn;
	private static $dao;
	private static $where;
	private static $limit;
	private static $order_by;
	private static $group_by;
	public function __construct($params = ''){
		header("Content-type:text/html;charset=utf-8");
		$conf = config::get('mysql');
		if(!isset(self::$conn)){
			self::$conn = new \mysqli($conf['host'],$conf['name'],$conf['password'], $conf['dbname']);
		}
		if(!self::$conn){
			die(mysqli_error());
		}
		mysqli_query(self::$conn,"SET NAMES UTF8");
		self::$dao = '`' . $conf['dbprefix'] . $params . '`';
	}

	// 第二个参数为数组的时候
	public function or_where($name , $arr){
		$where_arr = "";
		foreach($arr as $k => $v){
			switch ($k) {
				case 'in':
					$where_arr .= "`" .$name . "` in (" . $v . ") ";
					break;
				case 'like':
					$where_arr .= "`" .$name . "` like '". $v . "' ";
					break;
				/*case 'or':
					$where_arr .= "or `" .$name . "` = " . $v. " ";
					break;*/
			}
		}
		return $where_arr;
	}

	// 判断条件输出
	public function where($params = []){
		$where_arr = '';
		// 当第一个参数为数组
		if(is_array($params)){
			$i = 0;
			foreach($params as $k => $v){
				$where_arr .= $i > 0 ? ' and ' : ' ';
				$i++;
				if(is_array($v)){
					$res = $this->or_where($k , $v);
					$where_arr .= $res;
				}else{
					if(is_string($v)){
						$v = "'" . $v . "'";
					}
					$where_arr .=  "`".$k."`" . " = " . $v;
				}
			}
		}
		self::$where = $where_arr;
		return $this;
	}

	// 分页
	public function limit($pis = 0, $fis = ''){
		$limit = '';
		if(empty($fis)){
			$limit = " limit " . $pis . " ";
		}elseif(!empty($fis)){
			$limit = " limit " . $pis . "," . $fis . " ";
		}
		self::$limit = $limit;
		return $this;
	}

	// 排序
	public function order($order_by = ''){
		if($order_by){
			$query = $this->order_query($order_by);
			self::$order_by = " ORDER BY " . $query . " ";
		}
		return $this;
	}

	// 分组
	public function group($group_by = ''){
		if($group_by){
			$query = $this->order_query($group_by);
			self::$group_by = " GROUP BY " . $query . " ";
		}
		return $this;
	}

	// 创建查询
	public function query($field = "*"){
		if($field != "*"){
			$field = $this->string_query($field);
		}
		$sql = "SELECT ".$field." FROM " . self::$dao;
		if(self::$where){
			$sql .= " WHERE " . self::$where;
		}
		self::$where = "";
		if(self::$order_by){
			$sql .= self::$order_by;
			self::$order_by = "";
		}
		if(self::$group_by){
			$sql .= self::$group_by;
			self::$group_by = "";
		}
		return $sql;
	}

	// 序列化字符串
	public function string_query($val = ''){
		$arr = explode(",", $val);
		foreach($arr as $k => $v){
			$arr[$k] = '`' . $v . '`';
		}
		$val = implode(",", $arr);
		return $val;
	}

	// 序列化字符串
	public function order_query($val = ''){
		$arr = explode(",", $val);
		foreach($arr as $k => $v){
			$asv = explode(" ", $v);
			$arr[$k] = '`' . $asv[0] . '`' . (isset($asv[1]) ? ' '.$asv[1] : '');
		}
		$val = implode(",", $arr);
		return $val;
	}

	// 查询单条
	public function row($field = '*'){
		$sql = $this->query($field);
		$sql .= " limit 1";
		$query = mysqli_query(self::$conn,$sql);
		$result = mysqli_fetch_assoc($query);
		return ['data' => $result , 'sql' => $sql];
	}

	// 查询全部
	public function all($field = "*"){
		$sql = $this->query($field);
		if(self::$limit){
			$sql .= self::$limit;
			self::$limit = '';
		}
		$query = mysqli_query(self::$conn,$sql);
		$result = [];
		while($row = mysqli_fetch_assoc($query)){
			$result[] = $row;
			unset($row);
		}
		return ['data' => $result , 'sql' => $sql];
	}

	public function count($field = "id"){
		$sql = "SELECT COUNT(`".$field."`) AS nums FROM " . self::$dao;
		if(self::$where){
			$sql .= " WHERE " . self::$where;
		}
		self::$where = "";
		$query = mysqli_query(self::$conn,$sql);
		$count = mysqli_fetch_assoc($query);
		return $count ? $count['nums'] : 0;
	}

	// 添加
	public function add($params = []){
		$params_arr = $this->add_params_sql($params);
		$sql = "INSERT INTO " . self::$dao . " " . $params_arr;
		$result = mysqli_query(self::$conn,$sql);
		$id = mysqli_insert_id(self::$conn);  
		return ['data' => $id , 'sql' => $sql];
	}

	// 修改
	public function edit($params = []){
		$params_arr = $this->edit_params_sql($params);
		$sql = "UPDATE " . self::$dao . " SET " . $params_arr . " WHERE " . self::$where;
		self::$where = "";
		$result = mysqli_query(self::$conn,$sql);
		return ['data' => $result , 'sql' => $sql];
	}

	// 添加数量
	public function set($params = []){
		$arr = '';
		if(is_array($params)){
			$i = 0;
			foreach ($params as $k => $v) {
				if($i != 0){
					$arr .= ',';
				}
				$arr .= "`".$k."`" . " = `" . $k . "`" . $v;
			}
		}
		$sql = "UPDATE " . self::$dao . " SET " . $arr . " WHERE " . self::$where;
		self::$where = "";
		$result = mysqli_query(self::$conn,$sql);
		return ['data' => $result , 'sql' => $sql];
	}

	// 删除
	public function del($params = []){
		$params_arr = $this->edit_params_sql($params);
		$sql = "DELETE FROM " . self::$dao . " WHERE " . self::$where;
		self::$where = "";
		$result = mysqli_query(self::$conn,$sql);
		return ['data' => $result , 'sql' => $sql];
	}

	public function edit_params_sql($params){
		$arr = '';
		if(!empty($params) && is_array($params)){
			$i = 0;
			foreach($params as $k => $v){
				if($i != 0){
					$arr .= ',';
				}
				$v = is_string($v) ? ("'" . $v . "'") : $v;
				$arr .= "`" . $k . "`" . " = " . $v;
				$i += 1;
			}
		}
		return $arr;
	}

	// 提交输出
	public function add_params_sql($params = []){
		$arr = '';
		if(!empty($params) && is_array($params)){
			$key = '(';
			$values = ' VALUES (';
			$i = 0;
			foreach($params as $k => $v){
				if($i != 0){
					$key .= ',';
					$values .= ',';
				}
				$key .= "`".$k."`";
				if(empty($v)){
					$v = "''";
				}else{
					$v = is_string($v) ? ("'" . $v . "'") : $v;
				}
				$values .= $v;
				$i += 1;
			}
			$key .= ')';
			$values .= ')';
			$arr = $key . $values;
		}
		return $arr;
	}

	
}