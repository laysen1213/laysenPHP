<?php
namespace application\controller;
use application\core\Home_controller;
use application\model\blog_model;
// use system\model\mongodb;
// use system\model\redis;
// use system\model\memcache;

class Blog extends Home_controller{
	public function __construct(){
		parent::__construct();
		blog_model::visits(['cate'=>1 , 'type' => 1 , 'uid' => 0 , 'ip' => getonlineip()]);// 增加访问量
	}

	public function test(){
		// memcache::set('key'); 
		// $get_value = memcache::get('key'); 
		// echo $get_value;
		// $res = mongodb::find('runoob',[],['cate'],0,4,['cate' => -1]);
		// $res = mongodb::remove();
		// $res = mongodb::insert(['_id'=>5,'cate'=>2,'od'=>3]);
		// $res = mongodb::update(['cate'=>2],['od'=>555]);
		// $res = mongodb::find('runoob',['cate'=>2],['od']);
		// p($res);
		// mongodb::writes('runoob');
	}

	// 首页
	public function index(){
		$res = blog_model::blog_list();
		$this->assign('data', $res);
		$this->assign('nav' , 1);
		$this->display('blog/index');
	}

	// 列表
	public function lists(){
		$search = trim(Iget('search'));
		$page = Iget('page') ? intval(Iget('page')) : 1;
		$type = intval(Iget('type'));
		$sec = intval(Iget('sec'));
		$perpage = 10;
		$id_arr[] = $type;
		// 分类列表
		$cate = blog_model::blog_cate($type);
		foreach($cate as $k => $v){
			$id_arr[] = $v['id'];
		}
		
		$id_arr = implode(",", $id_arr);
		$where_arr = ['status' => 1];
		$where_arr['cid'] = $sec ? $sec : ['in' => $id_arr];// 分类刷选
		$search && $where_arr['title'] = ['like' => "%".$search."%"];// 搜索
		$res = blog_model::blog_page($where_arr , $page);
		
		// // 推荐
		$tj = blog_model::tj_list();
		$this->assign('tj_list' , $tj);
		$this->assign('strpage' , setpage($page,$perpage,$res['nums'],'blog/lists?type='.$type.'&sec='.$sec));
		$this->assign('page',$page);
		$this->assign('c1' , $type);
		$this->assign('c2' , $sec);
		$this->assign('cate',$cate);
		$this->assign('data' , $res['list']);
		$this->assign('nav' , $type == 1 ? 2 : 3);
		$this->assign('search' , $search);
		$this->display('blog/lists');
	}

	// 详情
	public function detail(){
		$pid = intval(Iget('pid'));
		$id = intval(Iget('id'));
		$res = blog_model::blog_row($id);// 详情
		$row = blog_model::blog_cate_row($res['cid']);// 分类
		$res['name'] = $row['name'];
		$res['ctime'] = date("Y-m-d" , $res['ctime']);
		// 推荐
		$tj = blog_model::tj_list();
		$this->assign('tj_list' , $tj);
		$this->assign('data' , $res);
		$this->assign('nav' , $pid == 1 ? 2 : 3);
		$this->display('blog/detail');
	}

	// 留言
	public function message(){
		$tj = blog_model::tj_list();
		$this->assign('tj_list' , $tj);
		$message = blog_model::message_list();
		$this->assign('message' , $message['data']);
		$this->assign('nav' , 4);
		$this->display('blog/message');
	}

	// 填写吐槽内容
	public function message_create(){
		$data = array(
			'name' => Ipost('name'),
			'sex' => Ipost('sex'),
			'phone' => Ipost('phone'),
			'email' => Ipost('email'),
			'content' => Ipost('content'),
			'ip' => getonlineip(),
			'status' => 1,
			'rank' => 100,
			'ctime' => time()
		);
		blog_model::message_create($data);
		success_return();
	}

	// 关于我
	public function about(){
		$tj = blog_model::tj_list();
		$this->assign('tj_list' , $tj);
		$this->assign('nav' , 5);
		$this->display('blog/about');
	}

	// 生成静态页
	public function static_index(){
		$url = 'http://'.$_SERVER['HTTP_HOST'];
		$a = api_connect($url , array() , 'html');
		$res = file_put_contents('./index.html',$a);
	}

	// 音乐
	public function music(){
		$this->assign('nav' , 3);
		$this->display('index/music');
	}
}
