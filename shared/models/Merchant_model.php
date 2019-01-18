<?php
!defined('APPPATH') && exit('Access Denied');
class Merchant_model extends Admin_Model
{

    public function addNew()
    {
        $merchant = $this->input->post('merchant',true);
        $address  = $this->input->post('address',true);
        $contactor  = $this->input->post('contactor',true);
        $mobile = $this->input->post('mobile',true);
        $pass = $this->input->post('pass',true);
        $zt = $this->input->post('dzt',true);
        $relate_id = $this->input->post('relate_id',true);
        $commission = $this->input->post('commission',true);
        $rebate = $this->input->post('rebate',true);

        if(!filter_var($commission,FILTER_SANITIZE_NUMBER_FLOAT) || !filter_var($rebate,FILTER_SANITIZE_NUMBER_FLOAT)){
            return '比率设置错误';
        }

        if($this->getByField('dshqc',$merchant)){
            return '商户已存在';
        }

        $maxIdArr = $this->db->query("select max(id)+1 as nextid from wesing_merchant")->result_array();
        $maxId = current($maxIdArr);
        if($maxId['nextid']==null){
            $maxId['nextid']=1;
        }

        $salt = substr(str_shuffle(uniqid(time())),3,6);
        $dshh = 'M'.str_pad($maxId['nextid'],6,'0',STR_PAD_LEFT);
        $data = array(
            'dshh'=>$dshh,
            'dshqc'=>$merchant,
            'dlxr'=>$contactor,
            'dlxdh'=>$mobile,
            'dlxdz'=>$address,
            'dshzt'=>$zt,
            'relate_id'=>$relate_id,
            'salt'=>$salt,
            'commission'=>$commission,
            'rebate'=>$rebate,
            'usermm'=>md5($dshh.$salt.$pass),
            'cdate'=>date('Y-m-d H:i:s')
        );

        $this->db->insert('wesing_merchant',$data);
        return;
    }

    public function getList($page=1,$pagesize=10)
    {
        $whereStr = $this->whereCondition ? ' and '.implode(' and ',$this->whereCondition) : '';
        $totalArr = $this->db->query(sprintf("select count(1) as total from wesing_merchant where 1=1 %s",$whereStr))->result_array();
        $total = current($totalArr);
        $adminList = $this->db->query(sprintf("select * from wesing_merchant where 1=1 %s ORDER  by id desc limit %d,%d" ,$whereStr,($page-1)*$pagesize,$pagesize ))->result_array();
        return array(
            'list'=>$adminList,
            'totalpage' =>ceil($total['total']/$pagesize),
            'page' =>$page
        );
    }

    public function getZamk()
    {
        $zamkList = $this->db->query("select * from wesing_zamk")->result_array();
        $returnData = array();
        foreach ($zamkList as $_zamk){
            $returnData[$_zamk['w_type']][$_zamk['type_val']]=$_zamk['type_name'];
        }

        return $returnData;
    }

    public function save()
    {
        $documentid = $this->input->post('documentid');
        $merchant = $this->input->post('merchant',true);
        $address  = $this->input->post('address',true);
        $contactor  = $this->input->post('contactor',true);
        $mobile = $this->input->post('mobile',true);
        $pass = $this->input->post('pass',true);
        $zt = $this->input->post('dzt',true);
        $relate_id = $this->input->post('relate_id',true);
        $commission = $this->input->post('commission',true);
        $rebate = $this->input->post('rebate',true);

        if(!filter_var($commission,FILTER_SANITIZE_NUMBER_FLOAT) || !filter_var($rebate,FILTER_SANITIZE_NUMBER_FLOAT)){
            return '比率设置错误';
        }
        if(!$this->getByPriIntKey($documentid)){
            return '记录不存在';
        }

        $data = array(
            'dshqc'=>$merchant,
            'dlxr'=>$contactor,
            'dlxdh'=>$mobile,
            'dlxdz'=>$address,
            'dshzt'=>$zt,
            'relate_id'=>$relate_id,
            'commission'=>$commission,
            'rebate'=>$rebate
        );

        if($pass){
            $data['usermm']=md5($this->getMeta('dshh').$this->getMeta('salt').$pass);
        }
        $this->db->where('id',$documentid);
        $this->db->update('wesing_merchant',$data);
        return;
    }

    public function update($data){
        foreach ($data as $_type=>$_insItem){
            foreach ($_insItem as $_code=>$_val){
                $this->db->query("insert into wesing_zamk VALUES (?,?,?) on DUPLICATE KEY UPDATE `type_name` =?",array($_type,$_code,$_val,$_val));
            }
        }
    }

    public function savepass($opass,$npass)
    {
        $pass = md5($_SESSION['merchant_dshh'].$_SESSION['merchant_salt'].$npass);
        $oPassMd = md5($_SESSION['merchant_dshh'].$_SESSION['merchant_salt'].$opass);
        $this->db->query("update wesing_merchant set usermm=? where id=? and usermm=?",array($pass,$_SESSION['merchant_id'],$oPassMd));
        if($this->db->affected_rows()==0) return '原始密码错误';
        return '';
    }
    /**
     * write by 陈恩杰
     * 获取用户信息
     * @param $fms_id 用户id
     * @return bool
     */
    public function get_user_info($fms_id)
    {
        $user_res = $this->db->where('id',$fms_id)->get('wesing_merchant')->row_array();
        if (empty($user_res)){
            return false;
        }else{
            return $user_res;
        }
    }

    /**
     * write by 陈恩杰
     * 修改密码
     * @param $uid 用户id
     * @param $newpassword 新密码
     * @return bool
     */
    public function change_passwd($uid,$newpassword)
    {
        $update_passwd = $this->db->set('usermm',$newpassword)->where('id',$uid)->update('wesing_merchant');
        if ($update_passwd!==false){
            return true;
        }else{
            return false;
        }
    }
}