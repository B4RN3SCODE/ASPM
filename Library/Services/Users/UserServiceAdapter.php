<?php
//namespace Services\Users;
require_once("UserServiceAdapterUI.php");
#region comment
/*************************************************
 * UserServiceAdapter
 * Using the Namespace's interface, it provides
 * the logic to the outlined methods, ultimately
 * defining the way an object will be handled
 * from database entity to applicaiton model
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class UserServiceAdapter implements UserServiceAdapterUI {

#region Vars::PROPS
	private $_dbAdapt;
#endregion

#region CONSTRUCTOR
	public function UserServiceAdapter(DBConn $db = null) {
		$this->_dbAdapt = (isset($db) && $db != null) ? $db : new DBConn();
		if(!$this->_dbAdapt->getIsLinked()) $this->_dbAdapt->Link();
	}
#endregion

#region METHODS::QUERY::SELECT::FILTER

	public function GetAllUsers() {
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null);
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}
	public function GetAllEmployees() {
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("Department !=" => " 'NA'"));
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}
	public function GetAllManagers() {
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("IsManager = " => "1"));
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}
	public function GetAllActiveUsers() {
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("Online = " => "1"));
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}
	public function GetUsersByDepartment($dept) {
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("Department = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("s", $dept);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}
	public function GetUsersByAccountType($type) {
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("AccType = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("s", $type);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}
	public function GetUsersByUserManager($managerID = null) {

		if($managerID == null) return $this->GetAllUsers();

		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("ManagerID = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $managerID);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}
	public function GetUserByName($ln = null) {
		if($ln == null) return $this->GetAllUsers();

		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("LastName LIKE " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("s", $ln);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}
	public function GetUserByUserName($un = null) {
		if($un == null) return $this->GetAllUsers();

		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("UName LIKE " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("s", $un);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}
	public function GetUserById($id = null) {
		if($id == null) return $this->GetAllUsers();
		$this->_dbAdapt->SStatement(array(0 => "*"), DB_TBL_USER, null, array("UID = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}

#endregion

#region METHODS::QUERY::INSERT

	public function InsertNewUser(User $user = null) {
		if($user == null || !($user instanceof User)) return false;
		$props = array(
			"UName,"	=>	$user->getUserName(),
			"UPass,"	=>	$user->getPassword(),
			"UEmail,"	=>	$user->getEmail(),
			"Session,"	=>	$user->getSession(),
			"FirstName,"	=>	$user->getFirstName(),
			"LastName,"	=>	$user->getLastName(),
			"Phone,"	=>	$user->getPhoneNumber(),
			"LastCheckInLocation,"	=>	$user->getLocation(),
			"Department,"	=>	$user->getDeparment(),
			"PayRate,"	=>	$user->getPayRate(),
			"Online,"	=>	$user->getActive(),
			"AccType,"	=>	$user->getAccountType(),
			"StatusID,"	=>	$user->getCurrentStatus(),
			"LastCheckIn,"	=>	$user->getLastActive(),
			"ManagerID,"	=>	$user->getManagerID(),
			"AccountVerified,"	=>	$user->getVerifStatus(),
			"IsManager"	=>	$user->getIsManager()
		);
		foreach($props as $k => $v) {
			if($v != null) {
				if($k == "IsManager") {
					$_props[$k] = "$v ";
				} else {
					if(gettype($v) == "string"){
						$_props[$k] = "'" . $v . "', ";
					} else {
						$_props[$k] = "$v, ";
					}
				}
			}
		}
		$this->_dbAdapt->IStatement(DB_TBL_USER, $_props);
		$tmp = $this->_dbAdapt->getLnk();
		$tmp->query($this->_dbAdapt->getQry());
		unset($tmp);
		return true;
	}

#endregion

#region METHODS::QUERY::UPDATE

	public function UpdateUser(User $user = null) {
		if($user == null || $user->getUID() == null) return false;
		$props = array(
			"UName"	=>	$user->getUserName(),
			"UPass"	=>	$user->getPassword(),
			"UEmail"	=>	$user->getEmail(),
			"Session"	=>	$user->getSession(),
			"FirstName"	=>	$user->getFirstName(),
			"LastName"	=>	$user->getLastName(),
			"Phone"	=>	$user->getPhoneNumber(),
			"LastCheckInLocation"	=>	$user->getLocation(),
			"Department"	=>	$user->getDeparment(),
			"PayRate"	=>	$user->getPayRate(),
			"Online"	=>	$user->getActive(),
			"AccType"	=>	$user->getAccountType(),
			"StatusID"	=>	$user->getCurrentStatus(),
			"LastCheckIn"	=>	$user->getLastActive(),
			"ManagerID"	=>	$user->getManagerID(),
			"AccountVerified"	=>	$user->getVerifStatus(),
			"IsManager"	=>	$user->getIsManager()
		);
		foreach($props as $k => $v) {
			if($v != null) {
				if($k == "IsManager") {
					$_props[$k] = "$v";
				} else {
					if(gettype($v) == "string"){
						$_props[$k] = "'" . $v . "', ";
					} else {
						$_props[$k] = "$v, ";
					}
				}
			}
		}
		$cond = array("UID = " => $user->getUID());
		$this->_dbAdapt->UStatement(DB_TBL_USER, $_props, $cond);
		$tmp = $this->_dbAdapt->getLnk();
		$tmp->query($this->_dbAdapt->getQry());
		unset($tmp);
		return true;
	}

#endregion

#region METHODS::QUERY::DROP

	public function RemoveUser($userID) {
		if($userID == null) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare("DELETE FROM UserMain WHERE UID = ?");
		$tmpStmt->bind_param("i", $userID);
		$tmpStmt->execute();
		$tmpStmt->close();
		return true;
	}

#endregion

}
?>
