<?php
namespace application\controller\api;
use application\core\Api_controller;
use application\model\api\forum_model;
class Forum extends Api_controller{
    public function __construct(){
        parent::__construct();
        $this->forum_model = new forum_model();
    }

	// 话题列表
	public function ht_list(){
		$type = Ipost('type') ? intval(Ipost('type')) : 1;//1.首页，2列表
		$data['list'] = $this->mobile_model->rankScroll('forum_ht',['status'=>1],'id,name,content,gzs,tzs,cover');
		foreach($data['list'] as $k => $v){
			$data['list'][$k]['cover'] = imgUrl($v['cover']);
			$data['list'][$k]['content'] = html_clear($v['content'],164,true);
			if($type == 1){
				$data['list'][$k]['user'] = $this->forum_model->ht_user_row($v['id'] , 4);
			}
		}
		success_return($data);
	}

	// 关注列表
	public function gz_list(){
		$data['list'] = $this->mobile_model->scroll('forum_gs' , ['type' => 4 , 'uid' => $this->uid] , "pid");
		foreach ($data['list'] as $k => $v) {
			$data['list'][$k] = $this->forum_model->ht_row($v['pid'],$this->uid);
			$data['list'][$k]['user'] = $this->forum_model->ht_user_row($v['pid'] , 4);
		}
		success_return($data);
	}

	// 话题详情
	public function ht_info(){
		$id = Ipost('id');//话题Id
		$data['info'] = $this->forum_model->ht_row($id , $this->uid);
		success_return($data);
	}

	// 帖子列表
	// type = {0 ： 帖子列表 ，1 : 我的帖子 , 2 : 已收藏的帖子 , 3 : 首页已关注的帖子}
	public function tz_list(){
		$ht_id = Ipost('ht_id');
		$type = Ipost('type') ? Ipost('type') : 0;
		$data['list'] = [];
		if($type == 0 || $type == 1)
		{
			// 帖子列表
			$uid = $type == 0 ? 0 : $this->uid;
			$data['list'] = $this->forum_model->tz_list($ht_id , $uid , $type);
		}
		elseif($type == 2)
		{
			// 我的收藏
			$data['list'] = $this->mobile_model->scroll('forum_gs',['type'=>2,'uid'=>$this->uid],"pid");
			foreach($data['list'] as $k => $v){
				$data['list'][$k]= $this->forum_model->tz_row($v['pid'],$this->uid);
			}
		}
		elseif($type == 3)
		{
			// 已关注的帖子
			$ht_all = $this->mobile_model->all("forum_gs" , ['type'=>4 , 'uid'=>$this->uid] , 'id,pid');
			$ht_id = arr_rush($ht_all , 'pid');
			$ht_id = !empty($ht_id) ? implode("," , $ht_id) : '';
			$data['list'] = $this->forum_model->tz_list($ht_id , 0 , $type);
		}
		success_return($data);
	}

	// 话题关注
	public function ht_gz_set(){
		$res = $this->forum_model->forum_type('forum_ht',4,'gzs',$this->uid);
		success_return();
	}

	// 帖子详情
	public function tz_info(){
		$id = Ipost('id');//帖子Id
		$where_arr = ['status' => 1 , 'id' => $id];
		$data['info'] = $this->forum_model->tz_row($id,$this->uid);
		if(empty($data['info'])){
			error_return("未找到帖子");
		}
		success_return($data);
	}

	// 帖子点赞
	public function tz_dz(){
		$res = $this->forum_model->forum_type('forum_tz',1,'dzs',$this->uid);
		success_return();
	}

	// 帖子收藏
	public function tz_sc(){
		$res = $this->forum_model->forum_type('forum_tz',2,'scs',$this->uid);
		success_return();
	}

	// 评论列表
	public function comment_list(){
		$id = Ipost('id');//帖子Id
		$data['list'] = $this->mobile_model->scroll('forum_comment',['tz_id'=>$id,'comment_id'=>0],'id,uid,comment_id,dzs,content,ctime');
		foreach($data['list'] as $k => $v){
			$data['list'][$k]['ctime'] = date('Y-m-d H:i:s' , $v['ctime']);
			// 用户信息
			$res = $this->forum_model->user_info($v['uid']);
			$data['list'][$k]['user_name'] = $res['user_name'];
			$data['list'][$k]['headimg'] = $res['headimg'];

			// 回复
			$hf = $this->mobile_model->scroll("forum_comment",['comment_id'=>$v['id']] , 'content,uid');
			foreach($hf as $kk => $vv){
				$res = $this->forum_model->user_info($vv['uid']);
				$hf[$kk]['user_name'] = $res['user_name'];
			}
			$data['list'][$k]['hf'] = $hf;

			// 判断用户是否点赞
			$row = $this->mobile_model->row('forum_gs' , ['type' => 3 , 'pid' => $v['id'] , 'uid' => $this->uid] , 'id');
			$data['list'][$k]['dz_type'] = $row ? 1 : 2;
		}
		success_return($data);
	}

	// 评论点赞
	public function comment_dz(){
		$res = $this->forum_model->forum_type('forum_comment',3,'dzs',$this->uid);
		success_return();
	}

	// 发布评论
	public function comment_create(){
		$id = Ipost('id');//帖子id
		$content = Ipost('content');
		$res = $this->mobile_model->add('forum_comment',[
			'uid'=>$this->uid,'tz_id'=>$id,'content'=>$content,'ctime'=>time()
		]);
		$this->mobile_model->set("forum_tz",['id'=>$id],['pls'=>'+1']);
		json_return($res);
	}

	// 发布回复
	public function replay_create(){
		$id = Ipost('id');//评论id
		$tz_id = Ipost('tz_id');//帖子id
		$content = Ipost('content');

		$res = $this->mobile_model->add('forum_comment',[
			'uid'=>$this->uid,'tz_id'=>$tz_id,'comment_id'=>$id,'content'=>$content,'ctime'=>time()
		]);
		$this->mobile_model->set("forum_comment",['id'=>$id],['hfs'=>'+1']);
		json_return($res);
	}

	// 发布帖子
	public function send_tz(){
		$params = [
			'uid' => $this->uid,
			'ht_id' => Ipost('ht_id'),
			'content' => Ipost('content','内容不能为空'),
			'image' => Ipost('image'),
			'ctime' => time()
		];
		$res = $this->mobile_model->add("forum_tz" , $params);
		json_return($res);
	}
}
