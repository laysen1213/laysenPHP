<?php
namespace system\lib;
class uploadFile{
	public $file_url = 'upload/';
	public function upload($file = array()) {
        $allow_ext = array ('jpg',"jpeg","png","gif");
        $path = $this->get_upload_path();
        $dir = $this->get_upload_dir();
        if(!is_dir($path.$dir)) {
            return array('ret' => -1 , 'msg' => '创建目录失败');
        }
        $result = array();
        if ($file['error'] > 0) {
        	return array('ret' => -2 , 'msg' => '文件上传失败');
        }
        $ext = strtolower($this->fileext($file['name']));
        $filename = $this->create_filename($ext);
        if (move_uploaded_file($file['tmp_name'] , $path.$dir.$filename)) {
            return array('ret'=>1,"path"=>'/'.$this->file_url.$dir.$filename);
        }
        return array('ret' => -2 , 'msg' => '文件上传失败');
    }

    // base64图片上传
    public function basePost($val = '' , $msg = ''){
    	$val = $_POST[$val];
		if(!empty($msg) && empty($val)){
			json_return(array('ret' => -1 , 'msg' => $msg));
		}
		if(substr($val, 0 , 5) == 'data:'){
			$image = base64_decode(substr($val, 22));
		    $path = $this->get_upload_path();
		    $dir = $this->get_upload_dir();
		    if (empty($dir)) {
		        json_return(array('ret'=>-3, 'msg'=>'图片文件创建失败'));
		    }
		    $filename = $this->create_filename('jpg');
		    file_put_contents($path . $dir . $filename, $image);
		    return '/'.$this->file_url . $dir . $filename;
	    }else{
	    	$val = str_replace(is_http().HTTP_HOST, "", $val);
	    	return $val;
	    }
    }

    //获取商家图片保存路径，返回图片路径
	public function get_upload_path(){
		$upload_file_path = $this->file_url;
		if(!is_dir($upload_file_path)){
			mkdir($upload_file_path);
		}
		return $upload_file_path;
	}

	//获取商家图片保存目录，返回false或图片目录
	function get_upload_dir(){
		$path = $this->get_upload_path();
		$dir = date("Y/md");
		$this->mkdirs($path,$dir);
		return $dir . '/';
	}

	// 生成图片目录
	function mkdirs($root_path, $dir) {
		$dir_arr = explode('/', $dir);
		for ($i = 0; $i < count($dir_arr); $i++) {
			$root_path .= $dir_arr[$i] . '/';
			if (!is_dir($root_path)) {
				mkdir($root_path);
			}
		}
	}

	//生成文件名称
	function create_filename($ext){
		return floor(microtime(true) * 1000) . '.' . $ext;
	}

	//获取文件格式
	function fileext($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

}