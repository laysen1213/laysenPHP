<?php
namespace application\controller\api;
use application\core\Api_controller;
// use application\model\api\person_model;
class Person extends Api_controller{
    public function __construct(){
        parent::__construct();
        $this->uid = 1;
        $this->appid = 'wx4230e8a67b05da94';
        $this->appsecret = '33b19518d57084fbb452e82d460946ad';
        // $this->person_model = new person_model();
    }
    
    //登录
    public function login() {
        $code = trim(Ipost('code'));
        if (empty($code)) {
            json_return(array('ret' => 99, 'msg' => 'code empty'));
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$this->appid&secret=$this->appsecret&js_code=$code&grant_type=authorization_code";
        $res = file_get_contents($url);
        $arr = json_decode($res, true);
        if (isset($arr['openid']) && $arr['openid']) {
            $arr['login_key'] = md5(microtime(true) . $arr['session_key'] . $arr['openid']);
            $arr['code'] = $code;
            $arr['ctime'] = time();
            $arr['expires_in'] = time() + 300;
            $login_info = $this->mobile_model->row('xcx_login',array('openid'=>$arr['openid']));
            if (empty($login_info)) {
                $this->mobile_model->add('xcx_login' , $arr);
            }else {
                $this->mobile_model->edit('xcx_login',array(
                    'openid' => $arr['openid'] , 'login_key '=> $arr['login_key'],'code '=> $arr['code'],
                    'session_key '=> $arr['session_key'],'expires_in '=> $arr['expires_in']
                ) , array('openid'=>$arr['openid']));
            }
            json_return(array('ret' => 1, 'msg' => '登录成功', 'login_key' => $arr['login_key']));
            
        } else {
            json_return(array('ret' => -1, 'msg' => '配置参数错误'));
        }
    }

    public function getUser(){ 
        $login_key = trim(Ipost('login_key'));
        $params = array(
            'nickname' => trim(Ipost('nickName')),
            'gender' => trim(Ipost('gender')),
            'country' => trim(Ipost('country')),
            'province' => trim(Ipost('province')),
            'city' => trim(Ipost('city')),
            'headimg' => trim(Ipost('avatarUrl')),
        );
        $login_key_info = $this->mobile_model->row('xcx_login',array('login_key'=>$login_key));
        if(empty($login_key_info)){
            json_return(array('ret' => 99, 'msg' => 'login_key error'));
        } 
        $this->openid = $login_key_info['openid'];
        //存入用户信息
        $wxuser_info = $this->mobile_model->row('wx_user',array('openid'=>$this->openid));
        if (empty($wxuser_info)) {
            $params['openid'] = $this->openid;
            $params['ctime'] = time();
            $res = $this->mobile_model->add('wx_user', $params);
        }
        json_return(array('ret' => 1, 'msg' => '登录成功'));
    }

    //登录验证
    public function login_check($login_key) {
        //判断是否合法
        if ($login_key) {
            $login_key_info = $this->mobile_model->row('xcx_login',array('login_key'=>$login_key));
            if(empty($login_key_info)){
                json_return(array('ret' => 99, 'msg' => 'login_key error'));
            } 

            if($login_key_info['expires_in'] < time()){
                json_return(array('ret' => 99, 'msg' => '已过期，请登录'));
            }else{
                $this->openid = $login_key_info['openid'];
                $wxuser = $this->mobile_model->row('wx_user',array('openid'=>$this->openid));
                if ($wxuser) {
                    $this->uid = $wxuser['uid'];
                    return true;
                } else {
                    json_return(array('ret' => 99, 'msg' => '请登录'));
                }
            }
        }
        json_return(array('ret' => 99, 'msg' => '请登录'));
    }

}
