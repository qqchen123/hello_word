<?php
/**
 *
 */
class Mysql_model extends CI_Model
{
    private $tableName = 'fms_qiyeinfo';
    private $db;

    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default',true);
//        $this->sqlserv_db = $this->load->database('sqlserver',true);
    }


    //获取MySQL所有数据表
    public function get_mysql_tabname()
    {
        $tabres = $this->db->query("SELECT table_name FROM information_schema. TABLES WHERE table_schema = 'fms'");
        return $tabres;
    }
    //获取MySQL所有数据表的字段
    public function get_sql_tabfield($tab)
    {
        $tabres = $this->db->query("select COLUMN_NAME from information_schema.COLUMNS where table_name = '{$tab}'");
        return $tabres;
    }
    //处理字段
    public function deal_field($data='')
    {
        $gsres = $this->get_sqlserv_z_name($data['sql_tabname']);
        $data['z_tabname'] = $gsres['tabName'];
        $resdeal = $this->db->insert('sql_mysql',$data);
        return $resdeal;
    }
    //获取sqlserver表字段的中文名
    public function get_sqlserv_z_name($where='')
    {
//        return $this->sqlserv_db->select('tabName')->where('tabID',$where)->get('tabDIYTable')->row_array();
        $res =  $this->db->select('tabName')->where('tabID',$where)->get_compiled_select('tabDIYTable');
        $res = str_replace('`', '', $res);
        $res = ['sql'=>$res];
        return $sql_sc_res = $this->request_post($this->url, $res);
    }
    //数据列表
    public function db_list()
    {
        $listres = $this->db->get('sql_mysql');
        return $listres;
    }
    //清空数据表
    public function clear_tab()
    {
        $clear = $this->db->query("truncate table sql_mysql");
        return $clear;
    }
// 获取规则表的所有字段
    public function get_relatemysql_data()
    {
        $myres = $this->db->select('mysql_tabname,mysql_tab_field,sql_tabname,sql_tab_field')
            // ->group_by('mysql_tabname')
            ->get('sql_mysql');
        return $myres;
    }

    // 获取指定表的指定字段
    public function get_tab($tab, $arr_field)
    {
        $select = implode(",", $arr_field);
        $this->db->select($select);
        $tab_info = $this->db->limit(5)->get($tab);
        return $tab_info;
    }

    // 获取规则表的 所有 MySQL表名，MySQL字段
    public function sql_my_field()
    {
        $fieldres = $this->db->select('mysql_tabname, sql_tabname')->get('sql_mysql');
        return $fieldres;
    }
    //删除规则表的一行
    public function delrow($id)
    {
        $delres = $this->db->where('id',$id)->delete('sql_mysql');
        return $delres;
    }

    function request_post($url = '', $post_data = array()) {
        if (empty($url) || empty($post_data)) {
            return false;
        }
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }




}