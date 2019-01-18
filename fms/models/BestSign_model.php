<?php

class BestSign_model extends CI_Model{
    public function __construct(){
        //parent::__construct();
        $this->load->service('bs/BestSign_service','bestsign');
    }
    /**
     * account 用户帐号
     * name 必须和证件上登记的姓名一致
     * userType  用户类型  1表示个人
     * identity 身份证号
     * taskId  是否为注册用户 true已注册 false未注册 如果为true,signname不用传
     * description 合同内容描述
     * title 合同标题
     * path 保存本地的地址  d:\xx\xxx.pdf
     * sjcxsqs数据查询授权书     grxxxs个人信息查询及使用授权书   二选一
     */
    
    public function bsAction($map){
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        $contractType = array(
            //数据查询授权书
            'sjcxsqs' =>array(
                'filedata' =>array(
                    'name'=>array('x'=>'0.25','y'=>'0.165','pageNum'=>'1','value' =>''),
                    'idno'=>array('x'=>'0.57','y'=>'0.165','pageNum'=>'1','value' =>''),
                    'signname'=>array('x'=>'0.7','y'=>'0.38','pageNum'=>'2','value' =>''),
                    'date'=>array('x'=>'0.73','y'=>'0.427','pageNum'=>'2','value' =>date('Y-m-d H:i:s',time())),
                ),
                'fileinfo' => array(
                    'path' => '/www/fms/upload/BestSign/sjcxsqs.pdf',
                    'ftype' => 'pdf',
                    'fname' => 'sjcxsqs.pdf',
                    'fpages' => '2',
                )
            ),
            //个人信息查询及使用授权书
            'grxxxs' => array(
                'filedata' =>array(
                    'name'=>array('x'=>'0.25','y'=>'0.212','pageNum'=>'1','value' =>''),
                    'idno'=>array('x'=>'0.55','y'=>'0.212','pageNum'=>'1','value' =>''),
                    'conname'=>array('x'=>'0.26','y'=>'0.23','pageNum'=>'1','value' =>''),
                    'appname1'=>array('x'=>'0.434','y'=>'0.23','pageNum'=>'1','value' =>''),
                    'appname2'=>array('x'=>'0.295','y'=>'0.34','pageNum'=>'1','value' =>''),
                    'appname3'=>array('x'=>'0.295','y'=>'0.433','pageNum'=>'1','value' =>''),
                    'appname4'=>array('x'=>'0.35','y'=>'0.785','pageNum'=>'1','value' =>''),
                    'appname5'=>array('x'=>'0.71','y'=>'0.822','pageNum'=>'1','value' =>''),
                    'signname'=>array('x'=>'0.22','y'=>'0.87','pageNum'=>'1','value' =>''),
                    'date'=>array('x'=>'0.615','y'=>'0.88','pageNum'=>'1','value' =>date('Y          m        d     H:i:s',time())),
                ),
                'fileinfo' => array(
                    'path' => '/www/fms/upload/BestSign/grxxxs.pdf',
                    'ftype' => 'pdf',
                    'fname' => 'grxxxs.pdf',
                    'fpages' => '1',
                )
            )
        );
        $reTurnData['code'] = '1';
        $reTurnData['data'] = '';
        if($map['taskId'] === false){
            //1.注册用户
            $regUser['account'] = $map['account'];
            $regUser['name'] = $map['name'];
            $regUser['userType'] = $map['userType'];
            $regUser['applyCert'] = '1';
            $regUser['credential']['identity'] = $map['identity'];
            $regUserData = $this->regUser($regUser);
            if($regUserData['errno'] != '0'){
                log_message('error', json_encode($regUserData, JSON_UNESCAPED_UNICODE));
                $reTurnData['msg'] = '注册失败';
                return $reTurnData;exit;
            }
            
            //2.生成用户签名/印章图片
            $createUserSignatureImage['account'] = $map['account'];
            if(isset($map['grxxxs'])){
                $cttype = 'grxxxs';
                $createUserSignatureImage['text'] = $map['grxxxs']['signname'];
            }
            if(isset($map['sjcxsqs'])){
                $cttype = 'sjcxsqs';
                $createUserSignatureImage['text'] = $map['sjcxsqs']['signname'];
            }
            $recreateUserSignatureImageData = $this->createUserSignatureImage($createUserSignatureImage);
            if($recreateUserSignatureImageData['errno'] != '0'){
                $reTurnData['msg'] = '生成用户签名/印章图片';
                return $reTurnData;exit;
            }
            //3.异步申请状态查询
            $asyncUserStauts['account'] = $map['account'];     //用户账号
            $asyncUserStauts['taskId'] = $regUserData['data']['taskId'];
            $asyncUserStautsData = $this->asyncUserStauts($asyncUserStauts);
            if($asyncUserStautsData['errno'] != '0' ){
                $reTurnData['msg'] = '异步申请状态查询失败';
                return $reTurnData;exit;
            }
            if($asyncUserStautsData['data']['status'] != '5'){
                $reTurnData['msg'] = '任务单号taskId有问题';
                return $reTurnData;exit;
            }else{
                $reData['taskId'] = $regUserData['data']['taskId'];
            }
        }
        
        //4。uploadStorage上传合同文件
        if($map['taskId'] === true){
            if(isset($map['grxxxs'])){
                $cttype = 'grxxxs';
            }
            if(isset($map['sjcxsqs'])){
                $cttype = 'sjcxsqs';
            }
        }
        $filedata = file_get_contents($contractType[$cttype]['fileinfo']['path'], "r");
        $uploadStorage['account'] = $map['account'];
        $uploadStorage['fmd5'] = md5($filedata);          //文件MD5值
        $uploadStorage['ftype'] = $contractType[$cttype]['fileinfo']['ftype'] ;        //文件类型
        $uploadStorage['fname'] = $contractType[$cttype]['fileinfo']['fname'] ;         //原始文件名
        $uploadStorage['fpages'] = $contractType[$cttype]['fileinfo']['fpages'] ;       //总页数
        $uploadStorage['fdata'] = base64_encode($filedata);        //文件内容
        $uploadStorage['isCleanup'] = '0';
        $uploadStorageData = $this->uploadStorage($uploadStorage);
        if($uploadStorageData['errno'] != '0'){
            $reTurnData['msg'] = '上传合同文件失败';
            return $reTurnData;exit;
        }
        //5.为PDF文件添加元素
        $addPdfElementsStorage['account'] = $map['account'];
        $addPdfElementsStorage['fid'] = $uploadStorageData['data']['fid'];
        $addPdfElementsStorage['account'] = $map['account'];
        $addPdfElementsStorage['account'] = $map['account'];
        $elements =  array();
        foreach ($contractType[$cttype]['filedata'] as $key => $value){
            $elementss =  array();
            if($key != 'signname'){
                foreach ($value as $k => $v){
                    if($k == 'value'){
                        
                        if($key == 'date'){
                            $elementss[$k] = $v;
                        }else{
                            $elementss[$k] = $map[$cttype][$key];
                        }
                    }else{
                        
                        $elementss[$k] = $v;
                        
                    }
                    $elementss['type'] = 'text';
                }
                $elements[] = $elementss;
            }
            
        }
        $addPdfElementsStorage['elements'] = $elements;
        $addPdfElementsStorageData = $this->addPdfElementsStorage($addPdfElementsStorage);
        log_message('error', json_encode($addPdfElementsStorageData, JSON_UNESCAPED_UNICODE));
        if($addPdfElementsStorageData['errno'] != '0'){
            
            $reTurnData['msg'] = '添加元素失败';
            return $reTurnData;exit;
        }
        //6 .4.11 创建合同createContract
        $createContract['account'] = $map['account'];
        $createContract['fid'] = $addPdfElementsStorageData['data']['fid'];
        $createContract['expireTime'] = (string)(time() + 10800);
        $createContract['title'] = $map['title'];
        $createContract['description'] = $map['description'];
        $createContractData = $this->createContract($createContract);
        log_message('error', json_encode($createContractData, JSON_UNESCAPED_UNICODE));
        if($createContractData['errno'] != '0'){
            $reTurnData['msg'] = '创建合同文件失败';
            return $reTurnData;exit;
        }
        //7。certContractSign签署合同
        $certContractSign['signerAccount'] = $map['account'];
        $certContractSign['contractId'] = $createContractData['data']['contractId'];
        
        $signaturePositions = array();
        foreach ($contractType[$cttype]['filedata']['signname'] as $key => $value){
            if($key != 'value'){
                $signaturePositions[$key] = $value;
            }
        }
        $certContractSign['signaturePositions'][] = $signaturePositions;//var_dump($certContractSign);exit;
        $certContractSignData = $this->certContractSign($certContractSign);
        log_message('error', json_encode($certContractSignData, JSON_UNESCAPED_UNICODE));
        if($certContractSignData['errno'] != '0'){
            $reTurnData['msg'] = '签署合同失败';
            return $reTurnData;exit;
        }
        //8。下载合同downloadStorageContract
        $downloadStorageContract['contractId']  = $createContractData['data']['contractId'];
        $path = $map['path'];
        $downloadStorageContractData = $this->downloadStorageContract($downloadStorageContract,$path);
        //9.锁定并结束合同lockStorageContract
        $lockStorageContract['contractId'] = $createContractData['data']['contractId'];
        $lockStorageContractData = $this->lockStorageContract($lockStorageContract);
        
        $reTurnData['code'] = '0';
        $reTurnData['data'] = $createContractData['data']['contractId'];
        return $reTurnData;
    }
    
    /**
     * 手动签第一步 $map
     * @return string
     */
    public function sdbsAction($map){
        
        /* $map['account'] = 'bannianshan@26.com';
         $map['name'] = '班大能';
         $map['userType'] = '1';
         $map['taskId'] = true;
         $map['identity'] = '412326198711254870';
         $map['description'] = '天上人间走一回,不回不切是上家';
         $map['title'] = '天上人间个人信息查询';
         $map['path'] = 'D:\work\baaa.pdf';
         //--------------------------------------------
         $map['grxxxs']['name'] = '班念山';
         $map['grxxxs']['idno'] = '412326198711254870';
         $map['grxxxs']['conname'] = '源都金服';
         $map['grxxxs']['appname1'] = '源都金服';
         $map['grxxxs']['appname2'] = '源都金服';
         $map['grxxxs']['appname3'] = '源都金服';
         $map['grxxxs']['appname4'] = '源都金服';
         $map['grxxxs']['appname5'] = '源都金服'; */
        // $map['grxxxs']['signname'] = 'bandaneng';
        //-------------------------------------
        /* $map['sjcxsqs']['name'] = '班念山';
         $map['sjcxsqs']['idno'] = '412326198711254870';
         $map['sjcxsqs']['signname'] = '班@大@能'; */
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        $contractType = array(
            //数据查询授权书
            'sjcxsqs' =>array(
                'filedata' =>array(
                    'name'=>array('x'=>'0.25','y'=>'0.165','pageNum'=>'1','value' =>''),
                    'idno'=>array('x'=>'0.57','y'=>'0.165','pageNum'=>'1','value' =>''),
                    'signname'=>array('x'=>'0.7','y'=>'0.38','pageNum'=>'2','value' =>''),
                    'date'=>array('x'=>'0.73','y'=>'0.427','pageNum'=>'2','value' =>date('Y-m-d H:i:s',time())),
                ),
                'fileinfo' => array(
                    'path' => 'f:/ban/sjcxsqs.pdf',
                    'ftype' => 'pdf',
                    'fname' => 'sjcxsqs.pdf',
                    'fpages' => '2',
                )
            ),
            //个人信息查询及使用授权书
            'grxxxs' => array(
                'filedata' =>array(
                    'name'=>array('x'=>'0.25','y'=>'0.212','pageNum'=>'1','value' =>''),
                    'idno'=>array('x'=>'0.55','y'=>'0.212','pageNum'=>'1','value' =>''),
                    'conname'=>array('x'=>'0.26','y'=>'0.23','pageNum'=>'1','value' =>''),
                    'appname1'=>array('x'=>'0.434','y'=>'0.23','pageNum'=>'1','value' =>''),
                    'appname2'=>array('x'=>'0.295','y'=>'0.34','pageNum'=>'1','value' =>''),
                    'appname3'=>array('x'=>'0.295','y'=>'0.433','pageNum'=>'1','value' =>''),
                    'appname4'=>array('x'=>'0.35','y'=>'0.785','pageNum'=>'1','value' =>''),
                    'appname5'=>array('x'=>'0.71','y'=>'0.822','pageNum'=>'1','value' =>''),
                    'signname'=>array('x'=>'0.22','y'=>'0.87','pageNum'=>'1','value' =>''),
                    'date'=>array('x'=>'0.615','y'=>'0.88','pageNum'=>'1','value' =>date('Y          m        d     H:i:s',time())),
                ),
                'fileinfo' => array(
                    'path' => 'f:/ban/grxxxs.pdf',
                    'ftype' => 'pdf',
                    'fname' => 'grxxxs.pdf',
                    'fpages' => '1',
                )
            )
        );
        
        
        $reTurnData['code'] = '1';
        $reTurnData['data'] = '';
        if($map['taskId'] === false){
            //1.注册用户
            $regUser['account'] = $map['account'];
            $regUser['name'] = $map['name'];
            $regUser['userType'] = $map['userType'];
            $regUser['applyCert'] = '1';
            $regUser['credential']['identity'] = $map['identity'];
            $regUserData = $this->regUser($regUser);
            if($regUserData['errno'] != '0'){
                log_message('error', json_encode($regUserData, JSON_UNESCAPED_UNICODE));
                $reTurnData['msg'] = '注册失败';
                return $reTurnData;exit;
            }
            
            //2.生成用户签名/印章图片
            $createUserSignatureImage['account'] = $map['account'];
            if(isset($map['grxxxs'])){
                $cttype = 'grxxxs';
                $createUserSignatureImage['text'] = $map['grxxxs']['signname'];
            }
            if(isset($map['sjcxsqs'])){
                $cttype = 'sjcxsqs';
                $createUserSignatureImage['text'] = $map['sjcxsqs']['signname'];
            }
            $recreateUserSignatureImageData = $this->createUserSignatureImage($createUserSignatureImage);
            if($recreateUserSignatureImageData['errno'] != '0'){
                
                $reTurnData['msg'] = '生成用户签名/印章图片';
                return $reTurnData;exit;
            }
            //3.异步申请状态查询
            $asyncUserStauts['account'] = $map['account'];     //用户账号
            $asyncUserStauts['taskId'] = $regUserData['data']['taskId'];
            $asyncUserStautsData = $this->asyncUserStauts($asyncUserStauts);
            if($asyncUserStautsData['errno'] != '0' ){
                
                $reTurnData['msg'] = '异步申请状态查询失败';
                return $reTurnData;exit;
            }
            if($asyncUserStautsData['data']['status'] != '5'){
                
                $reTurnData['msg'] = '任务单号taskId有问题';
                return $reTurnData;exit;
            }else{
                $reData['taskId'] = $regUserData['data']['taskId'];
            }
        }
        
        //4。uploadStorage上传合同文件
        if($map['taskId'] === true){
            if(isset($map['grxxxs'])){
                $cttype = 'grxxxs';
            }
            if(isset($map['sjcxsqs'])){
                $cttype = 'sjcxsqs';
            }
        }
        $filedata = file_get_contents($contractType[$cttype]['fileinfo']['path'], "r");
        $uploadStorage['account'] = $map['account'];
        $uploadStorage['fmd5'] = md5($filedata);          //文件MD5值
        $uploadStorage['ftype'] = $contractType[$cttype]['fileinfo']['ftype'] ;        //文件类型
        $uploadStorage['fname'] = $contractType[$cttype]['fileinfo']['fname'] ;         //原始文件名
        $uploadStorage['fpages'] = $contractType[$cttype]['fileinfo']['fpages'] ;       //总页数
        $uploadStorage['fdata'] = base64_encode($filedata);        //文件内容
        $uploadStorage['isCleanup'] = '0';
        $uploadStorageData = $this->uploadStorage($uploadStorage);
        if($uploadStorageData['errno'] != '0'){
            
            $reTurnData['msg'] = '上传合同文件失败';
            return $reTurnData;exit;
        }
        //5.为PDF文件添加元素
        $addPdfElementsStorage['account'] = $map['account'];
        $addPdfElementsStorage['fid'] = $uploadStorageData['data']['fid'];
        $addPdfElementsStorage['account'] = $map['account'];
        $addPdfElementsStorage['account'] = $map['account'];
        $elements =  array();
        foreach ($contractType[$cttype]['filedata'] as $key => $value){
            $elementss =  array();
            if($key != 'signname'){
                foreach ($value as $k => $v){
                    if($k == 'value'){
                        
                        if($key == 'date'){
                            $elementss[$k] = $v;
                        }else{
                            $elementss[$k] = $map[$cttype][$key];
                        }
                    }else{
                        
                        $elementss[$k] = $v;
                        
                    }
                    $elementss['type'] = 'text';
                }
                $elements[] = $elementss;
            }
            
        }
        $addPdfElementsStorage['elements'] = $elements;
        $addPdfElementsStorageData = $this->addPdfElementsStorage($addPdfElementsStorage);
        log_message('error', json_encode($addPdfElementsStorageData, JSON_UNESCAPED_UNICODE));
        if($addPdfElementsStorageData['errno'] != '0'){
            
            $reTurnData['msg'] = '添加元素失败';
            return $reTurnData;exit;
        }
        //6 .4.11 创建合同createContract
        $createContract['account'] = $map['account'];
        $createContract['fid'] = $addPdfElementsStorageData['data']['fid'];
        $createContract['expireTime'] = (string)(time() + 10800);
        $createContract['title'] = $map['title'];
        $createContract['description'] = $map['description'];
        $createContractData = $this->createContract($createContract);
        log_message('error', json_encode($createContractData, JSON_UNESCAPED_UNICODE));
        if($createContractData['errno'] != '0'){
            
            $reTurnData['msg'] = '创建合同文件失败';
            return $reTurnData;exit;
        }
        //手动签
        //发送合同sendContract
        $sendContract['contractId'] = $createContractData['data']['contractId'];
        $sendContract['signer'] = $map['account'];
        $sendContract['expireTime'] = (string)(time() + 3600);
        $sendContract['signaturePositions'][0]['pageNum'] = '1';
        $sendContract['signaturePositions'][0]['x'] = '0.22';
        $sendContract['signaturePositions'][0]['y'] = '0.87';//var_dump($sendContract);exit;
        $sendContractData = $this->sendContract($sendContract);
        if($sendContractData['errno'] != '0'){
            
            $reTurnData['msg'] = '调用手动签合同失败';
            return $reTurnData;exit;
        }
        
        $sendContractData['data']['contractId'] = $createContractData['data']['contractId'];
        return $sendContractData;exit;
    }
    /**
     * 手动签第二步
     * @return string
     */
    public function asycbsAction($map){
        //$map['path'] = 'D:\work\baaa.pdf';
        // $map['contractId'] = '153484408701000001';
        //8。下载合同downloadStorageContract
        $downloadStorageContract['contractId']  = $map['contractId'];
        $path = $map['path'];
        $downloadStorageContractData = $this->downloadStorageContract($downloadStorageContract,$path);
        //9.锁定并结束合同lockStorageContract
        $lockStorageContract['contractId'] = $map['contractId'];
        $lockStorageContractData = $this->lockStorageContract($lockStorageContract);
        
        $reTurnData['code'] = '0';
        $reTurnData['data'] = $map['contractId'];
        return $reTurnData;
    }
    
    /**
     * 用户注册
     */
    
    public function regUser($map){
        $path = "/user/reg/";
        //$map['mail'] = time() . rand(1000, 9999)."@test.com";
        /*  $map['mail'] = 'bandaneng@126.com';
         $map['account'] = $map['mail'];
         $map['mobile'] = "15618988192";
         $map['name'] = "班大能";
         $map['userType'] = "1";
         $map['applyCert'] = '1'; */
        
        //$map['credential']['identity'] = '412326198711254870';              //用户证件号        必须和证件上登记的号码一致
        /*$map['credential']['identityType'] ='0';         //用户证件类型  默认为“0”, 0-居民身份证  1-护照  B-港澳居民往来内地通行证  C-台湾居民来往大陆通行证   E-户口簿  F-临时居民身份证
         $map['credential']['contactMobile'] ='15618988192';        //联系手机
         $map['credential']['contactMail'] ='bandaneng@126.com';         //联系邮箱
         $map['credential']['province'] ='上海';       //省份
         $map['credential']['city'] ='上海';           //城市
         $map['credential']['address'] ='天上人间';    //地址  */
        
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        //var_dump($response);exit;
        return $response;
    }
    /**
     * 查询证书编号
     */
    public function getCert(){
        $path = "/user/getCert/";
        $map['account'] = 'bannianshan@126.com';       //用户帐号
        $response = $this->bestsign->publicAction($map,$path);
        var_dump($response);
    }
    /**
     * 查询个人用户证件信息
     */
    public function getPersonalCredential(){
        $path = "/user/getPersonalCredential/";
        $map['account'] = 'bannianshan@126.com';       //用户帐号
        $response = $this->bestsign->publicAction($map,$path);
        var_dump($response);
    }
    /**
     * 异步申请状态查询
     */
    public function asyncUserStauts($map){
        $path = "/user/async/applyCert/status/";
        // $map['account'] = "bandaneng@126.com";       //用户账号
        //$map['taskId'] = '153433679301000001';        //任务单号  异步申请证书返回的taskId
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
        
    }
    /**
     * 生成用户签名/印章图片
     */
    public function createUserSignatureImage($map){
        $path = "/signatureImage/user/create/";
        /*  $map['account'] = "bannianshan@126.com";
         $map['text'] = "班大能";
         $map['fontName'] = 'SimSun';        //字体名称（仅针对个人类型账号有效）     目前枚举值如下： SimHei 黑体 SimSun 宋体 SimKai 楷体
         $map['fontSize'] = '35';        //字号（仅针对个人类型账号有效）      12~120，默认30，此参数影响签名字体的清晰度和签名图片大小，字号越高，字体显示越大，清晰度越高。 注：过小的字号在手动签的预览页面上显示会与实际大小有差别，但签署之后的PDF上的大小正常。
         $map['fontColor'] = 'blue';     */       //字体颜色（仅针对个人类型账号有效）    指定字体的颜色，支持： red（红），black（黑），blue（蓝），purple（紫），grey（灰），brown（棕），tan(褐色)，cyan(青色)
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
    /**
     * 下载用户签名/印章图片
     */
    public function downloadUserSignatureImage(){
        $path = "/signatureImage/user/download/";
        $map['account'] = "bannianshan@126.com";
        // $map['text'] = "bannianshan";
        $response = $this->bestsign->downloadSignatureImage($map,$path);
        
        //response即签名图片二进制文件流，请按照自己的业务需求处理，以下代码仅示例写到文件中，请更换自己的文件路径
        $out_file_path = "D:/work/download.png";
        $out_file = fopen($out_file_path, "w") or die("Unable to open file!");
        fwrite($out_file, $response);
        
        var_dump($response);
    }
    /**
     * 上传并创建合同
     */
    public function uploadStorageContract(){
        $path = "/storage/contract/upload/";
        
        $filedata = file_get_contents("f:/ban/bantest.pdf", "r");
        
        //$filedata = fread($myfile,filesize("f:/ban/bantest.pdf"));
        //fclose($myfile);
        // echo ($filedata);exit;
        
        $map['expireTime'] = (string)(time() + 10800);        //有效期                   合同有效期，单位：秒；计算方法：当前系统时间的时间戳秒数+有效期秒数
        $map['description'] = '班念山的个人信用查询';       //合同内容描述
        $map['title']  = '个人征信查询';            //合同标题
        $map['fdata'] = base64_encode($filedata);
        $map['fpages'] = '1';            //文件总页数     pdf文件总页数
        $map['fname'] = 'bantest.pdf';             //文件名称  文件名必须带上后缀名，例如“XXXX.pdf”
        $map['ftype'] = 'pdf';             ///文件类型     文件类型，目前仅支持pdf
        $map['account'] = 'bannianshan@126.com';           //用户账号      必须要指定一个用户帐号作为操作者
        $map['fmd5'] = md5($filedata);              //文件MD5值        合同文件MD5值，例如： FileInputStream file = new FileInputStream("d: \\test\\接口系统.pdf"); byte[] bdata = IOUtils.toByteArray(file); String fmd5 = DigestUtils.md5Hex(bdata);
        $map['hotStoragePeriod'] = '10800';  //热存周期      	此参数是合同文件在热存储中保留的时间长度，单位为秒。保存在热存储中的合同数据，自合同结束时间算起，超过此此参数设定时长的合同文件，会转移到冷存储中。计算示例：如保存365天，则值为3600*24*365=31536000。参数可为空，为空时默认值为1年（31536000）。取值范围为3600（1小时）~157680000（5年）
        
        // var_dump($map);exit;
        
        $response = $this->bestsign->publicAction($map,$path);
        
        var_dump($response);
    }
    /**
     * 签署合同
     */
    public function certContractSign($map){
        $path = "/contract/sign/cert/";
        // $map['contractId'] = "153431944701000005";                //合同ID
        // $map['signerAccount'] = "bannianshan@126.com";             //签署者账号
        // $map['signaturePositions'][0]['pageNum'] = '1';     //签名页码
        // $map['signaturePositions'][0]['x'] = '0.2';            //签名x坐标
        // $map['signaturePositions'][0]['y'] = '0.8';                   //签名y坐标
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
    /**
     * 下载合同文件
     */
    public function downloadStorageContract($map,$out_file_path){
        $path = "/storage/contract/download/";
        //$map['contractId'] = '153432953601000001';     //合同ID     合同编号
        $response = $this->bestsign->downloadContract($map,$path);
        //$out_file_path = "D:/work/download112.pdf";
        $out_file = fopen($out_file_path, "w") or die("Unable to open file!");
        fwrite($out_file, $response);
    }
    /**
     * 锁定并结束合同
     */
    
    public function lockStorageContract($map){
        $path = "/storage/contract/lock/";
        //$map['contractId'] = '150270594701000001';
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
    /**
     * 上传合同文件
     */
    public function uploadStorage($map){
        $path = '/storage/upload/';
        //$filedata = file_get_contents("f:/ban/bantest.pdf", "r");
        //$map['account'] = 'bannianshan@126.com';
        //$map['fmd5'] = md5($filedata);          //文件MD5值
        //$map['ftype'] = 'pdf';         //文件类型
        //$map['fname'] = 'bantest.pdf';         //原始文件名
        // $map['fpages'] = '1';        //总页数
        // $map['fdata'] = base64_encode($filedata);        //文件内容
        //$map['isCleanup'] = '0';    //是否强制清理pdf 某些pdf中存在一些特殊元素，会导致签名无效。此参数可强制清理pdf中的特殊元素，保证签名有效。 枚举值： 0-不强制清理 1-强制清理
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
    /**
     * 为PDF文件添加元素
     */
    public function addPdfElementsStorage($map){
        $path =  "/storage/addPdfElements/";
        /* $map['fid'] = '2064017840575028459';                        //文件ID   通过文件上传接口上传的pdf文件id
         $map['elements'][0]['pageNum'] = '1';        //页码     新添加元素所在的页码
         $map['elements'][0]['x'] = '0.2';               //x坐标       	新添加元素x坐标，用百分比表示，取值0.0~1.0。
         $map['elements'][0]['y'] = '0.2';               //y坐标       	新添加元素y坐标，用百分比表示，取值0.0~1.0。
         $map['elements'][0]['type'] = 'text';            //元素类型          新添加元素的类型。目前支持：text，image两种，默认为text。
         $map['elements'][0]['value'] = '班大能';           //新添加元素的内容。如果是text类型，为文本；如果是image类型，为base64编码后的图片内容。
         $map['elements'][0]['fontSize'] = '14';        //如果新添加元素是text类型，可以用来指定新添加元素的字体大小。默认14
         $map['elements'][1]['pageNum'] = '1';        //页码     新添加元素所在的页码
         $map['elements'][1]['x'] = '0.6';               //x坐标       	新添加元素x坐标，用百分比表示，取值0.0~1.0。
         $map['elements'][1]['y'] = '0.6';               //y坐标       	新添加元素y坐标，用百分比表示，取值0.0~1.0。
         $map['elements'][1]['type'] = 'text';            //元素类型          新添加元素的类型。目前支持：text，image两种，默认为text。
         $map['elements'][1]['value'] = '412326198711254870';           //新添加元素的内容。如果是text类型，为文本；如果是image类型，为base64编码后的图片内容。
         $map['elements'][1]['fontSize'] = '14';        //如果新添加元素是text类型，可以用来指定新添加元素的字体大小。默认14
         $map['account'] = 'bannianshan@126.com';        */                 //用户帐号      必须要指定一个用户帐号作为操作者
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
    /**
     * 4.11创建合同
     */
    public function createContract($map){
        $path = "/contract/create/";
        //$map['account'] = 'bannianshan@126.com';
        // $map['fid'] = '8504489048692925806';               //文件ID
        // $map['expireTime'] = (string)(time() + 10800);        //有效时间
        // $map['title'] = '班念山的个人征信查询';             //合同标题
        // $map['description'] = '班念山的个人征信查询123456';          //合同内容描述
        //$map['hotStoragePeriod'] = '10800';      //热存周期
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
    /**
     * 4.3发送合同(/contract/send/)
     */
    public function sendContract($map){
        $path = "/contract/send/";
        //$map['account'] = 'bannianshan@126.com';
        // $map['fid'] = '8504489048692925806';               //文件ID
        // $map['expireTime'] = (string)(time() + 10800);        //有效时间
        // $map['title'] = '班念山的个人征信查询';             //合同标题
        // $map['description'] = '班念山的个人征信查询123456';          //合同内容描述
        //$map['hotStoragePeriod'] = '10800';      //热存周期
        $response = $this->bestsign->publicAction($map,$path);
        $response = json_decode($response,true);
        return $response;
    }
}