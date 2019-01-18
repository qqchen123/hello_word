<?php

/**
 * 
 */
class YxContract extends Admin_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @url /reptiles/YxContract/test
     */
    public function test()
    {
        $url = 'https://yx-oss-public.oss-cn-qingdao.aliyuncs.com/upload/20181109/43c97dc255704cd9923a9ea7dd61616c.pdf';
        $save_dir = '/www/upload/yxcontract';
        $filename = 'testcontract.pdf';
        $type = 1;
        $ret = $this->getFile($url, $save_dir, $filename, $type);
        var_dump($ret);
        exit;
    }

    /**
     * @url /reptiles/YxContract/view
     */
    public function view()
    {
        header("Content-type: application/pdf");
        readfile("/www/upload/yxcontract/testcontract.pdf");
    }

    /**
     * @url /reptiles/YxContract/viewword
     */
    public function viewword()
    {
        
        $filepath = APPPATH . 'libraries/'.'Smalot/PdfParser/Parser'.'.php';
        if (file_exists($filepath)) {
            include_once($filepath);
        }
        $obj = new Smalot\PdfParser\Parser();
        try {
            $document = $obj->parseFile('/www/upload/yxcontract/testcontract.pdf');
        } catch(\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }   
        
        echo '开始读取';
        $pages = $document->getPages();
        // // 逐页提取文本
        $text = '';
        foreach ($pages as $page) {
            $text .= $page->getText();
        }
        echo $text;
        echo '结束';
        exit;
    }

    /* 
    *功能：php完美实现下载远程图片保存到本地 
    *参数：文件url,保存文件目录,保存文件名称，使用的下载方式 
    *当保存文件名称为空时则使用远程文件原来的名称 
    */ 
    function getFile($url,$save_dir='',$filename='',$type=0){ 
        if(trim($url)==''){ 
            return array('code'=>1,'error'=>'文件url不合法'); 
        } 
        if(trim($save_dir)==''){ 
            $save_dir='./'; 
        } 
        if(trim($filename)==''){//保存文件名 
            $ext=strrchr($url,'.'); 
            if($ext!='.pdf'){ 
                return array('code'=>3,'error'=>'文件类型不合法'); 
            } 
            $filename=substr($url,strripos($url,"/")+1); 
        } 
        if(0!==strrpos($save_dir,'/')){ 
            $save_dir.='/'; 
        } 
        //创建保存目录 
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){ 
            return array('code'=>5,'error'=>'创建文件失败'); 
        } 
        //获取远程文件所采用的方法  
        if($type){ 
            $ch=curl_init(); 
            $timeout=5; 
            curl_setopt($ch,CURLOPT_URL,$url); 
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
            $file=curl_exec($ch); 
            curl_close($ch); 
        }else{ 
            ob_start();  
            readfile($url); 
            $file=ob_get_contents();  
            ob_end_clean();  
        } 
        //$size=strlen($img); 
        //文件大小  
        $fp2=@fopen($save_dir.$filename,'a'); 
        if (!$fp2) {
            var_dump($fp2);exit;
        }
        fwrite($fp2,$file); 
        fclose($fp2); 
        unset($img,$url); 
        return array('code'=>0,'error'=>"文件".$filename."保存成功"); 
    }
}


?>

