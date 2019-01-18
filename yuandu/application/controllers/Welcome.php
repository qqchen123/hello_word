<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require ('./vendor/autoload.php');
class Welcome extends CI_Controller {

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
	/*静态官网页面*/
	public function index()
	{
		$this->load->view('fittest');
	}
}
