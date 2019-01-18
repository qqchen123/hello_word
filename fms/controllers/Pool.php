<?php

class Pool extends Admin_Controller
{
    public function __construct(){
        parent::__construct();

        //加载池子配置项
        $this->config->load('pool');
        $this->pool = $this->config->item('pool');
        $this->sample_type = $this->input->get_post('sample_type',true)?$this->input->get_post('sample_type',true):$this->pool['sample_type'];

        $this->load->model('pool_sample_model');//加载 样本model类
        $this->load->model('pool_sample_type_model');//加载 样本类型model类
        $this->load->model('pool_model');       //加载 证据池model类
        $this->load->library('form_validation');//表单验证类

    }

    //样本添加权限参数的方法定义
    var $methods = [
        ['dir' => '', 'class' => 'Pool', 'method' => 'pool'],
        // ['dir' => '', 'class' => 'Pool', 'method' => 'get_pool'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'do_pool'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'baoShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'guoChuShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'backChuShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'guoFuShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'backFuShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'guoShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'backShen'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'stop'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'start'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'pleaseEdit'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'yesEdit'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'noEdit'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'history'],
        ['dir' => '', 'class' => 'Pool', 'method' => 'remind'],

        ['dir' => '', 'class' => 'Qiye', 'method' => 'qylist'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'mobilelist'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'banklist'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'instlist'],

        ['dir' => '', 'class' => 'Qiye', 'method' => 'editdo'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'edit'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'BaoShen'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'GuoShen'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'BackShen'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'Stop'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'Start'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'PleaseEdit'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'YesEdit'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'NoEdit'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'History'],

        ['dir' => '', 'class' => 'Qiye', 'method' => 'pre_a'],
        ['dir' => '', 'class' => 'Qiye', 'method' => 'pre_b'],
    ];

    // //样本归属类型定义
    // var $sample_types = [
    //     ['key'=>'user','text'=>'客户','table'=>'fms_pool_user','id'=>'user_id','init_pool_method'=>'get_user'],
    //     ['key'=>'house','text'=>'房屋','table'=>'fms_pool_hourse','id'=>'house_id','init_pool_method'=>'get_house'],
    //     ['key'=>'order','text'=>'订单','table'=>'fms_pool_order','id'=>'order_id','init_pool_method'=>'get_order'],
    // ];
    // //默认样本归属类型
    // var $sample_type = 'user';



//样本开始========================================================

    // 输出样本列表
    public function list_pool_sample(){
        $this->load->helper('checkrolepower');
        //$data['statusColor'] = json_encode($this->statusColor);
        $data['sample_types'] = json_encode($this->pool['sample_types']);
        $data['sample_type'] = $this->sample_type;
        $this->showpage('fms/list_pool_sample',$data);
    }

    //获取证据池样本表（fms_pool_sample）的数据 废弃代码
    // public function provelist( $rows = ''){
    //     $page     = $_GET['page'];
    //     $pageSize = $_GET['rows'];
    //     $first    = $pageSize * ($page - 1);
    //     //排序
    //     $sort     = $_GET['sort'];
    //     $order    = $_GET['order'];
    //     //搜索
    //     $search       = isset($_GET['like']) ? trim( $_GET['like']) : '';
    //     $res['rows']  = $this->pool_sample_model->getmessage( $search, $sort, $order, $pageSize, $first);
    //     //获取总数
    //     $res['total'] = $this->pool_sample_model->get_total();
    //     echo json_encode($res);
    // }
    
    //获取真假类型 by 奚晓俊
    public function getShowTypes(){
        $level = $this->input->get('level',true);
        if($level==2){
            $level = '自定义类型';
        }else{
            $level = null;
        }
        $arr = $this->pool_sample_type_model->getShowTypes($level);
        echo json_encode($arr,256);
    }

    //新增、编辑假类型 by 奚晓俊
    public function do_show_type(){
        $data = $this->do_sample(true);
        unset($data['create_date']);
        unset($data['edit_date']);
        unset($data['fore_end_check']);
        unset($data['back_end_check']);
        unset($data['js']);
        $data['level'] = '自定义类型';
        //判断show_type是否存在
        $arr = $this->pool_sample_type_model->getShowType($data['show_type']);
        //新增
        if($arr===null){
            $res['ret'] = $this->pool_sample_type_model->add_show_type($data);
            if($res['ret']){
                $res['info'] = '新增数据类型成功.';
            }else{
                $res['info'] = '新增数据类型失败.';
            }

        //编辑
        }else{
            if($arr['level']==='自定义类型'){
                $res['ret'] = $this->pool_sample_type_model->edit_show_type($data);
                if($res['ret']){
                    $res['info'] = '编辑数据类型成功.';
                }else{
                    $res['info'] = '编辑数据类型失败.';
                }
            }else{
                $res['ret'] = false;
                $res['info'] = '该样本已存在，且非自定义类型.';
            }
        }
        echo json_encode($res,256);
    }

    //删除假数据类型 by 奚晓俊
    public function delShowType(){
        $show_type = $this->input->get('show_type',true);
        $row = $this->pool_sample_type_model->getShowType($show_type);
        if($row['level']==='自定义类型'){
            $res = $this->pool_sample_type_model->delShowType($show_type);
        }else{
            $res = false;
        }
        echo json_encode($res);
    }

    //验证参数带有样本类型 可选返回当前样本类型配置
    private function _check_sample_type($return_now_sample_type_info=false){
        if( empty($this->sample_type) ||
            empty($this->pool['sample_types']) ||
            !in_array($this->sample_type,array_column($this->pool['sample_types'],'key'))
        ){
            exit(json_encode(['ret'=>false,'info'=>'请选择资料项归属类型.'],256));
        }else{
            if($return_now_sample_type_info){
                foreach ($this->pool['sample_types'] as $key => $val) {
                    if($val['key']==$this->sample_type) {
                        return $val;
                    }
                }
            }
        }
    }

    //获取某节点树型样本数据 by 奚晓俊
    public function getTreeSample(){
        $this->_check_sample_type();
        $id = $this->input->get('id')?$this->input->get('id'):0;
        $getLevel = $this->input->get('getLevel')?$this->input->get('getLevel'):0;
        $formatChildren = $this->input->get('formatChildren');
        // $detailPowers = $this->rolePowerDetails;
        $arr = $this->pool_sample_model->show_tree('*',$id,$getLevel,$formatChildren/*,'',$detailPowers*/);
        echo json_encode($arr);
    }

    //获取树型下拉框样本 by 奚晓俊
    public function getTreeSampleKey(){
        $this->_check_sample_type();
        $arr = $this->pool_sample_model->show_tree('`id`,`pool_key`,`parent_id`,`lft`,`rgt`');
        if($this->input->get('root',true))
            $arr = [[
                'id' => '0',
                'text' => '根目录',
                'children' => $arr,
            ]];
        echo  json_encode($arr);
    }

    //获取enum的元数据 by 奚晓俊
    public function getFiledOptions(){
        $field = $this->input->get('field',true);
        echo json_encode($this->pool_sample_model->getEnumOptions($field));
    }

    //保存样本附加类型 by 奚晓俊
    // public function addSampleType(){}


    /**
     *往证据池样本表（fms_pool_sample）添加数据 废弃代码
     */
    // public function insert_ps()
    // {
    //     // 设置验证规则
    //     $this->form_validation->set_rules('pool_key','证据池名称','required|is_unique[fms_pool_sample.pool_key]');
    //     if ($this->form_validation->run() == false) {
    //         // 未通过验证
    //         $res['code']    = 0;
    //         $res['message'] = htmlentities(validation_errors());
    //         $res['data']    = '';
    //         echo json_encode($res);
    //     }else{
    //         //通过验证
    //         $data['pool_key']    = $this->input->post('pool_key',true);
    //         $data['create_date'] = date('Y-m-d H:i:s');
    //         if ($this->pool_sample_model->add_pool_sample_info($data)) {
    //             $res['code']    = 1;
    //             $res['message'] = '数据添加成功！';
    //             $res['data']    = '';
    //             echo json_encode($res);
    //         }else{
    //             $res['code']    = 0;
    //             $res['message'] = '数据添加失败！';
    //             $res['data']    = '';
    //             echo json_encode($res);
    //         }
    //     }
    // }

    //获取一条样本供编辑 by 奚晓俊
    public function getOneSample(){
        $id = $this->input->get('id',true);
        $r = $this->pool_sample_model->get_one_info($id);
        // $r['data-options'] = '';
        echo json_encode($r);
    }

    //获取指定id的多条样本 by 奚晓俊
    public function getSamplesByIds(){
        $this->_check_sample_type();
        $ids = $this->input->get('ids',true);
        $res = $this->pool_sample_model->get_samples_by_ids($ids);
        echo json_encode($res);
    }

    function check_if_use($id){
        return ($this->pool_model->getSampleUseNum($id)==0);
    }

    function check_key_unique($pool_key){
        return ($this->pool_sample_model->get_key_num($pool_key)==0);
    }

    /**
     * 添加编辑样本 by 奚晓俊 2018-06-26
     */
    public function do_sample($do_show_type=false){
        $this->_check_sample_type();
        $this->load->library('form_validation');

        //基本设置项
        if(!$do_show_type){
            $this->form_validation->set_rules('id', '', 'integer|callback_check_if_use',['check_if_use'=>'该样本数据项已被使用，不得修改！']);
            $this->form_validation->set_rules('parent_id', '父项', 'required|integer');
            $this->form_validation->set_rules('pool_key','资料名称','max_length[20]|required|callback_check_key_unique',['check_key_unique'=>'同层级下资料名称 已被使用且必须唯一']);
            $this->form_validation->set_rules('sample_type','样本归属类型','required|in_list['.join(array_column($this->pool['sample_types'],'key'),',').']');
        }
        $this->form_validation->set_rules('show_type', '数据类型', 'max_length[20]');
        $this->form_validation->set_rules('is_json', '是否为数组', 'in_list[0,1]');
        $this->form_validation->set_rules('type', '真实数据类型', 'required|in_list[判断,文本,数字,日期,时间,日期时间,百分率,文件,图片,派生值,手机号,省市区地址]');
        $this->form_validation->set_rules('class', '输入框样式', 'required|in_list[easyui-textbox,easyui-numberbox,easyui-datebox,easyui-timespinner,easyui-datetimebox,easyui-numberbox rate,easyui-combobox,easyui-filebox,easyui-filebox photo,easyui-textbox derive,easyui-numberbox phone-num,easyui-textbox province]');
        $this->form_validation->set_rules('multiline', '是否多行输入', 'in_list[0,1]');

        //长度限制（文本、手机号、数字）
        $this->form_validation->set_rules('maxLength', '最大长度', 'is_natural|less_than_equal_to[250]');
        $this->form_validation->set_rules('minLength', '最小长度', 'is_natural|less_than_equal_to[250]');

        //派生值专用
        $this->form_validation->set_rules('fun', '公式', 'max_length[200]');

        // 数字专用
        if(@$_POST['class']=='easyui-numberbox' || @$_POST['class']=='easyui-numberbox rate'){
            $this->form_validation->set_rules('prefix', '前缀', 'max_length[10]');
            $this->form_validation->set_rules('suffix', '后缀', 'max_length[10]');
            $this->form_validation->set_rules('max', '最大值', 'numeric');
            $this->form_validation->set_rules('min', '最小值', 'numeric');
            $this->form_validation->set_rules('precision', '小数位数', 'required|in_list[0,1,2,3,4]');
            $this->form_validation->set_rules('groupSeparator', '千分位分隔符', 'max_length[1]');
            $this->form_validation->set_rules('decimalSeparator', '小数点符号', 'max_length[1]');
        }

        //手机号专用
        if(@$_POST['class']=='easyui-numberbox phone-num'){
            $this->form_validation->set_rules('precision', '小数位数', 'required|in_list[0]');
            $this->form_validation->set_rules('filtephonenum', '过滤号段', 'max_length[30]');
        }

        //下拉框专用
        if(@$_POST['class']=='easyui-combobox'){
            $this->form_validation->set_rules('multiple', '下拉框多选', 'in_list[0,1]');
            $this->form_validation->set_rules('data_value[]', '', 'required');
            $this->form_validation->set_rules('data_text[]', '', 'required');
        }

        if ($this->form_validation->run()) {
            //基本设置项
            if(!$do_show_type){
                $where['id'] = $this->input->post('id',TRUE);
                if(!$where['id'])
                    $data['parent_id'] = $this->input->post('parent_id',TRUE);
                $data['pool_key'] = $this->input->post('pool_key',TRUE);
                // $this->sample_type = $this->input->post('sample_type',TRUE);
            }
            $data['is_json'] = $this->input->post('is_json',TRUE);
            $data['type'] = $this->input->post('type',TRUE);
            $data['show_type'] = $this->input->post('show_type',TRUE);
            $data['class'] = $this->input->post('class',TRUE);

            // 暂留字段 开始----------
            $data['js'] = $this->input->post('js',TRUE);
            $data['fore_end_check'] = [];
            $data['back_end_check'] = [];
            // 暂留字段 结束----------

            if(!@$where['id']) $data['create_date'] = date('Y-m-d H:i:s');
            $data['edit_date'] = date('Y-m-d H:i:s');
            $data['data-options'] = [];

            $data['data-options']['multiline'] = (int) $this->input->post('multiline',TRUE);

            //强关联（一一对应）验证
            $this->check_type_class($data);

            //长度限制（文本、手机号、数字）
                if(isset($_POST['minLength']) && $_POST['minLength']!=''){
                    $minLength = (int)$this->input->post('minLength',true);
                }else{
                    $minLength = 0;
                }
                if (isset($_POST['maxLength']) && $_POST['maxLength']!='') {
                    $maxLength = (int)$this->input->post('maxLength',true);
                } else {
                    $maxLength = 250;
                }
                //验证大小
                if($minLength<=$maxLength){
                    //前端验证
                    $data['fore_end_check'][] = 'length['.$minLength.','.$maxLength.']';
                    //后端验证
                    $data['back_end_check'][] = 'max_length['.$maxLength.']|min_length['.$minLength.']';
                    //数值
                    $data['data-options']['maxLength'] = $maxLength;
                    $data['data-options']['minLength'] = $minLength;
                }else{
                    $ret['ret'] = false;
                    $ret['info'] = '最小长度必须小于等于最大长度！';
                    exit(json_encode($ret));
                }

            //小数位数（数字、手机号）

            //不同输入框专有设置
                switch ($_POST['class']) {
                    case 'easyui-datebox'://日期 强关联
                        $this->check_type_by_class($data,['日期']);
                        array_unshift($data['fore_end_check'],'date');
                        $data['back_end_check'][] = 'callback_check_date';
                        break;
                    case 'easyui-timespinner'://时间 强关联
                        $this->check_type_by_class($data,['时间']);
                        array_unshift($data['fore_end_check'],'time');
                        $data['back_end_check'][] = 'callback_check_time';
                        break;
                    case 'easyui-datetimebox'://日期时间 强关联
                        $this->check_type_by_class($data,['日期时间']);
                        array_unshift($data['fore_end_check'],'datetime');
                        $data['back_end_check'][] = 'callback_check_datetime';
                        break;
                    case 'easyui-textbox derive'://派生值 强关联
                        // $this->check_type_by_class($data,['派生值']);
                        $data['is_json'] = '0';
                        $data['data-options'] += [
                            'buttonIcon'=>'icon-reload',
                            'fun'=>$this->input->post('fun',true),
                        ];
                        //后端验证 ???
                        // $data['back_end_check'][] = 'callback_check_derive';
                        break;
                    case 'easyui-numberbox phone-num'://手机号 强关联
                        // $this->check_type_by_class($data,['手机号']);
                        $data['data-options']['precision'] = 0;//小数位数
                        $data['back_end_check'][] = 'is_natural';
                        if($_POST['filtephonenum']!=''){
                            $filtephonenum = $this->input->post('filtephonenum',TRUE);
                            $data['data-options']['filtephonenum'] = $filtephonenum;
                            array_unshift($data['fore_end_check'],'mobile['.$filtephonenum.']');
                            $data['back_end_check'][] = 'callback_check_mobile['.$filtephonenum.']';
                        }
                        break;
                    case 'easyui-textbox province'://省市区地址
                        $data['back_end_check'][] = 'callback_check_province';
                        array_unshift($data['fore_end_check'],'province');
                        $data['is_json'] = '0';
                        $data['data-options'] += ['buttonIcon'=>'icon-filter'];
                        break;
                    case 'easyui-numberbox'://数字 弱关联
                    case 'easyui-numberbox rate':
                        //大小验证
                        if($_POST['min']>$_POST['max']){
                            $ret['ret'] = false;
                            $ret['info'] = '最小值必须小于等于最大值！';
                            exit(json_encode($ret,256));
                        }

                        //可空
                        $arr = ['prefix','suffix','max','min','groupSeparator'];
                        foreach ($arr as $key => $val) {
                            $str = $this->input->post($val,TRUE);
                            if ($str!=='') {
                                if($val=='max' || $val=='min'){
                                    $data['data-options'][$val] = (int) $str;
                                }else{
                                    $data['data-options'][$val] = $str;
                                }
                            }
                        }
                        $data['data-options'] += [
                            'precision' => (int) $this->input->post('precision',TRUE),
                            'decimalSeparator' => $this->input->post('decimalSeparator',TRUE),
                        ];

                        //后端验证
                        $data['back_end_check'][] = 'numeric';
                        $data['back_end_check'][] = 'callback_check_precision['.$data['data-options']['precision'].']';
                        if(isset($data['data-options']['max']))
                            $data['back_end_check'][] = 'less_than_equal_to['.$data['data-options']['max'].']';
                        if(isset($data['data-options']['min']))
                            $data['back_end_check'][] = 'greater_than_equal_to['.$data['data-options']['min'].']';
                        break;
                    case 'easyui-combobox'://下拉选择框 弱关联
                        //下拉框是否多选 随is_json
                        if($data['is_json']=='1'){
                            $data['data-options']['multiple'] = 1;
                        }else{
                            $data['data-options']['multiple'] = 0;
                        }

                        foreach ($_POST['data_value'] as $key => $val) {
                            $arr[] = ['value'=>$val,'text'=>$_POST['data_text'][$key]];
                        }

                        $data['data-options'] += [
                            'data' => $arr,
                            'data_value' => $this->input->post('data_value',TRUE),
                            'data_text' => $this->input->post('data_text',TRUE),
                        ];
                        break;
                }
            $data['back_end_check'] = join('|',$data['back_end_check']);
            $data['fore_end_check'] = json_encode(['validType'=>$data['fore_end_check']],256);
            $data['data-options'] = json_encode($data['data-options'],JSON_UNESCAPED_UNICODE);

            if(!$do_show_type){
                //编辑
                if ($where['id']) {
                    $ret['ret'] = $this->pool_sample_model->edit_pool_sample_info($where,$data);
                    $ret['info'] = $ret['ret']?'资料项修改成功':'资料项修改失败';
                //新建
                }else{
                    $ret['ret'] = $this->pool_sample_model->add_pool_sample_info($data);
                    $ret['info'] = $ret['ret']?'资料项添加成功':'资料项添加失败';
                }
            }else{
                return $data;
            }
        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }

        echo json_encode($ret,256);
    }

    //批量从备份添加样本，并添加参数权限，程序员操作方法 会删除全部参数权限 谨慎操作 by 奚晓俊
    private function foreach_insert(){
        $backTable = 'fms_pool_sample_copy';
        if(!$this->db->table_exists($backTable) || $this->db->count_all($backTable)<100){
            echo $backTable.'不存在或数据不足！';
            exit();
        }

        $sql = "delete from `sys_role_method` where `method_id` in (select id from `sys_class_method` where `detail`!='')";
        $this->db->query($sql);
        $this->db->where('detail !=','')->delete('sys_class_method');

        $this->db->truncate('fms_pool_sample');
        $samples = $this->db->order_by('lft')->get($backTable)->result_array();

        $this->load->helper('checkrolepower');
        foreach ($samples as $key => $val) {
            if ($this->db->insert('fms_pool_sample',$val)) {
                $id = $this->db->insert_id();
                if($id){
                    /*添加池子样本时，同步添加参数权限*/
                    $name = $val['pool_key'];
                    $detail = $id;
                    poolSampleAddDetail($name, $detail, $this->methods);
                }
                echo "<p>'{$val['pool_key']}'样本及参数权限添加成功</p>";
            }else{
                echo "<p><red>'{$val['pool_key']}'样本及参数权限添加失败</red></p>";
            }
        }
    }

    // 获取样本数据
    // public function get_xlinfo()
    // {
    //     $this->load->helper('checkrolepower');
    //     $resrpd_arr = getRolePowerDetails( 'pool', 'add_pool');
    //     $user_id = $this->input->get('user_id');
    //     $res = $this->pool_sample_model->getxl($user_id, $resrpd_arr);
    //     echo json_encode($res);
    // }

    //删除一条fms_pool_sample的记录
    public function del_pool_s(){
        $id = $this->input->get('id');
        if (empty($id)){
            $res['code']    = 0;
            $res['message'] = 'id不能为空！';
            $res['data']    = '';
            echo json_encode($res);
            exit();
        }
        //子节点is_del验证开始 by 奚晓俊 -------------------
            if($this->pool_sample_model->get_no_del_son_num($id)>0){
                $res['code']    = 0;
                $res['message'] = '存在子数据，请先删除子数据！';
                $res['data']    = '';
                echo json_encode($res);
                exit();
            }

        //子节点is_del验证结束 by 奚晓俊 -------------------

        $r = $this->pool_sample_model->del_ps($id);
        if ($r){
            $res['code']    = 1;
            $res['message'] = '数据删除成功！';
            echo json_encode($res);
        }else{
            $res['code']    = 0;
            $res['message'] = '数据删除失败！';
            echo json_encode($res);
        }
    }

    //复制样本权限 by 奚晓俊
    public function copy_role(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', '', 'integer');
        $this->form_validation->set_rules('copy_id', '', 'integer');
        if ($this->form_validation->run()) {
            $id = $this->input->post('id',true);
            $copy_id = $this->input->post('copy_id',true);
            $this->load->model('Sys_model','sys');

            //删除参数==$id的旧权限
            $ids = array_column($this->sys->get_class_method(['id'],['detail'=>$id]),'id');
            $this->sys->del_role_power('',['method_id',$ids]);

            //获取copy_id的权限
            $arr = $this->sys->get_methodandrole_by_detail($copy_id);
            $success_num = 0;
            $error_num = 0;
            foreach ($arr as $key => $val) {
                $bool = $this->sys->insert_roles($id,$val);
                if($bool===true) {
                    $success_num++;
                }else{
                    $error_num++;
                }
            }
            $ret['ret'] = $success_num>0?true:false;
            $ret['info'] = "复制授权成功 {$success_num} 条";
            if($error_num>0) $ret['info'] .= "<br>复制授权失败 {$error_num} 条";
        }else{
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }
        echo json_encode($ret);
    }
//样本结束=======================================================

//证据池开始===================================================

    //输出证据池列表
    public function list_pool(){
        $data = $this->_check_sample_type(true);
        $id = $data['id'] = $this->input->get('id',true);
        $pool_id = $this->input->get('pool_id',true);
        if($pool_id!==null && $id==null)
            $id = $data['id'] = $this->pool_model->get_pool_by_poolid($pool_id)[$data['id_field']];

        switch ($this->sample_type) {
            case 'user':
                $this->load->model('user/User_model','user');
                $data += $this->user->get_user_info_by_id($id);
                break;
            case 'house':
                $data += ['house_code'=>1,'address'=>'静安区'];
                break;
            case 'order':
                $data += ['order_code'=>1];
                break;
        }

        $this->load->helper(['checkrolepower','publicstatus']);
        $data['statusColor'] = json_encode($this->statusColor);
        $this->sbtn_option->edit->method='do_pool';
        $data['editDetails'] = getRolePowerDetails($this->sbtn_option->edit->class,$this->sbtn_option->edit->method);
        $data['sample_type'] = $this->sample_type;
        $this->showpage('fms/list_pool', $data);
    }

    //获取证据池表的数据 1
    // public function pool()
    // {
    //     $page     = $this->input->get('page');
    //     $pageSize = $this->input->get('rows');
    //     $first    = $pageSize * ($page - 1);
    //     //搜索
    //     $search   = $this->input->get('like');
    //     //获取user_id;
    //     $user_id  = $this->input->get('user_id');
    //     if ($user_id) {
    //         $where = $this->rolePowerDetails;//获取当前角色当前方法的所有参数权限（2018-6-7 更新）
    //         // print_r($where);die;
    //         $res['rows']  = $this->pool_model->get_pool_info( $pageSize, $first, $search, $user_id, $where);
    //         $res['total'] = $this->pool_model->get_total( $search, $user_id);
    //     }else{
    //         $res['rows']  = $this->pool_model->get_pool_info( $pageSize, $first, $search);
    //         $res['total'] = $this->pool_model->get_total( $search);
    //     }
    //     echo json_encode($res);
    // }

    //获取证据池表的树数据 1
    public function pool(){
        $this->_check_sample_type();
        $obj_id  = $this->input->get('obj_id',true);
        if ($obj_id) {
            $where = $this->rolePowerDetails;//获取当前角色当前方法的所有参数权限（2018-6-7 更新）
            $tree_res = $this->pool_model->get_pool_tree($obj_id, $where);
        }
        // print_r($tree_res);die;
        echo json_encode($tree_res);
    }

    // 获取证据池表、样本表某id节点的所有子孙数据 by 奚晓俊
    private function get_pools_by_poolid(){
        $pool_id = $this->input->get('pool_id');
        $detailPowers = $this->rolePowerDetails;
        $pool = $this->pool_model->get_pool_by_poolid($pool_id);
        if($pool==[]) exit('[]');
        $res = $this->pool_model->get_pools_by_pool($pool,$detailPowers);
        echo json_encode($res);
    }

    // 获取证据池表、样本表某id节点的所有子孙数据 by 奚晓俊
    private function get_pools_by_sampleid_objid(){
        $now_pool_info = $this->_check_sample_type(true);
        $sample_id = $this->input->get('sample_id',true);
        $obj_id = $this->input->get('obj_id',true);

        $detailPowers = $this->rolePowerDetails;
        $sample = $this->pool_sample_model->get_one_info($sample_id);
        if($sample==[]) exit('[]');
        $sample[$now_pool_info['id_field']] = $obj_id;
        $res = $this->pool_model->get_pools_by_pool($sample,$detailPowers);
        echo json_encode($res,256);
    }

    // 选择pool_id或sample_id、obj_id调用pool已有数据 by 奚晓俊
    private function get_pools(){
        $type = $this->input->get('type',true);
        switch ($type) {
            case null:
                return;
            case 'getPoolsByPoolid':
                $this->get_pools_by_poolid();
                break;
            case 'getPoolsBySampleidObjid':
                $this->get_pools_by_sampleid_objid();
                break;
            case 'getTreeSample':
                $this->getTreeSample();
                break;
        }
        exit();      
    }

    //派生值公式、策略公式测试值 by 奚晓俊
    public function evalDeriveFun(){
        $fun = $this->input->post('fun');
        $sample_vals = $this->input->post('sample',true);
        // $this->sample_type = $this->input->post('sample_type',true);
        if(!is_array($sample_vals)){
            $sample_vals = [];
            $samples_ref = [];
        }else{
            //去池子数据内子数据空值
            $this->do_pool_trim_sample_vals($sample_vals,true,false);
            if($sample_vals != []) {
                $sample_ids = array_keys($sample_vals);
                //取样本数据（规则） 去派生值、接口值，做后端验证
                $samples = $this->pool_sample_model->get_samples_by_ids($sample_ids);
                //引用样本
                $samples_ref = $this->do_pool_ref_samples($samples);
                //过滤池子数据
                $this->do_pool_filter_sample_vals($samples_ref,$sample_vals,false);
                //后端验证
                $this->do_pool_back_end_check($samples_ref,$sample_vals);
            }else{
                $samples = [];
                $samples_ref = [];
            }
        }

        //测试值是派生值
        $res = $this->getRecursionDeriveFun($fun,$samples_ref,$sample_vals);

        if($res['ret'] && is_bool($res['info'])) $res['info'] = (int) $res['info'];
        // var_dump($fun);
        // if(is_array($res['info'])) $res['info'] = join($res['info']);
        echo json_encode($res,256);
    }

    //递归获取派生值公式 by 奚晓俊 
    private function getRecursionDeriveFun($fun,&$samples_ref=[],&$sample_vals=[]){
 
        preg_match_all("/{{(\d*)}}/", $fun, $fun_arr);
        // var_dump($fun_arr);
        if($fun_arr[1]!=[]){
            //补充不存在的样本格式
            $diff_arr = array_diff($fun_arr[1],array_keys($samples_ref));
            if(!empty($diff_arr)){
                $arr = $this->pool_sample_model->get_samples_by_ids($diff_arr);
                foreach ($arr as $key => $val) {
                    $samples_ref[$val['id']] =& $arr[$key];
                }
            }
            foreach ($fun_arr[1] as $id) {
                if($samples_ref[$id]['class']=='easyui-textbox derive'){
                    $tmp = json_decode($samples_ref[$id]['data-options'],true);
                    if(!isset($tmp['fun'])){
                        $res['ret'] = false;
                        @$res['info'][$id] = $samples_ref[$id]['pool_key'].'{{'.$samples_ref[$id]['id'].'}}'.'未定义公式！';
                    }else{
                        $tmp = $this->getRecursionDeriveFun($tmp['fun'],$samples_ref,$sample_vals);
                        if(!$tmp['ret']){
                            $res['ret'] = false;
                            if(isset($res['info'])){
                                $res['info'] += $tmp['info'];
                            }else{
                                $res['info'] = $tmp['info'];
                            }
                        }else{
                            $copy_arr['{{'.$id.'}}'] = $tmp['info'];
                        }
                    }
                }else{
                    if(isset($sample_vals[$id])){
                        $copy_arr['{{'.$id.'}}'] = $sample_vals[$id];
                    }else{
                        $res['ret'] = false;
                        @$res['info'][$id] = $samples_ref[$id]['pool_key'].'{{'.$samples_ref[$id]['id'].'}}值为空！';
                    }
                }
            }
        }else{
            $copy_arr = [];
        }

        if(!isset($res['ret'])){
            $fun = html_entity_decode(strtr($fun,$copy_arr));
            if(!strpos($fun, 'return')) $fun = 'return '.$fun;
            if($fun{strlen($fun)-1}!==';') $fun .= ';';
            $res['info'] = @eval($fun);
            $res['ret'] = true;
            $res['fun'] = $fun;
        }
        // var_dump($fun);
        return $res;
    }

    //新增、编辑证据池数据 by xxj 
    public function do_pool(){
        //写数据参数权限给数据
        $this->get_pools();
        //初步验证
        $this->do_pool_first_check();

        //通过验证后接受数据
        // $pool_id = $this->input->post('pool_id',true);
        $obj_id = $this->input->post('obj_id',true);
        $parent_id = $this->input->post('pool_sample_id',true);
        $sample_vals = $this->input->post('sample',true);

        //去池子数据内子数据空值
        $this->do_pool_trim_sample_vals($sample_vals);

        $sample_ids = array_keys($sample_vals);
        //判断有没有权限写入
        $this->load->helper(['checkrolepower','publicstatus']);
        checkDetails($sample_ids,true);

        //取样本数据（规则） 去派生值、接口值，做后端验证
        $samples = $this->pool_sample_model->show_tree('*',$parent_id,0,false);

        //引用样本
        $samples_ref = $this->do_pool_ref_samples($samples);
        //过滤池子数据
        $this->do_pool_filter_sample_vals($samples_ref,$sample_vals);
        //后端验证
        $this->do_pool_back_end_check($samples_ref,$sample_vals);
        //写入数据库
        $this->do_pool_in_db($obj_id,$sample_ids,$sample_vals);
    }

    // 池子数据初步验证 by 奚晓俊
    private function do_pool_first_check(){
        $this->now_pool_info = $this->_check_sample_type(true);
        // 设置验证规则
        $this->form_validation->set_rules('pool_id','','integer');
        $this->form_validation->set_rules('obj_id','归属对象id','required|integer');
        $this->form_validation->set_rules('sample_type','样本归属类型','required|in_list['.join(array_column($this->pool['sample_types'],'key'),',').']');
        // $this->form_validation->set_rules('obj_name','归属对象','required');
        $this->form_validation->set_rules('pool_sample_id','资料项','required|integer');

        if ($this->form_validation->run() == false) {
            // 未通过验证
            $res['ret']  = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $res['info'] = validation_errors();
            exit(json_encode($res));
        }
    }

    //去池子数据空值 by 奚晓俊
    private function do_pool_trim_sample_vals(&$sample_vals,$trim=false,$empty_exit=true){
        //去数组值的空值
        foreach ($sample_vals as $key => $val) {
            if(is_array($val)){
                foreach ($val as $k => $v) {
                    if($v==='') unset($val[$k]);
                }
                $sample_vals[$key] = array_values($val);
            }
            //编辑时原有数据改为空值 不能简单去空值
            if($trim && empty($val)) unset($sample_vals[$key]);
        }
        //数据全为空 返回报错
        if($empty_exit && $sample_vals == []) {
            $res['ret']  = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $res['info'] = '请录入客户资料后提交！';
            exit(json_encode($res));
        }
    }

    //引用样本 by 奚晓俊
    private function do_pool_ref_samples(&$samples){
        foreach ($samples as $key => $val) {
            //引用id
            $samples_ref[$val['id']] = &$samples[$key]; 
        }
        return $samples_ref;
    }

    //过滤池子数据 by 奚晓俊
    private function do_pool_filter_sample_vals($samples_ref,&$sample_vals,$unset_derive=true){
        foreach ($sample_vals as $key => $val) {
            if($val==='') continue;
            //过滤值
            switch ($samples_ref[$key]['class']) {
                case 'easyui-textbox derive'://删除派生值
                    if($unset_derive){
                        unset($sample_vals[$key]);
                    }
                    // }else{
                    //     $fun = json_decode($samples_ref[$key]['data-options'],true)['fun'];
                    //     $sample_vals[$key] = $this->getRecursionDeriveFun($fun,$samples_ref,$sample_vals,true);
                    // }
                    break;
                case 'easyui-numberbox rate'://百分率类型／100
                    $sample_vals[$key] /= 100;
                    break;
                case 'easyui-datebox'://日期格式去周
                    $sample_vals[$key] = explode('(',$val)[0];
                    break;
                case 'easyui-timespinner'://时间格式补秒
                    $arr = explode(':',$val);
                    if(!isset($arr[2])){
                        $arr[2] = '00';
                        $sample_vals[$key] = join(':',$arr);
                    }
                    break;
                case 'easyui-datetimebox'://日期时间去周
                    $sample_vals[$key] = preg_replace("/\(.*\)/","",$val);
                    break;
            }
        }
    }

    //后端验证 by 奚晓俊
    private function do_pool_back_end_check($samples_ref,&$sample_vals){
        foreach ($sample_vals as $key => $val) {
            //不为空后端验证值
            if(!empty($val)){
                $this->form_validation->set_rules('sample['.$key.']',$samples_ref[$key]['pool_key'],$samples_ref[$key]['back_end_check']);
            }
        }

        if ($this->form_validation->run() == false) {
            // 未通过验证
            $res['ret']  = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $res['info'] = validation_errors();
            exit(json_encode($res,256));
        }    
    }

    //池子数据读写验证状态入数据库 by 奚晓俊
    private function do_pool_in_db($obj_id,$sample_ids,$sample_vals){
        //获取该客户已有池子数据
        $old_data = $this->pool_model->getpoolinfo_l($obj_id,$sample_ids);
        $old_ref = [];
        $update_num = 0;
        $insert_num = 0;
        foreach ($old_data as $key => $val) {
            $old_ref[$val['pool_sample_id']] = &$old_data[$key];
        }

        //写数据库
        foreach ($sample_vals as $key => $val) {
            $data = [];
            $data['pool_val'] = is_array($val)?json_encode($val,256):$val;
            $data['edit_date'] = date('Y-m-d H:i:s');

            //编辑
            if(isset($old_ref[$key])){
                if(
                    //判断数据是否改变
                    $old_ref[$key]['pool_val'] != $data['pool_val']
                    &&
                    //判断可编辑状态
                    checkOneEditStatus('','',$old_ref[$key]['obj_status'])
                ){
                    $data['pool_id'] = $old_ref[$key]['pool_id'];
                    if($this->pool_model->edit_pool_info($data)) $update_num++;
                }

            //新增
            }else{
                if($data['pool_val']=='') continue;
                $data[$this->now_pool_info['id_field']] = $obj_id;
                $data['pool_sample_id'] = $key;
                $data['create_date'] = date('Y-m-d H:i:s');
                if($this->pool_model->add_pool_info($data)) $insert_num++;
            }
        }

        $r['info'] = '';
        if($update_num ==0 && $insert_num==0){
            $r['info'] .= "没有任何数据变化";
            $r['ret'] = true;
        }else{
            if($update_num>0) $r['info'] .= "成功更新".$update_num."条数据<br>";
            if($insert_num>0) $r['info'] .= "成功新增".$insert_num."条数据";
            $r['ret'] = true;
        }

        echo json_encode($r);
    }



    //池子数据写入后端验证 by 奚晓俊 开始==================
        //验证手机限制号段  by 奚晓俊
        function check_mobile($value,$filte_num){
            $arr = explode(',',$filte_num);
            foreach ($arr as $key => $val) {
                if(strpos($value,$val)===0){
                    $this->form_validation->set_message('check_mobile', '{field}禁用{param}号段！');
                    return false;
                }
            }
            return true;
        }

        //验证日期  by 奚晓俊
        function check_date($date){
            $date = explode('(',$date)[0];
            $this->form_validation->set_message('check_date', '“{field}”不符合日期格式yyyy-MM-dd或yyyy-M-d！');
            $dateArr1 = explode('-',$date);
            $dateArr2 = explode('-',date('Y-m-d',strtotime($date)));
            return ($dateArr1[0]==$dateArr2[0] && $dateArr1[1]==$dateArr2[1] && $dateArr1[2]==$dateArr2[2]);
        }

        //验证时间  by 奚晓俊
        function check_time($time){
            if($time===null) return true;
            $arr = explode(":",$time);
            $h = @$arr[0];
            $m = @$arr[1];
            $s = isset($arr[2])?$arr[2]:'00';
            $this->form_validation->set_message('check_time', '“{field}”不符合时间格式hh:mm.ss！');
            if(strlen($h)!=2 || strlen($m)!=2 || strlen($s)!=2) return false;
            return ($h>=0 && $h<23 && $m>=0 && $m<59 && $s>=0 && $s<59);
        }

        //验证日期时间  by 奚晓俊
        function check_datetime($datetime){
            $arr = explode(' ',$datetime);
            $date = $this->check_date($arr[0]);
            $time = $this->check_time($arr[1]);
            return ($date && $time);
        }

        //验证小数位数  by 奚晓俊
        function check_precision($num,$param){
            if($num===null) return true;
            $arr = explode('.',$num);
            $num = isset($arr[1])?strlen($arr[1]):0;
            $arr = [0,1,2,3,4];
            $this->form_validation->set_message('check_precision','“{field}”的小数位数不符合规定，规定小数位数为{param}');
            return (in_array($param,$arr) && $num==$param);
        }
        
        //验证百分率  by 奚晓俊
        function check_rate($float){
            if (round($float,4)==$float && floor($float)<100) {
                return true;
            }else{
                $this->form_validation->set_message('check_rate', '“{field}”百分率保留2位整数和4位小数！');
                return false;
            }
        }

        //验证省市区是否符合层级关系--write by 陈恩杰 2018/7/24
        public function check_province($address=''){
            $this->load->model('pool_model');
            if($this->router->method==__FUNCTION__) $address = $this->input->get('address',true);

            $arr_address = explode(",",$address);
            if(count($arr_address)!=3){
                $arr_add_res = ['code'=>0,'info'=>'省市区地址不全！'];
            }else{
                foreach ($arr_address as $k=>$v){
                    if ($k != 2){
                        if($v=='香港特别行政区'||$v=='澳门特别行政区'){
                            $arr_address[$k] = mb_substr($v,0,-5);
                        }else{
                            $arr_address[$k] = mb_substr($v,0,-1);
                        }
                    }
                }

                $arr_add_res = $this->pool_model->check_city_level_info($arr_address);
            }

            if($this->router->method==__FUNCTION__){
                echo json_encode($arr_add_res);
            }else{
                if($arr_add_res['code']){
                    return true;
                }else{
                    $this->form_validation->set_message('check_province', "“{field}” 的{$arr_add_res['info']}！");
                    return false;
                }
            }
        }

        //根据输入框检查数据类型 by 奚晓俊
        private function check_type_by_class($data,$types=[]){
            if(!in_array($data['type'],$types)){
                $ret['ret'] = false;
                $ret['info'] = '类型与输入框样式不符';
                exit(json_encode($ret));
            }
        }

        //检查数据类型和输入框是否相符(强制一一对应) by 奚晓俊
        private function check_type_class($data){
            $rules = [
                'easyui-textbox derive' => '派生值',
                'easyui-numberbox phone-num' => '手机号',
                'easyui-textbox province' => '省市区地址',
                'easyui-numberbox rate' => '百分率',
            ];
            $rules2 = array_flip($rules);
            if(isset($rules[$data['class']]) || isset($rules2[$data['type']]))
                if(@$rules[$data['class']] !== $data['type'] || @$rules2[$data['type']] !== $data['class']){
                    $ret['ret'] = false;
                    $ret['info'] = '该类型与输入框样式不符';
                    exit(json_encode($ret));
                }
        }

    //池子数据写入后端验证 by 奚晓俊 结束==================

    /**
     * 改变状态
     */
    private function do_status($fun){

        $this->load->library('form_validation');

        $this->form_validation->set_rules('pool_id[]', '', 'required');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            return json_encode($ret);
        }
        $obj_ids = $this->input->post('pool_id',true);
        $obj_ids = array_keys($obj_ids);
        $status_info = $this->input->post('status_info',true);
        $for_admins = $this->input->post('for_admins',true);

        $this->load->helper(['publicstatus','checkrolepower']);
        /****判断当前方法参数组权限****/
        $sample_ids = $this->pool_model->get_sample_ids_by_obj_ids($obj_ids);
        checkDetails($sample_ids,true);
        /****************************/

        $now_pool_info = $this->_check_sample_type(true);
        $obj_type = $now_pool_info['status_type'];

        //批量改
        $this->db->trans_start();
        foreach ($obj_ids as $val) {
            $res[] = ($fun($obj_type,$val,$status_info,$for_admins));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 报审证据池 1
     */
    public function baoShen(){
        $this->get_pools();
        echo $this->do_status('baoShenStatus');
    }

    /**
     * 初审通过证据池 1
     */
    public function guoChuShen(){
        $this->get_pools();
        echo $this->do_status('guoChuShenStatus');
    }

    /**
     * 初审驳回证据池 1
     */
    public function backChuShen(){
        $this->get_pools();
        echo $this->do_status('backChuShenStatus');
    }

    /**
     * 复审通过证据池 1
     */
    public function guoFuShen(){
        $this->get_pools();
        echo $this->do_status('guoFuShenStatus');
    }

    /**
     * 复审驳回证据池 1
     */
    public function backFuShen(){
        $this->get_pools();
        echo $this->do_status('backFuShenStatus');
    }

    /**
     * 审核通过 1
     */
    public function guoShen(){
        $this->get_pools();
        echo $this->do_status('guoShenStatus');
    }

    /**
     * 审核退回 1
     */
    public function backShen(){
        $this->get_pools();
        echo $this->do_status('backShenStatus');
    }

    /**
     * 停用证据池 1
     */
    public function stop(){
        $this->get_pools();
        echo $this->do_status('stopStatus');
    }

    /**
     * 启用证据池 1
     */
    public function start(){
        $this->get_pools();
        echo $this->do_status('startStatus');
    }

    /**
     * 申请修改证据池 1
     */
    public function pleaseEdit(){
        $this->get_pools();
        echo $this->do_status('pleaseEditStatus');
    }

    /**
     * 批准修改证据池 1
     */
    public function yesEdit(){
        $this->get_pools();
        echo $this->do_status('yesEditStatus');
    }

    /**
     * 驳回修改证据池 1
     */
    public function noEdit(){
        $this->get_pools();
        echo $this->do_status('noEditStatus');
    }

    /**
     * 显示证据池流程信息 1
     */
    public function history(){
        $pool_id = $this->input->get('obj_id',true);
        $status_type = $this->input->get('status_type',true);
        $this->load->helper('publicstatus');
        $arr = historyStatus($status_type,$pool_id);
        echo json_encode($arr);
    }

    /**
     * 提醒证据池流程 1
     */
    public function remind(){
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'required|integer');
        $this->form_validation->set_rules('remind_admins[]', '', 'required');
        $this->form_validation->set_rules('remind_info', '', 'max_length[240]');
        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
        }else{
            $obj_id = $this->input->post('obj_id',true);
            $remind_admins = $this->input->post('remind_admins[]',true);
            $remind_info = $this->input->post('remind_info',true);
            $this->load->helper('publicstatus');
            $ret['ret'] = remindStatus('Pool',$obj_id,$remind_admins,$remind_info);
            /****判断当前方法参数组权限****/
            $ps_id = $this->pool_model->get_sample_id_by_obj_id($obj_id);
            $this->load->helper('checkrolepower');
            $cd = checkDetails(array($ps_id),true);
            /****************************/
            if($ret['ret']){
                $ret['info'] = '提醒成功！';
            }else{
                $ret['info'] = '提醒失败！';
            }
        }
        echo json_encode($ret);
    }

//证据池结束============================================

//池子选择入口开始===================================================
    //输出池子入口界面
    public function list_pool_type(){
        $data['sample_types'] = json_encode($this->pool['sample_types']);
        $data['sample_type'] = $this->pool['sample_type'];
        $this->showpage('fms/list_pool_type',$data);
    }

    public function get_init_pool_method(){
        $this->_check_sample_type();
        $fun = array_column($this->pool['sample_types'],'init_pool_method','key')[$this->sample_type];
        $this->$fun();
    }
    
    //输出客户信息
    private function get_user(){
        $page     = $this->input->get('page',true);
        $pageSize = $this->input->get('rows',true);
        $first    = $pageSize * ($page - 1);
        //搜索
        $search   = $this->input->get('like',true);
        $this->load->service('user/User_service','userser');
        $res = $this->userser->get_user_list_for_pool($search, $first = 0, $pageSize = 10);
        $res['rows'] = &$res['list'];
        unset($res['list']);
        echo json_encode($res);
    }

    //输出房屋信息
    private function get_house(){
        echo json_encode([
            'rows'=>[[
                'id'=>1,
                'house_id'=>1,
                'address'=>'静安区',
            ]],
            'total'=>1
        ]);
    }

    //输出订单信息
    private function get_order(){
        echo json_encode([
            'rows'=>[[
                'id'=>1,
                'order_id'=>1,
            ]],
            'total'=>1
        ]);
    }

//池子选择入口结束===================================================

//决策 开始================================================
    //获取决策页面 by 奚晓俊
    public function list_decision(){
        $this->load->helper(['publicstatus','checkrolepower']);
        //$data['statusColor'] = json_encode($this->statusColor);
        $this->showpage('fms/list_decision');
    }

    //获取决策数据 by 奚晓俊
    public function get_decision(){
        $id = $this->input->get('id',true);
        $this->load->model("pool_decision_model");

        //获取指定策略id
        if($id) {
            $res = $this->pool_decision_model->get_decision_by_id($id);

        //获取列表
        }else{
            $like = $this->input->get('like',true);
            $rows = $this->input->get('rows',true);
            $page = $this->input->get('page',true);
            $sort = $this->input->get('sort',true);
            // if($sort=='obj_status_info') $sort='obj_status';
            $order = $this->input->get('order',true);
            $res = $this->pool_decision_model->list_decision($like,$page,$rows,$sort,$order,$id);
        }
        echo json_encode($res);
    }

    //获取全部决策供下拉框 by 奚晓俊
    public function select_decision(){
        $this->load->model("pool_decision_model");
        $res = $this->pool_decision_model->select_decision();
        echo json_encode($res,256);
    }

    //获取决策树数据 by 奚晓俊
    // public function getTreeDecision(){
    //     // $id = $this->input->get('id')?$this->input->get('id'):0;
    //     // $getLevel = $this->input->get('getLevel')?$this->input->get('getLevel'):0;
    //     // $formatChildren = $this->input->get('formatChildren');
    //     // $detailPowers = $this->rolePowerDetails;
    //     $this->load->model('pool_decision_model');
    //     $select = $this->input->get('select',true);
    //     if(empty($select)){
    //         $select = '*';
    //     }else{
    //         $select .= ',lft,parent_id,level';
    //     }
    //     $arr = $this->pool_decision_model->get_decision_tree($select);
    //     if($this->input->get('root',true))
    //         $arr = [[
    //             'id' => '0',
    //             'text' => '根目录',
    //             'children' => $arr,
    //         ]];
    //     echo json_encode($arr);
    // }

    //新增编辑决策 by 奚晓俊
    public function do_decision(){
        $this->load->library('form_validation');
        $this->load->model('pool_decision_model');

        $this->form_validation->set_rules('id', '', 'integer');
        // $this->form_validation->set_rules('parent_id', '父项', 'required|integer');
        if(empty($_POST['id'])){
            $this->form_validation->set_rules('name','策略名称','max_length[20]|required|is_unique[fms_pool_decision.name]');
        }else{
            $this->form_validation->set_rules('name','策略名称','max_length[20]|required');
        }
        $this->form_validation->set_rules('fun_info','策略公式描述','max_length[255]');
        $this->form_validation->set_rules('fun','策略公式描述','max_length[20000]');
        // $this->form_validation->set_rules('res_level', '策略结果等级', 'in_list[0,1]');
        // $this->form_validation->set_rules('res_info','策略结果信息','max_length[255]');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            exit(json_encode($ret,256));
        }
        $return_value = $this->input->post('return_value');
        $return_text = $this->input->post('return_text');

        foreach ($return_value as $key => $val) {
            if($val==='') {
                exit(json_encode(['ret'=>false,'info'=>'第'.($key+1).'个结果值缺失']));
            }else{
                $data['return'][$val] = $return_text[$key];
            }
        }
        $data['return'] = json_encode($data['return'],256);

        $where['id'] = $this->input->post('id',TRUE);
        $data['name'] = $this->input->post('name',TRUE);
        $data['fun_info'] = $this->input->post('fun_info',TRUE);
        $data['fun'] = $this->input->post('fun');
        $data['edit_admin_id'] = $_SESSION['fms_id'];

        if(!@$where['id']) $data['create_date'] = date('Y-m-d H:i:s');
        $data['edit_date'] = date('Y-m-d H:i:s');

        //编辑
        if ($where['id']) {
            $ret['ret'] = $this->pool_decision_model->edit_decision($data,$where);
            $ret['info'] = $ret['ret']?'策略修改成功':'策略修改失败';
        //新建
        }else{
            $ret['ret'] = $this->pool_decision_model->add_decision($data);
            $ret['info'] = $ret['ret']?'策略添加成功':'策略添加失败';
        }

        echo json_encode($ret,256);
    }

    // //根据id获取一条策略 by 奚晓俊
    // public function get_decision_by_id(){
    //     $id = $this->input->get('id',true);
    //     $this->load->model('pool_decision_model');
    //     $res = $this->pool_decision_model->get_decision_by_id($id);
    //     echo json_encode($res,256);
    // }

    //删除一条策略 by 奚晓俊
    public function del_decision(){
        $id = $this->input->get('id',true);
        $this->load->model('pool_decision_model');
        $res = $this->pool_decision_model->del_decision($id);
        echo json_encode($res);
    }
//决策 结束================================================

//决策树 开始================================================
    //策略树页面
    public function list_decision_tree(){
        $this->load->helper('checkrolepower');
        $this->showpage('fms/list_decision_tree');
    }

    //获取决策树数据 by 奚晓俊
    public function get_decision_tree(){
        // $id = $this->input->get('id')?$this->input->get('id'):0;
        // $getLevel = $this->input->get('getLevel')?$this->input->get('getLevel'):0;
        $formatChildren = $this->input->get('formatChildren');
        // $detailPowers = $this->rolePowerDetails;
        $this->load->model('pool_decision_tree_model','dt');
        $select = $this->input->get('select',true);
        $tree_id = $this->input->get('id',true)?'id':'tree_id';
        if(empty($select)){
            $select = '*';
        }else{
            $select .= ',lft,parent_id,level';
        }
        $arr = $this->dt->get_decision_tree($select,$tree_id);
        if($this->input->get('root',true))
            $arr = [[
                'id' => '0',
                'tree_id' => '0',
                'text' => '根目录',
                'children' => $arr,
                'parent_return' => '根目录',
            ]];
        echo json_encode($arr);
    }

    //获取动作 by 奚晓俊
    public function get_parent_return_actions($return=false){
        // $action_arrs = [
        //     ['id'=>'ret_true','text' =>'✅继续执行'],
        //     ['id'=>'ret_info','text' =>'❕输出信息并继续执行'],
        //     ['id'=>'ret_false','text'=>'❓错误并退出当前分支'],
        //     ['id'=>'ret_exit','text' =>'❌立即全部退出'],
        // ];
        $action_arrs[] = [
            ['id'=>'ret_go_on','text' =>'✅继续执行后续分支'],
            // ['id'=>'ret_return','text' =>'❕输出信息并继续执行'],
            ['id'=>'ret_return','text'=>'❓返回当前分支结果'],
            ['id'=>'ret_exit','text' =>'❌立即全部退出'],
        ];
        $action_arrs[] = [
            'ret_go_on' =>'✅执行',
            'ret_return' =>'❓返回',
            'ret_exit' =>'❌退出',
        ];

        if ($return) {
            return $action_arrs;
        }else{
            echo json_encode($action_arrs);
        }
    }

    //新增编辑决策树 by 奚晓俊
    public function do_decision_tree(){
        $this->load->library('form_validation');
        $this->load->model('pool_decision_tree_model');

        //初始验证
            $this->form_validation->set_rules('parent_id', '父项', 'required|integer');
            $this->form_validation->set_rules('return[]', '', 'required');
            if (!$this->form_validation->run()) {
                $ret['ret'] = false;
                $this->form_validation->set_error_delimiters('', '<br>');
                $ret['info'] = validation_errors();
                exit(json_encode($ret,256));
            }

            $decision_id = $this->input->post('decision_id',true);
            $parent_id = $this->input->post('parent_id',true);
            $data = $this->input->post('return',true);

        //验证每个结果的动作和后续策略id
            $action_arrs = $this->get_parent_return_actions(true)[0];
            $action_arr = array_column($action_arrs,'id');
            $ret['ret'] = true;
            $ret['info'] = '';
            foreach ($data as $key => $val) {
                //是否验证策略ID真实性？？？
                if (!is_numeric($val['decision_id']) && $val['decision_id']!=='') {
                    $ret['ret'] = false;
                    $ret['info'] .= '结果值为 '.$key.' 的后续策略不正确<br>';
                }
                if(!in_array($val['parent_return_action'],$action_arr)){
                    $ret['ret'] = false;
                    $ret['info'] .= '结果值为 '.$key.' 的动作不正确<br>';
                }
            }
            if (!$ret['ret']) exit(json_encode($ret,256));

        //写入数据库
            $add_num = $edit_num = 0;
            foreach ($data as $key => $val) {
                $val['parent_id'] = $parent_id;
                $val['parent_return'] = $key;

                $tree = $this->pool_decision_tree_model-> get_one_by_parentid_and_parentreturn($val['parent_id'],$val['parent_return']);
                //新增
                if($tree===null){
                    if($this->pool_decision_tree_model->add_decision_tree($val)) $add_num++;
                //编辑
                }else{
                    //差异检查
                    $bool_diff = false;
                    foreach ($val as $k => $v) {
                        if($v!=$tree[$k]){
                            $bool_diff = true;
                            break;
                        }
                    }
                    if($bool_diff && $this->pool_decision_tree_model->edit_decision_tree($val,['tree_id'=>$tree['tree_id']])) $edit_num++;
                }
            }
            if ($this->db->trans_status()){
                $res['ret'] = $parent_id;
                $res['info'] = '成功新增 '.$add_num.' 条数据.<br>成功更新 '.$edit_num.' 条数据.';
            }else{
                $res['ret'] = false;
                $res['info'] = '数据编辑失败.';
            }

        echo json_encode($res,256);
        // $this->output->enable_profiler(TRUE);
    }

    //删除一条策略树 by 奚晓俊
    public function del_decision_tree(){
        $tree_id = $this->input->get('tree_id',true);
        $this->load->model('pool_decision_tree_model');
        $res = $this->pool_decision_tree_model->del_decision_tree($tree_id);
        echo json_encode($res);
    }

    //获取一组父子策略树(含策略详情) by 奚晓俊
    public function get_one_group_by_treeid(){
        $tree_id = $this->input->get('tree_id');
        $this->load->model('pool_decision_tree_model');
        $res = $this->pool_decision_tree_model->get_one_group_by_treeid($tree_id);
        echo json_encode($res,256);
    }

    //策略执行 by 奚晓俊
    public function eval_decision_tree(){
        if(!isset($_GET['user_id'])) exit(json_encode(['ret'=>false,'info'=>'缺失用户']));
        $this->user_id = $this->input->get('user_id',true);

        $this->samples_ref = [];
        $this->sample_vals = [];
        $this->recursionNum = 0;//总递归起始数量(含派生值、策略树)
        $this->show_info = [];//策略树输出信息
        $this->test_model = true;//开启测试模式 输出完整数组
        $this->load->model('pool_decision_tree_model','dtm');
        //获取数据库策略树
        $select = '*';
        $tree = $this->dtm->get_decision_tree($select,'tree_id','string');
        foreach ($tree as $key => &$val) {
            $ret[] = $this->eval_recursion_tree_branch($val);
        }

        var_dump(Slog::log($ret));
        echo "<pre>";
        print_r($tree);
        print_r($ret);
        print_r($this->show_info);

    }

    //递归执行策略树分支
    private function eval_recursion_tree_branch(&$val){
        //计算公式
            $r = $this->pool_model->getRecursionDeriveValue($val['fun'],1000);
            if(!$r['ret']) exit(json_encode($r));
            if($this->test_model){
                // $val['eval_fun'] = $r['fun'];//取值替换后公式
                // $val['eval_ret'] = $r['ret'];//取值过程是否正确
                // $val['eval_val'] = is_bool($r['info'])?(int)$r['info']:$r['info'];//公式结果 bool转1或0 
                $val = [
                    'eval_fun' => $r['fun'],//取值替换后公式
                    'eval_ret' => $r['ret'],//取值过程是否正确
                    'eval_val' => is_bool($r['info'])?(int)$r['info']:$r['info'],//公式结果 bool转1或0 
                ]+$val;
            }

        //结果动作
            $son =& $val['children'][$val['eval_val']];
            if($son['parent_return_info'])
                $this->show_info[$val['tree_id']] = $son['parent_return_info'];

            switch ($son['parent_return_action']) {
                case 'ret_go_on'://执行后需策略
                    //没儿子（逻辑上不该存在）
                    if(!isset($val['children'][$val['eval_val']]))
                        exit(json_encode([
                            'info'=>$val['name'].'{{'.$val['tree_id'].'}}后续策略或动作缺失.',
                            'ret'=>false,
                        ]));
                    $r['children'] = $this->eval_recursion_tree_branch($son);
                    return $r;
                    break;
                case 'ret_exit'://立即全部退出
                    exit(json_encode(['ret'=>false,'info'=>$son['parent_return_info']]));
                    break;
                case 'ret_return'://返回分支值
                    $r['children'] = ['ret'=>false,'info'=>$son['parent_return_info']];
                    return $r;
                    break;
            }
    }
//决策树 结束================================================










}