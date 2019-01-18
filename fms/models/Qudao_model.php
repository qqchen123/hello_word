<?php

class Qudao_model extends Admin_Model
{
    //废弃代码 作用不明
    function updateQiyebankCardinfo($bankcardinfo)
    {
        $this->db->set("bankname", $bankcardinfo['bankname']);
        $this->db->set("bankcardNo", $bankcardinfo['bankcardNo']);
        $this->db->set("mobile", $bankcardinfo['mobile']);
        $this->db->set("bankcardvalid", $bankcardinfo['bankcardvalid']);
        $this->db->set("bankcardimgu", $bankcardinfo['bankcardimgu']);
        $this->db->set("bankcardimgd", $bankcardinfo['bankcardimgd']);

        $this->db->where("idnumber = '".$bankcardinfo['idnumber']."'");
        $ret = $this->db->update('fms_qiyeinfo');
        return $ret;
    }

    //废弃代码 作用不明
    public function getuserinfodata($status)
    {
        if($status != '') $this->db->where("status = '".$status."'");
        $ret = $this->db->get('fms_qiyeinfo')->result_array();
        return $ret;
    }

    //废弃代码 作用不明
    public function getZamk($name,$val)
    {
        $ret = $this->db->get_where('wesing_zamk', array('w_type' => $name, 'type_val' => $val))->result_array();
        return $ret;
    }


    //废弃代码 作用不明
    public function getdcbyid($id){
        return current($this->db->query('select * from fms_dockingman where dc_id='.$id)->result_array());
    }



    //废弃代码 作用不明
    public function del($q_id){
        $this->db->query('update fms_qudao set q_status=0 where q_id='.$q_id);
        $this->db->query('update fms_dockingman set dc_status=0 where q_id='.$q_id);
        return [200,''];
    }

    //废弃代码 作用不明
    public function adddc_row()
    {
        $data=array(
            'q_id'=>$this->input->post('q_id',true),
            'dc_jgid'=>$this->input->post('dc_jgid',true),
            'dc_name'=>$this->input->post('dc_name',true),
            'dc_department'=>$this->input->post('dc_department',true),
            'dc_group'=>$this->input->post('dc_group',true),
            'dc_qpik'=>$this->input->post('dc_qpik',true),
            'dc_role'=>$this->input->post('dc_role',true),
            'dc_wesing'=>$this->input->post('dc_wesing',true),
            'dc_phone'=>$this->input->post('dc_phone',true),
            'dc_comp_mail'=>$this->input->post('dc_comp_mail',true),
            'op_id'=>$_SESSION['fms_id']
        );

        $this->db->insert('fms_dockingman',$data);
        if($this->db->affected_rows()>0)
        {
            return [200,''];
        }

        return [500,'失败'];
    }

    //废弃代码 作用不明
    public function savedc_row()
    {
        $dcid=$this->input->post('dc_id',true);
        if(!current($this->db->query('select 1 from fms_dockingman where dc_id='.(int)$dcid)->result_array()))
            return [500,'记录不存在'];

        $data=array(
            'dc_name'=>$this->input->post('dc_name',true),
            'dc_jgid'=>$this->input->post('dc_jgid',true),
            'dc_department'=>$this->input->post('dc_department',true),
            'dc_group'=>$this->input->post('dc_group',true),
            'dc_qpik'=>$this->input->post('dc_qpik',true),
            'dc_role'=>$this->input->post('dc_role',true),
            'dc_wesing'=>$this->input->post('dc_wesing',true),
            'dc_phone'=>$this->input->post('dc_phone',true),
            'dc_comp_mail'=>$this->input->post('dc_comp_mail',true),
            'op_id'=>$_SESSION['fms_id']
        );
        $this->db->where('dc_id',$dcid*1);
        $this->db->update('fms_dockingman',$data);
        echo $this->db->last_query();
        if($this->db->affected_rows()>0)
        {
            return [200,''];
        }

        return [500,'失败'];
    }

    public function del_dc($id)
    {
        $this->db->query('update fms_dockingman set dc_status=0 where dc_id='.$id);
        return [200,''];
    }

// 渠道重构 by 奚晓俊 开始======================
    //废弃代码
    public function query_qudao_list($page,$rows,$spmc=''){
        $page=intval($page);
        $rows=intval($rows);
        $where = '';
        if ($spmc){
            $where=" and q_name like '%".$spmc."%' ";
        }

        $page = $page < 0 ? 1 : $page;
        $rows = $rows < 0 ? 10:$rows;
        $list=$this->db->query("select * from fms_qudao where q_status=1 ".$where." limit ".($page - 1 )*$rows.",".$rows *1)->result_array();
        $total=current($this->db->query("select count(q_id) as total from fms_qudao where q_status=1" .$where)->result_array());
        return ['rows'=>$list,'total'=>$total['total']];
    }

    //渠道列表
    function list_qudao($like='',$page=1,$rows=10,$sort='q_id',$order='ASC',$q_id=null){

        $this->load->helper('publicstatus');
        joinStatus('qd','q_id');

        if ($q_id) $this->db->where('q_id',$q_id);
        if ($like) $this->db->like('q_name',$like);
        $total = $this->db->count_all_results('fms_qudao',false);
        $rs = $this->db->order_by($sort,$order)->limit($rows,($page-1)*$rows)->get();
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
        return $res;
    }

    //废弃代码 新建渠道
    public function addrec()
    {
        $data=array(
            'q_name'=>$this->input->post('q_name',true),
            'q_code'=>$this->input->post('q_code',true),
            'q_company'=>$this->input->post('q_company',true),
            'q_level'=>$this->input->post('q_level',true),
            'q_picker'=>$this->input->post('q_picker',true),
            'q_picker_phone'=>$this->input->post('q_picker_phone',true),
            'q_picker_company'=>$this->input->post('q_picker_company',true),
            'q_picker_company_addr'=>$this->input->post('q_picker_company_addr',true),
            'q_picker_company_mail'=>$this->input->post('q_picker_company_mail',true),
            'q_picker_business_time'=>$this->input->post('q_picker_business_time',true),
            'q_picker_business'=>$this->input->post('q_picker_business',true),
            'q_team_numbers'=>$this->input->post('q_team_numbers',true),
            'q_if_has_risk_team'=>$this->input->post('q_if_has_risk_team',true),
            'q_if_has_stock'=>$this->input->post('q_if_has_stock',true),
            'op_id'=>$_SESSION['fms_id']
        );

        $this->db->insert('fms_qudao',$data);
        if($this->db->affected_rows()>0)
        {
            return [200,''];
        }

        return [500,'失败'];
    }

    //新建渠道
    function add_qudao($data){
        $this->load->helper('publicstatus');
        $data['ctime'] = date('Y-m-d H:i:s');
        $data['uptime'] = date('Y-m-d H:i:s');

        $this->db->trans_start();//开启事务
            //插入新对象
            $this->db->insert('fms_qudao',$data);
            $id = $this->db->insert_id();
            //插入公共状态
            addStatus('qd',$id);
        $this->db->trans_complete();

        if ($this->db->trans_status()){
            return $id;
        }else{
            return false;
        }
    }

    //废弃代码 获取单条渠道
    public function getqudaobyid($id){
        return current($this->db->query('select * from fms_qudao where q_id='.$id)->result_array());
    }

    //获取含公共状态的单条渠道
    function get_qudao_by_id($q_id){
        $this->load->helper('publicstatus');
        joinStatus('qd','q_id');
        return $this->db->get_where('fms_qudao',['q_id'=>$q_id],1)->row_array();
    }

    //获取渠道名称供筛选
    function get_qudao_name($where){
        $this->load->helper('publicstatus');
        joinStatus('qd','q_id');
        return $this->db->select(['q_name','q_id','obj_status'])->where($where)->order_by('obj_status','desc')->get('fms_qudao')->result_array();
        // return $this->db->last_query();
    }

    //废弃代码 编辑渠道
    public function saverec()
    {
        $qid=$this->input->post('q_id',true);
        if(!current($this->db->query('select 1 from fms_qudao where q_id='.(int)$qid)->result_array()))
            return [500,'记录不存在'];
        $data=array(
            'q_name'=>$this->input->post('q_name',true),
            'q_company'=>$this->input->post('q_company',true),
            'q_level'=>$this->input->post('q_level'),
            'q_picker'=>$this->input->post('q_picker',true),
            'q_picker_phone'=>$this->input->post('q_picker_phone',true),
            'q_picker_company'=>$this->input->post('q_picker_company',true),
            'q_picker_company_addr'=>$this->input->post('q_picker_company_addr',true),
            'q_picker_company_mail'=>$this->input->post('q_picker_company_mail',true),
            'q_picker_business_time'=>$this->input->post('q_picker_business_time',true),
            'q_picker_business'=>$this->input->post('q_picker_business',true),
            'q_team_numbers'=>$this->input->post('q_team_numbers',true),
            'q_if_has_risk_team'=>$this->input->post('q_if_has_risk_team',true),
            'q_if_has_stock'=>$this->input->post('q_if_has_stock',true),
            'op_id'=>$_SESSION['fms_id']
        );
        $this->db->where('q_id',$this->input->post('q_id')*1);
        $this->db->update('fms_qudao',$data);
        if($this->db->affected_rows()>0)
        {
            return [200,''];
        }

        return [500,'失败'];
    }

    //编辑渠道
    function edit_qudao($data,$where){
        $data['uptime'] = date('Y-m-d H:i:s');
        $this->db->update('fms_qudao',$data,$where);
        return ($this->db->affected_rows()==1);
    }

// 渠道重构 by 奚晓俊 结束======================

// 渠道对接人重构 by 奚晓俊 开始======================
    //废弃代码 获取对接人数据
    public function query_pickers($page,$rows,$qname)
    {
        $page=intval($page);
        $rows=intval($rows);
        $where = ' ';
        if ($qname){
            $where=" and dc_name like '%".$qname."%' ";
        }

        $page = $page < 0 ? 1 : $page;
        $rows = $rows < 0 ? 10:$rows;
        $list=$this->db->query("select dc.*,jg.jg_name from fms_dockingman dc ,fms_jigou_info jg where dc_status=1 and dc_jgid=jg_id ".$where." limit ".($page - 1 )*$rows.",".$rows *1)->result_array();
        $total=current($this->db->query("select count(dc_id) as total from fms_dockingman where dc_status=1 " .$where)->result_array());
        $mdepart=$this->qm->getzamktype('qudao_depa');
        $mgroup =$this->qm->getzamktype('qudao_grou');
        $mrol =$this->qm->getzamktype('qudao_rol');
        $pick =$this->qm->getzamktype('qudao_pik');
        if (is_array($list))
            foreach ($list as $_key=>$_row){
                $list[$_key]['dc_department'] = $mdepart[$_row['dc_department']];
                $list[$_key]['dc_group'] = $mgroup[$_row['dc_group']];
                $list[$_key]['dc_qpik'] = $pick[$_row['dc_qpik']];
                $list[$_key]['dc_role'] = $mrol[$_row['dc_role']];
            }

        return ['rows'=>$list,'total'=>$total['total']];
    }

    //获取渠道对接人数据
    function list_contact($q_status,$q_id,$like='',$page=1,$rows=10,$sort='belong_id',$order='ASC'){
        $this->load->helper('publicstatus');
        joinStatus('qd','ct.belong_id');
        if ($like) $this->db->like('ct.ct_name',$like);
        if ($q_status) $this->db->where('qd.status',$q_status);
        if ($q_id) $this->db->where('qd.q_id',$q_id);
        $total = $this->db
            ->select([
                'qd_status.obj_status q_status',
                'qd_status.obj_status_info q_status_info',
                'qd.q_name',
                'ct.*'
            ])
            ->join('fms_qudao qd','ct.belong_id = qd.q_id and ct_type = "qd"')
            ->count_all_results('fms_contact ct',false);
        $rs = $this->db
            ->order_by($sort,$order)
            ->limit($rows,($page-1)*$rows)
            ->get();
        // return $this->db->last_query();

        if ($rs->num_rows() > 0) {
            $res["total"]= $total;
            $res['rows'] = $rs->result_array();
            $rs->free_result();
        } else {
            $res["total"]= 0;
            $res["rows"] = '';
        }
        return $res;
    }

    //获取单条渠道联系人
    function get_contact_by_id($ct_id){
        $this->load->helper('publicstatus');
        joinStatus('qd','ct.belong_id');
        return $this->db
            ->select([
                'qd_status.obj_status q_status',
                'qd_status.obj_status_info q_status_info',
                'qd.q_id',
                'qd.q_name',
                'ct.*'
            ])
            ->join('fms_qudao qd','belong_id = q_id')
            ->get_where('fms_contact ct',['ct_id'=>$ct_id,'ct_type'=>'qd'],1)
            ->row_array();
    }

    //废弃代码
    public function querymember($username='',$gender='',$idtype='',$idno='',$mobile='',$addr='')
    {
        if($username!='') $this->db->where("username = '".$username."'");
        if($gender!='0') $this->db->where("gender = '".$gender."'");
        if($idtype!='0') $this->db->where("idtype = '".$idtype."'");
        if($idno!='') $this->db->where("idno = '".$idno."'");
        if($mobile!='') $this->db->where("mobile = '".$mobile."'");
        if($addr!='') $this->db->where("addr like '%".$addr."%'");
        $ret = $this->db->get('wesing_member')->result_array();
        return $ret;
    }

    //废弃代码
    public function regmember($username,$gender,$idtype,$idno,$mobile,$addr)
    {
        $res['val'] = false;
        $ret = $this->db->get_where('wesing_member', array('mobile' => $mobile))->result_array();

        if($ret)
        {
            $res['code'] = '-1';//'用户已存在';
            return $res;
        }

        $data = array(
            'memberid' => uniqid(),
            'username' => $username,
            'gender' => $gender,
            'idtype' => $idtype,
            'idno' => $idno,
            'mobile' => $mobile,
            'addr' => $addr
        );
        $this->db->insert('wesing_member',$data);
        if($this->db->affected_rows()>0)
        {
            $res['val'] = true;
            $res['code'] = '01';//'success';
            return $res;
        }

        $res['code'] = '-2';//'插入失败';
        return $res;
    }

    //废弃代码
    public function getzamktype($ztype)
    {
        $rows=$this->db->query("select * from wesing_zamk where ztype='".$ztype."'")->result_array();
        $ret = array();
        foreach ($rows as $_val){
            $ret[$_val['zname']] = $_val['zval'];
        }

        return $ret;
    }
// 渠道对接人重构 by 奚晓俊 结束======================

}