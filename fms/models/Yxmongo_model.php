<?php

/**
 * Created by PhpStorm.
 * User: a
 * Date: 2018/10/30
 * Time: 10:00 AM
 */
class Yxmongo_model extends CI_Model {
	private $task_time = '';

	function __construct()
	{
		parent::__construct();//YxRepaying_model
		$this->load->model('yx/YxRepaying_model', 'YxRepaying_model');
		$this->task_time = $this->YxRepaying_model->get_effective_task_time();
	}

	/**
	 * 客户信息管理汇总
	 * @param string $page
	 * @param string $first
	 * @param string $like
	 * @param string $where
	 * @param string $date
	 * @return mixed
	 */
	public function get_yx_account_mysql_data2(
		$page = '',
		$first = '',
		$like = '',
		$where = '',
		$date = '',
		$status = '',
		$sort = '',
		$order = '',
		$back_end_date = ''
	) {
		if ($sort && $order)
		{
			$this->db->order_by($sort, $order);
		}
		if ($where)
		{
			$this->db->where($where);
		}
		//客户姓名
		$this->con_where($like);
		if ($page !== '' && $first !== '')
		{
			$this->db->limit($page, $first);
		}
		$bed_where = '';
		if ($back_end_date == 'one_day')
		{
			$bed_where = ' and  next_repay_time >= \' '.date('Y-m-d').' \' and next_repay_time <= \''.date(
					"Y-m-d",
					strtotime("+1 day")
				).'\'';
		} elseif ($back_end_date == 'one_week')
		{
			$bed_where = $this->do_where_date($bed_where, 7);
		} elseif ($back_end_date == 'one_month')
		{
			$bed_where = $this->do_where_date($bed_where, 30);
		} else
		{
			$bed_where = '';
		}
		if ($status == 'borrowing')
		{
//			$sonsql = 'select account,`status` from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$sonsql = 'select account,`status`,loan_title from fms_yx_repaying where task_time >='.$this->task_time.$bed_where;

			//补充一个对 yx_in_money_detail 的连表
			//关联还款日期在最晚的借款信息
			//修改时间：2018-12-24  修改者：梁俸嘉 不能排序 取消修改
			// $yx_in_money_detail_sql = 'SELECT account, in_title, in_rate, MAX(expire_time) AS max_expire_time FROM fms_yx_in_money_detail WHERE expire_time > "2018-01-01" GROUP BY account HAVING MAX(expire_time)';

//			print_r($sonsql);die;
			$res['rows'] = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.status,c.loan_title'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$sonsql.') c', 'a.yx_account = c.account')
				// ->join('('.$yx_in_money_detail_sql.') d', 'a.yx_account = d.account AND c.loan_title = d.in_title')
				->where('c.status', '还款中')
				->where('b.check_status', 1)
				->get()
				->result_array();
			$this->con_where($like);
			$res['total'] = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.status,c.loan_title'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$sonsql.') c', 'a.yx_account = c.account')
				// ->join('('.$yx_in_money_detail_sql.') d', 'a.yx_account = d.account AND c.loan_title = d.in_title')
				->where('c.status', '还款中')
				->where('b.check_status', 1)
				->count_all_results();

//			echo $this->db->last_query();die;
		} elseif ($status == 'finish')
		{
			$b_sonsql = 'select account from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$b_res = $this->db->query($b_sonsql)->result_array();
			$sql = 'SELECT a.`yx_account`,a.`f_time_stamp`,a.`f_status`
					FROM   yx_finish a
						   JOIN (SELECT yx_account,Max(f_time_stamp) m,order_date
								 FROM   yx_finish
								 WHERE  order_date = (SELECT Max(order_date)
													  FROM   yx_finish)
								 GROUP  BY `yx_account`) b
							 ON a.yx_account = b.yx_account
					WHERE  a.f_time_stamp = b.m and a.`order_date`=(SELECT Max(order_date)
													  FROM   yx_finish)';
			$sql2 = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.f_status'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$sql.') c', 'a.yx_account=c.yx_account')
				->where_not_in('a.yx_account', array_column($b_res, 'account'))
				->where('b.check_status', 1)
				->get()
				->result_array();
			$res['rows'] = $sql2;
			$this->con_where($like);
			$res['total'] = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.f_status'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$sql.') c', 'a.yx_account=c.yx_account')
				->where('b.check_status', 1)
				->where_not_in('a.yx_account', array_column($b_res, 'account'))->count_all_results();
		} elseif ($status == 'empty')
		{
			$b_sonsql = 'select account from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$b_res = $this->db->query($b_sonsql)->result_array();
			$fres = 'select yx_account from yx_finish where order_date >=(select order_date from yx_finish order by order_date desc limit 1) order by id desc';
			$f_res = $this->db->query($fres)->result_array();
			$f_sonsql = 'select yx_account,`f_status`,f_time_stamp from yx_finish where order_date >=(select order_date from yx_finish order by order_date desc limit 1) order by id desc';
			$finish = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.f_status'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$f_sonsql.') c', 'a.yx_account = c.yx_account', 'left')
				->where_not_in('a.yx_account', array_column($b_res, 'account'))
				->where_not_in('a.yx_account', array_column($f_res, 'yx_account'))
				->where('b.check_status', 1)
				->get()
				->result_array();
			$finish = $this->array_unset_tt($finish, 'yx_account');
			$res['rows'] = array_values($finish);
			$this->con_where($like);
			$res['total'] = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.f_status'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$f_sonsql.') c', 'a.yx_account = c.yx_account', 'left')
				->where_not_in('a.yx_account', array_column($b_res, 'account'))
				->where_not_in('a.yx_account', array_column($f_res, 'yx_account'))
				->where('b.check_status', 1)
				->count_all_results();
		} elseif ($status == 'overdue')
		{
			$sonsql = 'select account,`status` from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$res['rows'] = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.status'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$sonsql.') c', 'a.yx_account = c.account')
				->where('c.status', '逾期')
				->where('b.check_status', 1)
				->get()
				->result_array();
			$this->con_where($like);
			$res['total'] = $this->db
				->select(
					'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.status'
				)
				->from('fms_user a')
				->join('fms_yx_account b', 'a.yx_account=b.account')
				->join('('.$sonsql.') c', 'a.yx_account = c.account')
				->where('b.check_status', 1)
				->where('c.status', '逾期')
				->count_all_results();
		} else
		{
			return ['code' => 0, 'msg' => '参数错误！'];
		}
//		$res['total'] = $this->db->count_all_results('fms_user as a');
//		echo $this->db->last_query();die;
		if ( ! empty($date['start_date']))
		{
			foreach ($res['rows'] as $k => $v)
			{
				if (strtotime($date['start_date']) > strtotime($v['ctime']))
				{
					unset($res['rows'][$k]);
				}
			}
		}

		if ( ! empty($date['end_date']))
		{
			foreach ($res['rows'] as $k => $v)
			{
				if (strtotime($date['end_date']) < strtotime($v['ctime']))
				{
					unset($res['rows'][$k]);
				}
			}
		}
		foreach ($res['rows'] as $k => $v)
		{
			//隐藏身份证后四位
			if (strlen($v['idnumber']) == 15)
			{
				$res['rows'][$k]['idnumber'] = substr_replace($v['idnumber'], "****", 8, 4);
			} else
			{
				$res['rows'][$k]['idnumber'] = substr_replace($v['idnumber'], "****", 6, 8);
			}
		}

		return $res;
	}

	/**
	 * 二维数组去重
	 * @param $arr
	 * @param $key
	 * @return array
	 */
	public function array_unset_tt($arr, $key)
	{
		//建立一个目标数组
		$res = [];
		foreach ($arr as $value)
		{
			//查看有没有重复项
			if (isset($res[$value[$key]]))
			{
				unset($value[$key]);  //有：销毁
			} else
			{
				$res[$value[$key]] = $value;
			}
		}

		return $res;
	}

	/**
	 * 客户信息管理汇总
	 * @param string $page
	 * @param string $first
	 * @param string $like
	 * @param string $where
	 * @param string $date
	 * @return mixed
	 */
	public function get_yx_account_mysql_data(
		$page = '',
		$first = '',
		$like = '',
		$where = '',
		$date = '',
		$status = '',
		$sort = '',
		$order = '',
		$back_end_date=''
	) {
		if ($sort && $order)
		{
			$this->db->order_by($sort, $order);
		}
		if ($where)
		{
			$this->db->where($where);
		}
		$this->con_where($like);
		if ($page !== '' && $first !== '')
		{
			$this->db->limit($page, $first);
		}
		$bed_where = '';
		if ($back_end_date == 'one_day')
		{
			$bed_where = ' and  next_repay_time >= \' '.date('Y-m-d').' \' and next_repay_time <= \''.date(
					"Y-m-d",
					strtotime("+1 day")
				).'\'';
		} elseif ($back_end_date == 'one_week')
		{
			$bed_where = $this->do_where_date($bed_where, 7);
		} elseif ($back_end_date == 'one_month')
		{
			$bed_where = $this->do_where_date($bed_where, 30);
		} else
		{
			$bed_where = '';
		}
//		$sonsql = 'select account,`status` from fms_yx_repaying where task_time >='.$this->task_time.$bed_where;
		$sonsql = 'select account,`status` from fms_yx_repaying where task_time >='.$this->task_time.$bed_where;
//		print_r($sonsql);die;
		$res['rows'] = $this->db
			->select(
				'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.status'
			)
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
//			->join('('.$sonsql.') c', 'a.yx_account = c.account', 'left')
			->join('('.$sonsql.') c', 'a.yx_account = c.account')
			->where('b.check_status', 1)
			->get()
			->result_array();
		$this->con_where($like);
		$res['total'] = $this->db
			->select(
				'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd,b.pwd_effective,b.reg_time,c.status'
			)
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
			->join('('.$sonsql.') c', 'a.yx_account = c.account', 'left')
			->where('b.check_status', 1)->count_all_results();
		if ( ! empty($date['start_date']))
		{
			foreach ($res['rows'] as $k => $v)
			{
				if (strtotime($date['start_date']) > strtotime($v['ctime']))
				{
					unset($res['rows'][$k]);
				}
			}
		}

		if ( ! empty($date['end_date']))
		{
			foreach ($res['rows'] as $k => $v)
			{
				if (strtotime($date['end_date']) < strtotime($v['ctime']))
				{
					unset($res['rows'][$k]);
				}
			}
		}
		foreach ($res['rows'] as $k => $v)
		{
			//隐藏身份证后四位
			if (strlen($v['idnumber']) == 15)
			{
				$res['rows'][$k]['idnumber'] = substr_replace($v['idnumber'], "****", 8, 4);
			} else
			{
				$res['rows'][$k]['idnumber'] = substr_replace($v['idnumber'], "****", 6, 8);
			}
		}
		foreach ($res['rows'] as $k => $v)//客户信息管理汇总--状态
		{
			if ($v['status'] == '')
			{
				$rep = $this->db->select('f_status')->where('yx_account', $v['yx_account'])->order_by(
					'f_time_stamp',
					'desc'
				)->limit(1)->get('yx_finish')->row_array();
				if ($rep)
				{
					$res['rows'][$k]['status'] = $rep['f_status'];
				} else
				{
					$res['rows'][$k]['status'] = '暂无借款信息';
				}
			}
		}

		return $res;
	}

	/**
	 * 手机号加星****
	 * @param $mobile
	 * @return string
	 * mb_substr
	 */
	function mobile_asterisk($mobile = '')
	{
		$mobile_asterisk = substr($mobile, 0, 3)."****".substr($mobile, 7, 4);

		return $mobile_asterisk;
	}

	/**
	 * 人名加星号******
	 * @param        $string
	 * @param        $sublen
	 * @param int    $start
	 * @param string $code
	 * @return string
	 */
	private function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
	{
		if ($code == 'UTF-8')
		{
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);
			if (count($t_string[0]) - $start > $sublen)
			{
				return join('', array_slice($t_string[0], $start, $sublen));
			}

			return join('', array_slice($t_string[0], $start, $sublen));
		} else
		{
			$start = $start * 2;
			$sublen = $sublen * 2;
			$strlen = strlen($string);
			$tmpstr = '';

			for ($i = 0; $i < $strlen; $i++)
			{
				if ($i >= $start && $i < ($start + $sublen))
				{
					if (ord(substr($string, $i, 1)) > 129)
					{
						$tmpstr .= substr($string, $i, 2);
					} else
					{
						$tmpstr .= substr($string, $i, 1);
					}
				}
				if (ord(substr($string, $i, 1)) > 129)
				{
					$i++;
				}
			}

			//if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
			return $tmpstr;
		}
	}

	/**
	 * 两个字的人名处理
	 * @param     $name
	 * @param int $num
	 * @return string
	 */
	function starReplace($name, $num = 0)
	{
		if ($num && mb_strlen($name, 'UTF-8') > $num)
		{
			return mb_substr($name, 0, 4).'*';
		}

		if ($num && mb_strlen($name, 'UTF-8') <= $num)
		{
			return $name;
		}

		$doubleSurname = [
			'欧阳',
			'太史',
			'端木',
			'上官',
			'司马',
			'东方',
			'独孤',
			'南宫',
			'万俟',
			'闻人',
			'夏侯',
			'诸葛',
			'尉迟',
			'公羊',
			'赫连',
			'澹台',
			'皇甫',
			'宗政',
			'濮阳',
			'公冶',
			'太叔',
			'申屠',
			'公孙',
			'慕容',
			'仲孙',
			'钟离',
			'长孙',
			'宇文',
			'司徒',
			'鲜于',
			'司空',
			'闾丘',
			'子车',
			'亓官',
			'司寇',
			'巫马',
			'公西',
			'颛孙',
			'壤驷',
			'公良',
			'漆雕',
			'乐正',
			'宰父',
			'谷梁',
			'拓跋',
			'夹谷',
			'轩辕',
			'令狐',
			'段干',
			'百里',
			'呼延',
			'东郭',
			'南门',
			'羊舌',
			'微生',
			'公户',
			'公玉',
			'公仪',
			'梁丘',
			'公仲',
			'公上',
			'公门',
			'公山',
			'公坚',
			'左丘',
			'公伯',
			'西门',
			'公祖',
			'第五',
			'公乘',
			'贯丘',
			'公皙',
			'南荣',
			'东里',
			'东宫',
			'仲长',
			'子书',
			'子桑',
			'即墨',
			'达奚',
			'褚师',
			'吴铭',
		];

		$surname = mb_substr($name, 0, 2);
		if (in_array($surname, $doubleSurname))
		{
			$name = mb_substr($name, 0, 2).str_repeat('**', (mb_strlen($name, 'UTF-8') - 2));
		} else
		{
			$name = mb_substr($name, 0, 1).str_repeat('**', (mb_strlen($name, 'UTF-8') - 1));
		}


		return $name;
	}


	/**
	 * 还款中
	 * @param $account
	 * @return mixed
	 */
	public function get_yx_loan_nofinish($account)
	{
		$ret = $this->db->select('max(task_time) as task_time')
			->where('account', $account)
			->limit(1)
			->get('fms_yx_repaying')
			->row_array();
		$nofinish = $this->db
			->where('account', $account)
			->where('task_time=', $ret['task_time'])
			->order_by('id desc')
			->get('fms_yx_repaying')
			->result_array();

		return $nofinish;
	}


	/**
	 * 获取已完结的借款信息
	 * @param string $page
	 * @param string $first
	 * @param string $account
	 * @param string $date
	 */
	public function get_finished_by_mysql($page = '', $first = '', $account = '', $date = '')
	{

		if ($page !== '' && $first !== '')
		{
			$this->db->limit($page, $first);
		}
		$order_date = $this->db->select('order_date')->order_by('id desc')->limit(1)->get('yx_finish')->row_array();
		$res['rows'] = $this->db->where('yx_account', $account)->where('order_date', $order_date['order_date'])->get(
			'yx_finish'
		)->result_array();
		$res['total'] = $this->db->where('yx_account', $account)->count_all_results('yx_finish');

		return $res;
	}

	public function back_plan($account, $title)
	{
		$order_date = $this->db->select('order_date')->limit(1)->order_by('id desc')->get('yx_back_plan')->row_array();
		$res = $this->db->where('yx_account', $account)->where('title', $title)->where(
			'order_date',
			$order_date['order_date']
		)->get('yx_back_plan')->result_array();

		return $res;
	}

	public function get_one_finished_by_mysql($account, $title)
	{
		$res = $this->db->where('yx_account', $account)->where('pname', $title)->get('yx_finish')->result_array();

		return $res;
	}

	public function get_kh_detail_info($account)
	{
		$res = $this->db
			->select('a.*,b.acctBal,b.acctAmount,b.frozBl,b.if_assessment,d.idnumber,c.binding_phone')
			->from('fms_yx_account_about a')
			->join('fms_yx_account_have_money b', 'a.account = b.account')
			->join('fms_yx_account c', 'a.account = c.account')
			->join('fms_user d', 'd.yx_account = c.account')
			->where('c.check_status', 1)
			->where('a.account', $account)
			->get()
			->row_array();
		if ($res && $res['if_assessment'] == 1)
		{
			$res['if_assessment'] = '已做风险测评';
		} elseif ($res && $res['if_assessment'] == 0)
		{
			$res['if_assessment'] = '未做风险测评';
		}
		if ($res && $res['account'] && $res['binding_phone'] && substr($res['account'], 2) == $res['binding_phone'])
		{
			$res['yz'] = '一致';
		} else
		{
			$res['yz'] = '不一致';
		}

		return $res;
	}

	/**
	 * 借款合同下载
	 * @param $account
	 * @param $title
	 * @return mixed
	 */
	public function get_finish_contract($account, $title)
	{
		$order_date = $this->db->select('order_date')->order_by('id desc')->limit(1)->get(
			'yx_finish_contract'
		)->row_array();
		$contractres = $this->db
			->where('yx_account', $account)
			->where('title', $title)
			->where('order_date', $order_date['order_date'])
			->get('yx_finish_contract')->row_array();

		return $contractres;
	}

	/**
	 * 获取单个用户信息
	 * @param $account
	 * @return array
	 */
	public function get_one_yx_account($account)
	{
		$res = $this->db
			->select('a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone')
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
			->where('b.check_status', 1)
			->where('a.yx_account', $account)
			->get()->row_array();

		return [$res];
	}

	/**
	 * 获取所有channel 渠道
	 */
	public function get_channel_data()
	{
		$chres = $this->db->select('channel')->get('fms_user')->result_array();
		$uniq_res = $this->array_unique_fb($chres);

		return $uniq_res;
	}

	/**
	 * 二维数组去重
	 * @param $array2D
	 * @return mixed
	 */
	private function array_unique_fb($array2D)
	{
		foreach ($array2D as $k => $v)
		{
			$v = join(',', $v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
			$temp[$k] = $v;
		}
		$temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
		foreach ($temp as $k => $v)
		{
			$array = explode(',', $v); //再将拆开的数组重新组装
			//下面的索引根据自己的情况进行修改即可
			$temp2[$k]['channel'] = $array[0];
		}

		return $temp2;
	}

	/**
	 * @param $like
	 */
	private function con_where($like)
	{
//客户姓名
		if ($like['a.name'])
		{
			$this->db->like('a.name', $like['a.name'], 'both');
		}
		//判断字符串是否全为数字--手机
		if ($like['b.reg_phone'])
		{
			$this->db->like('b.reg_phone', $like['b.reg_phone'], 'both');
		}
		//银信account
		if ($like['b.account'])
		{
			$this->db->where('b.account', $like['b.account']);
		}
		//客户id
		if ($like['a.channel'])
		{
			$this->db->where('a.channel', $like['a.channel']);
		}
	}

	/**
	 * @param $bed_where
	 * @return string
	 */
	private function do_where_date($bed_where, $max_num)
	{
		$bed_where .= ' and next_repay_time >= \''.date('Y-m-d').'\' ';
		$bed_where .= ' and next_repay_time <= \''.date('Y-m-d', strtotime("+$max_num day")).'\'';

		return $bed_where;
	}

	/**
	 * 获取对应银信数据
	 */
	public function get_all_type_data($status)
	{
		if ($status == 'borrowing')
		{
			$sonsql = 'select account,`status`,loan_title from fms_yx_repaying where task_time >='.$this->task_time;
			$res = $this->borring_sql_method($sonsql);

		} elseif ($status == 'finish')
		{
			$b_sonsql = 'select account from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$b_res = $this->db->query($b_sonsql)->result_array();
			$sql = 'SELECT a.`yx_account`,a.`f_time_stamp`,a.`f_status`
					FROM   yx_finish a
						   JOIN (SELECT yx_account,Max(f_time_stamp) m,order_date
								 FROM   yx_finish
								 WHERE  order_date = (SELECT Max(order_date)
													  FROM   yx_finish)
								 GROUP  BY `yx_account`) b
							 ON a.yx_account = b.yx_account
					WHERE  a.f_time_stamp = b.m and a.`order_date`=(SELECT Max(order_date)
													  FROM   yx_finish)';
			$res = $this->finish_sql($sql, $b_res);
		} elseif ($status == 'empty')
		{
			$b_sonsql = 'select account from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$b_res = $this->db->query($b_sonsql)->result_array();
			$fres = 'select yx_account from yx_finish where order_date >=(select order_date from yx_finish order by order_date desc limit 1) order by id desc';
			$f_res = $this->db->query($fres)->result_array();
			$f_sonsql = 'select yx_account,`f_status`,f_time_stamp from yx_finish where order_date >=(select order_date from yx_finish order by order_date desc limit 1) order by id desc';
			$res = $this->empty_sql($f_sonsql, $b_res, $f_res);
			foreach ($res as $k => $v)
			{
				$res[$k]['f_status'] = '暂无记录';
			}
		} elseif ($status == 'overdue')
		{
			$sonsql = 'select account,`status` from fms_yx_repaying where task_time >='.$this->task_time.' order by id desc';
			$res = $this->overdue_sql($sonsql);

		} else
		{
			return ['code' => 0, 'msg' => '参数错误！'];
		}
		foreach ($res as $k => $v)
		{
			$res[$k]['idnumber'] = $v['idnumber'].' ';
			$res[$k]['reg_phone'] = $v['reg_phone'].' ';
		}
		$first_res = $res[0];
		$title_arr = ['渠道编号', '客户姓名', '身份证号码', '我司客户编号', '创建时间', '银信账户', '注册手机', '密码有效性', '注册时间', '状态'];
		$res[0] = $title_arr;
		array_push($res, $first_res);

		return $res;
	}

	/**
	 * @param $sonsql
	 * @return mixed
	 */
	private function borring_sql_method($sonsql)
	{
		$res = $this->db
			->select(
				'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd_effective,b.reg_time,c.status,c.loan_title'
			)
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
			->join('('.$sonsql.') c', 'a.yx_account = c.account')
			->where('c.status', '还款中')
			->where('b.check_status', 1)
			->get()
			->result_array();

		return $res;
	}

	/**
	 * @param $sql
	 * @param $b_res
	 * @return mixed
	 */
	private function finish_sql($sql, $b_res)
	{
		$res = $this->db
			->select(
				'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd_effective,b.reg_time,c.f_status'
			)
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
			->join('('.$sql.') c', 'a.yx_account=c.yx_account')
			->where_not_in('a.yx_account', array_column($b_res, 'account'))
			->where('b.check_status', 1)
			->get()
			->result_array();

		return $res;
	}

	/**
	 * @param $f_sonsql
	 * @param $b_res
	 * @param $f_res
	 * @return array
	 */
	private function empty_sql($f_sonsql, $b_res, $f_res)
	{
		$finish = $this->db
			->select(
				'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd_effective,b.reg_time,c.f_status'
			)
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
			->join('('.$f_sonsql.') c', 'a.yx_account = c.yx_account', 'left')
			->where_not_in('a.yx_account', array_column($b_res, 'account'))
			->where_not_in('a.yx_account', array_column($f_res, 'yx_account'))
			->where('b.check_status', 1)
			->get()
			->result_array();
		$finish = $this->array_unset_tt($finish, 'yx_account');
		$res = array_values($finish);

		return $res;
	}

	/**
	 * @param $sonsql
	 * @return mixed
	 */
	private function overdue_sql($sonsql)
	{
		$res = $this->db
			->select(
				'a.fuserid,a.name,a.idnumber,a.channel,a.ctime,a.yx_account,b.reg_phone,b.pwd_effective,b.reg_time,c.status'
			)
			->from('fms_user a')
			->join('fms_yx_account b', 'a.yx_account=b.account')
			->join('('.$sonsql.') c', 'a.yx_account = c.account')
			->where('c.status', '逾期')
			->where('b.check_status', 1)
			->get()
			->result_array();

		return $res;
	}


}