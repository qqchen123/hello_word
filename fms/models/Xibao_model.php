<?php
class Xibao_model extends Admin_Model
{
    //获取正常状态的手机列表
    public function get_phone()
    {
        return $this->db->where('status',1)->order_by('id','DESC')->get('fms_xb_sms_phone')->result_array();
    }
    //获取所有状态的手机列表
    public function get_all_phone($page, $first)
    {
        $p_res['rows'] = $this->db->limit($page,$first)->order_by('id','DESC')->get('fms_xb_sms_phone')->result_array();
        $p_res['total'] = $this->db->count_all('fms_xb_sms_phone');
        return $p_res;
    }
    //添加喜报日志
    public function add_xb_sms_log($arr_sms)
    {
        $this->db->insert('fms_xb_sms_log',$arr_sms);
        if ($this->db->affected_rows()<>0){
            return true;
        }else{
            return false;
        }
    }
    //添加手机号
    public function add_phone($phone)
    {
        $this->db->insert('fms_xb_sms_phone',$phone);
        if ($this->db->affected_rows()<>0){
            return true;
        }else{
            return false;
        }
    }
//删除
    public function delete_sms_info($id)
    {
        $this->db->delete('fms_xb_sms_phone',['id'=>$id]);
        if ($this->db->affected_rows()<>0){
            return 1;
        }else{
            return 0;
        }
    }
    //禁用---启用
    public function toggle_sms_info($id,$status)
    {
        if ($status== 1){
            $data = ['status'=>0];
        }else{
            $data = ['status'=>1];
        }
        $this->db->update('fms_xb_sms_phone',$data,['id'=>$id]);
        if ($this->db->affected_rows()<>0){
            return 1;
        }else{
            return 0;
        }
    }
//获取一条信息
    public function get_one_phone($id)
    {
        $info = $this->db->where('id',$id)->get('fms_xb_sms_phone')->row_array();
        if ($info){
            return $info;
        }else{
            return false;
        }
    }
    //编辑用户信息
    public function edit_phone_info($data)
    {
        $this->db->where('id',$data['id'])->update('fms_xb_sms_phone',$data);
        if ($this->db->affected_rows()<>0){
            return 1;
        }else{
            return 0;
        }
    }
//验证手机的唯一性
    public function check_phone($phone)
    {
        $res = $this->db->where('phone',$phone)->get('fms_xb_sms_phone')->row_array();
        if ($res){
            return true;
        }else{
            return false;
        }
    }
    //添加任务--更新业务员喜报数量
    public function add_xb_task($arr)
    {
        $this->db->insert('fms_xb_sms_task',$arr);
    }
    //更新--业务员的喜报数量
    public function update_count($user_id)
    {
        $user_res = $this->db->select('id,xb_count')->where('id',$user_id)->get('fms_xb_sms_phone')->row_array();
        $count['xb_count'] = $user_res['xb_count']+1;
        $this->db->where('id',$user_res['id'])->update('fms_xb_sms_phone',$count);
    }
    //获取用户信息
    public function get_username_info($user_id='')
    {
        if (!empty($user_id)){
            $user_res = $this->db->where('id',$user_id)->select('id,username')->get('fms_xb_sms_phone')->row_array();
        }else{
            $user_res = $this->db->select('id,username')->get('fms_xb_sms_phone')->result_array();
        }
        return $user_res;
    }
    //获取喜报日志
    public function get_all_xb_log($page,$first)
    {
        $logres['rows'] = $this->db->select('fms_xb_sms_phone.username,fms_xb_sms_log.*')
                            ->limit($page,$first)
                            ->from('fms_xb_sms_log')
                            ->join('fms_xb_sms_phone','fms_xb_sms_phone.phone = fms_xb_sms_log.phone')
                            ->order_by('fms_xb_sms_log.id','DESC')
                            ->get()
                            ->result_array();
        $logres['total'] = $this->db->count_all('fms_xb_sms_log');
        return $logres;
    }

}