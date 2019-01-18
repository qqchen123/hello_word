<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {

	/**
	 * @var 默认样本归属类型
	 */
	public $sample_type = 'user';

	//write by chenenjie
	public function __construct()
	{
		parent::__construct();
//        $this->load->model('changepass_model','cp_model');
		$this->load->model('Merchant_model', 'cp_model');
		$this->load->library('form_validation');//表单验证类
	}

	public function index()
	{
		$this->load->model('sys_model', 'sys');
		if ($_SESSION['fms_userid'] == 'M000001')
		{
			$data['my_menu'] = $this->sys->getMenu('-1', $_SESSION['fms_userrole']);
		} else
		{
			$data['my_menu'] = $this->sys->getMenu('-1', $_SESSION['fms_userrole']);
		}

		$this->showpage('fms/admin_mainFrame', $data);
	}

	public function chpass()
	{
		$this->showpage('fms/changepass');
	}

	/**
	 * write by chenenjie
	 * 修改密码
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('oldpassword', '原始密码', 'required|max_length[30]');
		$this->form_validation->set_rules('newpassword', '新密码', 'required|max_length[30]');
		$this->form_validation->set_rules('newpassword2', '新密码', 'required|max_length[30]');
		if ($this->form_validation->run())
		{
			$oldpassword = $this->input->post('oldpassword', TRUE);
			$newpassword = $this->input->post('newpassword', TRUE);
			$newpassword2 = $this->input->post('newpassword2', TRUE);
			if ($newpassword !== $newpassword2)
			{
				$message['code'] = 0;
				$message['message'] = '两次输入不一致，请重新输入！';
				echo json_encode($message);
				die;
			}
			$user_res = $this->cp_model->get_user_info($_SESSION['fms_id']);
			if ($user_res == FALSE)
			{
				echo json_encode($user_res);
				die;
			}
			if (md5($user_res['userid'].$user_res['salt'].$oldpassword) == $user_res['usermm'])
			{
				$md5newpasswd = md5($user_res['userid'].$user_res['salt'].$newpassword);
				$change_res = $this->cp_model->change_passwd($user_res['id'], $md5newpasswd);
				if ($change_res == FALSE)
				{
					$message['code'] = 0;
					$message['message'] = '密码修改失败！';
					echo json_encode($message);
					die;
				} else
				{
					$message['code'] = 1;
					$message['message'] = '密码修改成功！';
					echo json_encode($message);
					die;
				}
			} else
			{
				$message['code'] = 0;
				$message['message'] = '原始密码不正确！';
				echo json_encode($message);
				die;
			}
		} else
		{
			$message['ret'] = 0;
			$this->form_validation->set_error_delimiters('', '<br>');
			$message['info'] = validation_errors();
			echo json_encode($message);
		}
	}

	/**
	 * 忘记密码-重置密码
	 */
	public function reset_pwd_page()
	{
		$this->showpage('fms/reset_pwd');
	}

	/**
	 * 忘记密码-重置密码
	 */
	public function rest_pwd()
	{
		$post_data = $this->input->post();
		$select_info = $this->db
			->where('userid', trim($post_data['userid']))
			->where('username', trim($post_data['username']))
			->get('wesing_merchant')
			->row_array();

		if ($select_info)
		{
			$data['salt'] = substr(str_shuffle(uniqid(time())), 3, 6);
			$data['usermm'] = md5($select_info['userid'].$data['salt'].'123456');
			$update_info = $this->db
				->where('userid', $post_data['userid'])
				->where('username', $post_data['username'])
				->update('wesing_merchant', $data);
			if ($update_info)
			{
				$message['code'] = 1;
				$message['message'] = '密码重置成功，新密码为：123456';
				echo json_encode($message);
				die;
			} else
			{
				$message['code'] = 0;
				$message['message'] = '密码重置失败！';
				echo json_encode($message);
				die;
			}
		}else{
			$message['code'] = 0;
			$message['message'] = '姓名与账户不匹配，请核对后重新输入！';
			echo json_encode($message);
			die;
		}
	}

	/**
	 * @name 首页
	 */
	public function homepage()
	{
		//日志
		$this->load->helper(['array', 'tools', 'slog']);
		//公共状态方法
		$this->load->helper(['publicstatus', 'checkrolepower']);
		$this->load->service('user/User_service', 'user_service');
		$this->load->service('user/Mobile_service', 'mobile_service');
		$this->load->service('public/Array_service', 'array_service');
		if ( ! empty($_POST))
		{
			if ( ! empty($_POST['condition']))
			{
				$retfinddata = $this->user_service->search_by_condition($_POST['condition']);
				echo json_encode($retfinddata);
			}
		} else
		{
			if ( ! empty($_GET['id']))
			{
				//获取用户基本信息
				$user_info = $this->user_service->get_all_data($_GET['id']);
				Slog::log($user_info);
				$user_info = $user_info[0];

				//获取用户手机卡信息
				$mobile_info = $this->mobile_service->get_all_data($user_info['fuserid']);
				if ( ! empty($user_info['smaple_112']))
				{
					if ($mobile_info[0])
					{
						array_unshift(
							$mobile_info,
							array_merge(
								['type_name' => '常用手机号', 'enable' => 0],
								$this->array_service->object_to_array($user_info['smaple_112'])
							)
						);
					} else
					{
						$mobile_info[0] = array_merge(
							['type_name' => '常用手机号', 'enable' => 0],
							$this->array_service->object_to_array($user_info['smaple_112'])
						);
					}

				}
				$mobile_count = count($mobile_info);
				//组合数据
				$data = [
					'basic' => [
						['用户姓名', '年龄', '签发机关'],
						[$user_info['name'], '', ''],
						['用户ID', '公司名称', '省份证有效截止时间'],
						[$user_info['fuserid'], '', ''],
						['身份证', '性别', '民族'],
						[$user_info['idnumber'], '', ''],
						['生日', '基本信息状态', '类别'],
						['0720', '', ''],
					],
					'card' => [
						[
							['手机卡'],
							['手机号码'],
							['12345678901'],
							['数量'],
							[$mobile_count],
						],
						[
							['银行卡'],
							['手机号码'],
							['12345678901'],
							['数量'],
							['2'],
						],
						[
							['机构管理'],
							['手机号码'],
							['12345678901'],
							['数量'],
							['2'],
						],
					],
					'card_detail' => [
						'mobile_info' => $mobile_info,
						'bank_info' => [],
						'inst_info' => [],
					],
				];
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			} else
			{
				$data = [
					'statusColor' => json_encode($this->statusColor),
				];
				$this->showpage('fms/homepage/searchview', $data);
			}
		}
	}

}
