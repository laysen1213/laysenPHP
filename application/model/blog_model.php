<?php
namespace application\model;
use application\core\My_model;
class Blog_model{
	public function __construct(){}

	// 博客列表
	public function blog_list(){
		$data = My_model::rankScroll('blog',['status'=>1],'id,cid,title,intro,cover,ctime',8);
		foreach($data as $k => $v){
			$data[$k]['ctime'] = date("Y-m-d" , $v['ctime']);
			$row = My_model::row('blog_cate',['id'=>$v['cid']],'pid,name');
			$data[$k]['cid'] = $row ? $row['name'] : '';
			$data[$k]['pid'] = $row ? $row['pid'] : '';
		}
		return $data;
	}

	// 博客详情
	public function blog_row($id = 0){
		$data = My_model::row('blog',['id'=>$id],'id,cid,title,content,ctime');// 详情
		return $data;
	}

	// 推荐列表
	public function tj_list(){
		$data = My_model::rankScroll('blog',['status'=>1,'is_recommend'=>1],'id,cid,title,ctime');
		foreach($data as $k => $v){
			$data[$k]['ctime'] = date("Y-m-d" , $v['ctime']);
			$row = My_model::row('blog_cate',['id'=>$v['cid']],'pid,name');
			$data[$k]['pid'] = $row ? $row['pid'] : '';
		}
		return $data;
	}

	// 博客分页
	public function blog_page($where_arr = [] , $page = 1){
		$data = My_model::page('blog',$where_arr,'id,cid,title,intro,cover,ctime',$page,10,'rank asc,id desc');
		foreach($data['list'] as $k => $v){
			$data['list'][$k]['cover'] = $v['cover'];
			$data['list'][$k]['ctime'] = date("Y-m-d" , $v['ctime']);
			$row = My_model::row('blog_cate',['id'=>$v['cid']],'id,name');
			$data['list'][$k]['cid'] = $row ? $row['name'] : '';
			$data['list'][$k]['pid'] = $row ? $row['id'] : '';
		}
		return $data;
	}

	// 博客分类详情
	public function blog_cate_row($id){
		$data = My_model::row('blog_cate',['id'=>$id],'name');
		return $data;
	}

	// 博客分类
	public function blog_cate($type){
		$data = My_model::rankScroll('blog_cate',['pid'=>$type],'id,name');
		return $data;
	}

	// 留言列表
	public function message_list(){
		$data = My_model::rankScroll('blog_message',['status'=>1],'id,name,content,hf,ctime');
		return $data;
	}

	// 添加留言
	public function message_create($data = []){
		$res = My_model::add('blog_message' , $data);
		return $res;
	}

	// 添加访问量
	public function visits($where_arr = []){
		$where_arr['cdate'] = date('Y-m-d');
		$row = My_model::row('visits',$where_arr,'id,nums');
		if($row){
			My_model::edit('visits',$where_arr,array('nums'=>$row['nums']+1));
		}else{
			$where_arr['ctime'] = time();
			$where_arr['nums'] = 1;
			My_model::add('visits',$where_arr);
		}
	}
}