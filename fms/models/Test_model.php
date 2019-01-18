<?php

class Test_model extends CI_Model
{
    
	function write_doc()
	{
		//这个是生成word的例子：
		include_once('/www/PHPWord-develop/vendor/autoload.php');
		
		
		$PHPWord = new \PhpOffice\PhpWord\PhpWord(); 
		$section = $PHPWord->createSection(); 
        $PHPWord->addFontStyle('rStyle', array('bold'=>false, 'italic'=>false,'size'=>16)); 
        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center','spaceAfter'=>100)); 
        $c = "前三日雨量报表"; 
        $section->addText($c, 'rStyle', 'pStyle');  
        $styleTable = array('borderSize'=>6, 'borderColor'=>'006699','cellMargin'=>80); 
        $styleFirstRow = array('borderBottomSize'=>18,'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF'); 
        // Define cell style arrays 
        $styleCell = array('valign'=>'center'); 
        // Define font style for first row 
        $fontStyle = array('bold'=>true, 'align'=>'center'); 
        //设置标题 
        $PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true,'size'=>16)); 
        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100)); 
        // Add table style 
        $PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);  
        // Add table 
        $table = $section->addTable('myOwnTableStyle');  
        // Add row设置行高 
        $table->addRow(500); 
        $table->addCell(2300, $styleCell)->addText('站码', $fontStyle); 
        $table->addCell(2300, $styleCell)->addText('站名', $fontStyle); 
        $table->addCell(2300, $styleCell)->addText('雨量', $fontStyle); 
        $table->addCell(2300, $styleCell)->addText('水文站监测类型', $fontStyle); 
        
        $table->addRow(); 
        $table->addCell(2300)->addText('AAA'); 
        $table->addCell(2300)->addText('测试'); 
        $table->addCell(2300)->addText('111'); 
        $table->addCell(2300)->addText('气象站'); 
        
        $section->addTextBreak(2); 
        
        $section = $PHPWord->createSection(); 
        $PHPWord->addFontStyle('rStyle', array('bold'=>false, 'italic'=>false, 'size'=>16)); 
        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100)); 
        $c = "地质灾害"; 
        $section->addText($c, 'rStyle', 'pStyle');  
        $content="根据市气象局未来24小时降雨预报和市水利局实时降雨数据，市国土资源局进行了地质灾害预报，请有关部门关注实时预警信息，做好地质灾害防范工作"; 
        $section->addText($content); 
        // Add image elements 
       // $section->addImage("images/image001.jpg", array('width'=>600,'height'=>480, 'align'=>'center')); 
       $fileName = "word".date("YmdHis"); 
       $PHPWord->save('/www/upload/'.$fileName.'.docx','Word2007');
        
		exit;
	}
	
	function read_xls()
	{
		//这个是读取excel的例子：
		$config['upload_path']      = '/www/upload/';
		$config['allowed_types']    = 'xls|xlsx';
		$this->load->library('upload', $config);
		$data = $this->upload->data();
		$upfile = $data['full_path'];
		$ext = $data['file_ext'];
		
		include_once('/www/fms/models/PHPExcel.php');
		$PHPExcel=new PHPExcel(); 
		if($ext=='.xls')
		{
			include_once('/www/fms/models/PHPExcel/Reader/Excel5.php');  
			$PHPReader=new \PHPExcel_Reader_Excel5(); 
		}
		if($ext=='.xlsx')
		{
			include_once('/www/fms/models/PHPExcel/Reader/Excel2007.php');
			$PHPReader=new \PHPExcel_Reader_Excel2007(); 
		}
		
		$PHPExcel=$PHPReader->load($upfile); 
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数  
		$allColumn=$currentSheet->getHighestColumn();  
		//获取总行数  
		$allRow=$currentSheet->getHighestRow(); 
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始  
		
		for($currentRow=1;$currentRow<=$allRow;$currentRow++)
		{  
		    //从哪列开始，A表示第一列  
		    
		    //读取到的数据，保存到数组$arr中  
		    $df_data[$currentRow-1][0]=$currentSheet->getCell('A'.$currentRow)->getValue();  
		    $df_data[$currentRow-1][1]=$currentSheet->getCell('B'.$currentRow)->getValue();
		    //以此类推。。。
		    
		}
	}




}