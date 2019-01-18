<?php

class Merchant_model extends Admin_Model
{
    /**
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