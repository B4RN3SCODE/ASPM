<?php
require_once("ASPMController.php");
include_once("Library/Core/Data/ClassExtensionMapper.php");
include_once("Library/Configuration/base.config.php");
include_once("Library/Configuration/datamod.config.php");
require_once("Library/Core/Data/DBConn.php");
require_once("Library/Core/Domain/Users/User.php");
include_once("App/Includes/System/ASPMDateTime.php");
require_once("App/Includes/System/MasterHash.php");
include_once("App/Includes/System/SimpleEncoDeco.php");
/**************************************************
 * ASPM.php
 * A new ASPM object controls miscellaneous parts
 * of the application. Most things are taken care
 * of in a service, module, or controller, but the
 * application will control things like session
 * data and URL redirects.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes
 * @contact			tbarnes@arbsol.com
 ************************************************/
class ASPM {

	public $Controller;
	public $DBObj;
	public $DBStatus;
	protected $DataToken;
	protected $ServerKey;
	public $CurrentUser;

	public function ASPM() {
	}

	public function Boot() {
		$this->setDBObj(new DBConn());
		$stat = -1;
		if($this->DBObj->Link()) $stat++;
		$this->setDBStatus($stat);
		$GLOBALS["App"]["DataBase"]["DBConn"] = $this->getDBObj();

		if(!isset($_SESSION["DataToken"]) && !isset($this->DataToken) && !isset($GLOBALS["InitialSec"]["_dt"]))
			$this->GenerateDataToken(false);
		else
			$this->HandleExistingToken();

		$tmpModel = (isset($_REQUEST["Model"])) ? $_REQUEST["Model"] : "Home";
		$tmpControlName = (in_array($tmpModel, ClassExtensionMapper::$MODLIST)) ? ClassExtensionMapper::$MAPPER["ModelController"][$tmpModel] : "HomeController";

		if(!file_exists("App/Controllers/${tmpModel}/${tmpControlName}.php")) self::Fold("Error loading initial resourced controller while starting/restarting applicaiton.");

		include_once("App/Controllers/${tmpModel}/${tmpControlName}.php");
		$this->Controller = new $tmpControlName();
		$this->UpdateInstanceGlob();

		$this->Controller->Initialize();
		$this->Controller->Execute($this->Controller->getAction());
	}

	public function getController() { return $this->Controller; }
	public function getDBObj() { return $this->DBObj; }
	public function getDBStatus() { return $this->DBStatus; }
	public function getDataToken() { return $this->DataToken; }
	public function getServerKey() { return $this->ServerKey; }
	public function getCurrentUser() { return $this->CurrentUser; }
	public function setController($control) { $this->Controller = $control; }
	public function setDBObj($dbo) { $this->DBObj = $dbo; }
	public function setDBStatus($int) { $this->DBStatus = $int; }
	public function setDataToken($dtk) { $this->DataToken = $dtk; }
	public function setServerKey($skey) { $this->ServerKey = $skey; }
	public function setCurrentUser(User $usr) { $this->CurrentUser = $usr; }

	public function UpdatePropertiesPerUser(User $inject = null) {
		$usr = null;
		if(!isset($inject) || $inject == null) {
			if(isset($_SESSION["User"]) && !(is_null($_SESSION["User"]->getUID()))) $usr = $_SESSION["User"];
			elseif(isset($this->CurrentUser) && !(is_null($this->CurrentUser->getUID()))) $usr = $this->getCurrentUser();
			elseif(isset($GLOBALS["App"]["User"]) && !(is_null($GLOBALS["App"]["User"]->getUID()))) $usr = $GLOBALS["App"]["User"];
			elseif(isset($GLOBALS["App"]["Instance"]->CurrentUser) && !(is_null($GLOBALS["App"]["Instance"]->CurrentUser->getUID()))) $usr = $GLOBALS["App"]["Instance"]->getCurrentUser();
		} else $usr = $inject;

		if($usr == null || $usr->getUID() < 1) return false;

		$this->setCurrentUser($usr);
		$this->UpdateInstanceGlob();
		$GLOBALS["App"]["User"] = $usr;
		$_SESSION["User"] = $usr;

		return true;
	}

	public function UpdateInstanceGlob() {
		$GLOBALS["App"]["Instance"] = $this;
	}

	protected function HandleExistingToken() {
		$token = null;

		if(isset($_SESSION["DataToken"]) && !(is_null($_SESSION["DataToken"]))) $token = $_SESSION["DataToken"];
		elseif(isset($_SESSION["User"]) && !(is_null($_SESSION["User"]->getSession()))) $token = $_SESSION["User"]->getSession();
		elseif(isset($GLOBALS["InitialSec"]["_dt"]) && !(is_null($GLOBALS["InitialSec"]["_dt"]))) $token = $GLOBALS["InitialSec"]["_dt"];
		elseif(isset($GLOBALS["App"]["User"]) && !(is_null($GLOBALS["App"]["User"]->getSession()))) $token = $GLOBALS["App"]["User"]->getSession();
		elseif(isset($GLOBALS["App"]["Instance"]) && !(is_null($GLOBALS["App"]["Instance"]->getDataToken()))) $token = $GLOBALS["App"]["Instance"]->getDataToken();

		if($token == null || strlen($token) < 1) {
			return false;
		} else {
			$_SESSION["DataToken"] = $token;
			if(isset($_SESSION["User"])) $_SESSION["User"]->setSession($token);
			$this->setDataToken($token);
			$this->UpdateInstanceGlob();
			$GLOBALS["InitialSec"]["_dt"] = $token;
			return true;
		}
	}

	public function GenerateDataToken($updateDatabase = false) {
		$this->setDataToken(MasterHash::_Hash(uniqid(MasterHash::getXStr(), true), MasterHash::getXStr()));

		$_SESSION["DataToken"] = $this->getDataToken();
		if(isset($_SESSION["User"])) $_SESSION["User"]->setSession($this->getDataToken());
		$GLOBALS["InitialSec"]["_dt"] = $this->getDataToken();

		$this->setServerKey(md5($this->getDataToken()));
		$GLOBALS["InitialSec"]["_sk"] = md5($this->getDataToken());
		$this->UpdateInstanceGlob();


		if($updateDatabase) {
			$uid = 0;
			if(isset($this->CurrentUser) && $this->CurrentUser->getUID() > 0) {
				$uid = $this->CurrentUser->getUID();
			} elseif(isset($_SESSION["User"]) && $_SESSION["User"]->getUID() > 0) {
				$this->UpdatePropertiesPerUser($_SESSION["User"]);
				$uid = $_SESSION["User"]->getUID();
			}

			if($uid > 0)
				$this->UpdateDbSessionValue($udi);
		}
		return $this->getDataToken();
	}

	protected function RegenerationReWrite() {
		if(isset($_SESSION["DataToken"])) unset($_SESSION["DataToken"]);
		if(isset($GLOBALS["InitialSec"]["_dt"])) unset($GLOBALS["InitialSec"]["_dt"]);
		if(isset($GLOBALS["InitialSec"]["_sk"])) unset($GLOBALS["InitialSec"]["_sk"]);
		if(isset($this->DataToken)) unset($this->DataToken);
		if(isset($this->ServerKey)) unset($this->ServerKey);
		$this->GenerateDataToken(true);
	}

	public function FetchDbSessionValue($userId = 0) {
		if(!isset($userId) || $userId < 1) return false;

		$db = $this->DBObj->getLnk();
		$stmt = $db->prepare("SELECT Session FROM UserMain WHERE UID = ?");
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		$rslt = $stmt->get_result();
		$sess = $rslt->fetch_assoc();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return $sess["Session"];

	}

	public function UpdateDbSessionValue($userId = 0) {
		if(!isset($userId) || $userId < 1) return false;

		$toke = $this->getServerKey();
		$db = $this->DBObj->getLnk();
		$stmt = $db->prepare("UPDATE UserMain SET Session = ? WHERE UID = ?");
		$stmt->bind_param("si", $toke, $userId);
		$stmt->execute();
		$stmt->close();
		unset($db);
		unset($stmt);

	}

	public function SessionActivate() {
		if(isset($_SESSION["ASPMGO"]) && $_SESSION["ASPMGO"] == true && isset($_COOKIE["BCSESSID"]) && !(is_null(session_id())))
			return false;

		if(isset($_SESSION["ASPMGO"])) unset($_SESSION["ASPMGO"]);
		session_start();
		$_SESSION["ASPMGO"] = true;
		return true;
	}

	public function SessionTerminate() {
		if(isset($_SESSION["ASPMGO"])) unset($_SESSION["ASPMGO"]);
		session_destroy();
	}

	public function CookieBake($name = null, $value = null, $expr = 1, $pth = "/", $domain = null, $secr = null, $httpOnly = null) {
		if(!isset($name) || empty($name)) return false;
		$expr = (time() + (3600 * $expr));
		$domain = (is_null($domain)) ? ((isset($_SERVER["HTTP_HOST"])) ? $_SERVER["HTTP_HOST"] : DB_HOST) : $domain;
		setcookie($name, $value, $expr, $pth, $domain, $secr, $httpOnly);
	}

	public function CookieBurn($name) {
		if(!isset($name) || empty($name)) return false;
		$this->CookieBake($name, "", -1);
	}

	public function BurnCookieBatch() {
		foreach($_COOKIE as $name => $props) {
			$this->CookieBurn($name);
		}
	}

	public function UserEntityToModel(array $userData = array()) {
		if(!isset($userData) || count($userData) < 1) return null;

		$tmpUser = new User();
		foreach($userData as $property => $value) {
			$mthd = "set${property}";
			if(!(property_exists($tmpUser, $property) && method_exists($tmpUser, $mthd)))
				continue;
			else
				$tmpUser->$mthd($value);

		}

		if($tmpUser->getUID() < 1 || is_null($tmpUser->getSession()))
			return false;

		return $tmpUser;
	}

	public static function IsEmptyNullOrWhiteSpace($str = null) {
		if(is_null($str) || empty($str) || strlen($str) < 1 || $str == "" || $str == " ") return true;
		$str = str_replace(" ", "", $str);
		$str = str_replace(array(" ", "\t"), "", $str);
		if(strlen($str) < 1 || empty($str) || is_null($str)) return true;
		return false;
	}


	public static function QuickLog($msg, $dir, $fileName) {
		if(!isset($msg) || !isset($dir) || !isset($fileName) || strlen($msg) < 1 || strlen($dir) < 1 || strlen($fileName) < 5) return false;
		$dir = "public/tmp/sessions/${dir}/";
		if(!(file_exists($dir))) mkdir($dir, 0777);
		$locate = "${dir}${fileName}.log";
		if(file_exists($locate))
			file_put_contents($locate, $msg, FILE_APPEND);
		else
			file_put_contents($locate, $msg);

		return true;
	}

	public static function LogErr($uid, $timestamp, $model, $module, $act, $errMsg, $dir, $fileName) {
		if(!isset($errMsg) || !isset($dir) || !isset($fileName) || strlen($errMsg) < 1 || strlen($dir) < 1 || strlen($fileName) < 5) return false;
		$formatted = "ASPM_APPLICATION_ERROR: ";
		$uid = (isset($uid) && $uid > 0) ? "[UID: ${uid}]" : "[UID Not Specified] | ";
		$timestamp = (isset($timestamp)) ? date("\\o\\n Y-m-d \\a\\t H:i:s", $timestamp) : date("\\o\\n Y-m-d \\a\\t H:i:s", time());
		$timestamp .= " | ";
		$pth = "path[";
		$pth .= (isset($model) && !empty($model)) ? "Model:${model}//" : "No:Model:Spec.//";
		$pth .= (isset($module) && !empty($module)) ? "module:${module}//" : "No:module:Spec.//";
		$pth .= (isset($act) && !empty($act)) ? "Req.ACTION: ${act}] ---msg---${errMsg}." : "NoActionSpecified ---msg---${errMsg}.";
		$formatted .= $uid . $timestamp . $pth;
		return self::QuickLog($formatted, $dir, $fileName);
	}

	public static function DBLog($uid, $tmstmp, $sql, $msg, $dir, $fileName) {
		if(!isset($sql) || !isset($msg) || strlen($sql) < 1 || strlen($msg) < 1) return false;
		$tmstmp = date("\\o\\n Y-m-d \\a\\t H:i:s", $tmstmp);
		$formatted = "DBConn Instance --- UID: ${uid} --- ${tmstmp} --- SQL: ${sql} --- MSG: ${msg}.";
		return self::QuickLog($formatted, $dir, $fileName);
	}

	public static function Fold($msg) {
		$APPERRMSG = $msg;
		include("App/Views/apperror.php");
		exit;
	}

	public static function VerifyPhoneNumberFormat($ph = null) {
		if(!isset($ph) || empty($ph) || is_null($ph)) return false;
		$ph = preg_replace("/\(|\)|\-|\s|[^A-Za-z0-9]/", "", $ph);
		if(strlen($ph) < 7 || strlen((string)((double)$ph)) < 7) return false;
		return (double)$ph;
	}

}
?>
