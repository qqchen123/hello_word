<?php 

/**
 * @desc 身份证号码service
 */
class Idnumber_service extends Admin_service
{
	
	/**
	 * @name 构造函数
	 */
	function __construct()
	{
		parent::__construct();
        $this->load->model('user/NativePlace_model', 'NativePlace');
	}

	/**
     * [年龄，性别，身份证校验，地区]
     * @param $perId 身份证号
     * @return array
     */
    public function checkId($perId)
    {
        $perId = trim($perId);
        $coefficient = [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,0];
        $checkCode = [1,0,'X',9,8,7,6,5,4,3,2];
        $sum = 0;
        $area = substr($perId,0,6);
        $sex  = substr($perId,16,1);
        $valid = substr($perId,17,1);
        foreach (str_split(substr($perId,0,17),1) as $_key => $value) {
            $sum += $value * $coefficient[$_key];
        }
        $prov = $this->NativePlace->get_prov($area);
        return [
        	'sex' => $sex % 2 == 0 ? '女' : '男',
        	'check' => $valid == $checkCode[$sum % 11],
        	'area' => $prov === false ? 0 : $prov[0]['jiguanadr']
        ];
    }

    /**
     * @name 获取年龄
     * @param string $id 身份证ID
     * @return int
     */
    public function get_age_by_id($id)
    {
		//过了这年的生日才算多了1周岁
        if(empty($id)) return '';
        $date = strtotime(substr($id,6,8));
		//获得出生年月日的时间戳
        $today = strtotime('today');
		//获得今日的时间戳 111cn.net
        $diff = floor(($today-$date)/86400/365);
		//得到两个日期相差的大体年数

		//strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($id,6,8).' +'.$diff.'years') > $today ? ($diff+1) : $diff;
        return $age;
    }

    /**
     * @name 获取生日
     * @param string $id 身份证ID
     * @return string
     */
    public function get_birth_date($id) 
    {
        $birth = substr($id, 6, 8);
        $year = substr($birth, 0, 4);
        $month = substr($birth, 4, 2);
        $day = substr($birth, 6, 2);
        return $year.'-'.$month.'-'.$day;
    }

}