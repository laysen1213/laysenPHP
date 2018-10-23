<?php
namespace application\controller\admin;
use application\core\Admin_controller;
use system\lib\verify;
class Login extends Admin_controller{
	public function __construct(){
		parent::__construct();
    }
    
    //登录验证码
    public function verify() {
        ob_clean();
        verify::create();
		$_SESSION['verify_admin'] = $_SESSION['verify'];
        unset($_SESSION['verify']);
		exit;
    }
    
    // 登录提交
    public function submit() {
        $name = Ipost('name','账号不能为空');
        $password = Ipost('password','密码不能为空');
		$verify = Ipost('code','验证码不能为空');
		if(md5(strtolower($verify)) != strtolower($_SESSION['verify_admin'])) {
            $_SESSION['verify_admin'] = '';
            error_return('验证码错误' , -2);
        }
        $_SESSION['verify_admin'] = '';
        $res = $this->model->row('admin',array('account' => $name , 'status' => 1));
		if(!$res || $res['password'] !== md5($password)){
            error_return('账号或密码错误' , -2);
		}
        // $permission = $this->model->row('pt_admin_role' , array('id'=>$admin_info['grade']) , "cid,super");
        $_SESSION['admin_id'] = $res['id'];
        // $_SESSION['admin_name'] = $name;
        // $_SESSION['admin_account'] = $name;
		// $_SESSION['admin_nickname'] = $admin_info['nickname'];
        // $_SESSION['admin_permission'] = $permission['cid'];
        // $_SESSION['admin_super'] = $permission['super'];
        // $_SESSION['admin_color'] = $admin_info['color'];
        // $_SESSION['admin_logo'] = $admin_info['logo'];

		$this->model->add('admin_log' , array(
            'admin_id'=>$res['id'],
            'admin_name'=>$name,
            'remark'=>'用户：'.$res['nickname'].'-登录成功！',
            'ip' => getonlineip(),
            'ctime' => time()
        ));
        success_return();
    }

    public function logout() {
        if($_SESSION['admin_id'] == '') {
            header('location:'.U('main/index'));exit;
        }
        if(isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])) {
            $this->model->add('admin_log' , array(
                'admin_id' => $_SESSION['admin_id'],
                'admin_name' => $_SESSION['admin_name'],
                'remark'=>'用户：'.$_SESSION['admin_nickname'].'-退出成功',
                'ip' => getonlineip(),
                'ctime' => time()
            ));
        }
        $_SESSION['admin_id'] = '';
  		$_SESSION['admin_name'] = '';
        $_SESSION['admin_nickname'] = '';
        $_SESSION['admin_permission'] = '';
        $_SESSION['admin_super'] = '';
        $_SESSION['admin_color'] = '';
        $_SESSION['admin_logo'] = '';
        $_SESSION['admin_account'] = '';
		header('location:'.U('main/index'));exit;
	}
}