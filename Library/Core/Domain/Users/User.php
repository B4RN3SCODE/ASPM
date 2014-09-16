<?php
//namespace Core\Domain\Users;
#region comment
/*************************************************
 * USER : Defines the domain data layer for a user
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class User {

#region Vars::PROPS

	public $UID;
	public $UserName;
	public $Password;
	public $Email;
	public $Session;
	public $FirstName;
	public $LastName;
	public $PhoneNumber;
	public $Location;
	public $Department;
	public $PayRate;
	public $Active;
	public $AccountType;
	public $CurrentStatus;
	public $LastActive;
	public $ManagerID;
	public $AccVerified;
	public $IsManager;

#endregion

#region CONSTRUCTOR

	public function User($uid = null, $un = null, $pwd = null, $eml = null, $sess = null, $fn = null, $ln = null, $pn = null, $loc = null, $dept = null, $pay = null, $actv = false, $acctyp = null, $stat = null, $lastact = null, $mngrID = null, $verd = null, $isMan = null) {
		$this->UID = $uid;
		$this->UserName = $un;
		$this->Password = $pwd;
		$this->Email = $eml;
		$this->Session = $sess;
		$this->FirstName = $fn;
		$this->LastName = $ln;
		$this->PhoneNumber = $pn;
		$this->Location = $loc;
		$this->Department = $dept;
		$this->PayRate = $pay;
		$this->Active = $actv;
		$this->AccountType = $acctyp;
		$this->CurrentStatus = $stat;
		$this->LastActive = $lastact;
		$this->ManagerID = $mngrID;
		$this->AccVerified = $verd;
		$this->IsManager = $isMan;
	}

#endregion

#region ACCESSORS::GETTERS

	public function getUID() { return $this->UID; }
	public function getUserName() { return $this->UserName; }
	public function getPassword() { return $this->Password; }
	public function getEmail() { return $this->Email; }
	public function getSession() { return $this->Session; }
	public function getFirstName() { return $this->FirstName; }
	public function getLastName() { return $this->LastName; }
	public function getPhoneNumber() { return $this->PhoneNumber; }
	public function getLocation() { return $this->Location; }
	public function getDepartment() { return $this->Department; }
	public function getPayRate() { return $this->PayRate; }
	public function getActive() { return $this->Active; }
	public function getAccountType() { return $this->AccountType; }
	public function getCurrentStatus() { return $this->CurrentStatus; }
	public function getLastActive() { return $this->LastActive; }
	public function getManagerID() { return $this->ManagerID; }
	public function getVerifStatus() { return $this->AccVerified; }
	public function getIsManager() { return $this->IsManager; }

#endregion

#region ACCESSORS::SETTERS

	public function setUID($i = null) {
		if($i == null || gettype($i) != "integer") return null;
		$this->UID = $i;
	}
	public function setUserName($n = null) {
		if($n == null || gettype($n) != "string") return null;
		$this->UserName = $n;
	}
	public function setPassword($p = null) {
		if($p == null || gettype($p) != "string") return null;
		$this->Password = $p;
	}
	public function setEmail($e = null) {
		if($e == null || gettype($e) != "string") return null;
		$this->Email = $e;
	}
	public function setSession($s = null) {
		if($s == null || gettype($s) != "string") return null;
		$this->Session = $s;
	}
	public function setFirstName($f = null) {
		if($f == null || gettype($f) != "string") return null;
		$this->FirstName = $f;
	}
	public function setLastName($l = null) {
		if($l == null || gettype($l) != "string") return null;
		$this->LastName = $l;
	}
	public function setPhoneNumber($n = null) {
		if($n == null || gettype($n) != "string") return null;
		$this->PhoneNumber = $n;
	}
	public function setLocation($l = null) {
		if($l == null || gettype($l) != "string") return null;
		$this->Location = $l;
	}
	public function setDepartment($d = null) {
		if($d == null || gettype($d) != "string") return null;
		$this->Department = $d;
	}
	public function setPayRate($r = null) {
		if($r == null || gettype($r) != "double") return null;
		$this->PayRate = $r;
	}
	public function setActive($a = null) {
		if($a == null || $a > 1) return null;
		$this->Active = $a;
	}
	public function setAccountType($t = null) {
		if($t == null || gettype($t) != "string") return null;
		$this->AccountType = $t;
	}
	public function setCurrentStatus($s = null) {
		if($s == null || gettype($s) != "string") return null;
		$this->CurrentStatus = $s;
	}
	public function setLastActive($s = null) {
		if($s == null || gettype($s) != "string") return null;
		$this->LastActive = $s;
	}
	public function setManagerID($s = null) {
		if($s == null || gettype($s) != "integer") return null;
		$this->ManagerID = $s;
	}
	public function setVerifStatus($s = null) {
		if($s == null || $s > 1) return null;
		$this->AccVerified = $s;
	}
	public function setIsManager($a = null) {
		if($a == null || $a > 1) return null;
		$this->IsManager = $a;
	}

#endregion

}
?>
