<?php
namespace application\controller\admin;
use application\core\Admin_controller;
class Data extends Admin_controller{
    public function __construct(){
        parent::__construct();
        // $this->load->model('admin/data_model');
	}

	public function index(){
		$data = array();
        $type = I('type') ? intval(I('type')) : 1;
        $status = I('status') ? intval(I('status')) : 1;
		$page = intval(I('page'));
		$page = $page < 1 ? 1 : $page;
		$perpage = C('perpage');
		$begin = I('begin');
		$end = I('end');
		$res = $this->create_days($begin, $end);//日期数组
		$nums = count($res['list']);
		$res['list'] = array_slice($res['list'] , ($page-1)*$perpage, $perpage);
		foreach ($res['list'] as $v) {
            $data['list'][] = $this->data_model->count_days_pvuv($v , $type);
        }
        $data['strpage'] = $this->fun_model->setpage($page, $nums , $perpage, $this->fileUrl . '/' . $type . '/' . $status);
		$data['begin'] = $res['begin'];
		$data['end'] = $res['end'];
		$data['type'] = $type;
		$data['status'] = $status;

		$dates = $pvs = $uvs = '';
		foreach ($data['list'] as $v) {
            $dates .= "'" . $v['cdate'] . "',";
            $pvs .= $v['pv'] . ",";
            $uvs .= $v['uv'] . ",";
        }
		$data['count'] = array(
            'dates' => substr($dates, 0 , -1),
            'pvs' => substr($pvs, 0 , -1),
            'uvs' => substr($uvs, 0 , -1),
        );

		// 累积
		$data['sums'] = $this->data_model->count_pvuv($type);
		apage($this->fileUrl , $data);
	}

    //从开始时间到结束时间所有的日期
    public function create_days($begin, $end) {
        $today = date("Y-m-d");
        $begin = (!empty($begin) && $begin > $today) ? $today : $begin;
        $end = (!empty($end) && $end > $today) ? $today : $end;
        if(empty($begin) && !empty($end)) 
        {
            $begin = date('Y-m-d', strtotime('-9 day', strtotime($end)));
        }
        elseif(!empty($begin) && empty($end))
        {
            $end = $today;
        } 
        elseif (empty($begin) && empty($end)) 
        {
            $begin = date('Y-m-d', strtotime('-9 day', time()));
            $end = $today;
        }
        $begin = strtotime($begin);
        $end   = strtotime($end);
        $result = array();
        if ($end < $begin) {
        	$end = $begin;
        }
        $current = $end;
        while ($current >= $begin) {
            $result[] = date("Y-m-d", $current);
            $current = strtotime("-1 day", $current);
        }
        return array('begin' => date("Y-m-d",$begin) , 'end' => date("Y-m-d",$end) , 'list' => $result);
    }
}
