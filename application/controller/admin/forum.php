<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class Forum extends Admin_controller{
    public function __construct(){
        parent::__construct();
    }

	// 话题
    public function ht_list() {
    	$data = array();
        $data = $this->lists('forum_ht');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['cover'] = imgUrl($v['cover']);
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            $data['list'][$k]['gzs'] = par($v['gzs'])['nums'];
        }
        success_return($data);
    }

    public function htSubmit(){
        $data = array(
            'name' => Ipost('name','名称不能为空'),
            'cover' => $this->files->basePost('cover','封面不能为空'),
            'content' => $_POST['content']
        );
        $this->setSubmit('forum_ht',$data);
    }

    // 帖子
    public function tz_list() {
        $ht_id = Ipost('id');
        $where_arr = array();
        $ht_id && $where_arr['ht_id'] = $ht_id;
        $data = $this->lists('forum_tz' , $where_arr);
        foreach($data['list'] as $k => $v){
            $row = $this->row("forum_ht" , ['id' => $v['ht_id']] , 'name');
            $data['list'][$k]['ht_name'] = $row ? $row['name'] : '';
            $data['list'][$k]['image'] = imgArr($v['image']);
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            $data['list'][$k]['dzs'] = par($v['dzs'])['nums'];
            $data['list'][$k]['scs'] = par($v['scs'])['nums'];
        }
        success_return($data);
    }

    // 评论
    public function comment_list() {
        $tz_id = Ipost('id');
        $where_arr = array('comment_id' => 0);
        $tz_id && $where_arr['tz_id'] = $tz_id;
        $data = $this->lists('forum_comment' , $where_arr , '*' ,'id desc');
        foreach($data['list'] as $k => $v){
            $row = $this->row("forum_tz" , array('id' => $v['tz_id']));
            $data['list'][$k]['tz_name'] = $row ? $row['title'] : '';

            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            $data['list'][$k]['dzs'] = par($v['dzs'])['nums'];
        }
        success_return($data);
    }
}