<?php

class CatPool extends Admin_Controller{
    private $lines = [];//线路数组 [1,2,3......]
    private $send_lines = [];//实发线路
    private $send_sms_time;//发送余额、套餐等查询短信时间(猫池服务器时间)
    private $send_sms_error = [];//发送短信错误数组 [线路号=>错误码,......]

    //不同运营商配置
    private $sim_options = [
        //中国移动
        'CHINA MOBILE'=>[
            'name'=>'中国移动',//中文名
            'sms_num'=>10086,//查询号
            'yecx'=>'yecx',//余额查询号
            'tc'=>103,//发短信代码查套餐
        ],
        //联通

        //电信
    ];

    public function __construct(){
        parent::__construct();

        //加载猫池爬虫类
        $this->load->library('CatPoolPaChong');
    }

    // 获取发短信状态(循环等待获取)
    private function get_send_sms_status(){
        $num = 0;//初始次数
        $wait_num = 20;//等待执行次数
        $wait_time = 1;//间隔时间
        $done = false;//完成标记
        while ($num<$wait_num && $done===false){
            sleep($wait_time);
            $num++;
            $sms_status_arr = $this->catpoolpachong->get_send_sms_status();
            foreach ($sms_status_arr as $k => $v) {
                if(!is_int($k)) continue;
                if($v['status']==='' || $v['status']==='DONE'){
                    $done = true;
                }else{
                    $done = false;
                    break;
                }
            }
        }
        Slog::log('发短信状态：');
        Slog::log($sms_status_arr);
        if($done){
            Slog::log('猫池获取发短信状态在 '.$num.' 次成功！');
            return $sms_status_arr;
        }else{
            Slog::log('猫池获取发短信状态在 '.$num.' 次失败！');
            return false;
        }
    }

    // 判断发短信状态 返回['线路'=>50,......] 空数组无错误
    private function check_send_sms_status($sms_status_arr){
        $this->send_sms_error = [];
        foreach ($sms_status_arr as $key => $val) {
            if(!is_int($key)) continue;
            if($val['error']!=='' && in_array($key,$this->send_lines)) {
                $this->send_sms_error[$key] = $val['error'];
                Slog::log('猫池线路 '.$key.' 发短信失败，错误码：'.$val['error']);
            }
        }
    }

    // 发送短信错误后续处理..........

    // &过滤取指定号码的短信
    private function filter_sms_from_num(&$sms_info,$from_num=[]){
        foreach ($sms_info as $key => &$val) {
            foreach ($val as $k => $v) {
                if(!in_array(@$v[1],$from_num)) unset($val[$k]);
            }
            if($val===[]) unset($sms_info[$key]);
        }
    }

    // &过滤收短信时间>发短信时间
    private function filter_sms_time(&$sms_info){
        //允许时间交错
        $time_intersect = $this->send_sms_time;

        foreach ($sms_info as $key => &$val) {
            foreach ($val as $k => $v) {
                if(strtotime($v[0])===false) $time = date('Y').'-'.$v[0];
                if(strtotime($time)<=$time_intersect){
                    unset($val[$k]);
                }else{
                    $val[$k][0] = $time;
                }
            }
            if($val===[]) unset($sms_info[$key]);
        }
    }

    // 获取余额短信(循环等待获取) return false or [线路=>[[],[]]]
    private function get_polling_sms(){
        $num = 0;//起始次数
        $wait_num = 30;//等待执行次数
        $wait_time = 3;//间隔时间
        $done = false;//完成标记

        //发送线路 - 发送状态错误线路 = 应获取余额短信线路
        $get_sms_lines = array_values(array_diff($this->send_lines,array_keys($this->send_sms_error)));

        while ($num<$wait_num) {
            sleep($wait_time);
            $num++;

            //获取短信收件箱
            $sms_info = $this->catpoolpachong->get_sms();

            // &过滤取号码为10086发来的短信
            $sms_num_arr = array_column($this->sim_options,'sms_num');
            $this->filter_sms_from_num($sms_info,$sms_num_arr);

            // &过滤取收短信时间>发短信时间
            $this->filter_sms_time($sms_info);

            //查询成功 多滚一次获取机制
            if($done) break;
            if($sms_info!=[] && array_diff($get_sms_lines,array_keys($sms_info))==[]) $done = true;
        }

        Slog::log($sms_info);
        Slog::log('预发送线路：'.json_encode($this->lines));
        Slog::log('实发送线路：'.json_encode($this->send_lines));
        Slog::log('状态错误线路：'.json_encode(array_keys($this->send_sms_error)));
        Slog::log('应收线路：'.json_encode($get_sms_lines));
        Slog::log('实收线路：'.json_encode(array_keys($sms_info)));
        if($done){
            Slog::log('猫池获取短信在 '.$num.' 次成功！');
            return $sms_info;
        }else{
            Slog::log('猫池获取短信在 '.$num.' 次失败！');
            return false;
        }
    }

    // 获取余额
    private function get_money($sms_info){
        $money = [];
        foreach ($sms_info as $key => $val) {
            foreach ($val as $k => $v) {
                $pos = mb_strpos($v[2],'余额为');
                if($pos===false) continue;
                $m = strstr(mb_substr($v[2],$pos+3),'元',true);
                if(is_numeric($m)){
                    $money[$key][$k] =& $sms_info[$key][$k];
                    $money[$key][$k][2] = $m;
                }
            }
            $money[$key] = @array_values($money[$key]);
        }
        return $money;
    }

    // 获取套餐价格
    private function get_tc_money($sms_info){
        $money = [];
        foreach ($sms_info as $key => $val) {
            foreach ($val as $k => $v) {
                $pos = mb_strpos($v[2],'合计');
                if($pos===false) continue;
                $m = strstr(mb_substr($v[2],$pos+2),'元',true);
                if(is_numeric($m)){
                    $money[$key][$k] =& $sms_info[$key][$k];
                    $money[$key][$k][2] = $m;
                }
            }
            $money[$key] = @array_values($money[$key]);
        }
        return $money;
    }

    //查询余额、套餐价
    private function get_sms_money($send_code='yexc'){
        //线路
            $this->lines = range(1,16);

        //发前获取线路运营商和状态
            $new = $this->update_status_and_data(true);
            $arr = [];
            foreach ($new as $k => $v) {
                if($v['运营商']!=null){
                    $arr[$v['运营商']][] = $k;
                    $this->send_lines[] = $k;
                }
            }
        
        //按不同运营商发短信
            foreach ($arr as $k => $v) {
                $lines = $v;
                $telnum = $this->sim_options[$k]['sms_num'];
                $smscontent = $this->sim_options[$k][$send_code];
                //发送查询短信
                $this->send_sms_time[] = $this->catpoolpachong->send_sms($lines,$telnum,$smscontent);
            }
        //取最早发送时间
            $this->send_sms_time = min($this->send_sms_time);

        //获取发短信状态(循环等待获取)
            $sms_status_arr = $this->get_send_sms_status();
            if(!$sms_status_arr) exit('发送短信状态错误！');

        //获取发短信失败数组 线路=>错误码 如 [4=>50,5=>50] 估计50是欠费
            $this->check_send_sms_status($sms_status_arr);

        //获取余额短信(循环等待获取)
            $sms_info = $this->get_polling_sms();
            if(!$sms_info) exit('获取短信失败！');

        switch ($send_code) {
            //提取余额
            case 'yecx':
                $money = $this->get_money($sms_info);
                break;
            //提取套餐价格
            case 'tc':
                $money = $this->get_tc_money($sms_info);
                break;
        }
        return $money;

        // //结合错误
        //     foreach ($this->send_sms_error as $key => &$val) {
        //         $val = [date('Y-m-d H:i:s'),null,$val];
        //     }
        //     return $money+$this->send_sms_error;
    }

    //获取实时状态
    private function get_status(){
        return $status_arr = $this->catpoolpachong->get_cat_pool_status();
        // echo json_encode($status_arr);
    }

    //输出猫池列表
    public function list_cat_pool(){
        $this->load->helper(['checkrolepower','publicstatus']);
        // $this->get_status();
        $data['sim_options'] = json_encode($this->sim_options);
        $this->showpage('fms/list_cat_pool',$data);
    }

    //获取数据库数据
    public function get_cat_pool(){
        $this->load->model("CatPool_model",'cp');
        $like = $this->input->get('like',true);
        $page = $this->input->get('page',true);
        $rows = $this->input->get('rows',true);
        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);
        $res = $this->cp->list_cat_pool($like,$page,$rows,$sort,$order);
        echo json_encode($res);
    }

    //获取数据库短信
    public function list_sms(){
    	$this->load->model('Catpool_mysql_model','cm');
    	$iccid = $this->input->get('iccid',true);
        $like = $this->input->get('like',true);
        if($like==='') $like=null;
        $page = $this->input->get('page',true);
        $rows = $this->input->get('rows',true);
        $sort = $this->input->get('sort',true);
        if($sort===null) $sort = 'receive.id';
        $order = $this->input->get('order',true);
        if($order===null) $order = 'DESC';

		$arr = $this->cm->list_sms($like,$page,$rows,$sort,$order,$iccid);
		echo json_encode($arr);
    }

    //获取指定cp_id的一条数据
    public function get_cat_pool_by_cpid(){
        $cp_id = $this->input->get('cp_id',true);
        if(!$cp_id) exit();
        $this->load->model('CatPool_model','cp');
        exit(json_encode($this->cp->get_cat_pool_by_cpid($cp_id)));
    }

    //更新猫池状态
    public function update_status_and_data($if_return=false){
        $this->load->model("CatPool_model",'cp');

        /* 爬虫字段
        [线路] => 1
        [SIM] => Y
        [注册] => Y
        [信号] => 31
        [GPRS注册] => Y
        [GPRS附着] => Y
        [运营商] => CHINA MOBILE
        [基站模式] => AUTO
        [BCCH] => 
        [位置区编码] => LAC:1841,CELL ID:9026
        [模块] => M35
        [模块版本] => M35FAR02A01_RSIM
        [SIM号码] => +8615900485938
        [IMEI] => 865794033659873
        [IMSI] => 460029004693045
        [ICCID] => 898600000917F4006152
        */

        /*
          `cp_id` int(11) NOT NULL COMMENT '主id',
          `user_id` int(11) NOT NULL COMMENT '用户id',
          `line_id` smallint(6) NOT NULL COMMENT '线路id',
          `goip_id` int(11) NOT NULL COMMENT '猫池数据库id',
          `carrier` enum('CHINA MOBILE') NOT NULL DEFAULT 'CHINA MOBILE' COMMENT '运营商',
          `sim_num` varchar(14) DEFAULT NULL COMMENT '手机卡号',
          `money` float(3,2) DEFAULT NULL COMMENT 'sim卡余额',
          `get_money_time` datetime DEFAULT NULL COMMENT '最后获取sim卡余额时间',
          `pay_money` float(3,2) DEFAULT NULL COMMENT '最后次支付金额',
          `pay_money_time` datetime DEFAULT NULL COMMENT '最后次支付时间',
          `imei` varchar(20) NOT NULL,
          `imsi` varchar(20) NOT NULL,
          `iccid` varchar(20) NOT NULL,
          `edit_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新',
          `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
          `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '猫池状态 1：有效、 0 ：无效',
        */

        //数据库字段=>爬虫字段 
        $fields = [
            'line_id' => '线路',//1
            'carrier' => '运营商',//CHINA MOBILE
            'sim_num' => 'SIM号码',
            'imei' => 'IMEI',
            'imsi' => 'IMSI',
            'iccid' => 'ICCID',
            'status' => 'status',
            //'status' => 'SIM',//Y N
            //'status' => '注册',//Y N
        ];

        $new = $this->get_status();
        $old = $this->cp->get_cat_pool();
        $new_ref = $old_ref = $insert_data = $update_data = $res['info'] = [];
        $res['ret'] = true;

        foreach ($new as $k => $v) {
            if($v['ICCID']) $new_ref[$v['ICCID']] =& $new[$k];
            if($v['SIM']=='Y' && $v['注册']=='Y' && $v['GPRS注册']=='Y' && $v['GPRS附着']=='Y') {
                $new[$k]['status'] = '有效';
            }else{
                $new[$k]['status'] = '无效';
            }
        }

        foreach ($old as $k => $v) {
            $old_ref[$v['iccid']] =& $old[$k];
        }

        //新增卡
        $new_sim = array_diff_key($new_ref,$old_ref);
        foreach ($new_sim as $k => $v) {
            $arr = [];
            foreach ($fields as $key => $val) {
                $arr[$key] =& $new_sim[$k][$val];
            }
            $insert_data[] = $arr;
        }

        if($res['ret'] && !empty($insert_data)){
            if($num = $this->cp->add($insert_data)) {
                $res['info'][0] = '<br>'.$num.' 张SIM卡新增成功';
            }else{
                $res['ret'] *= 0;
                $res['info'][0] = '<br>SIM卡新增失败';
                $res['insert_data'] =& $insert_data;
                Slog::log('猫池页面：'.$res['info'][0]);
                Slog::log($res['insert_data']);
            }
        }

        //去除卡
        $discard_sim = array_diff_key($old_ref, $new_ref);
        foreach ($discard_sim as $k => $v) {
            if($v['status']=='无卡') unset($discard_sim[$k]);
        }
        $discard_where = array_keys($discard_sim);
        if($res['ret'] && !empty($discard_where)){
           if($num = $this->cp->update_status($discard_where,'无卡')){
                $res['info'][1] = '<br>'.$num.' 张SIM卡移除成功';
           }else{
                $res['ret'] *= 0;
                $res['info'][1] = '<br>SIM卡移除失败';
                $res['discard_sim'] =& $discard_where;
                Slog::log('猫池页面：'.$res['info'][1]);
                Slog::log($res['discard_sim']);
           }
        }

        //共有卡核对
        $check_sim = array_intersect_key($old_ref,$new_ref);
        foreach ($check_sim as $key => $val) {
            $arr = [];
            foreach ($fields as $k => $v) {
                if($val[$k]!=$new_ref[$key][$v]){
                    if($k=='sim_num'){
                        if($new_ref[$key][$v] === '') continue;
                        if($val[$k] !== ''){
                            $res['info'][2] 
                                ='❌错误<br>线路：'.$val['line_id']
                                .'<br>  iccid：'.$key
                                .'<br>保存手机号：'.$val[$k]
                                .'<br>识别手机号：'.$new_ref[$key][$v]
                                .'<br>手机号码不相符，请人工核查！';
                            continue;
                        }
                    }
                    $arr['iccid'] = $key;
                    $arr[$k] =& $new_ref[$key][$v];
                }
            }
            if($arr) $update_data[] = $arr;
        }

        if($res['ret'] && !empty($update_data)){
            if($num = $this->cp->update_batch($update_data)){
                @$res['info'][2] .= '<br>'.$num.' 张SIM卡更新成功';
            }else{
                $res['ret'] *= 0;
                $res['info'][2] .= '<br>SIM卡更新失败';
                $res['update_data'] =& $update_data;
                Slog::log('猫池页面：'.$res['info'][2]);
                Slog::log($res['update_data']);
            }
        }
        if($if_return) return $new;

        if($res['info']==[]){
            $res['ret'] = false;
            $res['info'] = '无任何变动';
        }else{
            $res['ret'] = (bool)$res['ret'];
            $res['info'] = join($res['info']);  
        }

        echo json_encode($res,256);
    }

    //查询余额
    public function update_money($if_return=false){
        $money = $this->get_sms_money('yecx');
        $cat_pool_data = $pay_list = $lines = [];

        foreach ($money as $key => $val) {
            if(is_array($val[0])) {
                if($money[$key][0][2]*1==$money[$key][0][2]){
                    $cat_pool_data[$key] = [
                        'get_money_time'=>& $money[$key][0][0],
                        'money'=>& $money[$key][0][2],
                        'line_id'=> $key,
                    ];
                    $lines[] = $key;
                    foreach ($val as $k => $v) {
                        $pay_list[] = [
                            'get_money_time' =>& $money[$key][$k][0],
                            'money' =>& $money[$key][$k][2],
                            'line_id' => $key,
                        ];
                    }
                }
            }/*else{
                $err[$key] = [
                    'get_money_time'=>& $money[$key][0],
                    'money'=>& $money[$key][2],
                    'status'=> '无效',
                    'line_id'=> $key,
                ];
            }*/
        }
        $this->load->model("CatPool_model",'cp');
        $this->db->trans_start();

            $num1 = $this->cp->update_batch($cat_pool_data,['有效','无效'],'line_id');
            $arr = $this->cp->get_id_by_lines($lines);
            $arr = array_column($arr,'cp_id','line_id');

            foreach ($pay_list as $key => $val) {
                $pay_list[$key]['cp_id'] = $arr[$val['line_id']];
                unset($pay_list[$key]['line_id']);
            }

            $num2 = 0;
            foreach ($pay_list as $key => $val) {
                $num2 += $this->cp->update_pay_list($val);
            }
        $this->db->trans_complete();
        if ($this->db->trans_status()){
            $res['ret'] = true;
            $res['info'] = '查询余额成功';
        }else{
            $res['ret'] = false;
            $res['info'] = '查询余额失败';
        }
        $res['info2'] = '更新猫池库:'.$num1.'  更新充值库:'.$num2;
        foreach ($this->send_sms_error as $key => $val) {
            $res['info2'] .= '  猫池线路'.$key.'发送错误(错误码'.$val.')';
        }
        if($if_return){
            return $res;
        }else{
            echo json_encode($res,256);
        }
    }

    //查询套餐价格
    public function update_tc_money(){
        $money = $this->get_sms_money('tc');
        $cat_pool_data = $pay_list = $lines = [];

        foreach ($money as $key => $val) {
            if(is_array($val[0])) {
                if($money[$key][0][2]*1==$money[$key][0][2]){
                    $cat_pool_data[$key] = [
                        'get_tc_money_time'=>& $money[$key][0][0],
                        'tc_money'=>& $money[$key][0][2],
                        'line_id'=> $key,
                    ];
                    $lines[] = $key;
                }
            }else{
                $cat_pool_data[$key] = [
                    'get_tc_money_time'=>& $money[$key][0],
                    'tc_money'=>& $money[$key][2],
                    'status'=> '无效',
                    'line_id'=> $key,
                ];
            }
        }
        $this->load->model("CatPool_model",'cp');
        $this->db->trans_start();
        $num1 = $this->cp->update_batch($cat_pool_data,['有效','无效'],'line_id');
        $this->db->trans_complete();
        if ($this->db->trans_status()){
            $res['ret'] = true;
            $res['info'] = '查询套餐成功';
        }else{
            $res['ret'] = false;
            $res['info'] = '查询套餐失败';
        }
        $res['info2'] = '更新猫池库:'.$num1;
        foreach ($this->send_sms_error as $key => $val) {
            $res['info2'] .= '  猫池线路'.$key.'发送错误(错误码'.$val.')';
        }
        echo json_encode($res,256);
    }

    //编辑补充资料
    public function edit(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cp_id', '', 'integer|required');
        $this->form_validation->set_rules('user_id', '客户名称', 'integer|required');
        $this->form_validation->set_rules('sim_type', '电话卡类型', 'in_list[本地,外地]|required');
        $this->form_validation->set_rules('if_get', '是否移交', 'in_list[是,否]|required');
        $this->form_validation->set_rules('from_date', '申请日期', 'required');
        $this->form_validation->set_rules('from_man', '移交人', 'required');
        $this->form_validation->set_rules('note', '备注', 'max_length[250]');

        $this->form_validation->set_rules('sim_num', '手机号码', 'max_length[15]|min_length[11]');

        if (!$this->form_validation->run()) {
            $res['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $res['info'] = validation_errors();
            exit(json_encode($res));
        }

        $cp_id = $this->input->post('cp_id',true);
        $data['user_id'] = $this->input->post('user_id',true);
        $data['sim_type'] = $this->input->post('sim_type',true);
        $data['if_get'] = $this->input->post('if_get',true);
        $data['from_date'] = $this->input->post('from_date',true);
        $data['from_man'] = $this->input->post('from_man',true);
        $data['note'] = $this->input->post('note',true);
        $data['edit_time'] = date('Y-m-d H:i:s');
        $sim_num = $this->input->post('sim_num',true);
        $this->load->model('CatPool_model','cp');
        if($sim_num){
            $old = $this->cp->get_cat_pool_by_cpid($cp_id);
            if($old['sim_num']=='') $data['sim_num'] = $sim_num;
        }

        $res['ret'] = (bool)$this->cp->update_by_cpid($data,$cp_id);
        echo json_encode($res);
    }

    //充值记录
    public function get_pay_data(){
        $cp_id = $this->input->get('cp_id',true);
        if($cp_id==null) exit();

        $like = $this->input->get('like',true);
        $page = $this->input->get('page',true);
        $rows = $this->input->get('rows',true);
        $sort = $this->input->get('sort',true);
        $order = $this->input->get('order',true);

        $this->load->model("CatPool_model",'cp');
        $pay_list = $this->cp->get_pay_data($cp_id,$like,$page,$rows,$sort,$order);
        // echo $this->db->last_query();
        echo json_encode($pay_list);
    }

    //开关自动充值
    public function auto_pay(){
        $cp_id = $this->input->get('cp_id',true);
        if($cp_id==null) exit();
        $this->load->model('CatPool_model','cp');
        echo (int) $this->cp->update_auto_pay($cp_id);
    }
    
    //轮询余额 低于阀值充值
    public function autoCheckAndPay(){
        Slog::log("===================================自动充值检查开始===================================");

        //查余额时间偏差 减5分钟
        $get_money_time = date('Y-m-d H:i:s',strtotime('-5 minutes'))."<br>";
        //充值时间偏差 减1天
        $pay_money_time = date('Y-m-d H:i:s',strtotime('- 24 hours'));

        $res = $this->update_money(true);
        if(!$res['ret']) exit($res['info']);

        //数据库取符合条件的应充值项
        $this->load->model("CatPool_model",'cp');
        $pay_list = $this->cp->get_cat_pool([
            '`get_money_time`>=' => $get_money_time,//查余额时间>程序开始时间-5分钟
            '`pay_money_time`<=' => $pay_money_time,//上次充值时间<程序开始时间-24小时
            'auto_pay' => '已开启自动充值',//开启自动充值
            // 手机号是否必须
            'tc_money is not null' => null,//套餐价格存在
            'tc_money > money' => null,//余额>套餐价格
            'status' => '有效',//状态有效
        ]);

        if($pay_list==[]) exit('没有需要充值的sim卡');
        var_export($pay_list);
        Slog::log($pay_list);

        //充值入库
        $num1 = $num2 = 0;
        $res['info'] = '';
        foreach ($pay_list as $key => $val) {
            //充值接口

            //成功后入库
            $pay_money = 100;
            $pay_money_time = date('Y-m-d H:i:s');

            $this->db->trans_start();
            $this->cp->update_by_cpid([
                'pay_money'=>$pay_money,
                'pay_money_time'=>$pay_money_time,
            ],$val['cp_id']);
            $this->cp->update_pay_list([
                'cp_id'=>$val['cp_id'],
                'pay_money'=>$pay_money,
                'pay_money_time'=>$pay_money_time
            ]);
            $this->db->trans_complete();
            if($this->db->trans_status()){
                $res['info'][] = '线路：'.$val['line_id'].'cp_id：'.$val['cp_id'].' 成功充值'.$pay_money."元\r\n";
            }else{
                $res['info'][] = '线路：'.$val['line_id'].'cp_id：'.$val['cp_id'].' 充值'.$pay_money."元失败\r\n";
            }
        }
        echo "\r\n".join($res['info']);
        Slog::log("===================================自动充值检查结束===================================");
    }

    //获取短信 直接读取猫池数据库 receive表
    // public function get_sms(){
    //     //获取短信收件箱
    //     $sms_info = $this->catpoolpachong->get_sms();
    //     echo "<pre>";
    //     print_r($sms_info);
    //     echo "</pre>";
    //     // var_dump();
    // }

    // 猫池mysql数据进sqlserver
    public function sim_in_sqlserver(){
        return ;
   //  	//写sqlserver的windoes主机中间件
   //  	$r_url = 'http://139.224.113.2/middle/index.php/middle/index';
   //  	$w_url = 'http://139.224.113.2/middle/index.php/middle/update_data';

   //  	//sqlserver表名称
   //  	$table_name = 'tabDIYTable72';

   //  	//字段转译规则
   //  	$rule = [
			// 'cp_id' => 232,//猫池id
			// 'fuserid' => 233,//客户编号
			// 'name' => 234,//客户姓名
			// 'line_id' => 235,//猫池线路
			// 'carrier' => 236,//运营商
			// 'status' => 237,//状态
			// 'sim_num' => 238,//手机号码
			// 'money' => 239,//余额
			// 'get_money_time' => 240,//获取余额时间
			// 'tc_money' => 241,//套餐价格
			// 'get_tc_money_time' => 242,//获取套餐价格时间
			// 'iccid' =>  243,//iccid
			// 'sim_type' => 244,//电话卡类型 本地or外地
			// 'if_get' => 245,//是否移交
			// 'from_man' => 246,//移交人
			// 'from_date' => 247,//申请日期
			// 'note' => 248//备注
   //  	];

   //  	foreach ($rule as $k => $v) {
   //  		$rule[$k] = 'F'.$v;
   //  	}

   //  	// sqlserver必带字段
   //  	$sqlserver_required = [
   //  		'RepID' => '60',//模版id
   //  		'bIsFinish' => '1',//
   //  		'ID' => '1',//
   //  		'iOrd' => '1',//
   //  	];

   //  	//条件字段
   //  	$where_field = $rule['iccid'];

   //  	//读取猫池数据
   //      $this->load->model("CatPool_model",'cp');
   //  	$arr = $this->cp->list_cat_pool(null,1,10000);

   //  	//转译为sqlserver数据
   //  	$data = [];
   //  	foreach ($arr['rows'] as $key => $val) {
   //  		foreach ($val as $k => $v) {
   //  			if(isset($rule[$k])) $data[$key][$rule[$k]] = ($v===null)?'':$v;
   //  		}
   //  		$data[$key] += $sqlserver_required;
   //  	}

   //  	//读取sqlserver的iccid
   //  	$sql = "select {$where_field} from {$table_name}";
   //  	$arr = file_get_contents($r_url.'?sql='.urlencode($sql));
   //  	$arr = json_decode($arr,true);
   //  	$arr = array_column($arr, $where_field);

   //  	//判断读还是写
   //  	foreach ($data as $k => $v) {
   //  		//已存在改写
   //  		if(in_array($v[$where_field],$arr)){
   //  			$sql = $this->db->update_string($table_name,$v,[$where_field=>$v[$where_field]]);
   //  		//不存在插入
   //  		}else{
   //  			$sql = $this->db->insert_string($table_name,$v);
   //  		}
			// echo $sql = str_replace('`', '', $sql);
			// echo "<br>\r\n";
   //  		$r = file_get_contents($w_url.'?sql='.urlencode($sql));

   //  		if($r==1){
   //  			echo '猫池数据 iccid：'.$v[$where_field]."入sqlserver成功<br>\r\n";
   //  		}else{
			// 	echo '猫池数据 iccid：'.$v[$where_field]."入sqlserver失败<br>\r\n";
   //  		}
   //  	}
    }

    //猫池短信数据进sqlserver
    public function sms_in_sqlserver(){
        return ;
  //   	//写sqlserver的windoes主机中间件
  //   	$r_url = 'http://139.224.113.2/middle/index.php/middle/index';
  //   	$w_url = 'http://139.224.113.2/middle/index.php/middle/update_data';

  //   	//sqlserver表名称
  //   	$table_name = 'tabDIYTable125';

  //   	//字段转译规则
  //   	$rule = [
		// 	'id' => 1423,//短信id
		// 	'srcnum' => 1424,//对方号码
		// 	'msg' => 1425,//短信内容
		// 	'time' => 1426,//收到时间
		// 	'iccid' => 1427,//iccid
		// 	// 'goipname' => 1428,//客户编号
		// 	'srcid' => 1429,//srcid
		// 	'srcname' => 1430,//srcname
		// 	'srclevel' => 1431,//获取余额时间
		// 	'status' => 1432,//status
		// 	'smscnum' => 1433,//smscnum
  //   	];

  //   	foreach ($rule as $k => $v) {
  //   		$rule[$k] = 'F'.$v;
  //   	}

  //   	// sqlserver必带字段
  //   	$sqlserver_required = [
  //   		'RepID' => '194',//模版id
  //   		'bIsFinish' => '1',//
  //   		'ID' => '1',//
  //   		'iOrd' => '1',//
  //   	];

  //   	//条件字段
  //   	$where_field = $rule['iccid'];

  //   	//读取sqlserver最大id
  //   	$sql = "select max({$rule['id']}) as max_id from {$table_name}";
  //   	$arr = file_get_contents($r_url.'?sql='.urlencode($sql));
  //   	$arr = json_decode($arr,true);
  //   	$max_id = (int) $arr[0]['max_id'];
  //   	echo '增量起始短信id：'.$max_id."<br>\r\n";

		// //读取猫池短信max_id后的短信
		// $this->load->model('Catpool_mysql_model','cm');
		// $arr = $this->cm->get_sms(['receive.id >'=>$max_id]);
		// echo '增量短信数量：'.count($arr)."<br>\r\n";;
  //   	//转译为sqlserver数据
  //   	$data = [];
  //   	foreach ($arr as $key => $val) {
  //   		foreach ($val as $k => $v) {
  //   			if(isset($rule[$k])) $data[$key][$rule[$k]] = ($v===null)?'':$v;
  //   		}
  //   		$data[$key] += $sqlserver_required;
  //   	}

  //   	//增量插入
  //   	$succ_num = $error_num = 0;
  //   	foreach ($data as $k => $v) {
  //   		//不存在插入
  //   		$sql = $this->db->insert_string($table_name,$v);
		// 	$sql = str_replace('`', '', $sql);
		// 	// echo "<br>\r\n";
  //   		$r = file_get_contents($w_url.'?sql='.urlencode($sql));

  //   		if($r==1){
  //   			echo '猫池短信 id：'.$v[$rule['id']].',  iccid：'.$v[$where_field]." 入sqlserver成功<br>\r\n";
  //   			$succ_num++;
  //   		}else{
		// 		echo '猫池短信 id：'.$v[$rule['id']].',  iccid：'.$v[$where_field]." 入sqlserver失败<br>\r\n";
		// 		$error_num++;
  //   		}
  //   	}
  //   	echo '成功：'.$succ_num.'  失败：'.$error_num.'=================================================<br>\r\n';
    }

//===========================================================










}