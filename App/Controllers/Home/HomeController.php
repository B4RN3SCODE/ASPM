<?php
include_once("Library/Services/Home/LoginServiceAdapter.php");
/***********************************************************
 * HomeController
 * Controls the initial steps after login to something the
 * user wants to do.  The home page is a simple list that
 * displays a few options - using it as a stepping stone
 * moving on to the other modules.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ***********************************************************/
class HomeController extends ASPMController {

	// adapter built to handle logins
	protected $_loginServ;

	/*******************************
	 * Setup
	 * This instantiates a new adapter
	 * object or objects and updates
	 * the global variable accordingly
	 *********************************/
	public function SetUpController() {
		$this->_loginServ = (isset($GLOBALS["App"]) && isset($GLOBALS["App"]["ServiceAdapter"])) ? $GLOBALS["App"]["ServiceAdapter"] : new LoginServiceAdapter($GLOBALS["App"]["DataBase"]["DBConn"], new UserLogin(isset($_REQUEST["username"]) ? $_REQUEST["username"] : null, isset($_REQUEST["password"]) ? $_REQUEST["password"] : null, (isset($_REQUEST["rememberme"]) && $_REQUEST["rememberme"] == true) ? true : false));
		$GLOBALS["App"]["ServiceAdapter"] = $this->_loginServ;
	}

	/*************************
	 * Action: index
	 ************************/
	public function index() {
		$this->_Destruct();
		$this->Processor->setDefaults(array("Head" => true, "Menu" => true, "Foot" => false));
		$this->Processor->SetUpPaths($this->getModel(), $this->getModule(), "login");
		//include_once("App/Models/Home/Views/view.login.php");
		$this->Processor->RenderView();
	}

	/******************************
	 * Action: login
	 * This action handles the login
	 * action and utilizes the login adapter
	 ********************************/
	public function Login() {
		if($this->_loginServ->AllRequiredDataSubmitted() == false && isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
			$this->_loginServ->SetValidationStrings($_REQUEST["username"], $_REQUEST["password"], ((isset($_REQUEST["rememberme"]) && $_REQUEST["rememberme"] == true) ? true : false));
		}

		if(!$this->_loginServ->AllRequiredDataSubmitted()) {
			$err = $this->_loginServ->ErrorExists();
			if(!$err) $err = "Insufficient login data.";
			$GLOBALS["App"]["Error"] = $err;
			$this->index();
			return null;
		} else {

			$tmp = $this->_loginServ->ValidateSubmission();
			if($tmp != false && strlen($tmp["Uname"]) > 0 && $tmp["Id"] > 0) {
				$this->_loginServ->ProceedLogInAction($tmp);
				if(isset($_SESSION["User"]) && isset($GLOBALS["App"]["User"]) && $_SESSION["User"] != null && $GLOBALS["App"]["User"] != null) header("Location: index.php?Model=Home&Module=&Action=Home&View=userhome");
				else {
					$GLOBALS["App"]["Error"] = "Unkown Error occured with login";
					$this->index();
					return null;
				}
			} else {
				$GLOBALS["App"]["Error"] = $this->_loginServ->ErrorExists();
				$this->index();
				return null;
			}
		}
	}

	/******************************
	 * Action: login
	 * This action determines if the
	 * user can access the user home,
	 * and routes the application based
	 * on that determination
	 ********************************/
	public function Home() {
		$this->Processor->SetUpPaths($this->getModel(), $this->getModule(), "userhome");
		$tmp = BasicAuthService::EntryPointCheck();
		if($tmp !== true) ASPM::Fold($tmp);
		else $this->Processor->RenderView();
	}

	/******************************
	 * Action: logout
	 ********************************/
	public function Logout() {
		$GLOBALS["App"]["Instance"]->SessionTerminate();
		$this->index();
		return null;
	}

	public function About() {
		include_once("App/Models/Home/Views/view.about.php");
	}

	public function Support() {
		include_once("App/Models/Home/Views/view.support.php");
	}


}
?>

