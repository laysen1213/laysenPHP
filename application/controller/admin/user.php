<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class User extends Admin_controller{
    public function __construct(){
        parent::__construct();
    }

	// 首页
    public function index() {
        $search = Ipost('search');
        $where_arr = array();
        $search && $where_arr["(name like '%{$search}%' or phone like '%{$search}%')"] = NULL;
        $data = $this->lists('user' , $where_arr , '*' , 'id desc');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['headimg'] = userHeadimg($v['headimg']);
            $data['list'][$k]['address'] = $v['province'].$v['city'];
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }
}