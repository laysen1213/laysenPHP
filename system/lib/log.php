<?php
// 日志类
#key:缓存路径文件名 
#value:缓存数组
#cacheTime:缓存时间，（秒），0为永久缓存
// 用法
// use system\lib\cache;
// $res =  cache::set("router");
// p($res);
namespace system\lib;
class log {
	const Dir = './app/log/';
	const EXT = '.log';
	public function __construct(){}
	// 创建缓存
	public function set($fielname = '' , $name = "" , $content = ""){
		$arr = [
			'name' => $name,
			'content' => $content,
			'url' => $_SERVER['REDIRECT_URL'],
			'post' => $_POST,
			'ctime' => date("Y-m-d H:i:s",time())
		];
		$arr = json_encode($arr);

		$filepath = self::Dir . '/';
		if(!is_dir($filepath)){
			mkdir($filepath);
		}
		$filepath .= $fielname.'_'.date("Ymd",time()).self::EXT;
	    $fp = fopen($filepath, 'a+');
	    fwrite($fp, $arr);
	    fclose($fp);
	}

	

function k_data($type = '' , $dao , $param = array() , $where = array() , $remark = ''){
	$name = C('log_arr');
	$log = '{"time":"' . date("Y-m-d H:i:s",time()) . '","dao":"' . $dao . '","remark":"' . $remark . '","admin":"' . $_SESSION['admin_account'] . '"';
	if(!empty($param)){
		$log .= ',"param":' . k_array($param);
	}
	if(!empty($where)){
		$log .= ',"where":' . k_array($where);
	}
	$log .= '},';
	klog('model' , $log , $name[$type]);
}

function k_array($arr = array()){
	if(!empty($arr) && is_array($arr)){
		$t = '{';
		foreach($arr as $k => $v){
			$t .= '"' . $k . '":"' . $v . '",';
		}
		return rtrim($t , ',') . '}';
	}
}

// 查看日志
function l_log($fielname , $times){
	$filepath = APPPATH.'logs/'.$fielname . '/model_' . $times . '.log';
	if(!is_file($filepath)){
		return false;
	}
	return json_decode('[' . rtrim(file_get_contents($filepath) , ',') . ']',true);
}


	// 获取缓存
	public function get($key = ''){
		if($key == ''){
			die("参数错误");
		}
		$filename = self::Dir . $key . self::EXT;
		if(!is_file($filename)){
			die("未找到缓存文件：".$filename);
		}
		// 读取缓存文件
		$contents = file_get_contents($filename);
		$cacheTime = (int)substr($contents , 0 , 11);
		// 判断缓存文件是否过期
		if($cacheTime != 0 && ($cacheTime + filemtime($filename)) < time()){
			unlink($filename);
			return false;
		}
		$value = substr($contents,11);
		return json_decode($value , true);
	}

	// 删除缓存文件
	public function del($key = ''){
		if($key == ''){
			die("参数错误");
		}
		$filename = self::Dir . $key . self::EXT;
		if(substr($key , -1) == '/'){
			// 删除文件夹下所有文件
			$this->deldir($this->_dir . rtrim($key,'/'));
			return true;
		}else{
			return @unlink($filename);
		}
	}

	// 创建多个文件夹
	public function mkdir_all($dir_arr = array()){
		$root_path = $this->_dir;
		for ($i = 0; $i < count($dir_arr); $i++) {
			$root_path .= $dir_arr[$i] . '/';
			if (!is_dir($root_path)) {
				mkdir($root_path);
			}
		}
	}

	// 删除文件夹下所有文件
	public function deldir($dir) {
		if(is_dir($dir)){
			// 打开文件目录
			$dh = opendir($dir);
		  	//先删除目录下的文件：
			while ($file = readdir($dh)) {
				if($file != "." && $file != "..") {
					$fullpath = $dir. '/' . $file;
					if(!is_dir($fullpath)) {
						unlink($fullpath);
					} else {
						$this->deldir($fullpath);
					}
				}
			}
			// 关闭文件目录
			closedir($dh);
			//删除当前文件夹：
			if(rmdir($dir)) {
				return true;
			}else{
				return false;
			}
		}
	}
}