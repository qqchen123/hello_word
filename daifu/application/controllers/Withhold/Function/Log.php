<?php

class Log
{
	public static function LogWirte($Astring)
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . '/shared/logs';
		$path = $path."/daifu/";
		$file = $path."log".date('Ymd',time()).".txt";
		if(!is_dir($path)){	mkdir($path); }
		$LogTime = date('Y-m-d H:i:s',time());
		if(!file_exists($file))
		{
			$logfile = fopen($file, "w") or die("Unable to open file!");
			fwrite($logfile, "[$LogTime]:".$Astring."\r\n");
			fclose($logfile);
		}else{		
			$logfile = fopen($file, "a") or die("Unable to open file!");
			fwrite($logfile, "[$LogTime]:".$Astring."\r\n");
			fclose($logfile);
		}			
	}
}

?>