<?php
//namespace Services\Home;
require_once("LoginServiceAdapterUI.php");
#region comment
/*************************************************
 * LoginServiceAdapter
 * Service for the Login process - sets up methods
 * for the controller to use to make logins less
 * messy
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class LoginServiceAdapter implements LoginServiceAdapterUI {

#region Vars::PROPS

	protected $_dbAdapt;
	protected $ResponseError;
	protected $LgInObj;

#endregion

#region CONSTRUCTOR

	public function LoginServiceAdapter(DBConn $db = null, UserLogin $obj = null) {
		$this->_dbAdapt = $db;
		$this->ResponseError = null;
		$this->LgInObj = $obj;
	}

#endregion

#region METHODS::DATACHECK

	public function AllRequiredDataSubmitted() {
		if($this->LgInObj->getSubUserName() != null && strlen($this->LgInObj->getSubUserName()) > 0 && $this->LgInObj->getSubPassword() != null && strlen($this->LgInObj->getSubPassword()) > 0) return true;
		return false;
	}

	public function SetValidationStrings($uname, $pwd, $bool = false) {
		if(!isset($uname) || $uname == null || empty($uname) || !isset($pwd) || $pwd == null || empty($pwd) || ($bool != false && $bool != true)) {
			$this->ResponseError = "Please provide valid credentials for log in.";
			return false;
		}
		$this->LgInObj->setSubUserName($uname);
		$this->LgInObj->setSubPassword($pwd);
		$this->LgInObj->setRememberMe($bool);
		$this->ResponseError = null;
	}

#endregion

#region METHODS::PROCESSES

	public function ValidateSubmission() {

		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare("SELECT * FROM UserMain WHERE UPass = ? AND UName = ? OR UEmail = ?");

		$binds = array($this->LgInObj->getSubPassword(), $this->LgInObj->getSubUserName(), $this->LgInObj->getSubUserName());
		$tmpStmt->bind_param("sss", $binds[0], $binds[1], $binds[2]);

		$tmpStmt->execute();
		$tmpRslts = $tmpStmt->get_result();
		$tmpRslts = $tmpRslts->fetch_array();

		if(isset($tmpRslts["UID"])  && $tmpRslts["UID"] != null && $tmpRslts["UID"] > 0) {
			$this->ResponseError = null;

			$tempDataToken = $GLOBALS["App"]["Instance"]->getDataToken();
			$tempDate = date("Y-m-d H:i:s");

			$_SESSION["DataToken"] = $tempDataToken;
			$GLOBALS["InitialSec"]["_dt"] = $tempDataToken;

			$tempUser = new User($tmpRslts["UID"], $tmpRslts["UName"], null, null, $tempDataToken, $tmpRslts["FirstName"], $tmpRslts["LastName"], null, null, null, null, true, null, $tmpRslts["StatusID"], $tempDate, null, null, null);

			$GLOBALS["App"]["User"] = $tempUser;
			$_SESSION["User"] = $tempUser;
			$_SESSION["LIStamp"] = time();

			/**		TODO:
			 * 			LOG ENTRY SHOWING LOGIN
			 **/

			$finalRes = array(
				"Id"	=>	$tmpRslts["UID"],
				"Uname"	=>	$tmpRslts["UName"],
				"SessionKey"	=>	$tempDataToken,
				"LastActive"	=>	$tempDate
			);

			unset($tempDataToken);
			unset($tempDate);
			unset($tempUser);

			$tmpStmt->close();

			unset($tmpStmt);
			unset($binds);
			unset($tmp);

			return $finalRes;
		}

		/**		TODO:
		 * 			LOG ENTRY SHOWING LOGIN ATTEMPT
		 **/

		$tmpStmt->close();

		unset($tmpStmt);
		unset($binds);
		unset($tmp);

		$this->ResponseError = "Invalid username / password combination";
		return false;
	}

	public function ProceedLogInAction(array $info) {
		if(isset($info) && gettype($info) == "array" && $info["Id"] > 0 && strlen($info["Uname"]) > 0 && strlen($info["SessionKey"]) > 0) {
			$this->ResponseError = null;

			$tmp = $this->_dbAdapt->getLnk();
			$tmpStmt = $tmp->prepare("UPDATE UserMain SET Online = 1, Session = ?, LastCheckIn = ? WHERE UID = ?");

			$binds = array(md5($info["SessionKey"]), $info["LastActive"], $info["Id"]);
			$tmpStmt->bind_param("ssi", $binds[0], $binds[1], $binds[2]);

			$tmpStmt->execute();
			$tmpStmt->close();

			unset($tmpStmt);
			unset($binds);
			unset($tmp);

			return true;
		}
		$this->ResponseError = "Error loading user info. Cannot login at this time.";
		return false;
	}

#endregion

	public function ErrorExists() {
		if(!isset($this->ResponseError) || empty($this->ResponseError) || $this->ResponseError == null) return false;
		return $this->ResponseError;
	}

}
?>


