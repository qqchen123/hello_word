<?php
require_once '../Config/init.php';
$pay_code = isset($_POST["pay_code"])? $_POST["pay_code"] :"";//银行卡编码
$acc_no = isset($_POST["acc_no"])? trim($_POST["acc_no"]):"";//银行卡卡号
$id_card = isset($_POST["id_card"])? trim($_POST["id_card"]):"";//身份证号码
$id_holder = isset($_POST["id_holder"])? trim($_POST["id_holder"]):"";//姓名
$mobile = isset($_POST["mobile"])? trim($_POST["mobile"]):"";//银行预留手机号

if(!isset($_POST["txn_amt"])){
    Log::LogWirte("参数错误【txn_amt】不能为空！");
    throw new Exception("参数错误【txn_amt】不能为空！");
}
        
$txn_amt = isset($_POST["txn_amt"])? trim($_POST["txn_amt"]):"";//交易金额额
$txn_amt *=100;//金额以分为单位（把元转成分）

$data_content_parms = array();
$data_content_parms["biz_type"] = "0000";
$data_content_parms["txn_sub_type"] = "13";
$data_content_parms["member_id"] = $GLOBALS["member_id"];
$data_content_parms["terminal_id"] = $GLOBALS["terminal_id"];
$data_content_parms["pay_code"] =$pay_code ;
$data_content_parms["acc_no"] =$acc_no ;
$data_content_parms["id_card_type"] =$id_card_type ;
$data_content_parms["id_card"] =$id_card ;
$data_content_parms["id_holder"] =$id_holder ;
$data_content_parms["mobile"] =$mobile ;
$data_content_parms["valid_date"] =$valid_date ;
$data_content_parms["valid_no"] =$valid_no ;
$data_content_parms["trans_id"] ="Tid".Tools::getTransid().Tools::getRand4();//订单号
$data_content_parms["txn_amt"] =$txn_amt ;
$data_content_parms["trade_date"] =  Tools::getTime() ;//交易日期
$data_content_parms["trans_serial_no"] = "TSN".Tools::getTransid().Tools::getRand4();//商户流水号
$data_content_parms["additional_info"] ="附加字段" ;
$data_content_parms["req_reserved"] ="保留域" ;


if($GLOBALS["data_type"] == "json"){
	$Encrypted_string = str_replace("\\/", "/",json_encode($data_content_parms));//转JSON
}else{
	$toxml = new SdkXML();	//实例化XML转换类
	$Encrypted_string = $toxml->toXml($data_content_parms);//转XML
}
Log::LogWirte("序列化结果：".$Encrypted_string);
$BFRsa = new BFRSA($GLOBALS["pfxfilename"], $GLOBALS["cerfilename"], $GLOBALS["private_key_password"],TRUE); //实例化加密类。
$Encrypted = $BFRsa->encryptedByPrivateKey($Encrypted_string);	//先BASE64进行编码再RSA加密
$PostArry = Tools::getPostParm($data_content_parms["txn_sub_type"], $Encrypted);
$return = HttpClient::Post($PostArry, $request_url);  //发送请求到宝付服务器，并输出返回结果。
Log::LogWirte("请求返回参数：".$return);
if(empty($return)){
    throw new Exception("返回为空，确认是否网络原因！");
}

$return_decode = $BFRsa->decryptByPublicKey($return);//解密返回的报文
Log::LogWirte("解密结果：".$return_decode);
$endata_content = array();

if(!empty($return_decode)){//解析XML、JSON
    if($GLOBALS["data_type"] =="xml"){
        $endata_content = SdkXML::XTA($return_decode);
    }else{
        $endata_content = json_decode($return_decode,TRUE);
    }
    $Mstr=$endata_content;
    if(is_array($endata_content) && (count($endata_content)>0)){
        if(array_key_exists("resp_code", $endata_content)){
            $return_decode = "订单状态码：".$endata_content["resp_code"].",返回消息：".$endata_content["resp_msg"];
             if($endata_content["resp_code"] == "0000"){
                $return_decode .=", 成功金额：".$endata_content["succ_amt"]."（分）, 商户订单号：".$endata_content["trans_id"];
             }
            echo $return_decode;//输出
        }else{
            throw new Exception("[resp_code]返回码不存在!");
        }
    }
}  else {
    throw new Exception("请求出错，请检查网络!");
}