<?php 

/**
 * @desc 录入基础资料 用户信息 手机卡信息 银行卡信息 机构开户信息 service
 */
class RecordBasicData_service extends Admin_service
{
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
	}

    /**
     * @name 更新数据
     * @param array $data 
     *        $data 结构
     *        ['business_type'=>'user', 
     *
     *        ]
     * @return array ['code' => status, 'data' => data, 'message' => string]
     */
    public function update_data($data)
    {
        //数据分配
        //业务类型 单业务类型有效
        $business_type = $data['business_type'];
        $business_model  = $data['business_model'];
        $business_service = $data['business_service'];
        $business_pool_sample_method = $data['business_pool_sample_method'];
        //返回的url
        $finish_url = $data['finish_url'];
        //去要前置接收的数据
        //['post_key',['new_key'=>'post_key']] 两种写法
        $pre_post = $data['pre_post'];
        //业务规则拦截设置 业务拦截的参数必须前置接收  不然不生效
        //['post_key' => method]  [需要拦截的key => 拦截调用的方法]
        $rule_post = $data['rule_post'];
        //上传文件错误提示前缀
        $upload_error_notice = $data['upload_error_notice'];

        //init 变量
        $str = '';
        $info = [];
        $code = 1;
        foreach ($pre_post as $key => $value) {
            if (!is_string($key)) {
                $info[$value] = isset($_POST[$value]) ? $_POST[$value] : '';
            } else {
                $info[$key] = isset($_POST[$value]) ? $_POST[$value] : '';
            }
        }
        $this->load->model($business_model[0], $business_model[1]);
        $model = $data['business_model'][1];
        //检查id
        if (empty($info['id']) && 'user' != $data['business_type']) {
            //创建记录
            $ret = $this->$model->insert_record($data['business_type'], $info['fuserid']);
            if ($ret > 0) {
                $info['id'] = $ret;
            } else {
                //新增记录失败 
                echo json_encode(
                    [
                        'msg' => '新增记录失败', 
                        'url' => site_url($data['finish_url'])
                    ]
                );
                exit;
            }
        }

        //业务规则拦截
        if (!empty($rule_post)) { 
            $this->load->service('business/Rule_service', 'rs');
            foreach ($rule_post as $key => $value) {
                if (isset($info[$key]) && !$this->rs->$value($info[$key])) {
                    echo json_encode(['msg' => '该号段不符合', 'url' => site_url($finish_url)]);
                    exit;
                }
            }
        }

        //获取该业务类型所关联的证据池样本
        foreach (array_keys($this->$model->$business_pool_sample_method()) as $pool_value) {
            $info[$pool_value] = $this->input->post($pool_value, true);
        }

        //文件上传
        if (!empty($_FILES) && isset($info[$data['upload_key']])) {
            $this->load->service('public/File_service', 'fs');
            $upload_ret = $this->fs->upload_img_front_and_back(
                $info['fuserid'], 
                $data['upload_floder']
            );
            Slog::log($upload_ret);
            if (isset($upload_ret['data'])) {
                //成功 
                Slog::log('文件上传成功');
                Slog::log($upload_ret['data']);
                $info = array_merge($info, $upload_ret['data']);
            } else {
                //失败 这个位置目前是失效的 @fixme
                // $this->scriptcomplete(500, $upload_error_notice . $upload_ret['message']);
                $str = $upload_error_notice . $upload_ret['message'];
                $code = 0;
            }
        }
        return [
            'code' => $code,
            'data' => $info,
            'message' => $str,
        ];
    }

}