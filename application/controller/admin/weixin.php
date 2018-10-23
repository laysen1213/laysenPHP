<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class Weixin extends Admin_controller{
    public function __construct(){
        parent::__construct();
    }

	// 系统设置
    public function system(){
        $data['info'] = $this->row("wx_api");
        success_return($data);
    }

    public function systemSubmit(){
        $data = array(
            'appid' => Ipost('appid'),
            'appsecret' => Ipost('appsecret'),
            'mchid' => Ipost('mchid'),
            'paykey' => Ipost('paykey'),
            'wx_share' => Ipost('wx_share'),
        );
        $this->setSubmit("wx_api",$data);
    }

    // 分享设置
    /*public function share(){
        $data['info'] = $this->row("wx_api");
        apage($this->fileUrl , $data);
    }*/

    public function shareSubmit(){
        $data = array(
            'share_title' => Ipost('share_title'),
            'share_desc' => Ipost('share_desc'),
            'share_logo' => Ipost('share_logo'),
            'share_url' => Ipost('share_url'),
        );
        $res = $this->set("wx_api",$data);
        json_return($res);
    }
}