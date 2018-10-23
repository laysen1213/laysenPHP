<?php
namespace application\model\api;
use application\core\My_model;
class Forum_model{
	public function __construct(){
		$this->mobile_model = new My_model();
	}

	// 话题详情
	public function ht_row($id = 0 , $uid = 0){
		$where_arr = ['status' => 1 , 'id' => $id];
		$data = $this->mobile_model->row('forum_ht',$where_arr,'id,cover,name,gzs,tzs,content');
		if($data){
			$data['cover'] = imgUrl($data['cover']);
			$row = $this->mobile_model->row('forum_gs',['type' => 4 , 'pid' => $data['id'] , 'uid' => $uid],'id');
			$data['gz_type'] = $row ? 1 : 2;
		}
		return $data;
	}

	// 关注话题用户信息
	public function ht_user_row($pid = 0 , $type = 1){
		// 获取关注用户信息
		$data = [];
		for($i = 0 ; $i < 4 ; $i++){
			$row = $this->mobile_model->row('forum_gs',['type'=>$type,'pid'=>$pid],'uid');
			if($row){
				$data[] = $this->user_info($row['uid']);
			}
		}
		return $data;
	}

	public function gs_row($type = 1 , $uid = 0){
		$where_arr = ['type' => $type , 'uid' => $uid];
		$data = $this->mobile_model->row('forum_gs',$where_arr,'params');
		return $data;
	}

	// 帖子列表
	public function tz_list($ht_id = '' , $uid = 0 , $type = 0){
		$where_arr = ['status' => 1];
		$ht_id && $where_arr['ht_id'] = $type == 3 ? ['in'=>$ht_id] : $ht_id;
		$uid && $where_arr['uid'] = $uid;
		$field = 'id,uid,content,image,pls,dzs,ctime';
		$data = $this->mobile_model->rankScroll('forum_tz',$where_arr,$field);
		foreach($data as $k => $v){
			$data[$k]['image'] = imgArr($v['image']);
			$data[$k]['ctime'] = date('Y-m-d H:i:s' , $v['ctime']);
			$data[$k]['content'] = html_clear($v['content'],86,true);

			$user = $this->user_info($v['uid']);
			$data[$k]['user_name'] = $user['user_name'];
			$data[$k]['headimg'] = $user['headimg'];

			// 点赞
			$row = $this->mobile_model->row('forum_gs',['type' => 1 , 'pid' => $v['id'] , 'uid' => $v['uid']],'id');
			$data[$k]['dz_type'] = $row ? 1 : 2;
		}
		return $data;
	}

	// 帖子详情
	public function tz_row($id = 0 , $uid = 0){
		$where_arr = ['status' => 1 , 'id' => $id];
		$data = $this->mobile_model->row('forum_tz',$where_arr,'id,uid,image,pls,dzs,scs,content,ctime');
		if($data){
			$data['ctime'] = date("Y-m-d H:i:s" , $data['ctime']);
			$data['image'] = imgArr($data['image']);
			$user = $this->user_info($data['uid']);
			$data['user_name'] = $user['user_name'];
			$data['headimg'] = $user['headimg'];

			$row = $this->mobile_model->row('forum_gs',['type' => 1 , 'pid' => $id , 'uid' => $uid] , 'id');
			$data['dz_type'] = $row ? 1 : 2;
			$row = $this->mobile_model->row('forum_gs',['type' => 2 , 'pid' => $id , 'uid' => $uid] , 'id');
			$data['sc_type'] = $row ? 1 : 2;
		}
		return $data;
	}

	// 用户信息
	public function user_info($uid = ''){
		$user = $this->mobile_model->row("user" , ['id'=>$uid] , 'name,headimg');// 用户信息
		return ['user_name' => $user ? $user['name'] : '' , 'headimg' => $user ? $user['headimg'] : ''];
	}

	// 帖子话题点赞关注收藏
	public function forum_type($dao = '' , $type = 1 , $filed = '',$uid = 0){
		$id = Ipost('id');
		// 添加到点赞详情
		$where_arr = ['uid' => $uid , 'type' => $type , 'pid' => $id];
		$row = $this->mobile_model->row('forum_gs' , $where_arr , 'id');
		if($row){
			$nums = '-1';
			$this->mobile_model->delete('forum_gs' , $where_arr);
		}else{
			$nums = '+1';
			$params = $where_arr;
			$params['ctime'] = time();
			$this->mobile_model->add('forum_gs' , $params);
		}
		$res = $this->mobile_model->set($dao , ['id' => $id] , [$filed => $nums]);
		return $res;
	}

}