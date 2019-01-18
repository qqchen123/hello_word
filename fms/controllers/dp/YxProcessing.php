<?

/**
 * @desc 银信数据整理
 */
class YxProcessing extends Admin_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @url /dp/YxProcessing/test
	 */
	public function test()
	{
		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$ret = $this->yxrepaying_model->get_repaying_total_amount();
		var_dump($ret);
		exit;
	}

	/**
	 * @name 银信基础统计页
	 * @url /dp/YxProcessing/YxBaseStatis
	 */
	public function YxBaseStatis()
	{
		$this->load->model('yx/YxRepaying_model', 'yxrepaying_model');
		$this->load->model('yx/YxAccount_model', 'yxaccount_model');
		$this->load->model('yx/YxOutMoney_model', 'yxoutmoney_model');
		$total = $this->yxaccount_model->get_account_total();
		$master_total = $this->yxoutmoney_model->get_total();
		$total_amount = $this->yxrepaying_model->get_repaying_total_amount();
		$repaying_total = $this->yxrepaying_model->get_repaying_username_total();
		$repaying_order_total = $this->yxrepaying_model->get_repaying_order_total();
		$data = [
			'total' => $total,
			'master_total' => $master_total,
			'repaying_total' => $repaying_total,
			'total_amount' => $total_amount,
			'repaying_order_total' => $repaying_order_total,
		];
		$this->showpage('fms/statis/base', $data);
	}
}


?>
