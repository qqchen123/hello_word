<?php

class Trade extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('merchant_model','mers');
    }

    public function index()
    {
        $args = $this->mers->getZamk();
        $this->showpage('mers/tradequery',$args);
    }
}