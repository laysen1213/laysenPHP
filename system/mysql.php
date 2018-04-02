<?php
namespace core\db;
class Mysql{
	public $conn;
	public function __construct(){
		header("Content-type:text/html;charset=utf-8");
		define('host','localhost');
		define('user','root');
		define('pwd','root');
		$this->conn = mysql_connect(host,user,pwd);
		if(!$this->conn){
			die(mysql_error());
		}
		mysql_select_db('laysen',$this->conn);
		mysql_query('SET NAMES UTF8');
	}

	// 查询
	public function row($dao , $where = '' , $filed = '*'){
		$where_arr = $this->where_sql($where);
		$sql = "select " . $filed . " from " . $dao . $where_arr . " limit 1";
		$query = mysql_query($sql,$this->conn);
		$result = mysql_fetch_assoc($query);
		return array('data' => $result , 'sql' => $sql);
	}

	// 分页
	public function lists($dao , $where = array() , $filed = '*' , $page = 1 , $perpage = 10 , $order = "id desc" , $like = ''){
		$where_arr = $this->where_sql($where);
		$sql = "select " . $filed . " from ".  $dao . $where_arr;
		if($like){
			$sql .= " " . $like;
		}
		$sql .= " order by " . $order;
		$sql .= " limit ";
		if($page > 1){
		    $sql .= (($page - 1)*$perpage) . ",";
		}
		$sql .= $perpage;
		$query = mysql_query($sql,$this->conn);
		$result = array();
		while($row = mysql_fetch_assoc($query)){
			$result[] = $row;
			unset($row);
		}
		return array('data' => $result , 'sql' => $sql);
	}

	// 列表
	public function page($dao , $where = array() , $filed = '*' , $page = 1 , $perpage = 10 , $order = "id desc" , $like = ''){
		$where_arr = $this->where_sql($where);
		$sql = "select " . $filed . " from ".  $dao . $where_arr;
		if($like){
			$sql .= " " . $like;
		}
		$sql .= " order by " . $order;
		$sql .= " limit ";
		if($page > 1){
		    $sql .= (($page - 1)*$perpage) . ",";
		}
		$sql .= $perpage;
		$query = mysql_query($sql,$this->conn);
		$result = array();
		while($row = mysql_fetch_assoc($query)){
			$result[] = $row;
			unset($row);
		}

		// 查询总数量
		$sql_count = "select count(id) as nums from ".  $dao . $where_arr;
		$query_count = mysql_query($sql_count);
		$count = mysql_fetch_assoc($query_count);
		return array('data' => $result , 'sql' => $sql , 'nums' => $count['nums']);
	}

	// 列表
	public function all($dao , $where = '' , $filed = '*' , $order = "id desc"){
		$where_arr = $this->where_sql($where);
		$sql = "select " . $filed . " from ".  $dao . $where_arr;
		$sql .= " order by " . $order;
		$query = mysql_query($sql);
		$result = array();
		while($row = mysql_fetch_assoc($query)){
			$result[] = $row;
			unset($row);
		}
		return array('data' => $result , 'sql' => $sql);
	}

	// 添加
	public function add($dao , $params = array()){
		$params_arr = $this->params_sql($params);
		$sql = "insert into user " . $params_arr;
		$result = $query = mysql_query($sql);
		return array('data' => $result , 'sql' => $sql);
	}

	// 修改
	public function edit($dao , $where = '', $params = array()){
		$where_arr = $this->where_sql($where);
		$params_arr = $this->edit_params_sql($params);
		$sql = "update " . $dao . " set " . $params_arr . ' ' . $where_arr;
		$result = mysql_query($sql);
		return array('data' => $result , 'sql' => $sql);
	}

	// 删除
	public function delete($dao , $where = array()){
		$where_arr = $this->where_sql($where);
		$sql = "delete from " . $dao . $where_arr;
		$result = mysql_query($sql);
		return array('data' => $result , 'sql' => $sql);
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

	public function edit_params_sql($params){
		$arr = '';
		if(!empty($params) && is_array($params)){
			$i = 0;
			foreach($params as $k => $v){
				if($i != 0){
					$arr .= ',';
				}
				$arr .= $k . " = " . "'" . $v . "'";
				$i += 1;
			}
		}
		return $arr;
	}

	// 提交输出
	public function params_sql($params = array()){
		$arr = '';
		if(!empty($params) && is_array($params)){
			$key = '(';
			$values = ' values (';
			$i = 0;
			foreach($params as $k => $v){
				if($i != 0){
					$key .= ',';
					$values .= ',';
				}
				$key .= $k;
				$values .= "'" . $v . "'";
				$i += 1;
			}
			$key .= ')';
			$values .= ')';
			$arr = $key . $values;
		}
		return $arr;
	}

	// 判断输出
	public function where_sql($where = ''){
		$where_arr = '';
		if(!empty($where)){
			if(is_array($where)){
				$where_arr .= ' where ';
				$i = 0;
				foreach($where as $k => $v){
					if($i != 0){
						$where_arr .= " and ";
					}
					$where_arr .= $k . " = " . "'" . $v . "'";
					$i += 1;
				}
			}elseif(is_string($where)){
				$where_arr = $where;
			}
		}else{
			$where_arr .= ' where 1=1';
		}
		return $where_arr;
	}
}