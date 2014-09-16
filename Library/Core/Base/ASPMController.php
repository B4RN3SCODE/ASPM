<?php
include_once("Library/Core/Base/BasicAuthService.php");
include_once("Viewer.php");
/**************************************************
 * ASPMController.php
 * The main controller for the application.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes
 * @contact			tbarnes@arbsol.com
 ************************************************/
class ASPMController {

	protected $Model;
	protected $Module;
	protected $View;
	protected $Processor;
	protected $PageTitle;
	protected $Action;
	protected $_mapperServ;
	protected $RequestProcessed;
	protected $DataToken;

	public static $DEFAULTS = array(
		"model"			=>	"Home",
		"module"		=>	"",
		"view"			=>	"index",
		"controller"	=>	"HomeController",
		"action"		=>	"index"
	);

	public function ASPMController() {
	}

	public function getModel() { return $this->Model; }
	public function getModule() { return $this->Module; }
	public function getView() { return $this->View; }
	public function getProcessor() { return $this->Processor; }
	public function getPageTitle() { return $this->PageTitle; }
	public function getAction() { return $this->Action; }
	public function getMapper() { return $this->_mapperServ; }
	public function getRequestProcessed() { return $this->RequestProcessed; }
	public function getDataToken() { return $this->DataToken; }

	public function setModel($item) { $this->Model = $item; }
	public function setModule($item) { $this->Module = $item; }
	public function setView($item) { $this->View = $item; }
	public function setProcessor($item) { $this->Processor = $item; }
	public function setPageTitle($item) { $this->PageTitle = $item; }
	public function setAction($item) { $this->Action = $item; }
	public function setMapper($item) { $this->_mapperServ = $item; }
	public function setRequestProcessed($bool) { $this->RequestProcessed = $bool; }
	public function setDataToken($token) { $this->DataToken = $token; }

	public function Initialize() {
		$this->setRequestProcessed(false);
		$this->_mapperServ = (isset($this->_mapperServ) && $this->_mapperServ != null) ? $this->_mapperServ : new ClassExtensionMapper();
		$this->Processor = (isset($this->Processor) && $this->Processor != null) ? $this->Processor : new Viewer();

		$this->Isolate();
		$this->ValidateRoute();

		if($this->ReqHasView() !== false) $this->setView($this->ReqHasView());

		$this->setDataToken(((isset($_REQUEST["DataToken"])) ? $_REQUEST["DataToken"] : null));

		if(in_array($this->getModel(), ClassExtensionMapper::$MODLIST))
			$this->GlobalizeController($this->_mapperServ->MapControllerName($this->getModel()));
	}

	public function Execute($DoAction = "index") {
		$this->SetUpController();
		if(!(method_exists($this, $DoAction) && $this->getRequestProcessed())) ASPM::Fold("Application Execution Failure");
		$this->UpdateGlobals(true);
		$this->$DoAction();
	}

	protected function _Destruct() {
		if(isset($_SESSION["User"])) $_SESSION["User"] = null;
		if(isset($_SESSION["LIStamp"])) $_SESSION["LIStamp"] = "";
		if(isset($GLOBALS["App"]["User"])) $GLOBALS["App"]["User"] = null;
	}

	public function ValidateRequest() {
		return (isset($_REQUEST["Model"]) && !empty($_REQUEST["Model"]) && isset($_REQUEST["Module"]) && !empty($_REQUEST["Module"]) && isset($_REQUEST["Action"]) && !empty($_REQUEST["Action"]));
	}

	public function Isolate() {
		$this->Model = (!empty($_REQUEST["Model"])) ? $_REQUEST["Model"] : self::$DEFAULTS["model"];
		$this->Module = (!empty($_REQUEST["Module"])) ? $_REQUEST["Module"] : self::$DEFAULTS["module"];
		$this->Action = (!empty($_REQUEST["Action"])) ? $_REQUEST["Action"] : self::$DEFAULTS["action"];
		if(!method_exists($this, $this->getAction())) $this->setAction("index");
	}

	public function ReqHasView() {
		return (isset($_REQUEST["View"]) && !empty($_REQUEST["View"])) ? $_REQUEST["View"] : false;
	}

	public function GlobalizeController($controllerName = null) {
		if($controllerName != null && strlen($controllerName) > 0 && $controllerName != get_class($this)) {
			$path = "App/Controllers/" . $this->getModel() . "/${controllerName}.php";
			if(file_exists($path)) {
				include_once($path);
				$CTL = new $controllerName();
				$GLOBALS["App"]["Controller"] = $CTL;
				$GLOBALS["App"]["Instance"]->setController($CTL);
			}
		} else {
			$GLOBALS["App"]["Controller"] = $this;
			$GLOBALS["App"]["Instance"]->setController($this);
		}
		$GLOBALS["App"]["Instance"]->UpdateInstanceGlob();
		$this->setRequestProcessed(true);
	}

	public function PostEditCallBack(array $fails = null) {
		if(!isset($fails) || $fails == null || count($fails) == 0) {
			include_once("App/Views/view.success.php");
		} else {
			$GLOBALS["App"]["Error"] = "<span style='color: red;'>";
			foreach($fails as $msg) {
				$GLOBALS["App"]["Error"] .= "${msg}<br />";
			}
			$GLOBALS["App"]["Error"] .= "</span>";
			include_once("App/Views/view.error.php");
		}
	}

	protected function ValidateRequestToken($req = null, $strict = false) {
		$bool = ($req == $GLOBALS["InitialSec"]["_dt"]);
		if($strict) $bool = ($req == $GLOBALS["App"]["Instance"]->FetchDbSessionValue($GLOBALS["App"]["User"]->getUID()));
		return $bool;
	}

	protected function ValidateRoute() {
		$stat = false;
		if($tmp = $this->_mapperServ->MapModuleList($this->getModel())) {
			foreach($tmp as $int => $module) {
				if($this->getModule() == $module) $stat = true;
			}
		}
		if(!$stat) $this->UnknownRoute($this->getModel(), $this->getModule());
	}

	protected function ValidateGeneralRoute($M, $m) {
		if(!isset($M) || !isset($m) || $M == null || $m == null) return false;

		$stat = false;
		if($tmp = $this->_mapperServ->MapModuleList($M)) {
			foreach($tmp as $int => $module) {
				if($m == $module) $stat = true;
			}
		}
		return $stat;
	}

	public function UpdateGlobals($overwrite = false) {
		$GLOBALS["App"]["Model"] = $this->getModel();
		$GLOBALS["App"]["Model"] = $this->getModule();
		//$GLOBALS["App"]["View"] = $this->getView();
		if($overwrite) {
			$GLOBALS["App"]["Instance"]->UpdateInstanceGlob();
			$GLOBALS["App"]["Controller"] = $this;

			if(isset($GLOBALS["App"]["User"]) && $GLOBALS["App"]["User"]->getUID() > 0) $GLOBALS["App"]["Instance"]->UpdatePropertiesPerUser($GLOBALS["App"]["User"]);
			elseif(isset($_SESSION["User"]) && $_SESSION["User"]->getUID() > 0) $GLOBALS["App"]["Instance"]->UpdatePropertiesPerUser($_SESSION["User"]);

		}
	}

	protected function SubmitErrorRedirect($uid = null, $tm = null, $M = null, $m = null, $act = null, $err = "") {
		if(!isset($tm) || $tm == null) $tm = date("\\D\\a\\t\\e \\T\\i\\m\\e\\: Y-m-d H:i:s");
		ASPM::LogError($uid, $tm, $M, $m, $act, $err);
		$tmp = "${tm} : Fatal Error Occurred. System Log complete<br />There was unacceptable data somewhere. If you think this is an error or are not sure why you are seeing this, please notify an administrator.<br />[Error]: ";
		die($tmp . $err);
	}

	private function UnknownRoute($M = null, $m = null) {
		if(isset($M) && isset($m) && !is_null($M) && !is_null($m))
			ASPM::Fold("Your request attempted access into an unknown place with a path of<br /><strong>..../$M/$m/</strong>");
		else
			ASPM::Fold("The location you have requested does not exist");
	}
}
?>
