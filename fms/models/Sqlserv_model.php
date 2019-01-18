<?php
/**
 *
 */
class Sqlserv_model extends CI_Model
{
    private $db;
    private $url = "http://139.224.113.2/middle/index.php/middle/index";
    function __construct()
    {
        parent::__construct();
        // $this->db = $this->load->database('sqlserver',true);
        $this->mysql_db = $this->load->database('default',true);
    }

    //获取数据库的所有表名--没用到
    // public function sqlserv_show_tables()
    // {
    //     $query = $this->db->query("select name from sysobjects where xtype='u'");
    //     return $query;
    // }


    //获取sqlserver表名
    public function get_sql_tabname()
    {
        // $url = "http://139.224.113.2/middle/index.php/middle/index";
        $sql = ['sql'=>"select tabID,tabName from tabDIYTable"];
        $res = $this->request_post($this->url, $sql);
        return $res;
    }
    //获取sqlserver表的字段
    public function get_sql_tabfield($tab = 'tabDIYTable')
    {
        // $f_sql = $this->db->query("Select name from syscolumns Where ID=OBJECT_ID('{$tab}')");
        $sql = ['sql'=>"Select name from syscolumns Where ID=OBJECT_ID('{$tab}')"];
        $f_sql = $this->request_post($this->url, $sql);
        return $f_sql;
    }

// 获取最后的的一条数据--本类用到
    public function gettabinfo($tabname)
    {
        $tabname = "tabDIYTable".$tabname;
        // $tabinfo = $this->db->query("select top 1 * from {$tabname} order by ID desc;")->result_array();
        $sql =['sql'=>"select top 1 * from {$tabname} order by ID desc;"];
        $tabinfo = $this->request_post($this->url, $sql);
// print_r($tabinfo);die;
        return $tabinfo;
    }
    // 将数据插入到sqlserver表
    public function insert_my_sql($tab, $arr_data)
    {
//        print_r($tab);
//        print_r($arr_data);die;
        $rule = $this->mysql_db->where(['sql_tabname'=>$tab,'condition'=>1])->get('sql_mysql')->result_array();
//        print_r($rule);die;
         if ($rule) {
            $my_condres = array_column($rule, 'mysql_tab_field');
            $sql_condres = array_column($rule, 'sql_tab_field');
            foreach ($arr_data as $k => $v) {
                $uu_arr = $v;//需要更新的字段
                foreach ($sql_condres as $k1 => $v1) {
                    $sc[$v1] = $v[$v1];
                    unset($uu_arr[$v1]);
                }
                // $sql_sc_res = $this->db->where($sc)->get('tabDIYTable'.$tab)->result_array();
                $sql = $this->mysql_db->where($sc)->get_compiled_select('tabDIYTable'.$tab);
                $sql = str_replace('`', '', $sql);
                $arrsql = ['sql'=>$sql];
                $sql_sc_res = $this->request_post($this->url, $arrsql);
                $sql_sc_res = json_decode($sql_sc_res, true);
                if ($sql_sc_res) {
                // if (0) {
                    foreach ($sql_sc_res as $k => $v) {
                        $u_arr = array_merge($v,$uu_arr);
                        $usql = $this->mysql_db->update_string('tabDIYTable'.$tab, $u_arr, ['ID'=>$u_arr['ID']]);
                        $usql = str_replace('`', '', $usql);
                        $up_sql = $this->request_post("http://139.224.113.2/middle/index.php/middle/update_data",['sql'=>$usql]);
                        // $up_sql = $this->db->where('ID',$u_arr['ID'])->update('tabDIYTable'.$tab,$u_arr);

                        if (!$up_sql) {
                            echo "代码153行，更新sqlserver有error！";
                        }
                    }
                }else{
                    // $tabdiytable = $this->db->select('tabID,tabName')->where('tabID',$tab)->order_by('tabID','desc')->get('tabDIYTable')->row_array();
                    $sql = $this->mysql_db->select('tabID,tabName')->where('tabID',$tab)->order_by('tabID','desc')->get_compiled_select('tabDIYTable');
                    $sql = str_replace('`', '', $sql);
                    $tabdiytable = $this->request_post($this->url, ['sql'=> $sql]);
                    $tabdiytable = json_decode($tabdiytable,true);
                    // print_r($tabdiytable);die;
                    if (!$tabdiytable) {
                        echo "代码159行查询失败！";
                    }
                    $pnum = stripos($tabdiytable[0]['tabName'], '_');
                    $table = substr($tabdiytable[0]['tabName'], 0,7);
                    // $tabreportres = $this->db->select('RepID,sName')->where('sName', $table)->get('tabReport')->row_array();
                    $tabreportresql = $this->mysql_db->select('RepID,sName')->where('sName', $table)->get_compiled_select('tabReport');
                    $tabreportresql = str_replace('`', '', $tabreportresql);
                    $tabdiytableres = $this->request_post($this->url, ['sql'=> $tabreportresql]);
                    $tabdiytableres = json_decode($tabdiytableres,true);
                    // print_r($tabdiytableres);die;
                    if (!$tabdiytableres) {
                        echo "代码165行查询失败！";
                    }
                    $tabres =  $this->gettabinfo($tab);
                    $tabres = json_decode($tabres, true);
                    // print_r($tabres);die;
                    if (!$tabres) {
                        $tabres[0]['ID'] = 1;
                    }

                    $tabres = Array('ID' => $tabres[0]['ID']+1,'iOrd'=>1,'RepID'=>$tabdiytableres[0]['RepID'],'FillDate'=>date("Y-m-d H:i:s",time()),'ModifyDate' =>'2018-10-12 17:28:17.000','DeptID' => 0,'OperID' => 13, 'sDesc' => '','bIsFinish' => 1 ,'bLocked' => 0 ,'bCanViewWhenNoFinished' => 0 ,'OperID2' => 13, 'dUpdate2' => '');
                    $end = array_merge($tabres,$v);
                    // print_r($end);die;
                    // $eres = $this->db->insert('tabDIYTable'.$tab,$end);
                    $eres = $this->db->insert_string('tabDIYTable'.$tab,$end);
                    $eres = str_replace('`', '"', $eres);
                    $eres = $this->request_post('http://139.224.113.2/middle/index.php/middle/update_data', ['sql'=>$eres]);
                    // print_r($eres);die;
                    if (!$eres) {
                        echo "代码175行插入失败！";
                    }
                }
            }
        }else{
            // echo "2223";die;
            $this->sqlserv_insert_mysqldata($tab, $arr_data);
        }
    }

    // //获取规则表的条件condition
    // public function get_condition($select = '*', $where, $tab)
    // {
    //     $rule_res = $this->mysql_db->select($select)->where($where)->get($tab)->result_array();
    //     print_r($rule_res);die;

    // }
//将数据插入到sqlserver中
    public function sqlserv_insert_mysqldata($tab, $arr_data)
    {
        // $tabdiytable = $this->db->select('tabID,tabName')->where('tabID',$tab)->order_by('tabID','desc')->get('tabDIYTable')->row_array();
        $tabdiytable = $this->mysql_db->select('tabID,tabName')->where('tabID',$tab)->order_by('tabID','desc')->get_compiled_select('tabDIYTable');
        $tabdiytable = str_replace('`', '', $tabdiytable);
        $tabdiytable = $this->request_post($this->url, ['sql'=> $tabdiytable]);
        $tabdiytable = json_decode($tabdiytable,true);
        if (!$tabdiytable) {
            echo "代码189行查询失败！";
        }
        $pnum = stripos($tabdiytable[0]['tabName'], '_');
        $table = substr($tabdiytable[0]['tabName'], 0,7);
        // $tabreportres = $this->db->select('RepID,sName')->where('sName', $table)->get('tabReport')->row_array();
        $tabreportres = $this->mysql_db->select('RepID,sName')->where('sName', $table)->get_compiled_select('tabReport');
        $tabreportres = str_replace('`', '', $tabreportres);
        $tabreportres = $this->request_post($this->url, ['sql'=> $tabreportres]);
        $tabreportres = json_decode($tabreportres,true);
        if (!$tabreportres) {
            echo "代码192行查询失败！";
        }
        $tabres =  $this->gettabinfo($tab);
        $tabres = json_decode($tabres,true);
        if (!$tabres) {
            $tabres[0]['ID'] = 1;
        }
        $tabres = Array('ID' => $tabres[0]['ID'],'iOrd'=>1,'RepID'=>$tabreportres[0]['RepID'],'FillDate'=>date("Y-m-d H:i:s",time()),'ModifyDate' =>'2018-10-12 17:28:17.000','DeptID' => 0,'OperID' => 13, 'sDesc' => '','bIsFinish' => 1 ,'bLocked' => 0 ,'bCanViewWhenNoFinished' => 0 ,'OperID2' => 13, 'dUpdate2' => '');
        $i = 0;
        foreach ($arr_data as $k => $v) {
            $end = array_merge($tabres,$v);
            $i++;
            $end['ID'] = $end['ID']+$i;
            $insert_res = $this->mysql_db->insert_string('tabDIYTable'.$tab,$end);//insert_string
            $insert_res = str_replace('`', '"', $insert_res);
            $insert_res = $this->request_post("http://139.224.113.2/middle/index.php/middle/update_data",['sql'=>$insert_res]);
            if (!$insert_res) {
                echo "代码203行插入失败！";
            }
        }
    }

    //根据中文表名获取表的所有字段
    public function  get_field_by_zn_name($zn_name='')
    {
        // $tdt_res = $this->db->select('tabID')->where('tabName',$zn_name)->get('tabDIYTable')->row_array();
        $tdt_res = $this->mysql_db->select('tabID')->where('tabName',$zn_name)->get_compiled_select('tabDIYTable');
        print_r($tdt_res);die;
        $tdt_res = str_replace('`', '', $tdt_res);
        $tdt_res = $this->request_post($this->url, ['sql'=> $tdt_res]);
        $tdt_res = json_decode($tdt_res,true);
        // $zn_field = $this->db->select('tabID,fieldID,fieldName')->where('tabID',$tdt_res['tabID'])->get("tabDIYTableField")->result_array();
        $zn_field = $this->mysql_db->select('tabID,fieldID,fieldName')->where('tabID',$tdt_res[0]['tabID'])->get_compiled_select("tabDIYTableField");
        $zn_field = str_replace('`', '', $zn_field);
        $zn_field = $this->request_post($this->url, ['sql'=> $zn_field]);
        $zn_field = json_decode($zn_field,true);
//        print_r($zn_field);die;
        foreach ($zn_field as $k => $v) {
            $zn_field[$k]['fieldID'] = 'F'.$v['fieldID'];
        }

        return $zn_field;
    }
//模拟post请求
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

    //获得查询sqlserver的sql语句
    public function get_select_sql($table, $where)
    {
        $res = $this->mysql_db->where($where)->get_compiled_select($table);
        return $res;
    }
    //获得插入sqlserver的sql语句
    public function get_insert_sql($table, $data)
    {
        return $this->mysql_db->insert_string($table, $data);
    }
    //获得更新的sqlserver的sql语句
    public function get_update_sql($table, $data,$where)
    {
        return $this->mysql_db->update_string($table, $data, $where);
    }



}