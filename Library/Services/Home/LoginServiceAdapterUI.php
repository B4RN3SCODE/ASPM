<?php
//namespace Services\Home;
include("Library/Core/Domain/Login/UserLogin.php");
#region comment
/*************************************************
 * LoginServiceAdapterUI
 * Interface for the Login Service Adapter
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
interface LoginServiceAdapterUI {

	public function AllRequiredDataSubmitted();
	public function SetValidationStrings($uname, $pwd, $bool = false);
	public function ValidateSubmission();
	public function ProceedLogInAction(array $info);
	public function ErrorExists();

}
?>

