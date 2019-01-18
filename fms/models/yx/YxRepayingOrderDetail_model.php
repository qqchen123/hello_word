<?php

/**
 * @desc 还款中的借款详情信息
 *		存在插入 更新操作 用户的一个借款中的信息只有一条记录
 */
class YxRepayingOrderDetail_model extends Admin_model
{
	public $table_name = 'fms_yx_repaying_order_detail';

	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

	public function record_data($data, $account, $loan_title)
	{
		$check = $this->db->select(['account'])
		->where('account', $account)
		->where('loan_title', $loan_title)
		->get($this->table_name)
		->row_array();
		if (empty($check)) {
			$insert_array = [
				'account' => $account,
				'loan_title' => $loan_title,
				'reference_annualized' => isset($data['参考年化收益']) ? $data['参考年化收益'] : '',
				'loan_amount' => isset($data['借款金额']) ? $data['借款金额'] : '',
				'loan_term' => isset($data['借款期限']) ? $data['借款期限'] : '',
				'progress' => isset($data['进度']) ? $data['进度'] : '',
				'min_loan_amount' => isset($data['最小出借金额']) ? $data['最小出借金额'] : '',
				'max_loan_amount' => isset($data['最大出借金额']) ? $data['最大出借金额'] : '',
				'public_time' => isset($data['发布时间']) ? $data['发布时间'] : '',
				'loan_service_rate' => isset($data['出借人服务费率']) ? $data['出借人服务费率'] : '',
				'repaying_by' => isset($data['还款方式']) ? $data['还款方式'] : '',
				'interest_rate_by' => isset($data['计息方式']) ? $data['计息方式'] : '',
				'remaining_time' => isset($data['剩余时间']) ? $data['剩余时间'] : '',
				'actual_tender_amount' => isset($data['实际招标金额']) ? $data['实际招标金额'] : '',
				'tender_complete_time' => isset($data['满标时间']) ? $data['满标时间'] : '',
				'remark' => isset($data['备注']) ? $data['备注'] : '',
				'ctime' => date('Y-m-d H:i:s', time()),
				'lutime' => date('Y-m-d H:i:s', time()),
			];
			$this->db->insert($this->table_name, $insert_array);
			return $this->db->insert_id();
		} else {
			//update
			$this->db->set('reference_annualized', isset($data['参考年化收益']) ? $data['参考年化收益'] : '未知');
			$this->db->set('loan_amount', isset($data['借款金额']) ? $data['借款金额'] : '未知');
			$this->db->set('loan_term', isset($data['借款期限']) ? $data['借款期限'] : '未知');
			$this->db->set('progress', isset($data['进度']) ? $data['进度'] : '未知');
			$this->db->set('min_loan_amount', isset($data['最小出借金额']) ? $data['最小出借金额'] : '未知');
			$this->db->set('max_loan_amount', isset($data['最大出借金额']) ? $data['最大出借金额'] : '未知');
			$this->db->set('public_time', isset($data['发布时间']) ? $data['发布时间'] : '未知');
			$this->db->set('loan_service_rate', isset($data['出借人服务费率']) ? $data['出借人服务费率'] : '未知');
			$this->db->set('repaying_by', isset($data['还款方式']) ? $data['还款方式'] : '未知');
			$this->db->set('interest_rate_by', isset($data['计息方式']) ? $data['计息方式'] : '未知');
			$this->db->set('remaining_time', isset($data['剩余时间']) ? $data['剩余时间'] : '未知');
			$this->db->set('actual_tender_amount', isset($data['实际招标金额']) ? $data['实际招标金额'] : '未知');
			$this->db->set('tender_complete_time', isset($data['满标时间']) ? $data['满标时间'] : '未知');
			$this->db->set('remark', isset($data['备注']) ? $data['备注'] : '未知');
			$this->db->set('lutime', date('Y-m-d H:i:s', time()));
			$this->db->where('account', $account);
			$this->db->where('loan_title', $loan_title);
			$ret = $this->db->update($this->table_name);
			return $ret;
		}
	}

	public function get_order_detail($account, $loan_title)
	{
		$ret = $this->db->select([
			'account',
			'loan_title',
			'reference_annualized',
			'loan_amount',
			'loan_term',
			'progress',
			'min_loan_amount',
			'max_loan_amount',
			'public_time',
			'loan_service_rate',
			'repaying_by',
			'interest_rate_by',
			'remaining_time',
			'actual_tender_amount',
			'tender_complete_time',
		])
		->where(" account = '" .$account."' AND loan_title = '". $loan_title ."' ")
		->get($this->table_name)
		->row_array();
		return [
			'登录用户名' => isset($ret['account']) ? $ret['account'] : '',
			'借款标题' => isset($ret['loan_title']) ? $ret['loan_title'] : '',
			'参考年化' => isset($ret['reference_annualized']) ? $ret['reference_annualized'] : '',
			'借款金额' => isset($ret['loan_amount']) ? $ret['loan_amount'] : '',
			'借款期限' => isset($ret['loan_term']) ? $ret['loan_term'] : '',
			'进度' => isset($ret['progress']) ? $ret['progress'] : '',
			'最小出借金额' => isset($ret['min_loan_amount']) ? $ret['min_loan_amount'] : '',
			'最大出借金额' => isset($ret['max_loan_amount']) ? $ret['max_loan_amount'] : '',
			'发布时间' => isset($ret['public_time']) ? $ret['public_time'] : '',
			'出借人服务费率' => isset($ret['loan_service_rate']) ? $ret['loan_service_rate'] : '',
			'还款方式' => isset($ret['repaying_by']) ? $ret['repaying_by'] : '',
			'计息方式' => isset($ret['interest_rate_by']) ? $ret['interest_rate_by'] : '',
			'剩余时间' => isset($ret['remaining_time']) ? $ret['remaining_time'] : '',
			'实际招标金额' => isset($ret['actual_tender_amount']) ? $ret['actual_tender_amount'] : '',
			'满标时间' => isset($ret['tender_complete_time']) ? $ret['tender_complete_time'] : '',
		];
	}
}

?>