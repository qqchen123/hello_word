<?php
/**
 * 小程序--评估专员、机构、业务经理审核小程序报单信息的界面搭建与数据处理的控制器
 * Created by PhpStorm.
 * User: a
 * Date: 2019/1/9
 * Time: 9:43 AM
 */
class MiniProgramCheck extends Admin_Controller {
	public function __construct()
	{
		parent::__construct();
//		$this->output->enable_profiler(TRUE);
		$this->load->model('MiniProgramCheck_model');
	}
	/**
	 * 评估专员-处理-页面
	 */
	public function mini_pinggu_deal_page()
	{
		$res = $this->MiniProgramCheck_model->get_look_pg_data();
		if(isset($res['code']) && $res['code'] ==0){
			$this->showpage('fms/miniprogram/mini_404', ['look404' => $res]);
			return;
		}
		$this->showpage('fms/miniprogram/mini_pinggu_look_page', ['look' => $res]);
	}

	/**
	 * 评估专员-查看-页面
	 */
	public function mini_pinggu_look_page()
	{
		$res = $this->MiniProgramCheck_model->get_look_pg_data();
		$res['look'] = 1;
		$this->showpage('fms/miniprogram/mini_pinggu_look_page', ['look' => $res]);
	}
	/**
	 * 评估专员订单列表页面
	 */
	public function mini_pinggu_order_list_page()
	{
		$this->showpage('fms/miniprogram/mini_pinggu_order_list_page');
	}
	/**
	 * 评估专员订单列表数据
	 */
	public function mini_pinggu_order_list_data()
	{
		$res = $this->MiniProgramCheck_model->get_pg_list_data();
		echo json_encode($res);die;
	}
	/**
	 * 机构人员查看订单管理页面
	 */
	public function mini_jigou_look_orderlist_page()
	{
		$this->showpage('fms/mini_jigou_look_orderlist_page');
	}
	/**
	 * 机构人员处理页面
	 */
	public function mini_jigou_dealdetail_page()
	{
		$this->showpage('fms/mini_jigou_dealdetail_page');
	}
	/**
	 * 机构人员查看详情页面
	 */
	public function mini_jigou_look_detail_page()
	{
		$this->showpage('fms/mini_jigou_look_detail_page');
	}
	/**
	 * 风控订单管理页面
	 */
	public function mini_fengkong_order_page()
	{
		$this->showpage('fms/mini_jigou_look_detail_page');
	}

	/**
	 * 评估处理
	 */
	public function mini_pinggu_deal()
	{
		$res = $this->MiniProgramCheck_model->deal();
		if ($res){
			echo json_encode(['code'=>1,'msg'=>'操作成功！']);
			die;
		}else{
			echo json_encode(['code'=>0,'msg'=>'操作失败！']);
			die;
		}
	}

	
	
	
	
	







}