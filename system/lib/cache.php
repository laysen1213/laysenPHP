<?php
// 文件缓存类
#key:缓存路径文件名 
#value:缓存数组
#cacheTime:缓存时间，（秒），0为永久缓存
// 用法
// use system\lib\cache;
// $res =  cache::set("router");
// p($res);
namespace system\lib;
class cache {
	const Dir = './app/cache/';
	const EXT = '.txt';
	public function __construct(){}
	// 创建缓存
	public function set($key = '' , $value = "" , $cacheTime = 0){
		if($key == ''){
			die("参数错误");
		}
		if($value == ''){
			die($key."请输入缓存内容");
		}
		//缓存文件名
		$filename = self::Dir . $key . self::EXT;
		// 创建目录
		$dir = dirname($filename);
		if(!is_dir($dir)){
			$arr = explode("/", $key);
			if(count($arr) > 2){
				// 当存在多个文件夹时
				array_pop($arr);
				$this->mkdir_all($arr);
			}else{
				mkdir($dir , 0777);
			}
		}
		// 生成缓存时间
		$cacheTime = sprintf('%011d' , $cacheTime);
		// 创建缓存文件
		if(file_put_contents($filename,$cacheTime . json_encode($value))){
			return true;
		}
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