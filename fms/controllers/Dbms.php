<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dbms extends Admin_Controller {

    public function __construct($value='')
    {
        parent::__construct();
        $this->load->model('sqlserv_model');
        $this->load->model('mysql_model');
    }
    public function db_page()
    {
        $this->showpage('db_page');
    }

    //sqlserver表
    public function sql_tab()
    {
        $sql_res = $this->sqlserv_model->get_sql_tabname();
        // echo json_encode($sql_res,JSON_UNESCAPED_UNICODE);
        echo $sql_res;
    }
    //sqlserver表字段
    public function sql_tab_field()
    {
        $tabid = $this->input->post('tabid');
        $sql_res = $this->sqlserv_model->get_sql_tabfield('tabDIYTable'.$tabid);
        // echo json_encode($sql_res);
        echo $sql_res;
    }
    //MySQL表
    public function mysql_tab()
    {
        $mysql_res = $this->mysql_model->get_mysql_tabname()->result_array();
        echo json_encode($mysql_res);
    }
    //mysql表字段
    public function mysql_tab_field()
    {
        $tabid = $this->input->post('tabid');
        $sql_res = $this->mysql_model->get_sql_tabfield($tabid)->result_array();
        echo json_encode($sql_res);
    }

    //处理sqlserver、MySQL表的字段
    public function dealdata()
    {
        $datares = $this->input->post();
        $dealres = $this->mysql_model->deal_field($datares);
        print_r($dealres);die;
    }
    //数据列表
    public function deal_mysql_sqlserv()
    {
        $list = $this->mysql_model->db_list()->result_array();
        echo json_encode($list);
    }
    //清空数据表
    public function clear_tab()
    {
        $clear = $this->mysql_model->clear_tab();
        echo $clear;
    }
    //中间件的主要方法
    public function get_relatemysql_data()
    {
        $my_ress = $this->mysql_model->get_relatemysql_data()->result_array();
        $my_res = array_column($my_ress, 'mysql_tabname');
        $unique = array_unique($my_res);
        // print_r($my_res);die;
        foreach ($unique as $k => $v) {
            foreach ($my_ress as $k1 => $v1) {

                if($v == $v1['mysql_tabname']){
                    $arr1[] = $v1['mysql_tab_field'].' as '.$v1['sql_tab_field'];
                }
            }
            $arrname[$v] = $arr1;
            $arr1 = [];
        }
        foreach ($arrname as $k => $v) {

            $insert_res[$k] = $this->mysql_model->get_tab($k, $v)->result_array();
        }
        $tab = $this->mysql_model->sql_my_field()->result_array();
        // print_r($insert_res);die;
        $sql_mys_arr = $this->array_unique_fb($tab);
        foreach ($insert_res as $k1 => $v1) {
            foreach ($sql_mys_arr as $k => $v) {
                if ($k1 == $v[0]) {
                    $this->sqlserv_model->insert_my_sql($v[1],$v1);
                }
            }
        }
        echo "----";
        echo 1;//返回1；
    }
    //二维数组去重
    public function array_unique_fb($array2D){
        foreach ($array2D as $v){
            $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[]=$v;
        }
        $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
        foreach ($temp as $k => $v){
            $temp[$k]=explode(',',$v); //再将拆开的数组重新组装
        }
        return $temp;
    }
    //删除规则表的一条数据
    public function delrowdata()
    {
        $id = $this->input->post('id');
        $del_res = $this->mysql_model->delrow($id);
        echo $del_res;
    }
    public function phpinfo()
    {
        echo phpinfo();
    }


    public function get_field_by_zn_name()
    {
        $res = $this->sqlserv_model->get_field_by_zn_name('其他2_主表');
        print_r($res);die;
    }

    //处理post传输过来的数据到sqlserver---ceshi
    public function deal_post_data11()
    {
        $data  = $this->input->post('data');
        if (empty($data)){
            echo '数据不能为空';die;
        }
//        $data = '{"repid":7,"db_name":"yxtest","table_name":"借款信息未完结_主表","key":{"借款标题":"安享201808246","登录用户名":"YX15901889950"},"data":{"借款标题":"安享201808246","登录用户名":"YX15901889950","网址":"https:\/\/www.yinxinsirencaihang.com\/investment\/7b87dbf839074f4bae6272c4d332f235\/projectdetail","满标时间":"2018-08-27 17:36:09","借款金额(元)":"200,000.00","状态":"还款中","期数":"1\/3","下期还款本息(元)":"1,166.66","下期还款日":"2018-09-27","还款方式":"先息后本","操作":"开通自动还款"}}';
//        $data = '{"repid":13,"db_name":"yxtest","table_name":"出借记录_主表","key":{"标题名":"安享201808246"},"data":{"登录用户名":"YX15901889950","标题名":"安享201808246","序号":"1","出借用户":"Y****4","出借金额(元)":"1000.00","出借时间":"2018-08-25 09:54:03","出借方式":"苹果 手动出借"}}  ';
//        $data = '{"repid":13,"db_name":"yxtest","table_name":"出借记录_主表","key":{"标题名":"安享201808246","序号":"1"},"data":{"登录用户名":"YX15901889950","标题名":"安享201808246","序号":"1","出借用户":"Y****4","出借金额(元)":"1000.00","出借时间":"2018-08-25 09:54:03","出借方式":"苹果 手动出借"}}';
        $de_data = json_decode($data,true);
        //切换数据库
        $de_sql = 'select "tabID" from "tabDIYTable" where tabName='.'\''.$de_data['table_name'].'\'';
        $sql_info = ['sql'=>$de_sql,'db'=>$de_data['db_name']];
        $deres = $this->request_post("http://139.224.113.2/middle/index.php/middle/index_db",$sql_info);
        $deres = json_decode($deres,true);
//        print_r($deres);die;
        if (isset($deres['code']) && $deres['code']==0){
            echo json_encode($deres);die;
        }
        $dend_sql = 'select "tabID","fieldID","fieldName" from "tabDIYTableField" where tabID='.$deres[0]['tabID'];
        $sql_infos = ['sql'=>$dend_sql,'db'=>$de_data['db_name']];
        $dend_res = $this->request_post("http://139.224.113.2/middle/index.php/middle/index_db",$sql_infos);
        $dend_data = json_decode($dend_res,true);//get_select_sql
        foreach ($dend_data as $k=>$v){
            foreach ($de_data['key'] as $k1=>$v1){
                if ($v['fieldName']==$k1){
                    $field_arr['F'.$v['fieldID']] = $v1;
                }
            }
        }
//        print_r($field_arr);
        $ssql_re = $this->sqlserv_model->get_select_sql('tabDIYTable'.$dend_data[0]['tabID'], $field_arr);
        $ssql_re = str_replace('`', '"', $ssql_re);
//        print_r($ssql_re);die;
        $search_select_res = $this->request_post("http://139.224.113.2/middle/index.php/middle/index_db",['sql'=>$ssql_re,'db'=>$de_data['db_name']]);
        $search_select_res = json_decode($search_select_res,true);
        if (!count($search_select_res)){
            foreach ($dend_data as $k=>$v){
                foreach ($de_data['data'] as $k1=>$v1){
                    if ($v['fieldName'] == $k1){
                        $arr_de['F'.$v['fieldID']] = $v1;
                    }
                }
            }
            //插入之前查询最后一条数据的ID
            $lasqlid = "select top 1 * from ".'tabDIYTable'.$dend_data[0]['tabID']." order by ID desc";

            $get_last_id = $this->request_post(
                "http://139.224.113.2/middle/index.php/middle/index_db",
                ['sql'=>$lasqlid,'db'=>$de_data['db_name']]
            );
            $get_last_id = json_decode($get_last_id, true);
            if (count($get_last_id)){
                $id = $get_last_id[0]['ID']+1;
            }else{
                $id = 1;
            }

            $arr = ['ID'=>$id,'iOrd'=>1,'RepID'=>$de_data['repid'],'bIsFinish'=>'1'];
            $arr_de = array_merge($arr, $arr_de);
            $insert_sql = $this->sqlserv_model->get_insert_sql('tabDIYTable'.$dend_data[0]['tabID'],$arr_de);
            $isql = str_replace('`', '"', $insert_sql);
            $insert_sql_ser = $this->request_post("http://139.224.113.2/middle/index.php/middle/update_data_db",['sql'=>$isql,'db'=>$de_data['db_name']]);
            echo $insert_sql_ser;die;
        }else{
            $where = '';
            foreach ($dend_data as $k=>$v){
                foreach ($de_data['data'] as $k1=>$v1){
                    if ($v['fieldName'] == $k1){
                        $arr_de['F'.$v['fieldID']] = $v1;
                    }
                    if ($v['fieldName'] == array_keys($de_data['key'])[0]){
                        $where = ['F'.$v['fieldID']=>array_values($de_data['key'])[0]];
                    }
                }
            }
            $insert_sql = $this->sqlserv_model->get_update_sql('tabDIYTable'.$dend_data[0]['tabID'],$arr_de,$where);
            $isql = str_replace('`', '"', $insert_sql);
            $insert_sql_ser = $this->request_post("http://139.224.113.2/middle/index.php/middle/update_data_db",['sql'=>$isql,'db'=>$de_data['db_name']]);
            echo $insert_sql_ser;die;
        }
    }
//处理post传输过来的数据到sqlserver
    public function deal_post_data()
    {
        $data  = $this->input->post('data');
//        echo $data;die;
        if (empty($data)){
            echo '数据不能为空';die;
        }
//        $data = '{"repid":7,"db_name":"yxtest","table_name":"借款信息未完结_主表","key":{"借款标题":"安享201808246","登录用户名":"YX15901889950"},"data":{"借款标题":"安享201808246","登录用户名":"YX15901889950","网址":"https:\/\/www.yinxinsirencaihang.com\/investment\/7b87dbf839074f4bae6272c4d332f235\/projectdetail","满标时间":"2018-08-27 17:36:09","借款金额(元)":"200,000.00","状态":"还款中","期数":"1\/3","下期还款本息(元)":"1,166.66","下期还款日":"2018-09-27","还款方式":"先息后本","操作":"开通自动还款"}}';
//        $data = '{"repid":13,"db_name":"yxtest","table_name":"出借记录_主表","key":{"标题名":"安享201808246"},"data":{"登录用户名":"YX15901889950","标题名":"安享201808246","序号":"1","出借用户":"Y****4","出借金额(元)":"1000.00","出借时间":"2018-08-25 09:54:03","出借方式":"苹果 手动出借"}}  ';
//        $data = '{"repid":13,"db_name":"yxtest","table_name":"出借记录_主表","key":{"标题名":"安享201808246","序号":"1"},"data":{"登录用户名":"YX15901889950","标题名":"安享201808246","序号":"1","出借用户":"Y****4","出借金额(元)":"1000.00","出借时间":"2018-08-25 09:54:03","出借方式":"苹果 手动出借"}}';
        $de_data = json_decode($data,true);
        //切换数据库
        $de_sql = 'select "tabID" from "tabDIYTable" where tabName='.'\''.$de_data['table_name'].'\'';
        $sql_info = ['sql'=>$de_sql,'db'=>$de_data['db_name']];
        $deres = $this->request_post("http://139.224.113.2/middle/index.php/middle/index_db",$sql_info);
        $deres = json_decode($deres,true);
//        print_r($deres);die;
        if (isset($deres['code']) && $deres['code']==0){
            echo json_encode($deres);die;
        }
        $dend_sql = 'select "tabID","fieldID","fieldName" from "tabDIYTableField" where tabID='.$deres[0]['tabID'];
        $sql_infos = ['sql'=>$dend_sql,'db'=>$de_data['db_name']];
        $dend_res = $this->request_post("http://139.224.113.2/middle/index.php/middle/index_db",$sql_infos);
        $dend_data = json_decode($dend_res,true);//get_select_sql
        foreach ($dend_data as $k=>$v){
            foreach ($de_data['key'] as $k1=>$v1){
                if ($v['fieldName']==$k1){
                    $field_arr['F'.$v['fieldID']] = $v1;
                }
            }
        }
//        print_r($field_arr);
        $ssql_re = $this->sqlserv_model->get_select_sql('tabDIYTable'.$dend_data[0]['tabID'], $field_arr);
        $ssql_re = str_replace('`', '"', $ssql_re);
//        print_r($ssql_re);die;
        $search_select_res = $this->request_post("http://139.224.113.2/middle/index.php/middle/index_db",['sql'=>$ssql_re,'db'=>$de_data['db_name']]);
        $search_select_res = json_decode($search_select_res,true);
        if (!count($search_select_res)){
            foreach ($dend_data as $k=>$v){
                foreach ($de_data['data'] as $k1=>$v1){
                    if ($v['fieldName'] == $k1){
                        $arr_de['F'.$v['fieldID']] = $v1;
                    }
                }
            }
            //插入之前查询最后一条数据的ID
            $lasqlid = "select top 1 * from ".'tabDIYTable'.$dend_data[0]['tabID']." order by ID desc";

            $get_last_id = $this->request_post(
                "http://139.224.113.2/middle/index.php/middle/index_db",
                ['sql'=>$lasqlid,'db'=>$de_data['db_name']]
            );
            $get_last_id = json_decode($get_last_id, true);
            if (count($get_last_id)){
                $id = $get_last_id[0]['ID']+1;
            }else{
                $id = 1;
            }

            $arr = ['ID'=>$id,'iOrd'=>1,'RepID'=>$de_data['repid'],'bIsFinish'=>'1'];
            $arr_de = array_merge($arr, $arr_de);
            $insert_sql = $this->sqlserv_model->get_insert_sql('tabDIYTable'.$dend_data[0]['tabID'],$arr_de);
            $isql = str_replace('`', '"', $insert_sql);
            $insert_sql_ser = $this->request_post("http://139.224.113.2/middle/index.php/middle/update_data_db",['sql'=>$isql,'db'=>$de_data['db_name']]);
            echo $insert_sql_ser;die;
        }else{
            $where = '';
            foreach ($dend_data as $k=>$v){
                foreach ($de_data['data'] as $k1=>$v1){
                    if ($v['fieldName'] == $k1){
                        $arr_de['F'.$v['fieldID']] = $v1;
                    }
                    if ($v['fieldName'] == array_keys($de_data['key'])[0]){
                        $where = ['F'.$v['fieldID']=>array_values($de_data['key'])[0]];
                    }
                }
            }
            $insert_sql = $this->sqlserv_model->get_update_sql('tabDIYTable'.$dend_data[0]['tabID'],$arr_de,$where);
            $isql = str_replace('`', '"', $insert_sql);
            $insert_sql_ser = $this->request_post("http://139.224.113.2/middle/index.php/middle/update_data_db",['sql'=>$isql,'db'=>$de_data['db_name']]);
            echo $insert_sql_ser;die;
        }
    }


    //模拟post请求
    public function request_post($url = '', $post_data = array()) {
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