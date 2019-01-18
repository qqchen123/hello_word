<?php
class BaodanStatus_model extends CI_Model{
    public function __construct(){
        //状态历史表数据结构
        $this->status_history_data = [
            'role_id' => @$_SESSION['fms_userrole'],
            'role_name' => @$_SESSION['fms_role_name'],
            'admin_id' => @$_SESSION['fms_id'],
            'admin_name' => @$_SESSION['fms_username'],

            'status_edit_time' => date('Y-m-d H:i:s'),

            'obj_type' => 'bd',
            'obj_id' => null,//需赋值
            'obj_status' => null,//需赋值
            'obj_status_info' => '',//需赋值
            'note' => ''//需赋值
        ];

        //状态定义
        $this->statusArr = [
            404 => '报单终止',
            14 => '业务补件中',

            20 => '待录入',
            21 => '录入中',
            23 => '录入中',
            24 => '补录中',
    
            30 => '待初评',
            31 => '初评中',
            33 => '初评中',
            34 => '机构退回',
            38 => '机构审核完成',
                
            40 => '待机构审核',
            41 => '机构审核中',
            43 => '机构审核中',
                
            50 => '待业务经理审核',
            51 => '业务经理审核中',
            53 => '业务经理审核中',
            58 => '待预约进件',
        ];

        //动作定义
        $this->statusAction = [
            404 => '终止报单',
            14 => '退回业务',

            20 => '业务首报录入',
            21 => '业务补件录入',
            23 => '录入抢单',
            24 => '退回录入',
    
            30 => '录入首报评估',
            31 => '录入补件评估',
            33 => '评估抢单',
            34 => '退回评估',
            38 => '机构过审',
                
            40 => '评估首报机构',
            41 => '机构审核中',
            43 => '机构审核中',
                
            50 => '待业务经理审核',
            51 => '业务经理审核中',
            53 => '业务经理审核中',
            58 => '待预约进件',
        ];

        //状态前提条件
        $this->statusIf = [
            //终止报单
                404 => [
                    //录入终止
                    ['status'=>14,'admin_id'=>$_SESSION['fms_id']],
                    //评估终止
                    ['status in (33,31,34,38)'=>null,'pg_admin_id'=>$_SESSION['fms_id']],
                ],
            //退回业务
                14 => [
                    //录入退回
                    ['status in (23,21,24)'=>null,'lr_admin_id'=>$_SESSION['fms_id']],
                    //评估退回
                    ['status in (33,31,34,38)'=>null,'pg_admin_id'=>$_SESSION['fms_id']],
                ],

            // 20 => '待录入',不需要
            //业务补件录入 14业务补件中->21录入中
                21 => ['status'=>14,'admin_id'=>$_SESSION['fms_id'],'lr_admin_id is not null'=>null],
            //录入抢单 20->23
                23 => ['status'=>20,'lr_admin_id is null'=>null],
            //退回录入 (33,31,34,38)->24
                24 => ['status in (33,31,34,38)'=>null,'pg_admin_id'=>$_SESSION['fms_id']],
            //录入首报评估 (23,21,24)->30
                30 => ['status in (23,21,24)'=>null,'lr_admin_id'=>$_SESSION['fms_id'],'pg_admin_id is null'=>null],
            //录入补件评估
                31 => ['status in (23,21,24)'=>null,'lr_admin_id'=>$_SESSION['fms_id'],'pg_admin_id is not null'=>null],
            //评估抢单
                33 => ['status'=>30,'pg_admin_id is null'=>null],
            //
                // 34 => '机构退回',
                // 38 => '机构审核完成',
                    
                // 40 => '待机构审核',
                // 41 => '机构审核中',
                // 43 => '机构审核中',
                    
                // 50 => '待业务经理审核',
                // 51 => '业务经理审核中',
                // 53 => '业务经理审核中',
                // 58 => '待预约进件',
        ];

        //状态
        // $this->
    }

    // //判断是否能停用
    // function if404($bd_id){
    //     return (bool) $this->db
    //         ->join('fms_baodan','fms_baodan.bd_id=fms_baodan_status.bd_id')
    //         //录入终止
    //         ->where(['fms_baodan_status.bd_id'=>$bd_id,'status'=>14,'admin_id'=>$_SESSION['fms_id']])
    //         //评估终止
    //         ->or_where_in('status',[33,31,34,38])
    //         ->where(['fms_baodan_status.bd_id'=>$bd_id,'pg_admin_id'=>$_SESSION['fms_id']])
    //         ->count_all_results('fms_baodan_status');
    // }

    // //停用
    // function to404($bd_id,$note=''){
    //     if(!$this->if404($bd_id)){
    //         return ['ret'=>false,'msg'=>'操作错误，无法停用'];
    //     }else{
    //         $this->db->trans_start();
    //             $this->db->update('fms_baodan_status',['status'=>404,'status_info'=> $this->statusArr[404],'note'=>$note],['bd_id'=>$bd_id]);
    //             $this->status_history_data['obj_id'] = $bd_id;
    //             $this->status_history_data['obj_status'] = 404;
    //             $this->status_history_data['obj_status_info'] = $this->statusArr[404];
    //             $this->status_history_data['note'] = $note;
    //             $this->db->insert('fms_status_history',$this->status_history_data);
    //         $this->db->trans_complete();
    //         if ($this->db->trans_status() === FALSE){
    //             return ['ret'=>false,'msg'=>'操作错误，无法停用'];
    //         }else{
    //             return ['ret'=>true,'msg'=>'停用成功'];
    //         }
    //     }
    // }

    // //判断是否能业务补件
    // function if21($bd_id){
    //     return (bool) $this->db
    //         ->join('fms_baodan','fms_baodan.bd_id=fms_baodan_status.bd_id')
    //         ->where(['fms_baodan_status.bd_id'=>$bd_id,'status'=>14,'admin_id'=>$_SESSION['fms_id'],'lr_admin_id is not null'=>null])
    //         ->count_all_results('fms_baodan_status');
    // }

    //判断报单是否能变为指定状态 返回bool
    function ifStatus($bd_id,$status){
        if(!isset($this->statusIf[$status])) throw new Exception('该状态值不符合要求！');
        $this->db
            ->join('fms_baodan','fms_baodan.bd_id=fms_baodan_status.bd_id')
            ->where(['fms_baodan_status.bd_id'=>$bd_id]);
        if(!@$this->statusIf[$status][1]){
             $this->db->where($this->statusIf[$status]);
        }else{
            $this->db->where($this->statusIf[$status][0])->or_where('1=1')->where(['fms_baodan_status.bd_id'=>$bd_id])->where($this->statusIf[$status][1]);
        }
        return (bool)$this->db->count_all_results('fms_baodan_status');
    }

    //改变状态 返回['ret'=>bool,'msg'=>'动作信息']
    function toStatus($bd_id,$status,$note=''){
        if(!$this->ifStatus($bd_id,$status)){
            return ['ret'=>false,'msg'=>'❌ “'.$this->statusAction[$status].'” 失败！'];
        }else{
            $this->db->trans_start();
                $status_arr = [
                    'status'=>$status,
                    'status_info'=>$this->statusArr[$status].'',
                    'note'=>$note
                ];
                if($status == 23) $status_arr['lr_admin_id'] = $_SESSION['fms_id'];
                if($status == 33) $status_arr['pg_admin_id'] = $_SESSION['fms_id'];

                $this->db->update('fms_baodan_status',$status_arr,['bd_id'=>$bd_id]);
                $this->status_history_data['obj_id'] = $bd_id;
                $this->status_history_data['obj_status'] = $status;
                $this->status_history_data['obj_status_info'] = $this->statusArr[$status];
                $this->status_history_data['note'] = $note;
                $this->db->insert('fms_status_history',$this->status_history_data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                return ['ret'=>false,'msg'=>'❌ “'.$this->statusAction[$status].'” 失败！'];
            }else{
                return ['ret'=>true,'msg'=>'✅ “'.$this->statusAction[$status].'” 成功！'];
            }
        }
    }

    // //业务补件
    // function to21($bd_id,$note=''){
    //     if(!$this->if21($bd_id)){
    //         return ['ret'=>false,'msg'=>'操作错误，无法补件'];
    //     }else{
    //         $this->db->trans_start();
    //             $this->db->update('fms_baodan_status',['status'=>21,'status_info'=> $this->statusArr[21],'note'=>$note],['bd_id'=>$bd_id]);
    //             $this->status_history_data['obj_id'] = $bd_id;
    //             $this->status_history_data['obj_status'] = 21;
    //             $this->status_history_data['obj_status_info'] = $this->statusArr[21];
    //             $this->status_history_data['note'] = $note;
    //             $this->db->insert('fms_status_history',$this->status_history_data);
    //         $this->db->trans_complete();
    //         if ($this->db->trans_status() === FALSE){
    //             return ['ret'=>false,'msg'=>'操作错误，无法补件'];
    //         }else{
    //             return ['ret'=>true,'msg'=>'补件成功'];
    //         }
    //     }
    // }

    // //判断是否能抢单
    // function if23($bd_id){
    //     return (bool) $this->db
    //         ->join('fms_baodan','fms_baodan.bd_id=fms_baodan_status.bd_id')
    //         ->where(['fms_baodan_status.bd_id'=>$bd_id,'status'=>20,'lr_admin_id'=>null])
    //         ->count_all_results('fms_baodan_status');
    // }

    // //抢单
    // function to23($bd_id){

    // }


    //业务首次报单 状态初始化20
    function addStatus($bd_id,$note=''){
        $data = [
            'bd_id' => $bd_id,
            'status' => 20,
            'status_info' => $this->statusArr[20],
            'note' => $note
        ];
        $this->db->insert('fms_baodan_status',$data);

        $this->status_history_data['obj_id'] = $bd_id;
        $this->status_history_data['obj_status'] = 20;
        $this->status_history_data['obj_status_info'] = $this->statusArr[20];
        $this->status_history_data['obj_status_info'] = $note;
        $this->db->insert('fms_status_history', $this->status_history_data);

        // return $this->db->insert_id();
        return $this->db->affected_rows();
    }

    function getOneHistoryStatus($obj_type,$obj_id){
        return $this->db->order_by('status_edit_time')->get_where('fms_status_history',[
            'obj_type' => $obj_type,
            'obj_id' => $obj_id
        ])->result_array();
    }
    
    function getStatusObjType($obj_type){
        return $this->db
            ->select('obj_name')
            ->get_where('fms_obj',[
                'type' => $obj_type
            ],1)->row_array()['obj_name'];
    }

    // function getStatusUser($where,$obj_status_in,$limit){
    //     if ($obj_status_in!=[]) 
    //         $this->db->where_in('obj_status',$obj_status_in);
    //     return $this->db
    //         ->select(['admin_id','admin_name','role_id','role_name'])
    //         ->where($where)
    //         ->order_by('status_edit_time','desc')
    //         ->limit($limit)
    //         ->get('fms_status_history')
    //         ->result_array();
    //     // return $this->db->last_query();
    // }

}
?>