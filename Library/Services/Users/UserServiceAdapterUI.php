<?php
//namespace Services\Users;
//include("Library/Core/Domain/Users/User.php");
#region comment
/*************************************************
 * UserServiceAdapterUI
 * Interface for the User Service Adapter
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
interface UserServiceAdapterUI {

#region METHODS::QUERY::SELECT::FILTER

	public function GetAllUsers();
	public function GetAllEmployees();
	public function GetAllManagers();
	public function GetAllActiveUsers();
	public function GetUsersByDepartment($dept);
	public function GetUsersByAccountType($type);
	public function GetUsersByUserManager($managerID = null);
	public function GetUserByName($ln = null);
	public function GetUserByUserName($un = null);
	public function GetUserById($id = null);

#endregion

#region METHODS::QUERY::INSERT
	public function InsertNewUser(User $user);
#endregion

#region METHODS::QUERY::UPDATE
	public function UpdateUser(User $user);
#endregion

#region METHODS::QUERY::DROP
	public function RemoveUser($userID);
#endregion
}
?>
