<?php

/**
 * @desc 债权管理
 */
class Zhaiquanlist extends Admin_Controller
{
	
	public function __construct()
    {
        parent::__construct();
        $this->basicloadhelper();
    }

    /**
     * @name 加载基本的helper
     */
    private function basicloadhelper()
    {
        //日志
        $this->load->helper(['array', 'tools', 'slog']);

        //公共状态方法
        $this->load->helper(['publicstatus', 'checkrolepower']);
    }

    /**
     * @name 查询列表
     * @url Zhaiquanlist/getlist
     */
    public function getlist()
    {
    	if (!empty($_POST)) {
            $idnumber = $this->input->post('idnumber',true);
            $name = $this->input->post('name',true);
            $page = $this->input->post('page',true);
            $rows = $this->input->post('rows',true);
            $sort = $this->input->post('sort',true);
            $order = $this->input->post('order',true);
            if($idnumber == 'err'){$idnumber = '';}
            if($name == 'err'){$name = '';}else{$name = urldecode($name);}
            $this->load->model('Zhaiquan_model', 'zhaiquan_model');
            $rest = $this->zhaiquan_model->find_record($idnumber, $name, $page, $rows, $sort, $order);
            foreach($rest['data'] as $k=>$v){
                $c_list[$k]["id"] = $v['id'];
                $c_list[$k]["fuserid"] = $v['fuserid'];
                $c_list[$k]["name"] = $v['name'];
                $c_list[$k]["idnumber"] = $v['idnumber'];
                $c_list[$k]['account_info'] = $v['account_info'];
				$c_list[$k]['area'] = $v['area'];
				$c_list[$k]['quanli_name'] = $v['quanli_name'];
				$c_list[$k]['address'] = $v['address'];
				$c_list[$k]['house_loan_amount'] = $v['house_loan_amount'];
				$c_list[$k]['house_loan_term'] = $v['house_loan_term'];
				$c_list[$k]['house_loan_start'] = $v['house_loan_start'];
				$c_list[$k]['house_loan_end'] = $v['house_loan_end'];
				$c_list[$k]['house_loan_remaining_time'] = $v['house_loan_remaining_time'];
				$c_list[$k]['house_loan_quanli'] = $v['house_loan_quanli'];
				$c_list[$k]['terrace_loan_amount'] = $v['terrace_loan_amount'];
				$c_list[$k]['terrace_loan_term'] = $v['terrace_loan_term'];
				$c_list[$k]['terrace_loan_start'] = $v['terrace_loan_start'];
				$c_list[$k]['terrace_loan_end'] = $v['terrace_loan_end'];
				$c_list[$k]['terrace_loan_remaining_time'] = $v['terrace_loan_remaining_time'];
				$c_list[$k]['mispairing_time'] = $v['mispairing_time'];
            }
            $reponseData = [];
            $reponseData['rows'] = !empty($c_list) ? $c_list : [];
            $reponseData["total"] = $rest['total'];
            echo json_encode($reponseData);
        } else {
	    	$this->load->service('public/Html_service', 'html_service');
	    	$data = [
	            'jscontroller' => $this->html_service->no_backspace(),
	            'statusColor' => json_encode($this->statusColor),
	            'btnCode' => showStatusBtn(true),
	    	];
	    	$this->showpage('fms/zhaiquanlist',$data);
    	}
    }

    public function update_remaining_time_important() {
        $this->load->model('Zhaiquan_model', 'zhaiquan_model');
        $rest = $this->zhaiquan_model->update_remaining_time(1);
        echo json_encode(['code'=>1,'msg'=>'','data'=>''], JSON_UNESCAPED_UNICODE);
    }

}

?>

