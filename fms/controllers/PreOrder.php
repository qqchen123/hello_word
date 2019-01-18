<?
/**
 * @desc pre order 报单 后台控制器
 * 前台页面在 client/PreOrder (计划) 
 */
class PreOrder extends Admin_Controller
{
	/**
	 * @name 构造函数
	 */
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('', 'preorder');

        // $this->basicloadhelper();

        // //根据业务类型加载不同的service 组合
        // $business_type = $this->uri->segment(2);

        // $this->load->service('user/User_service', 'user_service');

        // if (in_array($business_type, ['index'])) {
        //     $this->load->service('public/Html_service', 'html_service');
        // }
        
        // //字段定义
        // $this->field = [
        //     'idcard'=>'身份证',
        //     'mc'=>'结婚证',
        //     'household'=>'户口本',
        //     'house'=>'房产证',
        //     'credit'=>'征信',
        //     'agreement'=>'合约',
        // ];
    }

    /**
     * @name 列表页
     */
    public function index()
    {
    	if (!empty($_POST)) {
    		$condition = $this->input->post('condition',true);
    		$page = $this->input->post('page',true);
    		$page = $this->input->post('page',true);
            if($condition == 'err'){$condition = '';}
            $retfinddata = $this->preorder->get_list($condition, $page, $rows);
            if (isset($retfinddata['order_id'])) {
                $retfinddata_tmp = [];
                $retfinddata_tmp[] = $retfinddata;
                $retfinddata = $retfinddata_tmp;
            }
            if (isset($retfinddata['data'])) {
                $total = $retfinddata['total'];
                $retfinddata = $retfinddata['data'];
            }
            if(!isset($retfinddata[0]['order_id'])){
                Slog::log($retfinddata);
                exit;
            } else {
                // Slog::log($retfinddata[0]);
                foreach($retfinddata as $k=>$v){
                    $c_list[$k]["order_id"] = $v['order_id'];//报单编号
                    $c_list[$k]["user_id"] = $v['user_id'];//客户编号
                    $c_list[$k]["status"] = $v['status'];//状态
                    //解析 json的statis信息
                    $stats_info = json_decode($v['pic_stats']);

                    $c_list[$k]["idcard_stats"] = empty($stats_info['idcard_stats']) ? '?/?' : $stats_info['idcard_stats'];//身份证信息统计
                    $c_list[$k]["house_stats"] = empty($stats_info['house_stats']) ? '?/?' : $stats_info['house_stats'];//房产证信息统计
                    $c_list[$k]["household_stats"] = empty($stats_info['household_stats']) ? '?/?' : $stats_info['household_stats'];//户口本信息统计
                    $c_list[$k]["mc_stats"] = empty($stats_info['mc_stats']) ? '?/?' : $stats_info['mc_stats'];//结婚证信息统计 marriage certificate
                    $c_list[$k]["credit_stats"] = empty($stats_info['credit_stats']) ? '?/?' : $stats_info['credit_stats'];//个人征信信息统计

                    $c_list[$k]["oper"] = $v['oper'];//业务员
                    $c_list[$k]["ctime"] = $v['ctime'];//创建时间
                    if (isset($v['status'])) {
                        //输出状态按钮
                        $c_list[$k]["op"] = '<span class="dn op" id="op'. $v['order_id'] .'" data-status='. implode('-', $v['status']) .'>'. '</span>';
                    } else {
                        $c_list[$k]["op"] = '';
                    }
                }
            }
            $reponseData = [];
            $reponseData['rows'] = $c_list;
            $reponseData["total"] = !empty($total) ? $total : count($c_list);
            echo json_encode($reponseData);
		} else {
			$data = [
				'statusColor' => json_encode($this->statusColor),
                'jscontroller' => $this->html_service->no_backspace(),
                'btnCode' => showStatusBtn(true),
			];
    		$this->showpage('fms/preorder/index', $data); 
		}
    }

    /**
     * @name 创建报单编号
     * @other 根据客户信息、业务员信息、报单时间 创建编号
     * 		  提供给客户端页面ajax调用 调用位置：起始页提交时调用
     */
    public function create_pre_order()
    {
    	$info = $_POST;
    	//检查数据内容是否完整
    	$name = $this->input->post('name',true);
    	$idnumber = $this->input->post('idnumber',true);
    	$opnum = $this->input->post('opnum',true);
    	$channel = $this->input->post('channel',true);

    	if (!empty($name) && !empty($idnumber) && !empty($opnum) && !empty($channel)) {
    		//检查员工编号


    		//查询用户是否已有客户编号 有的话直接使用
    		//如果没有则根据 渠道编号+user表自增ID 拼接创建
    		@$_SESSION['fms_userrole'] = 19;
            $_SESSION['fms_role_name'] = 'H5';
            $_SESSION['fms_id'] = 14;
            $_SESSION['fms_username'] = 'H5';
            //获取入参
            $fuserid = $this->user_service->create_uid($channel);
            $ret = $this->user_service->register_user([
                'name' => $name,
                'idnumber' => $idnumber,
                'channel' => $channel,
                'fuserid' => $fuserid,
                'cjyg' => $opnum,
                'ctime' => date('Y-m-d H:i:s', time()),
            ]);
            Slog::log($ret);

    	} else {
    		$message = '参数不完整 请检查';
    		$code = 1;
    	}
    	
    }

    /**
     * @name 上传图片
     */
    public function upload_pic()
    {

    }
    

/*####基础begin############################################*/
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
     * @name ajax相应
     */
    public function ajax_responese($data) 
    {
    	echo json_encode([
    		'code' => $code,
    		'meaasge' => $massage,
    		'data' => $data
    	]);
    	exit;
    }



// 报单后台 by 奚晓俊 开始=========================================

    /**
     * 验证日期
     */
    function check_date($date){
        if (date('Y-m-d',strtotime($date))==$date) {
            return true;
        }else{
            $this->form_validation->set_message('check_date', '“{field}”不符合日期格式！');
            return false;
        }
    }

    //输出后台报单页面
    public function list_pre_order(){
        $this->load->helper(['checkrolepower','publicstatus']);
        $data['statusColor'] = json_encode($this->statusColor);
        $data['pre_order_id'] = $this->input->get('pre_order_id',true);
        $this->showpage('fms/PreOrder/list_pre_order',$data);
    }

    //获取后台报单数据
    public function get_pre_order(){
        $this->load->model('PreOrder_model','po');
        $pre_order_id = $this->input->get('pre_order_id',true);
        $like = $this->input->get('like',true);
        $rows = $this->input->get('rows',true);
        $page = $this->input->get('page',true);
        $sort = $this->input->get('sort',true);
        if($sort=='obj_status_info') $sort='obj_status';
        $order = $this->input->get('order',true);
        $res = $this->po->list_pre_order($like,$page,$rows,$sort,$order,$pre_order_id);

        //json格式（身份证、结婚证、户口本、征信、房产证）转译
        if($res['total']>0){
            foreach ($res['rows'] as $k => $v) {
                $res['rows'][$k]['content'] = json_decode($v['content'],true);
                if($res['rows'][$k]['content']){
                    foreach ($res['rows'][$k]['content'] as $type => $type_v) {
                        $res['rows'][$k][$type.'_num'] = 0;
                        foreach ($type_v as $key => $key_v) {
                            foreach ($key_v['src'] as $kk => $vv) {
                                $res['rows'][$k][$type.'_num'] ++;
                            }
                        }
                    }
                }
            }
        }

        echo json_encode($res);
    }

    // 编辑报单
    public function do_pre_order(){
        // var_dump($_POST);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('obj_id', '', 'required|integer');
        //身份证资料
            $this->form_validation->set_rules("身份证[姓名]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("身份证[性别]", '', 'required|in_list[男,女]');
            $this->form_validation->set_rules("身份证[民族]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("身份证[出生日期]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("身份证[住址]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("身份证[身份证号码]", '', 'required|max_length[18]');
            $this->form_validation->set_rules("身份证[签发机关]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("身份证[有效期限：开始日期]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("身份证[有效期限：结束日期]", '', 'required|callback_check_date');
        //结婚证资料
            $this->form_validation->set_rules("结婚证[登记日期]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("结婚证[结婚证字号]", '', 'required|max_length[20]');
            $this->form_validation->set_rules("结婚证[配偶姓名]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("结婚证[配偶性别]", '', 'required|in_list[男,女]');
            $this->form_validation->set_rules("结婚证[配偶国籍]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("结婚证[配偶出生日期]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("结婚证[配偶身份证号码]", '', 'required|max_length[18]');
            $this->form_validation->set_rules("结婚证[结婚证编号]", '', 'required|integer|max_length[30]');
            $this->form_validation->set_rules("结婚证[登记机关]", '', 'required|max_length[30]');
        //房产证
            $this->form_validation->set_rules("房产证[区县]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[街道]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[街坊号]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[宗地号]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[座落地址]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[所在名义层]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[所在实际层]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[自然层数]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[地下层数]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[幢号与门牌]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[室号与部位]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[建筑面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[套内面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[分摊面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[地下建筑面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[房产证编号]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("房产证[登记日]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("房产证[权利人]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("房产证[房地坐落]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("房产证[土地权属性质]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("房产证[土地使用权取得方式]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("房产证[土地用途]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[完整宗地号]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("房产证[宗地（丘）面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[土地使用权面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[土地独用面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[土地分摊面积]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[土地使用期限（开始日期）]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("房产证[土地使用期限（结束日期）]", '', 'required|callback_check_date');
            $this->form_validation->set_rules("房产证[幢号]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[室号或部位]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[建筑类型]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[用途]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[总层数]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[竣工日期]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("房产证[填证单位]", '', 'required|max_length[10]');
        //户口本资料
            $this->form_validation->set_rules("户口本[1][户口本编号]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("户口本[1][户别]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("户口本[1][户主姓名]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("户口本[1][户号]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("户口本[1][住址]", '', 'required|max_length[30]');
            $this->form_validation->set_rules("户口本[1][登记机关]", '', 'required|max_length[10]');
            $this->form_validation->set_rules("户口本[1][签发时间]", '', 'required|callback_check_date');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            exit(json_encode($ret,256));
        }
        $obj_id = $this->input->post('obj_id',true);
        $where = ['pre_order_id'=>$obj_id];

        //查找原数据
        $this->load->model('PreOrder_model','po');
        $old = $this->po->list_pre_order(null,1,1,null,null,$obj_id)['rows'][0];
        $old['content'] = json_decode($old['content'],true);

        //替换原数据
        $old['content']['idcard']['text'] = $this->input->post('身份证',true);
        $old['content']['mc']['text'] = $this->input->post('结婚证',true);
        $old['content']['house']['text'] = $this->input->post('房产证',true);
        $old['content']['household']['text'] = $this->input->post('户口本',true);

        //写入数据
        $data = [
            'remark' => $this->input->post('remark',true),
            'content' => json_encode($old['content'],256),
        ];

        $res = $this->po->do_pre_order($data,$where);
        if($res){
            echo json_encode(['ret'=>true,'info'=>'编辑成功']);
        }else{
            echo json_encode(['ret'=>false,'info'=>'编辑失败']);
        }
    }

    // public function edit_jpg2(){
    //     if(isset($_GET['url'])){
    //         include $_SERVER["DOCUMENT_ROOT"].'/assets/lib/jquery-cropper-master/docs/index.php';
    //         exit();
    //     }

    //     // var_dump($_POST);
    //     $this->load->library('form_validation');
    //     $this->form_validation->set_rules('img_base64', '', 'required');
    //     $this->form_validation->set_rules('file_name', '', 'required|max_length[20]');
    //     $this->form_validation->set_rules('pre_order_id', '', 'required|integer');
    //     $this->form_validation->set_rules('type', '', 'required|max_length[10]');
    //     $this->form_validation->set_rules('key', '', 'required|max_length[10]');

    //     if (!$this->form_validation->run()) {
    //         $ret['ret'] = false;
    //         $this->form_validation->set_error_delimiters('', '<br>');
    //         $ret['info'] = validation_errors();
    //         exit(json_encode($ret,256));
    //     }

    //     // 验证pre_order_id权限状态 是否可改

    //     $img_base64 = $this->input->post('img_base64');
    //     $file_name = $this->input->post('file_name',true);
    //     $pre_order_id = $this->input->post('pre_order_id',true);
    //     $type = $this->input->post('type',true);
    //     $key = $this->input->post('key',true);
    //     if(!in_array($type,$this->field)) exit(json_encode(['ret'=>false,'info'=>'类型错误']));
    //     $img_base64 = urldecode($img_base64);
    //     if ($a = strstr($img_base64,",")){
    //         $img_base64 = explode(',',$img_base64);
    //         $img_base64 = $img_base64[1];
    //     }

    //     $path = $_SERVER['DOCUMENT_ROOT'].'/upload/preorder/'.$pre_order_id.'/'.$type.'/'.$key;

    //     if (!is_dir($path)){ //判断目录是否存在 不存在就创建
    //        mkdir($path,0777,true);
    //     }

    //     $path .= '/'.$file_name;

    //     $r = file_put_contents($path, base64_decode($img_base64));
    //     if ($r) {
    //        echo json_encode(['ret'=>true,"info"=>"图片保存成功"]);
    //     }else{
    //        echo json_encode(['ret'=>false,"info"=>"图片保存失败"]);
    //     }
    // }

    public function edit_jpg(){
        if(isset($_GET['file_name'])){
            $dir = $this->input->get('dir',true);
            $file_name = $this->input->get('file_name',true);
            $url = '../PublicMethod/getImg?name='.$dir.'/' . $file_name;
            include $_SERVER["DOCUMENT_ROOT"].'/assets/lib/jquery-cropper-master/docs/index.php';
            exit();
        }

        // var_dump($_POST);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('img_base64', '', 'required');
        $this->form_validation->set_rules('file_name', '', 'required|max_length[200]');
        // $this->form_validation->set_rules('pre_order_id', '', 'required|integer');
        // $this->form_validation->set_rules('type', '', 'required|max_length[10]');
        // $this->form_validation->set_rules('key', '', 'required|max_length[10]');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            exit(json_encode($ret,256));
        }

        // 验证pre_order_id权限状态 是否可改

        $img_base64 = $this->input->post('img_base64');
        $file_name = $this->input->post('file_name',true);
        $dir = $this->input->post('dir',true);
        // $pre_order_id = $this->input->post('pre_order_id',true);
        // $type = $this->input->post('type',true);
        // $key = $this->input->post('key',true);
        // if(!in_array($type,$this->field)) exit(json_encode(['ret'=>false,'info'=>'类型错误']));
        $img_base64 = urldecode($img_base64);
        if ($a = strstr($img_base64,",")){
            $img_base64 = explode(',',$img_base64);
            $img_base64 = $img_base64[1];
        }

        // $path = $_SERVER['DOCUMENT_ROOT'].'/upload/preorder/'.$pre_order_id.'/'.$type.'/'.$key;
        $path = '/home/upload/'.$dir.'/'.$file_name;
        $path = '/upload/'.$dir.'/'.$file_name;

        // if (!is_dir($path)){ //判断目录是否存在 不存在就创建
        //    mkdir($path,0777,true);
        // }

        // $path .= '/'.$file_name;

        $r = file_put_contents($path, base64_decode($img_base64));
        if ($r) {
           echo json_encode(['ret'=>true,"info"=>"图片保存成功"]);
        }else{
           echo json_encode(['ret'=>false,"info"=>"图片保存失败"]);
        }
    }

    /*
     * 打包下载 by 奚晓俊
     * $down_name下载文件名
     * $src=[
     *    [
     *      'dir' => '打包文件目录(相对打包文件)', //如 /a/b/c
     *      'name' => '文件重命名(无后缀)', //null或缺失 表示保持原名称
     *      'src' => '相对网站根目录完整文件路径', // '/upload/preorder/1/身份证/2/1.jpg'
     *    ],......
     * ]
    */
    public function down_zip($down_name='',$src=[]){

        $down_name = '1.zip';
        $src = [
            [
                'dir' => '身份证',
                'name' => 'a',
                'src' => '/upload/preorder/1/身份证/2/3.txt',
            ]

        ];

        //允许最大内存使用
            set_time_limit(0);
            ini_set('memory_limit','3072M');

        $path = $_SERVER['DOCUMENT_ROOT'].'/upload/';
        if(!is_dir($path)) exit('目录不存在');

        do{
            $file_name = date('YmdHis').'-'.rand(1,999);
            $file_all_name = $path.$file_name.'.zip';
        }while (file_exists($file_all_name));

        file_put_contents($file_all_name,'');

        //新建对象
            $zip = new ZipArchive();
        //创建空zip包
            if ($zip->open($file_all_name, ZipArchive::CREATE) !== TRUE) throw new Exception("创建空zip文件失败", 1);
        //插入文件
            foreach ($src as $k => $v) {
                $tmp = $_SERVER['DOCUMENT_ROOT'].'/'.trim($v['src'],'/');
                if(!file_exists($tmp)) throw new Exception('文件 "'.$tmp.'" 不存在',1);
                if(!is_readable($tmp)) throw new Exception('文件 "'.$tmp.'" 无权访问',1);

                if(!isset($v['name']) || $v['name']===null){
                    $v['name'] = strrchr($v['src'],'/');
                }else{
                    $v['name'] .= strrchr($tmp,'.');
                }

                $zip -> addFromString (trim($v['dir'],'/').'/'.$v['name'],file_get_contents($tmp,false,null,0,2));               
            }
            $zip->close();

        //下载文件需要用到的头
            Header('Content-type: application/octet-stream');
            Header('Accept-Length:'.filesize($file_all_name));
            Header('Content-Disposition:attachment;filename='.$down_name);

        //清下输出缓存（坑子输出换行）    
            ob_clean();
            // flush(); 

        //全量输出下载
            //readfile($file_all_name);

        //逐步输出下载
            $buffer = 1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器）
            $file_count = 0; //读取的总字节数
            $file_size = filesize($file_all_name);
            $fp = fopen($file_all_name,"r");

            //向浏览器返回数据 如果下载完成就停止输出，如果未下载完成就一直在输出。根据文件的字节大小判断是否下载完成
            while(!feof($fp) && $file_count<$file_size){  
                echo fread($fp,$buffer);  
                $file_count += $buffer;  
            }
            fclose($fp);
            unlink($file_all_name);
    }

    public function del_jpg(){
        // exit(json_encode(['ret'=>true,'info'=>'删除文件成功！']));
        $url = $this->input->post('url',true);
        $data = explode('/',$url);
        $pre_order_id = $data[3];
        $type = array_keys($this->field,$data[4])[0];
        $key = $data[5];
        $file_name = $data[6];
        
        //删除数据库
            //查找原数据
            $this->load->model('PreOrder_model','po');
            $old = $this->po->list_pre_order(null,1,1,null,null,$pre_order_id)['rows'][0];
            $old['content'] = json_decode($old['content'],true);
            $arr =& $old['content'][$type]['src'][$key];
            $str = array_keys($arr,$file_name);
            if(isset($str[0])){
                unset($arr[$str[0]]);
            }else{
                exit(json_encode(['ret'=>false,'info'=>'删除文件失败！']));
            }
            
            //回写数据库
            $data = [
                'content' => json_encode($old['content'],256),
            ];

            $res = $this->po->do_pre_order($data,['pre_order_id'=>$pre_order_id]);

        //删除文件
            if($res && unlink($_SERVER['DOCUMENT_ROOT'].$url)){
                exit(json_encode(['ret'=>true,'info'=>'删除文件成功！']));
            }else{
                exit(json_encode(['ret'=>false,'info'=>'删除文件失败！']));
            }
    }

    //后台新建报单
    public function add(){
        // var_export($_SESSION);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('channel', '渠道', 'required|integer');
        $this->form_validation->set_rules('remark', '备注', 'max_length[255]');

        if (!$this->form_validation->run()) {
            $ret['ret'] = false;
            $this->form_validation->set_error_delimiters('', '<br>');
            $ret['info'] = validation_errors();
            exit(json_encode($ret,256));
        }

        $channel = $this->input->post('channel',true);
        $remark = $this->input->post('remark',true);
        $uid = $_SESSION['fms_id'];
        $pre_order = date('YmdHi').$_SESSION['fms_userid'];

        $this->load->model('PreOrder_model','po');
        echo $this->po->create_record($pre_order,$channel,$uid,$remark);
    }
// 报单后台 by 奚晓俊 结束=========================================
}
?>

