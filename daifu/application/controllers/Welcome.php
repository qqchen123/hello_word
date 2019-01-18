<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('./vendor/autoload.php');
class Welcome extends My_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function read_word()
	{
		$filename = './123.doc';//license.txt
		// $filename = './license.txt';//license.txt
		$phpWord = new \PhpOffice\PhpWord\TemplateProcessor($filename);
		print_r($phpWord);die;
	}
	public function index()
	{
		$this->newview('system');
	}
	/*闲卡网 trade页面*/
	public function trade ()
	{
		$this->load->view('tradpage');
	}
	/*玉桂静态官网页面*/
	public function fitt()
	{
		$this->load->view('fittest');
	}
	//登录页
	public function test1()
	{
		$this->load->view('yglogin');
	}
	//系统公告
	public function test2()
	{
		$this->newview('system');
	}
	//修改密码
	public function test3()
	{
		$this->newview('updatepwd');
	}
	//修改成功页
	public function test4()
	{
		$this->newview('updatesc');
	}
	//设置短信
	public function test5()
	{
		$this->newview('smssetting');
	}
	//短信设置
	public function test6()
	{
		$this->newview('smssettingtwo');
	}
	//设置成功
	public function test7()
	{
		$this->newview('setupsc');
	}
	//空页面
	public function test8()
	{
		$this->newview('empty');
	}
	//交易管理(交易明细查询)
	public function test9()
	{
		$this->newview('trsaction_mg1');
	}
	//交易管理（查询详情）
	public function test19()
	{
		$this->newview('trsaction_mg1_1');
	}
	//交易管理（上传代付文件）
	public function test10()
	{
		$this->newview('trsaction_mg2');
	}
	//交易管理（上传代付文件失败）
	public function test11()
	{
		$this->newview('trsaction_mg3');
	}
	//交易管理（上传代付 输入密码）
	public function test12()
	{
		$this->newview('trsaction_mg4');
	}
	//交易管理（上传代付 密码有误的跳转）
	public function test13()
	{
		$this->newview('trsaction_mg5');
	}
	//代付成功
	public function test14()
	{
		$this->newview('trsaction_mg6');
	}
	//代付详情页
	public function test15()
	{
		$this->newview('trsaction_mg7');
	}
	public function test18()
	{
		$this->newview('trsaction_mg2_2');
	}
	//交易预存款变动查询
	public function test16()
	{
		$this->newview('trsaction_mg8');
	}
	//交易预存款变动查询（明细查询）
	public function test17()
	{
		$this->newview('trsaction_mg9');
	}
	//交易审核页
	public function toE1()
	{
		$this->newview('toExamine1');
	}
	//交易审核页（明细查询）
	public function toE2()
	{
		$this->newview('toExamine2');
	}
	//交易审核页（通过）
	public function toE3()
	{
		$this->newview('toExamine3');
	}
	//交易审核页（失败）
	public function toE4()
	{
		$this->newview('toExamine4');
	}
	public function ceshi()
	{
		$this->load->view('ceshiye');
	}

}
