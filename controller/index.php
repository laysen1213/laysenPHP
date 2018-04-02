<?php
namespace index;
use core\controller as ls;
class Index extends ls\HOME_Controller{
	public function __construct(){
		parent::__construct();
	}

	// 首页
	public function index(){
		$res = $this->model->lists('pt_blog',array('status'=>1),'id,cid,title,intro,cover,ctime',1,8,'rank asc,id desc');
		foreach($res['data'] as $k => $v){
			$res['data'][$k]['cover'] = $this->domain . $v['cover'];
			$res['data'][$k]['ctime'] = date("Y-m-d" , $v['ctime']);
			$row = $this->model->row('pt_blog_cate','name',array('id'=>$v['cid']));
			$res['data'][$k]['cid'] = $row['data'] ? $row['data']['name'] : '';
		}
		$this->assign('data' , $res['data']);
		$this->assign('nav' , 1);
		$this->display('index/index');
	}

	// 列表
	public function lists(){
		$search = trim(get('search'));
		$where_arr = array('status'=>1);
		$s = '';
		if($search){
			$s = 'and title like "%'.$search.'%"';
		}
		$res = $this->model->lists('pt_blog',$where_arr,'id,cid,title,intro,cover,ctime',1,10,'rank asc,id desc',$s);
		foreach($res['data'] as $k => $v){
			$res['data'][$k]['cover'] = $this->domain . $v['cover'];
			$res['data'][$k]['ctime'] = date("Y-m-d" , $v['ctime']);
			$row = $this->model->row('pt_blog_cate',array('id'=>$v['cid']),'name');
			$res['data'][$k]['cid'] = $row['data'] ? $row['data']['name'] : '';
		}
		$this->assign('data' , $res['data']);
		$tj = $this->model->lists('pt_blog',array('status'=>1,'is_recommend'=>1),'id,title,ctime',1,10,'rank asc,id desc');
		foreach($tj['data'] as $k => $v){
			$tj['data'][$k]['ctime'] = date("Y-m-d" , $v['ctime']);
		}
		$this->assign('tj_list' , $tj['data']);
		$this->assign('nav' , 2);
		$this->assign('search' , $search);
		$this->display('index/lists');
	}

	// 详情
	public function detail(){
		$id = intval(get('id'));
		$res = $this->model->row('pt_blog',array('id'=>$id),'id,cid,title,content,ctime');
		$row = $this->model->row('pt_blog_cate',array('id'=>$res['data']['cid']),'name');
		$res['data']['name'] = $row['data']['name'];
		$res['data']['ctime'] = date("Y-m-d" , $res['data']['ctime']);
		$this->assign('data' , $res['data']);
		// // 推荐阅读
		$tj = $this->model->lists('pt_blog',array('status'=>1,'is_recommend'=>1),'id,title,ctime',1,10,'rank asc,id desc');
		foreach($tj['data'] as $k => $v){
			$tj['data'][$k]['ctime'] = date("Y-m-d" , $v['ctime']);
		}
		$this->assign('tj_list' , $tj['data']);
		// 导航
		$this->assign('nav' , 2);
		$this->display('index/detail');
	}

	// 音乐
	public function music(){
		// 推荐阅读
		// $res = api_connect('Index/tj_list');
		// $this->assign('tj_list' , $res['data']);
		// 导航
		$this->assign('nav' , 3);
		$this->display('index/music');
	}
}
