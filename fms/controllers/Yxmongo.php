<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2018/10/30
 * Time: 9:59 AM
 */

$env = 'local';
if ('local' == $env)
{
	require_once "./models/PHPExcel.php";
	require('../vendor/autoload.php');
} else
{
	require('/www/guzzle-master/vendor/autoload.php');
	require('/www/PHPWord-develop/vendor/autoload.php');
}

//银信
class Yxmongo extends Admin_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Yxmongo_model');//YxBill_model
		$this->load->model('yx/YxBill_model');//YxBill_model
	}

//未完结的借款
	public function get_yx_loan_nofinish()
	{
		$account = $this->input->post('account');
		$nofinish = $this->Yxmongo_model->get_yx_loan_nofinish($account);
		$arr_data['rows'] = $nofinish;
		$arr_data['total'] = count($nofinish);
		echo json_encode($arr_data);
		die;
	}

	/**
	 * 充值提现汇总--电子账单
	 */
	public function show_rw_collect()
	{
		$this->showpage('fms/rw_collect_page');
	}

	/**
	 *借款人及投资人列表
	 */
	public function show_borrower_investor_list()
	{
		$this->showpage('fms/borrower_investor_list_page');
	}

	/**
	 * 借款人详情
	 */
	public function show_borrower_detailt()
	{
		$this->showpage('fms/borrower_detail_page');
	}

	/**
	 * 借款人详情副本
	 */
	public function show_borrower_detail_copy()
	{
		$this->showpage('fms/borrow_detail_copy_page');
	}

	/**
	 * 投资人详情
	 */
	public function show_investor()
	{
		$this->showpage('fms/investor_page');
	}

	/**
	 * 我的借款——点开标题——出借记录
	 */
	public function show_lend_log()
	{
		$this->showpage('fms/lend_log_page');
	}

	/**
	 * 我的借款——点开标题——还款计划
	 */
	public function show_repayment_plan()
	{
		$this->showpage('fms/repayment_plan_page');
	}

	/**
	 * 我的借款——未完结
	 */
	public function show_loan_nofinish()
	{
		$this->showpage('fms/loan_nofinish_page');
	}

	/**
	 * 我的借款——已完结
	 */
	public function show_loan_finish()
	{
		$this->showpage('fms/loan_finish_page');
	}

	/**
	 * 账户设置信息汇总
	 */
	public function show_account_detail()
	{
		$this->showpage('fms/account_detail_page');
	}

	/**
	 * 客户账户信息总览
	 */
	public function base_user_yx_account()
	{
		$this->showpage('fms/base_user_yx_account');
	}

	/**
	 * 银信账户--mysql表
	 */
	public function show_mysql_yx_account()
	{
		$this->showpage('fms/mysql_yx_account');
	}

	/**
	 * 客户借款信息
	 */
	public function show_kh_borrow_msg()
	{
		$account = $this->input->get('account');
		$this->showpage('fms/kh_borrow_msg', ['account' => $account]);
	}

	//客户信息详情管理
	public function kh_detail_info()
	{
		$account = $this->input->get('account');

		$data = [];
		$this->load->model('yx/YxAccountAbout_model', 'yxaccountabout');
//        $data = $this->yxaccountabout->get_account_about($account);
		$data = $this->Yxmongo_model->get_kh_detail_info($account);
//		print_r($data);die;
		$this->showpage('fms/kh_detail_info', ['account' => $account, 'about' => $data]);
	}

	//银信申请信息管理
	public function yx_apply_info()
	{
		$this->showpage('fms/yx_apply_info');
	}

	//银信申请信息管理
	public function base_user_info2()
	{
		$this->showpage('fms/base_user_info2');
	}

	//银信客户在贷信息管理
	public function yx_loaning_info()
	{
		$this->showpage('fms/yx_loaning_info');
	}

	//客户到期信息管理
	public function yx_warning_info()
	{
		$this->showpage('fms/yx_warning_info');
	}

	//客户借款信息管理
	public function kh_borrow_info()
	{
		$account = $this->input->get('account');
		$this->showpage('fms/kh_borrow_info', ['account' => $account]);
	}

	//电子账单
	public function el_bills()
	{
		$account = $this->input->get('account');
		$this->showpage('fms/el_bills', ['account' => $account]);
	}

	//借款中详情--已完成
	public function borrowing_detail()
	{
		$account = $this->input->get('account');
		$title = $this->input->get('title');
		$contract = $this->Yxmongo_model->get_finish_contract($account, $title);
		$this->showpage('fms/finish_info', ['account' => $account, 'title' => $title, 'contract' => $contract]);
	}

	//借款中详情
	public function finish_detail()
	{
		$account = $this->input->get('account');
		$this->showpage('fms/borrowing_detail', ['account' => $account]);
	}

	/**
	 * @name 获取还款中信息
	 */
	public function get_repaying_data()
	{
		$account = $this->input->post('account');
		$this->load->model('yx/YxRepaying_model', 'yxrepaying');
		$data = $this->yxrepaying->get_record($account);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}

	public function get_repaying_plan()
	{
		$account = $this->input->post('account');
		$loan_title = $this->input->post('loan_title');
		$this->load->model('yx/YxRepayingPlan_model', 'yxrepayingplan');
		$data = $this->yxrepayingplan->get_record($account, $loan_title);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}

	//获取银信账户信息--mysql表
	public function get_yx_account_mysql2()
	{
		$sort = $this->input->post('sort');
		$order = $this->input->post('order');
		$status = $this->input->post('status');
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$back_end_date = $this->input->post('back_end_date');
		$like['a.channel'] = $this->input->post('channel');
		$like['a.name'] = $this->input->post('name');
		$like['b.reg_phone'] = $this->input->post('reg_phone');
		$like['b.account'] = $this->input->post('yx_account');
		$date['start_date'] = $this->input->post('start_date');
		$date['end_date'] = $this->input->post('end_date');
		$first = $rows * ($page - 1);
		$where = '';
		$yx_mysql_res = $this->Yxmongo_model->get_yx_account_mysql_data2(
			$rows,
			$first,
			$like,
			$where,
			$date,
			$status,
			$sort,
			$order,
			$back_end_date
		);
//        print_r($yx_mysql_res);die;
		Slog::log('标记');
		//这里用来关联其它表的数据
		//获取借款信息 借款标题 借款年化
		$temp = array_values($yx_mysql_res['rows']);
		$this->load->model('yx/YxRepayingOrderDetail_model', 'yxrepayingorderdetail_model');
		$load_repay_detail_loan = [];
		foreach ($temp as $key => $value) {
			if (isset($value['status']) && '还款中' == $value['status']) {
				$ret = $this->yxrepayingorderdetail_model->get_order_detail($value['yx_account'], $value['loan_title']);
				$temp[$key] = array_merge($value, $ret);
			}
		}
		//获取借款到期时间 find_by_userinfo($account)
		$this->load->model('yx/YxInMoneyDetail_model', 'yxinmoneydetail_model');
		$load_repay_detail_loan = [];
		foreach ($temp as $key => $value) {
			if (isset($value['status']) && '还款中' == $value['status']) {
				$ret = $this->yxinmoneydetail_model->find_last_record($value['yx_account']);
				$temp[$key] = array_merge($value, $ret);
			}
		}

		$yx_mysql_res['rows'] = $temp;
		echo json_encode($yx_mysql_res);
		die;
	}

	//获取银信账户信息--mysql表
	public function get_yx_account_mysql()
	{
//		print_r($_POST);die;
		$sort = $this->input->post('sort');
		$order = $this->input->post('order');
		$status = $this->input->post('status');
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$back_end_date = $this->input->post('back_end_date');
		$like['a.channel'] = $this->input->post('channel');
		$like['a.name'] = $this->input->post('name');
		$like['b.reg_phone'] = $this->input->post('reg_phone');
		$like['b.account'] = $this->input->post('yx_account');
		$date['start_date'] = $this->input->post('start_date');
		$date['end_date'] = $this->input->post('end_date');
		$first = $rows * ($page - 1);
		$where = '';
		$yx_mysql_res = $this->Yxmongo_model->get_yx_account_mysql_data(
			$rows,
			$first,
			$like,
			$where,
			$date,
			$status,
			$sort,
			$order,
			$back_end_date
		);
		$yx_mysql_res['rows'] = array_values($yx_mysql_res['rows']);
		echo json_encode($yx_mysql_res);
		die;
	}

	/**
	 * 获取单个用户信息
	 */
	public function get_one_yx_account()
	{
		$account = $this->input->post('account');
		$oneres = $this->Yxmongo_model->get_one_yx_account($account);
		echo json_encode($oneres);
		die;
	}

	//get_one_yx_account_mysql
	public function get_one_yx_account_mysql()
	{
//        print_r($_POST);die;
		$like = $this->input->post('like');
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$account = $this->input->post('account');
//        $where = ['account'=>'YX13916868765'];
		$where = ['a.yx_account' => $account];
		$first = $rows * ($page - 1);
		$yx_mysql_res = $this->Yxmongo_model->get_yx_account_mysql_data($rows, $first, $like, $where);
		echo json_encode($yx_mysql_res);
		die;
	}

	/**
	 * 电子账单数据
	 */
	public function get_el_bill_data()
	{
		$account = $this->input->post('account');
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$first = $rows * ($page - 1);
		$yxres = $this->YxBill_model->get_el_bill_data($account, $first, $rows);
		echo json_encode($yxres);
		die;
	}

	/**
	 * @name 修改银信账户密码
	 * @request POST
	 */
	public function change_pwd()
	{
		$account = $this->input->post('account');
		$pwd = $this->input->post('pwd');
		$this->load->model('yx/YxAccount_model', 'yxaccount_model');
		$ret = $this->yxaccount_model->change_pwd($account, $pwd);
		if ($ret)
		{
			$code = 0;
			$msg = '修改成功';
		} else
		{
			$code = -1;
			$msg = '修改失败';
		}
		echo json_encode(['code' => $code, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
		exit;
	}

	/**
	 * 已完结的借款
	 */
	public function get_finished_by_mysql()
	{
		$account = $this->input->post('account');
		$page = $this->input->post('page');
		$rows = $this->input->post('rows');
		$first = $rows * ($page - 1);
		$res = $this->Yxmongo_model->get_finished_by_mysql($rows, $first, $account);
		echo json_encode($res);
		die;
	}

	/**
	 * 获取一条已完结的借款
	 */
	public function get_one_finished_by_mysql()
	{
		$account = $this->input->post('account');
		$title = $this->input->post('title');
		$res = $this->Yxmongo_model->get_one_finished_by_mysql($account, $title);
		echo json_encode($res);
		die;
	}

	/**
	 * 还款计划
	 */
	public function back_plan()
	{
		$account = $this->input->post('account');
		$title = $this->input->post('title');
		$res = $this->Yxmongo_model->back_plan($account, $title);
		echo json_encode($res);
		die;
	}

	/**
	 * 获取所有channel 渠道
	 */
	public function get_channel_data()
	{
		$ch_res = $this->Yxmongo_model->get_channel_data();
		echo json_encode(array_values($ch_res));
		die;
	}

	public function bed_date()
	{
		$bed_where = '';
		$back_end_date = 'one_month';
		if ($back_end_date == 'one_day')
		{
			$bed_where = 'where next_repay_time >= \' '.date('Y-m-d').' \' and next_repay_time <= \''.date(
					"Y-m-d",
					strtotime("+1 day")
				).'\'';
		} elseif ($back_end_date == 'one_week')
		{
			$bed_where = $this->do_where_date($bed_where, 7);
		} elseif ($back_end_date == 'one_month')
		{
			$bed_where = $this->do_where_date($bed_where, 30);
		} else
		{
			$bed_where = 'null';
		}
		print_r($bed_where);
		die;
	}

	/**
	 * @param $bed_where
	 * @return string
	 */
	private function do_where_date($bed_where, $max_num)
	{
		$bed_where .= ' and next_repay_time >= \''.date('Y-m-d').'\' ';
		$bed_where .= ' and next_repay_time <= \''.date('Y-m-d', strtotime("+$max_num day")).'\' ';

		return $bed_where;
	}

	/**
	 * 导出银信数据生成excel
	 */
	public function export_yx_excel()
	{
		$post_status = $this->input->get('status');
		if (empty($post_status))
		{
			echo json_encode(['code' => 0, 'msg' => '参数不能为空！']);
			exit();
		}
		include_once('./models/PHPExcel.php');
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$yx_data = $this->Yxmongo_model->get_all_type_data($post_status);

		if ('borrowing' == $post_status) {
			$yx_data[0][] = '开始时间';
			$yx_data[0][] = '到期时间';
			$yx_data[0][] = '剩余时间';

			Slog::log('标记');
			//这里用来关联其它表的数据
			//获取借款信息 借款标题 借款年化
			$temp = $yx_data;
			$this->load->model('yx/YxRepayingOrderDetail_model', 'yxrepayingorderdetail_model');
			$load_repay_detail_loan = [];
			foreach ($temp as $key => $value) {
				if (0 == $key) {continue;}
				if (isset($value['status']) && '还款中' == $value['status']) {
					$ret = $this->yxrepayingorderdetail_model->get_order_detail($value['yx_account'], $value['loan_title']);
					unset($temp[$key]['loan_title']);
				}
			}
			//获取借款到期时间 find_by_userinfo($account)
			$this->load->model('yx/YxInMoneyDetail_model', 'yxinmoneydetail_model');
			$load_repay_detail_loan = [];
			foreach ($temp as $key => $value) {
				if (0 == $key) {continue;}
				if (isset($value['status']) && '还款中' == $value['status']) {
					$ret = $this->yxinmoneydetail_model->find_last_record($value['yx_account']);
					$temp[$key][] = $ret['in_date'];
					$temp[$key][] = $ret['expire_time'];
					$diff_time = floor(
						(
							strtotime($ret['expire_time']) - strtotime(date('Y-m-d', time()))
						)/86400);
					Slog::log($diff_time);
					if (($diff_time/30) > 0) {
						$temp[$key][] = floor($diff_time/30) . '月' . floor($diff_time%30) . '日';
					} else {
						$temp[$key][] = ceil($diff_time/30) . '月' . floor($diff_time%30) . '日';
					}
					
				}
			}
			$yx_data = $temp;
		}

		$objPHPExcel->getActiveSheet()->fromArray($yx_data, NULL, 'A1');
//		$objPHPExcel->getActiveSheet()
//			->setCellValue("E2", '=SUM(A1:C1）/3');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="filename.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997$hourValueGMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		$objPHPExcel->disconnectWorksheets();
	}

}