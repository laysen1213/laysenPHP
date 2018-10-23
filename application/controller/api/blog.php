<?php
namespace application\controller\api;
use application\core\Api_controller;
class Blog extends Api_controller{
    public function __construct(){
        parent::__construct();
    }

	// 列表
	public function blog_list(){
		$data['list'] = $this->mobile_model->rankScroll('blog',['status'=>1],'id,title,cover,intro,ctime');
		foreach($data['list'] as $k => $v){
			$data['list'][$k]['cover'] = imgUrl($v['cover']);
			$data['list'][$k]['ctime'] = date("Y-m-d" , $v['ctime']);
		}
		success_return($data);
	}

	// 分类
	public function blog_cate(){
		$data['list'] = $this->mobile_model->rankScroll('blog_cate',['status'=>1],'id,name');
		success_return($data);
	}

	// 详情
	public function blog_detail(){
		$id = intval(Ipost("id" , 'id不能为空'));

		$data['info'] = $this->mobile_model->row('blog',['id'=>$id],'id,cid,title,content,ctime');
		if(empty($data['info'])){
			error_return(-2 , '未找到信息');
		}
		$data['info']['ctime'] = date("Y-m-d" , $data['info']['ctime']);
		success_return($data);
	}
}