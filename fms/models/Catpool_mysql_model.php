<?php
//myexcel 猫池72 短信125
class Catpool_mysql_model extends CI_Model{
    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('catpoolmysql',TRUE);
    }

    //获取短信
    function get_sms($where=[]){
        $field = [
            'receive.id' => '短信id',
            'srcnum' => '对方号码',
            'msg' => '短信内容',
            'time' => '时间',
            'iccid' => 'iccid',
            // 'goipid' => '猫池id',
            // 'goipname' => '客户编号',
            'srcid' => '',
            'srcname' => '',
            'srclevel' => '',
            'status' => '',
            'smscnum' => '',
        ];
        $field =array_keys($field);
        
        return $this->db
            ->where($where)
            ->select($field)
            ->join('goip','goip.id=goipid')
            ->order_by('receive.id')
            ->get('goip.receive')
            ->result_array();
    }

    //获取短信列表
    function list_sms($like=null,$page=1,$rows=10,$sort='receive.id',$order='DESC',$iccid){
        $field = [
            'receive.id' => '短信id',
            'srcnum' => '对方号码',
            'msg' => '短信内容',
            'time' => '时间',
            'iccid' => 'iccid',
            // 'goipid' => '猫池id',
            // 'goipname' => '客户编号',
            // 'srcid' => '',
            // 'srcname' => '',
            // 'srclevel' => '',
            // 'status' => '',
            // 'smscnum' => '',
        ];
        $field =array_keys($field);
        if($like!==null) {
            $this->db->or_like('srcnum',$like)->where(['iccid'=>$iccid]);
            $this->db->or_like('msg',$like)->where(['iccid'=>$iccid]);
        }else{
            $this->db->where(['iccid'=>$iccid]);
        }
        $this->db
            ->select($field)
            ->join('goip','goip.id=goipid');
        $total = $this->db->count_all_results('receive',false);
        $rs = $this->db
            ->order_by($sort,$order)
            ->limit($rows,($page-1)*$rows)
            ->get();

        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        // echo $this->db->last_query();
        return $res;
    }
}
?>