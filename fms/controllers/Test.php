<?
require('/www/svn-code/php_sdk-master/include.php');
use TencentYoutuyun\Youtu;
use TencentYoutuyun\Conf;
error_reporting(E_ALL & ~E_NOTICE);
class Test extends CI_Controller
{
	private $appid='10134247';
	private $secretId='AKIDthLqDSAd1FIAC64Rs8DB3o6FDa4ZjSho';
	private $secretKey='JTwJVWQ9h4FGAtWzGOMApIajiZFqKo8H';
	private $userid='45737921';
	
    public function __construct()
    {
    	Conf::setAppInfo($this->appid, $this->secretId, $this->secretKey, $this->userid,conf::API_YOUTU_END_POINT );
        parent::__construct();
    }


	/*
	session_id 相应请求的session标识符，可用于结果查询 
	name 证件姓名 
	sex 性别 
	nation 民族 
	birth 出生日期 
	address 地址 
	id 身份证号 
	frontimage OCR识别的身份证正面照片 
	valid_date 证件的有效期 
	authority 发证机关 
	backimage OCR识别的证件身份证反面照片 
	detail_errorcode 详细的错误原因 
	detail_errormsg 详细的错误原因说明 
	errorcode 返回状态值 
	errormsg 返回错误消息
	*/
	public function idcardocr()
	{
		
		$uploadRet = YouTu::idcardocr('/www/image/idcard.jpg', 0);//0-代表正面，1-代表反面
		
		//echo "<pre>";var_dump($uploadRet);echo "</pre>";
		return $uploadRet;
	}
	
    public function upload()
    {
        $config['upload_path'] = '/www/upload/';
        echo "<pre>";
        var_dump($_FILES);
        echo "</pre>";
        $filename_yyzz = "yyzz." . substr(strrchr($_FILES['yyzz']['name'], '.'), 1);
        if (!@copy($_FILES['yyzz']['tmp_name'], $config['upload_path'] . $filename_yyzz)) {
            $data['ret'] = false;
            $data['msg'] = iconv('gb2312', 'utf-8', '上传营业执照失败!');
            echo json_encode($data);
            exit;
        }
        $filename_idno = "idno." . substr(strrchr($_FILES['idno']['name'], '.'), 1);
        if (!@copy($_FILES['idno']['tmp_name'], $config['upload_path'] . $filename_idno)) {
            $data['ret'] = false;
            $data['msg'] = iconv('gb2312', 'utf-8', '上传身份证失败!');
            echo json_encode($data);
            exit;
        }

        $data['ret'] = true;
        $data['msg'] = iconv('gb2312', 'utf-8', '新增成功!');
        echo json_encode($data);
        exit;
    }

    public function file()
    {
        $args = array();
        $this->load->view('fms/file', $args);
    }

    public function id()
    {
        $this->load->model('qiye_model', 'qiye');
        $idnu = '452626199810137627';
        $coefficient = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $checkCode = array(1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2);
        $sum = 0;
        $area = substr($idnu, 0, 6);
        $year = substr($idnu, 6, 4);
        $mon = substr($idnu, 10, 2);
        $day = substr($idnu, 12, 2);
        $sex = substr($idnu, 16, 1);
        $valid = substr($idnu, 17, 1);
        $prov = $this->qiye->getprov($area);
        var_dump($prov);
        print_r([$area, $year, $mon, $day, $sex, $valid]);
        foreach (str_split(substr($idnu, 0, 17), 1) as $_key => $value) {
            echo $_key, '#', $coefficient[$_key], '<br/>';
            $sum += $value * $coefficient[$_key];
        }
        echo $sum, "<br/>";
        echo $sum % 11, "<br/>";
        echo $checkCode[$sum % 11], "<br/>";
        echo date('Y');
    }

    function compress()
    {
        $zip = new ZipArchive();
        $res = $zip->open('test.zip', ZipArchive::OVERWRITE | ZipArchive::CREATE);
        if ($res) {
            $this->compressDir('../upload/', $zip);
            $zip->close();
        } else {
            show_error('err', 'errmsg');
        }

        header('Content-Type:text/html;charset=utf-8');
        header('Content-disposition:attachment;filename=test.zip');
        $filesize = filesize('./test.zip');
        readfile('./test.zip');
        header('Content-length:' . $filesize);

        //unlink('./test.zip');
    }

    function compressDir($dir, $zip, $prev = '.')
    {
        $handler = opendir($dir);
        $basename = basename($dir);
        //$zip->addEmptyDir($prev . '/' . $basename);
        while ($file = readdir($handler)) {
            $realpath = $dir . '/' . $file;
            if (is_dir($realpath)) {
                if ($file !== '.' && $file !== '..') {
                    $zip->addEmptyDir($prev . '/' . $basename . '/' . $file);
                    $this->compressDir($realpath, $zip, $prev . '/' . $basename);
                }
            } else {
                $zip->addFile($realpath, $prev . '/' . $basename . '/' . $file);
            }
        }

        closedir($handler);
        return null;
    }

    public function checkstats()
    {
        $this->load->helper('publicstatus');
        print_r($this->statusArr);
    }

    /**
     * @url test/testtpl
     */
    public function testtpl()
    {
        $this->load->view('fms/test',[],false);
    }

    // public function testshixin()
    // {
    //     $this->load->library('')
    // }


    public function ocrtest()
    {
        $id = $this->uri->segment(3);
        $this->load->service('public/Youtu_service', 'youtu_service');
        return $this->youtu_service->youtu_test($id);
    }

}

?>