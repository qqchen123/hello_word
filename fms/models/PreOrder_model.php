<?php
class PreOrder_model extends CI_Model{
	// function add_jg($data){
 //        $this->load->helper('publicstatus');
 //        $data['last_edit_time'] = date('Y-m-d H:i:s');

 //        $this->db->trans_start();//开启事务
 //            //插入新对象
 //            $this->db->insert('fms_jigou_info',$data);
 //            $id = $this->db->insert_id();
 //            //插入公共状态
 //            addStatus('jg',$id);
 //        $this->db->trans_complete();

 //        if ($this->db->trans_status()){
 //            return $id;
 //        }else{
 //            return false;
 //        }
 //    }

 //    function edit_jg($data,$where){
 //        $data['last_edit_time'] = date('Y-m-d H:i:s');
 //        $this->db->update('fms_jigou_info',$data,$where);
 //        return ($this->db->affected_rows()==1);
 //    }

    function list_pre_order($like='',$page=1,$rows=10,$sort='pre_order_id',$order='DESC',$pre_order_id=null){

        $this->load->helper('publicstatus');
        joinStatus('pre_order','pre_order_id');
        $this->db->join('wesing_merchant','wesing_merchant.id=fms_pre_order.admin_id');

        if ($pre_order_id){
            $this->db->where('pre_order_id',$pre_order_id);
        }else{
            if ($like){
                $this->db->or_like('pro_order',$like);
                $this->db->or_like('channel',$like);
                $this->db->or_like('username',$like);
            }
        }

        $total = $this->db->count_all_results('fms_pre_order',false);
        $rs = $this->db
            // ->select('fms_user.name,fms_user.idnumber,fms_pre_order.*,pre_order_status.*')
            ->order_by($sort,$order)
            ->limit($rows,($page-1)*$rows)
            ->get();
        // return $this->db->last_query();
        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
            // $res['rows'] = showStatusColor($res['rows']);
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        // echo $this->db->last_query();
        return $res;
    }

    function do_pre_order($data,$where){
        // $data['last_edit_time'] = date('Y-m-d H:i:s');
        $this->db->update('fms_pre_order',$data,$where);
        return ($this->db->affected_rows()==1);
    }


    /**
     * @name 创建报单
     * @param $pre_order string
     * @return id | (异常)
     */
    public function create_record($pre_order, $channel, $admin_id, $remark='')
    {
        $data = [
            'remark' => $remark,
            'pre_order' => $pre_order,
            'channel' => $channel,
            'admin_id' => $admin_id,
            'ctime' => date('Y-m-d H:i:s', time()),
            'content' => json_encode([
                // '身份证' => [
                'idcard' => [
                    '1' => [
                        'src' => [],
                        'type' => [],
                        'text' => [],
                    ]
                ],
                // '结婚证' => [
                'mc' => [
                    '1' => [
                        'src' => [],
                        'type' => [],
                        'text' => [],
                    ]
                ],
                // '户口本' => [
                'household' => [
                    '1' => [
                        'src' => [],
                        'type' => [],
                        'text' => [],
                    ]
                ],
                // '征信' => [
                'credit' => [
                    '1' => [
                        'src' => [],
                        'type' => [],
                        'text' => [],
                    ]
                ],
                // '房产证' => [
                'house' => [
                    '1' => [
                        'src' => [],
                        'type' => [],
                        'text' => [],
                    ]
                ],
                // '合约' => [
                'agreement' => [
                    '1' => [
                        'src' => [],
                        'type' => [],
                        'text' => [],
                    ]
                ],
            ], JSON_UNESCAPED_UNICODE)
        ];

        $this->load->helper('publicstatus');
        // $data['last_edit_time'] = date('Y-m-d H:i:s');

        $this->db->trans_start();//开启事务
            //插入新对象
            $this->db->insert('fms_pre_order',$data);
            $id = $this->db->insert_id();
            //插入公共状态
            addStatus('pre_order',$id);
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return $id;
        }else{
            return false;
        }
    }

    /**
     * @name 查询用户是否已有报单
     * @param $fuserid string 
     * @return array
     */
    public function find_record_by_uid($uid)
    {
        return $this->db
        ->select(['pre_order'])
        ->where(['uid' => $uid])
        ->get('fms_pre_order')->result_array();
    }

    /**
     * @name 查询报单中上传的资料
     * @param $fuserid string 
     * @return array
     */
    public function find_record_by_preorder($preorder)
    {
        return $this->db
        ->select(['pre_order_id', 'content'])
        ->where(['pre_order' => $preorder])
        ->get('fms_pre_order')->row_array();
    }

    /**
     * @name 图片上传 更新src信息到content中
     * @param $content json string 
     * @param $preorder string
     * @return int 0 | 1
     */
    public function upload_pic_src_info($content, $preorder)
    {
        $this->db->set("content", $content);
        $this->db->where(" pre_order = '" . $preorder . "'");
        return $this->db->update('fms_pre_order');
    }














    // function get_jg_by_id($jg_id){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','jg_id');
    //     return $this->db->get_where('fms_jigou_info',['jg_id'=>$jg_id],1)->row_array();
    // }

    // function get_jg_name($where){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','jg_id');
    //     return $this->db->select(['jg_name','jg_id','obj_status'])->where($where)->order_by('obj_status','desc')->get('fms_jigou_info')->result_array();
    //     // return $this->db->last_query();
    // }

    // function list_product($jg_status,$jg_id,$like='',$page=1,$rows=10,$sort='jg_id',$order='ASC',$product_id=null){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','jp.jg_id');
    //     joinStatus('jg_product','jp.product_id');
    //     if ($like) $this->db->like('product_name',$like);
    //     if ($jg_status) $this->db->where('jg_status',$jg_status);
    //     if ($jg_id) $this->db->where('jp.jg_id',$jg_id);
    //     if($product_id) $this->db->where('jp.product_id',$product_id);
    //     $total = $this->db
    //         ->select([
    //             'jg_status.obj_status jg_status',
    //             'jg_status.obj_status_info jg_status_info',
    //             'jg_product_status.obj_status obj_status',
    //             'jg_product_status.obj_status_info obj_status_info',
    //             'jg.jg_name',
    //             'jp.*'
    //         ])
    //         ->join('fms_jigou_info jg','jp.jg_id = jg.jg_id')
    //         ->count_all_results('fms_jigou_product jp',false);
    //     $rs = $this->db
    //         ->order_by($sort,$order)
    //         ->limit($rows,($page-1)*$rows)
    //         ->get();
    //     // return $this->db->last_query();

    //     if ($rs->num_rows() > 0) {
    //         $res["total"]= $total;
    //         $res['rows'] = $rs->result_array();
    //         $rs->free_result();
    //     } else {
    //         $res["total"]= 0;
    //         $res["rows"] = '';
    //     }
    //     return $res;
    // }

    // function add_product($data){
    //     $data['last_edit_time'] = date('Y-m-d H:i:s');

    //     $this->db->trans_start();//开启事务
    //         //插入新对象
    //         $this->db->insert('fms_jigou_product',$data);
    //         $id = $this->db->insert_id();
    //         //插入公共状态
    //         addStatus('jg_product',$id);
    //     $this->db->trans_complete();

    //     if ($this->db->trans_status()){
    //         return $id;
    //     }else{
    //         return false;
    //     }
    // }

    // function edit_product($data,$where){
    //     $data['last_edit_time'] = date('Y-m-d H:i:s');
    //     $this->db->update('fms_jigou_product',$data,$where);
    //     return ($this->db->affected_rows()==1);
    // }

    // function get_jg_by_productid($product_id){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','jg_id');
    //     return $this->db
    //         ->select(['jg.*','jg_status.*','jp.product_id'])
    //         ->join('fms_jigou_product jp','jp.jg_id=jg.jg_id')
    //         ->get_where('fms_jigou_info jg',['product_id'=>$product_id],1)
    //         ->row_array();
    // }

    // function get_product_by_id($product_id){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','jp.jg_id');
    //     joinStatus('jg_product','jp.product_id');
    //     return $this->db
    //         ->select([
    //             'jg_status.obj_status jg_status',
    //             'jg_status.obj_status_info jg_status_info',
    //             'jg_product_status.obj_id',
    //             'jg_product_status.obj_status obj_status',
    //             'jg_product_status.obj_status_info obj_status_info',
    //             'jg.jg_name',
    //             'jp.*'
    //         ])
    //         ->join('fms_jigou_info jg','jp.jg_id = jg.jg_id')
    //         ->get_where('fms_jigou_product jp',['product_id'=>$product_id],1)
    //         ->row_array();
    // }

    // function list_cost($jg_id,$product_id,$page=1,$rows=10,$sort='product_id',$order='ASC'){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','cost.jg_id');
    //     joinStatus('jg_product','cost.product_id');

    //     if ($product_id) $this->db->where(['cost.product_id'=>$product_id]);
    //     if ($jg_id) $this->db->where(['cost.jg_id'=>$jg_id]);

    //     $total = $this->db
    //         ->select([
    //             'zm.zval cost_type_name',//zm名称
    //             'jg_status.obj_status jg_status',//jg状态
    //             'jg_status.obj_status_info jg_status_info',//jg状态info
    //             'jg_product_status.obj_status product_status',//机构产品状态
    //             'jg_product_status.obj_status_info product_status_info',//机构产品状态info
    //             'jg_name','product_name',
    //             'cost.*'
    //         ])
    //         ->join('wesing_zamk zm','ztype = "cost" and zm.zname = cost.cost_type')
    //         ->join('fms_jigou_product product','product.product_id = cost.product_id')
    //         ->join('fms_jigou_info jg','jg.jg_id = cost.jg_id')
    //         ->count_all_results('fms_jigou_product_cost cost',false);
    //     $rs = $this->db
    //         ->order_by($sort,$order)
    //         ->limit($rows,($page-1)*$rows)
    //         ->get();
    //     // return $this->db->last_query();

    //     if ($rs->num_rows() > 0) {
    //         $res["total"]= $total;
    //         $res['rows'] = $rs->result_array();
    //         $rs->free_result();
    //     } else {
    //         $res["total"]= 0;
    //         $res["rows"] = '';
    //     }
    //     return $res;
    // }

    // function get_product_name($where){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg_product','product_id');
    //     return $this->db->select(['product_name','product_id','obj_status'])->where($where)->order_by('obj_status','desc')->get('fms_jigou_product')->result_array();
    // }

    // function get_cost_name(){
    //     return $this->db->select(['zname','zval'])
    //         ->where('ztype = "cost"')
    //         ->get('wesing_zamk')->result_array();
    // }

    // function add_cost($data){
    //     $this->db->trans_start();
    //         $sql = "INSERT INTO `fms_jigou_product_cost`
    //             (`jg_id`, `product_id`, `cost_type`, `cost_rate`, `is_before`, `pay_type`, `last_edit_time`)
    //             select `jg_id`,{$data['product_id']},{$data['cost_type']},{$data['cost_rate']},{$data['is_before']},{$data['pay_type']},now()
    //             from `fms_jigou_product`
    //             where product_id = {$data['product_id']}";
    //         $this->db->query($sql);
    //         //$this->edit_product(['status' => 1,'status_info' =>'待提交：新建收费标准'],['product_id' => $data['product_id']]);
    //     $this->db->trans_complete();

    //     if ($this->db->trans_status()){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    // function edit_cost($data,$where){
    //     $this->db->trans_start();
    //         unset($data['product_id']);
    //         $data['last_edit_time'] = date('Y-m-d H:i:s');
    //         $this->db->update('fms_jigou_product_cost',$data,$where);
    //         // $product_id = $this->db->select('product_id')->get_where('fms_jigou_product_cost',$where,1)->row_array()['product_id'];
    //         // $this->edit_product(['status' => 1,'status_info' =>'待提交：编辑收费标准'],['product_id' => $product_id]);

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status()){
    //         // $this->db->trans_commit();
    //         return true;
    //     }else{
    //         // $this->db->trans_rollback();
    //         return false;
    //     }
    // }

    // function get_cost_by_id($cost_id){
    //     return $this->db
    //         ->select([
    //             'jg.jg_name',
    //             'product.product_name',
    //             'cost.*',
    //         ])
    //         ->join('fms_jigou_info jg','jg.jg_id = cost.jg_id')
    //         ->join('fms_jigou_product product','product.product_id = cost.product_id')
    //         ->get_where('fms_jigou_product_cost cost',['cost_id'=>$cost_id],1)
    //         ->row_array();
    //     // return $this->db->last_query();
    // }

    // function add_contact($data){
    //     $data['last_edit_time'] = date('Y-m-d H:i:s');
    //     $this->db->insert('fms_contact',$data);
    //     return $this->db->insert_id();
    // }

    // function edit_contact($data,$where){
    //     $data['last_edit_time'] = date('Y-m-d H:i:s');
    //     $this->db->update('fms_contact',$data,$where);
    //     return ($this->db->affected_rows()==1);
    // }

    // function list_contact($jg_status,$jg_id,$like='',$page=1,$rows=10,$sort='belong_id',$order='ASC'){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','ct.belong_id');
    //     if ($like) $this->db->like('ct.ct_name',$like);
    //     if ($jg_status) $this->db->where('jg.status',$jg_status);
    //     if ($jg_id) $this->db->where('jg.jg_id',$jg_id);
    //     $total = $this->db
    //         ->select([
    //             'jg_status.obj_status jg_status',
    //             'jg_status.obj_status_info jg_status_info',
    //             'jg.jg_name',
    //             'ct.*'
    //         ])
    //         ->join('fms_jigou_info jg','ct.belong_id = jg.jg_id and ct_type = "jg"')
    //         ->count_all_results('fms_contact ct',false);
    //     $rs = $this->db
    //         ->order_by($sort,$order)
    //         ->limit($rows,($page-1)*$rows)
    //         ->get();
    //     // return $this->db->last_query();

    //     if ($rs->num_rows() > 0) {
    //         $res["total"]= $total;
    //         $res['rows'] = $rs->result_array();
    //         $rs->free_result();
    //     } else {
    //         $res["total"]= 0;
    //         $res["rows"] = '';
    //     }
    //     return $res;
    // }

    // function get_contact_by_id($ct_id){
    //     $this->load->helper('publicstatus');
    //     joinStatus('jg','ct.belong_id');
    //     return $this->db
    //         ->select([
    //             'jg_status.obj_status jg_status',
    //             'jg_status.obj_status_info jg_status_info',
    //             'jg.jg_id',
    //             'jg.jg_name',
    //             'ct.*'
    //         ])
    //         ->join('fms_jigou_info jg','belong_id = jg_id')
    //         ->get_where('fms_contact ct',['ct_id'=>$ct_id,'ct_type'=>'jg'],1)
    //         ->row_array();
    // }
}
?>