<?php
#region comment
/*************************************************
 * UserLogin
 * Defines the domain layer data element for the
 * login process.  Collects basic data and allows
 * the controller to validate a bit less painfully
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class UserLogin {

#region Vars::PROPS
	protected $SubUserName;
	protected $SubPassword;
	protected $RememberMe;
#endregion

#region CONSTRUCTOR
	public function UserLogin($un = null, $pw = null, $bool = false) {
		$this->SubUserName = $un;
		$this->SubPassword = $pw == null ? $pw : md5($pw);
		$this->RememberMe = $bool;
	}
#endregion

#region METHODS::GETTERS

	public function getSubUserName() { return $this->SubUserName; }
	public function getSubPassword() { return $this->SubPassword; }
	public function getRememberMe() { return $this->RememberMe; }

#endregion

#region METHODS::SETTERS

	public function setSubUserName($un) {
		if(!isset($un) || empty($un) || strlen($un) < 1) return false;
		$this->SubUserName = $un;
	}

	public function setSubPassword($pw) {
		if(!isset($pw) || empty($pw) || strlen($pw) < 1) return false;
		$this->SubPassword = md5($pw);
	}

	public function setRememberMe($bool = false) {
		if($bool != true && $bool != false) return null;
		$this->RememberMe = $bool;
	}

#endregion


	public function ready(UserLogin $ul = null) {
		if(!isset($ul) || empty($ul) || $ul == null) $ul = $this;

		$tmp = ($this->SubUserName != null && strlen($this->SubUserName) > 0);
		$tmp = ($this->SubPassword != null && strlen($this->SubPassword) > 0);
		$tmp = ($this->RememberMe != null);

		return $tmp;
	}
}
?>
