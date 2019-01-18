<?php

class Consume_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pay($mer_id,$balance,$payno,$paypass,$orderid)
    {
        $sql = sprintf("call wesing_pay('%s','%s',%d,'%s','%s')",$payno,$paypass,$balance,$mer_id,$orderid);
        $rest = $this->db->query($sql)->result_array();
        return current($rest);
    }

    public function refund($mer_id,$balance,$payno,$paypass,$orderid)
    {
        $sql = sprintf("call wesing_refund('%s','%s',%d,'%s','%s')",$payno,$paypass,$balance,$mer_id,$orderid);
        $rest = $this->db->query($sql)->result_array();
        return current($rest);
    }
}