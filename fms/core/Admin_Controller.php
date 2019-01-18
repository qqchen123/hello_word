<?php
 
class Admin_Controller extends CI_Controller
{
    // static $rolePower = [];

    public function __construct()
    {
        parent::__construct();
        //session_start();

        // if(!$_SESSION){
        //     redirect('/Auth/login');
        // }

        // $usrKey = isset($_SESSION['fms_id']) ? $_SESSION['fms_id'] : '';

        // if(!$usrKey){
        //     redirect('/Auth/login');
        // }
    }

    public function showpage($pagefile,array $args = array(),$returnString = false)
    {
        // $args['menu'] = $this->config->item('menu');
        $args['admin_uname'] = $_SESSION['fms_uname'];
        return $this->load->view($pagefile,$args,$returnString);
    }

    protected function _response($msg,$code=200)
    {
        if (is_array($msg)){
            $code=$msg[0];
            $msg=$msg[1];
        }
        echo json_encode(array(
            'responseCode'=>$code,
            'responseMsg'=>$msg
        ));
        exit;
    }
}