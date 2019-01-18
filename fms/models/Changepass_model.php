<?php

/**
 * Class Changepass_model（此类已废弃，请看 share/models/merchant_model.class）
 */
class Changepass_model extends CI_Model{
    public function get_user_info($fms_id)
    {
        $user_res = $this->db->where('id',$fms_id)->get('wesing_merchant')->row_array();
        if (empty($user_res)){
            return false;
        }else{
            return $user_res;
        }
    }

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