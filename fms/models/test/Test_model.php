<?php


class Test_model extends CI_Model 
{

	public function test()
	{
		echo 'success';
		$this->db->delete('fms_mobile','id = 28');
	}

}