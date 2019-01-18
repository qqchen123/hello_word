<?

/**
 * @desc 角色助手
 */
class RoleHelper 
{
	
	/**
	 * @name 权限检查
	 */
	public static function permissioncheck()
	{
		if ($_SESSION['fms_userrole'] != 1) {
			echo "没有权限！";
			exit;
		}
	}


}