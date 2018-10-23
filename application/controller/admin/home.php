<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class Home extends Admin_controller{
    public function __construct(){
        parent::__construct();
    }

	// 首页
    public function index() {
    	$data = array();
        $data = $this->lists('admin_log',array(),'id desc');
        $data['type'] = 0;
        apage($this->fileUrl , $data);
    }

    // 栏目管理
    public function column(){
        $data = $this->cate_page('admin_column' , 2);
		success_return($data);
    }

    // 提交
    public function columnSubmit(){
    	$data = array(
			'pid' => intval(Ipost('pid','菜单不能为空')),
			'name' => Ipost('name','名称不能为空'),
			'url' => Ipost('url' , '链接不能为空'),
		);
        $this->setSubmit('admin_column',$data);
    }

    // 管理员列表
    public function admin_list(){
        $data = $this->lists('admin');
        foreach($data['list'] as $k => $v){
            $row = $this->row('admin_role',array('id'=>$v['grade']),'name');
            $data['list'][$k]['grade'] = !empty($row)?$row['name']:'';
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    public function admin_role_all(){
        $data = $this->cate_page('admin_role');
        success_return($data);
    }

    // 提交
    public function adminSubmit(){
        $data = array(
            'grade' => intval(Ipost('grade')),
            'nickname' => Ipost('nickname','名称不能为空'),
        );
        $id = Ipost('id');
        $account = Ipost("account",'账号不能为空');
        $row = $this->row('admin',['id <> ' => $id,'account'=>$account],'id');
        if(!empty($row)){
            json_return(array('ret' => -1 , 'msg' => '该账号已被使用'));
        }
        $data['account'] = $account;
        $password = Ipost('password','密码不能为空');
        $data['password'] = md5($password);
        $this->setSubmit('admin',$data);
    }

    // 管理员列表
    public function role_list(){
        $data = $this->lists('admin_role');
        foreach($data['list'] as $k => $v){
            $data['list'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
        }
        success_return($data);
    }

    // 修改视图
	/*public function role_set(){
        $data = $this->setInfo('admin_role');
        $res = $this->all('admin_column',array('status'=>1));
        $data['list'] = recursive($res , 2);

        $cid = isset($data['info']['cid'])?$data['info']['cid']:'';
        $data['cid'] = !empty($cid)?explode("|" , $cid):'';
        apage($this->fileUrl , $data);
	}*/

    public function roleSubmit(){
        $data = array(
            'name' => Ipost('name','角色名称不能为空'),
            'super' => Ipost('super'),
        );
        $cid = Ipost('cid');
        $data['cid'] = !empty($cid) ? implode("|", $cid) : 0;
        if($this->admin_id == Ipost('id')){
            $_SESSION['admin_permission'] = $data['cid'];
            $_SESSION['admin_super'] = $data['super'];
        }
        // S('admin/column/', null);
        $this->setSubmit('admin_role',$data);
    }

    // 系统设置
    public function account($type = 1){
        // $this->admin_id
        $data['info'] = $this->row('admin',['id'=>1]);
        $data['info']['logo'] = imgUrl($data['info']['logo']);
        success_return($data);
    }

    public function accountSubmit(){
        $type = Ipost('type');
        if($type == 1){
            $data = array(
                'nickname' => Ipost('nickname'),
                'color' => Ipost('color'),
                'logo' => $this->files->basePost('logo','封面不能为空'),
            );
            $_SESSION['admin_nickname'] = $data['nickname'];
            $_SESSION['admin_color'] = $data['color'];
            $_SESSION['admin_logo'] = $data['logo'];
        }else{
            $data = array(
                'password' => md5(Ipost('password'))
            );
        }
        $this->setSubmit('admin',$data);
    }

    // 查看日志
    public function log(){
        $type = I('type') ? I('type') : 1;
        $name = C('log_arr');
        $data['times'] = I('times') ? I('times') : date('Y-m-d');
        $data['list'] = l_log($name[$type] , str_replace("-" , "" , $data['times']));
        $data['type'] = $type;
        apage($this->fileUrl , $data);
    }

    // 系统设置
    public function system(){
        $data['info'] = $this->row("system");
        success_return($data);
    }

    public function systemSubmit(){
        $data = array(
            'title' => Ipost('title'),
            'login_type' => Ipost('login_type'),
            'login_all' => Ipost('login_all'),
            'data_count' => Ipost('data_count'),
        );
        $this->setSubmit('system',$data);
    }

    // 清除缓存
    public function cacheDelete(){
        $res = S('admin/' , null);
        $res ? json_return(array('ret' => 1 , 'msg' => '清除缓存成功')) : json_return(array('ret' => -1 , 'msg' => '清除缓存失败'));
    }
}