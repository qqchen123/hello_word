<?
defined('BASEPATH') OR exit('No direct script access allowed');

class YxZong extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
    }

    /**
     * 输出总账户列表
     */
    public function list_yx_zong(){
        $this->showpage('fms/yx_zong');
    }

    // 出借信息 开始--------------------
        /**
         * 获取出借数据
         */
        public function get_out_money(){
            $this->load->model("yx/YxOutMoney_model",'yom');

            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            $order = $this->input->get('order',true);
           
            if($sort===null) $sort='reg_time';
            if($order===null) $order = 'desc';
            
            $res = $this->yom->list_out_money($like,$page,$rows,$sort,$order);
            echo json_encode($res);
        }

        /**
         * 输出出借详情列表
         */
        // public function list_out_money_detail(){
        //     $data['account'] = $this->input->get('account',true);
        //     $this->showpage('fms/yx_zong_out_money_detail',$data);
        // }

        /**
         * 获取出借详情数据
         */
        public function get_out_money_detail(){
            $account = $this->input->get('account',true);
            if(!$account) exit();
            $this->load->model("yx/YxOutMoneyDetail_model",'yomd');

            $account = $this->input->get('account',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            $order = $this->input->get('order',true);
            $res = $this->yomd->list_out_money_detail($like,$page,$rows,$sort,$order,$account);
            echo json_encode($res);
        }
    // 出借信息 结束--------------------

    // 借款信息 开始--------------------
        /**
         * 获取借款数据
         */
        public function get_in_money(){
            $this->load->model("yx/YxInMoney_model",'yim');

            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            $order = $this->input->get('order',true);

            if($sort===null) $sort='reg_time';
            if($order===null) $order = 'desc';

            $res = $this->yim->list_in_money($like,$page,$rows,$sort,$order);
            echo json_encode($res);
        }

        /**
         * 输出借款详情列表
         */
        // public function list_in_money_detail(){
        //     $data['account'] = $this->input->get('account',true);
        //     $this->showpage('fms/yx_zong_in_money_detail',$data);
        // }

        /**
         * 获取借款详情数据
         */
        public function get_in_money_detail(){
            $account = $this->input->get('account',true);
            if(!$account) exit();
            $this->load->model("yx/YxInMoneyDetail_model",'yimd');

            $account = $this->input->get('account',true);
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            $order = $this->input->get('order',true);
            $res = $this->yimd->list_in_money_detail($like,$page,$rows,$sort,$order,$account);
            echo json_encode($res);
        }

    // 借款信息 结束--------------------

    // 获取一条数据供编辑
        public function get_account_user_info(){
            $account = $this->input->get('account',true);
            if(!$account) exit('[]');
            $this->load->model("user/User_model",'user');
            $res = $this->user->get_account_user_info($account);
            echo json_encode($res);
        }

    // 执行编辑
        public function do_edit(){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account', '', 'required');
            $this->form_validation->set_rules('binding_phone', '绑定手机', 'integer|max_length[11]|min_length[11]');
            $this->form_validation->set_rules('fuserid', '客户编号', 'required|max_length[30]');
            $this->form_validation->set_rules('name', '客户姓名', 'required|max_length[30]');
            $this->form_validation->set_rules('idnumber', '客户身份证号', 'required|max_length[18]');

            if (!$this->form_validation->run()) {
                $ret['ret'] = false;
                $this->form_validation->set_error_delimiters('', '<br>');
                $ret['info'] = validation_errors();
                exit(json_encode($ret));
            }

            $account = $this->input->post('account',TRUE);
            $account_data['binding_phone'] = $this->input->post('binding_phone',TRUE);
            $user_data['fuserid'] = $this->input->post('fuserid',TRUE);
            $user_data['name'] = $this->input->post('name',TRUE);
            $user_data['idnumber'] = $this->input->post('idnumber',TRUE);

            $this->load->model("user/User_model",'user');
            $this->load->model("yx/YxAccount_model","account");
            $this->db->trans_start();
                $info = $this->user->replace_one($account,$user_data);
                if($info===true)
                    $this->account->replace_one($account,$account_data);
            $this->db->trans_complete();

            if($this->db->trans_status() && $info===true){
                $res['ret'] = true;
                $res['info'] = '编辑成功！' ;
            }else{
                $res['ret'] = false;
                $res['info'] = '编辑失败！'.@$info;
            }
            echo json_encode($res);
        }

    /**
     * 导出出借人列表生成excel
     */
    public function get_out_money_excel(){
        require_once('./models/PHPExcel.php');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        
        $this->load->model("yx/YxOutMoney_model",'yom');
        $out_money = $this->yom->list_out_money('',1,999999999);
        $option = [
            'account' => '被推荐人用户名',
            'recommend_tie' => '推荐关系',
            'reg_time' => '注册时间',
            'out_money' => '被推荐人出借金额',
            'fms_user-fuserid' => '客户编号',
            'fms_user-idnumber' => '客户身份证号',
            'fms_user-name' => '客户姓名',
            'fms_yx_account-bind_phone' => '客户绑定手机号',
        ];
        $arr[] = array_values($option);
        // $keys = array_keys($options);
        foreach ($out_money['rows'] as $key => $val) {
            $tmp = [];
            foreach ($option as $k => $v) {
                $tmp[] = $val[$k];
            }
            $arr[] = $tmp;
        }

        $objPHPExcel -> getActiveSheet() -> getColumnDimension('A') -> setAutoSize(true); 
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('B') -> setWidth(12);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('C') -> setAutoSize(true); 
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('D') -> setAutoSize(true); 
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('E') -> setAutoSize(true); 
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('F') -> setAutoSize(true); 
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('G') -> setWidth(12);
        $objPHPExcel -> getActiveSheet() -> getColumnDimension('H') -> setAutoSize(true); 

        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        $objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

        $objPHPExcel->getActiveSheet()->fromArray($arr, NULL, 'A1');
        $objPHPExcel->getActiveSheet()->setTitle('银信出借人列表');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="银信出借人列表.xlsx"');
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
?>