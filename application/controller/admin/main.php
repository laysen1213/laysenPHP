<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class Main extends Admin_controller{
	public function __construct(){
		parent::__construct();
	}

	// 首页
    public function column(){
        $data['list'] = $this->alls('admin_column',['status'=>1,'pid'=>0]);
        foreach($data['list'] as $k => $v){
            $child = $this->alls('admin_column',['status'=>1,'pid'=>$v['id']]);
            $data['list'][$k]['child'] = $child;
        }
        success_return($data);
    }

	// 通用删除
	public function delAjax(){
		$id = Ipost("id",'id不能为空');//Id
		$dao = Ipost("dao",'dao不能为空');//数据表
        $id_arr = explode(",", $id);
        if($id_arr && is_array($id_arr)){
            foreach($id_arr as $v){
                $this->delete($dao , ['id'=>$v]);
            }
            success_return();
        }
        error_return("删除失败");
	}

	// 通用排序
	public function rankAjax(){
		$id = Ipost("id",'id不能为空');//Id
		$dao = Ipost("dao",'dao不能为空');//数据表
		$val = Ipost("val",'val不能为空');//修改值
		$res = $this->edit($dao , ['id'=>$id] , ['rank' => $val]);
		success_return();
	}

	// 通用开关
	public function switchAjax(){
		$id = Ipost("id",'id不能为空');//Id
		$dao = Ipost("dao",'dao不能为空');//数据表
		$val = Ipost("val",'val不能为空');//修改值
		$field = Ipost("field",'field不能为空');//修改字段
		$res = $this->edit($dao , ['id' => $id] , [$field => $val]);
		success_return();
	}

	// 分页列表
	public function pageList(){
		$id = Ipost("id",'id不能为空');//栏目Id
		$column = $this->model->row("admin_column" , ['id'=>$id]);
		empty($column) && error_return("参数错误");
		$dao = $column['dao'];//数据表名
		$grade = $column['pageType'];//页面等级 1.普通分页  >2递归页面等级
		$order = $column['rankStatus'] == 1 ? 'rank asc,id desc' : 'id desc';//排序

		$where_arr = [];
		if($grade == 1){
			// 普通分页
			$page = Ipost('page');
			$data = $this->model->page($dao , $where_arr , $page , 10 , $order);
		}else{
			// 递归页
			$res = $this->model->all($dao , $where_arr , $order);
			$data['list'] = recursive($res , $grade);
		}
		foreach($data['list'] as $k => $v){
			$data['list'][$k]['ctime'] = date("Y-m-d H:i:s",$v['ctime']);
		}
		$data['grade'] = $grade;
		success_return($data);
	}

	// 修改页详情
	public function setInfo(){
		$a = C('setType');
		$id = Ipost("id");
		$column_id = intval(Ipost("column_id",'id不能为空'));//栏目Id
		$column = $this->model->row("admin_column" , ['id'=>$column_id]);
		empty($column) && error_return("参数错误");
		$dao = $column['dao'];//数据表名

		// 字段列表
		$data['all'] = $this->model->all("admin_field" , ['column_id'=>$column_id,'setStatus'=>1],'rank asc,id desc');
		foreach($data['all'] as $k => $v){
			if($v['setSource']){
				// $data['all'][$k]['setSource'] = C($v['setSource']);
			}
		}
		$data['info'] = [];
		if($id){
			$data['info'] = $this->model->row($dao,['id'=>$id]);
		}
		success_return($data);
	}

	// 提交
	public function submitAction(){
		$id = Ipost('id');
		$params = Ipost();
		$dao = 'admin_field';
		$params['column_id'] = 64;
		if($id){
			$res = $this->model->edit($dao , ['id'=>$id] , $params);
		}else{
			$params['ctime'] = time();
			$res = $this->model->add($dao , $params);
		}
        success_return();
	}
}