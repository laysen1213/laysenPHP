<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class Blog extends Admin_controller{
    public function __construct(){
        parent::__construct();
    }

	// 首页
    public function index() {
        $data = $this->lists('blog' , [] , 'id,title,cid,status,cover,is_recommend,rank,ctime,intro,seo_title,seo_keyword,seo_describe');
        foreach($data['list'] as $k => $v){
            $row = $this->row("blog_cate" , ['id' => $v['cid']]);
            $data['list'][$k]['cate_name'] = $row ? $row['name'] : '';
            $data['list'][$k]['cover'] = imgUrl($v['cover']);
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    public function blogSubmit(){
        $data = array(
            'title' => Ipost('title','标题不能为空'),
            'cover' => $this->files->basePost('cover','封面不能为空'),
            'cid' => Ipost('cid','请选择分类'),
            'intro' => Ipost('intro'),
            'content' => $_POST['content'],
            'seo_title' => Ipost('seo_title'),
            'seo_keyword' => Ipost('seo_keyword'),
            'seo_describe' => Ipost('seo_describe')
        );
        $this->setSubmit('blog',$data);
    }

    // 分类
    public function blogCateAll() {
        $data = $this->cate_page('blog_cate');
        success_return($data);
    }

    // 首页
    public function blogCate() {
        $data = $this->lists('blog_cate');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    // 提交
    public function blogCateSubmit(){
        $data = array(
            'name' => Ipost('name','名称不能为空'),
            'pid' => Ipost('pid')
        );
        $this->setSubmit('blog_cate',$data);
    }

    // 留言
    public function message() {
        $data = $this->lists('blog_message');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    public function blogMessageSubmit(){
        $data = array(
            'hf' => Ipost('hf','回复内容不能为空'),
        );
        $this->setSubmit('blog_message',$data);
    }

    // 歌曲列表
    public function music() {
        $data = array();
        $data = $this->lists('music');
        foreach($data['list'] as $k => $v){
            $row = $this->row("music_singer",array('id'=>$v['sid']));
            $data['list'][$k]['singer'] = $row ? $row['name'] : '';
            $data['list'][$k]['cover'] = imgUrl($v['cover']);
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    public function musicSubmit(){
        $data = array(
            'sid' => Ipost('sid'),
            'title' => Ipost('title','名称不能为空'),
            'cover' => $this->files->basePost('cover','封面不能为空'),
            // 'music' => Ipost('music','音频不能为空'),
            'intro' => Ipost('intro')
        );
        $this->setSubmit('music',$data);
    }

    // 歌手列表
    public function musicSingerAll() {
        $data = $this->cate_page('music_singer');
        success_return($data);
    }

    // 歌手列表
    public function musicSinger() {
        $data = array();
        $data = $this->lists('music_singer');
        foreach($data['list'] as $k => $v){
            $row = $this->row("music_cate",array('id'=>$v['cid']));
            $data['list'][$k]['cate_name'] = $row ? $row['name'] : '';
            $data['list'][$k]['cover'] = imgUrl($v['cover']);
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    public function musicSingerSubmit(){
        $data = array(
            'cid' => Ipost('cid'),
            'name' => Ipost('name','名称不能为空'),
            'cover' => $this->files->basePost('cover','封面不能为空'),
            'intro' => Ipost('intro')
        );
        $this->setSubmit('music_singer',$data);
    }

    // 歌曲分类
    public function musicCate() {
        $data = $this->lists('music_cate');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    // 歌手列表
    public function musicCateAll() {
        $data = $this->cate_page('music_cate');
        success_return($data);
    }

    public function musicCateSubmit(){
        $data = array(
            'name' => Ipost('name','名称不能为空'),
        );
        $this->setSubmit('music_cate',$data);
    }

    // 歌手列表
    public function album() {
        $data = $this->lists('blog_album');
        foreach($data['list'] as $k => $v){
            $row = $this->row("blog_album_cate",array('id'=>$v['cid']));
            $data['list'][$k]['cate_name'] = $row ? $row['name'] : '';
            $data['list'][$k]['cover'] = imgUrl($v['cover']);
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    // 修改视图
    public function albumCateList(){
        $data['list'] = $this->alls('blog_album_cate');
        success_return($data);
    }

    public function albumSubmit(){
        $data = array(
            'cid' => Ipost('cid',"请选择分类"),
            'title' => Ipost('title','名称不能为空'),
            'cover' => $this->files->basePost('cover','相片不能为空'),
        );
        $this->setSubmit('blog_album',$data);
    }

    // 相册分类
    public function albumCate() {
        $data = array();
        $data = $this->lists('blog_album_cate');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    public function albumCateSubmit(){
        $data = array(
            'name' => Ipost('name','名称不能为空'),
        );
        $this->setSubmit('blog_album_cate',$data);
    }
}