<?

/**
 * 
 */
class FileHelper
{
	public static function checkdir($dir)
	{
		if (!is_dir($dir)) {
		    mkdir($dir);
		}
	}
	
}

?>