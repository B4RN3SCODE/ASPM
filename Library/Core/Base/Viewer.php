<?php
include_once("App/Includes/Build/ElementBuilder.php");
include_once("App/Includes/Build/Processor.php");
/**************************************************
 * Viewer
 * Creates a response to render
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes
 * @contact			tbarnes@arbsol.com
 ************************************************/
class Viewer {

	public $Opts = array();
	public $DisplayDat = array();
	public $CSSLnks = array();
	public $JSSrcs = array();
	public $PgTitle;
	protected $VwPath;
	protected $TplPath;
	protected $TplData;
	protected $Procssr;
	protected $UseSimple;
	private $HasView;
	private $HasTpl;
	private $HTMLHead;
	private $BodyContent;
	private $IsPrepared;
	private $IsProcessed;
	private $AuthCheck;
	private $ExtractVars = array();

	private static $DEFAULT_OPTIONS = array("HEAD" => true, "MENU" =>true, "FOOT" => true);
	private static $DEFAULT_TITLE = "ASPM | Management Software";

	public function Viewer($options = array(), $ddata = array(), $csslinks = array(), $jssources = array(), $pagetitle = null,
							$M = null, $m = null, $view = null, $templatename = null, $simpleview = false, $requireauth = false) {

		$this->IsPrepared = false;
		$this->IsProcessed = false;
		$this->TplData = "";
		$this->HTMLHead = "";
		$this->BodyContent = "";
		$this->ExtractVars = array();

		if(isset($options) && count($options) == 3 && isset($options["HEAD"]) && isset($options["MENU"]) && isset($options["FOOT"]) &&
			is_bool($options["HEAD"]) && is_bool($options["MENU"]) && is_bool($options["FOOT"]))
			$this->Opts = $options;
		else
			$this->Opts = self::$DEFAULT_OPTIONS;

		/**	TODO:	Add step for authentication check here in C'TOR. Example follows		**/
		//CHANGE
		$this->AuthCheck = false;
		//TO SOMETHING LIKE:
		//if($requireauth) BasicAuthServce::CheckView....(params...);

		if(!isset($pagetitle) || empty($pagetitle) || gettype($pagetitle) != "string") {
			if(isset($GLOBALS["App"]) && isset($GLOBALS["App"]["PageTitle"]) && !(ASPM::IsEmptyNullOrWhiteSpace($GLOBALS["App"]["PageTitle"])))
				$this->PgTitle = $GLOBALS["App"]["PageTitle"];
			else
				$this->PgTitle = self::$DEFAULT_TITLE;
		} else $this->PgTitle = $pagetitle;

		if($simpleview) {
			$ARGS["PTITLE"] = $this->PgTitle;
			$ARGS["VIEW"] = $view;
			$ARGS["MODEL"] = $M;
			$ARGS["MODULE"] = $m;
			$this->RenderSimple($this->Opts, $ARGS, $csslinks, $jssources);
			return null;
		}

		$this->UseSimple = false;
		$this->DisplayDat = $ddata;
		$this->CSSLnks = $csslinks;
		$this->JSSrcs = $jssources;

		$this->Procssr = null;

		if(isset($M) && !(ASPM::IsEmptyNullOrWhiteSpace($M))) {

			if(isset($view) && !(ASPM::IsEmptyNullOrWhiteSpace($view))) {
				$tmpPth = $this->AssemblePath($M, $m, $view, false);
				if($this->ValidPath($tmpPth)) {
					$this->VwPath = $tmpPth;
					$this->HasView = true;
				}
			}

			if(isset($templatename) && !(ASPM::IsEmptyNullOrWhiteSpace($templatename))) {
				$tmpPth = $this->AssemblePath($M, $m, $templatename, true);
				if($this->ValidPath($tmpPth)) {
					$this->TplPath = $tmpPth;
					$this->HasTpl = true;
				}
			}
		}

		if($this->getHasTpl())
			$this->Procssr = new Processor();

	}



	public function getOpts() { return $this->Opts; }
	public function getDisplayDat() { return $this->DisplayDat; }
	public function getProcssr() { return $this->Procssr; }
	public function getPgTitle() { return $this->PgTitle; }
	public function getVwPath() { return $this->VwPath; }
	public function getTplPath() { return $this->TplPath; }
	public function getTplData() { return $this->TplData; }
	public function getUseSimple() { return $this->UseSimple; }
	public function getHasView() { return $this->HasView; }
	public function getHasTpl() { return $this->HasTpl; }
	public function getHTMLHead() { return $this->HTMLHead; }
	public function getBodyContent() { return $this->BodyContent; }
	public function getIsPrepared() { return $this->IsPrepared; }
	public function getIsProcessed() { return $this->IsProcessed; }
	public function getAuthCheck() { return $this->AuthCheck; }
	public function getExtractVars() { return $this->ExtractVars; }


	public function setOpts($ops = array()) {
		if(isset($ops) && count($ops) == 3 && isset($ops["HEAD"]) && isset($ops["MENU"]) && isset($ops["FOOT"]) &&
			is_bool($ops["HEAD"]) && is_bool($ops["MENU"]) && is_bool($ops["FOOT"]))
			$this->Opts = $ops;
		else
			$this->Opts = self::$DEFAULT_OPTIONS;
	}

	public function setDisplayDat($data = array()) {
		if(gettype($data) != "array") return null;
		$this->DisplayDat = $data;
	}

	protected function setProcssr($proc = null) {
		$this->Procssr = $proc;
	}

	public function setPgTitle($title = null) {
		if(!isset($title) || ASPM::IsEmptyNullOrWhiteSpace($title)) {
			if(isset($GLOBALS["App"]) && isset($GLOBALS["App"]["PageTitle"]) && strlen($GLOBALS["App"]["PageTitle"]) > 0)
				$this->PgTitle = $GLOBALS["App"]["PageTitle"];
			else
				$this->PgTitle = self::$DEFAULT_TITLE;
		}
	}

	protected function setVwPath($path = null) {
		$this->VwPath = $path;
	}
	protected function setTplPath($path = null) {
		$this->TplPath = $path;
	}
	private function setTplData($tdat = null) {
		$this->TplData = $tdat;
	}
	private function setHasView($bool = false) {
		$this->HasView = $bool;
	}
	private function setHasTpl($bool = false) {
		$this->HasTpl = $bool;
	}
	protected function setHTMLHead($str = "", $appnd = false) {
		$this->HTMLHead = ($appnd) ? $this->HTMLHead . $str : $str;
	}
	protected function setBodyContent($str = "", $appnd = false) {
		$this->BodyContent = ($appnd) ? $this->BodyContent . $str : $str;
	}
	private function setIsPrepared($bool = false) {
		$this->IsPrepared = $bool;
	}
	private function setIsProcessed($bool = false) {
		$this->IsProcessed = $bool;
	}
	protected function setAuthCheck($bool = false) {
		$this->AuthCheck = $bool;
	}
	protected function setExtractVars($arr = array(), $key = null, $val = "") {
		if((!isset($arr) || count($arr) < 1) && (!isset($key) || ASPM::IsEmptyNullOrWhiteSpace($key)) && !isset($val)) return null;
		if(isset($arr) && count($arr) > 0)
			$this->ExtractVars = $arr;
		else
			$this->ExtractVars[$key] = $val;
	}


	public function AssemblePath($model = null, $module = null, $filename = null, $isTplPth = false) {
		$tmpPth = "";
		if(!$isTplPth) {
			if(ASPM::IsEmptyNullOrWhiteSpace($module))
				$tmpPth = "App/Models/${model}/Views/view.${filename}.php";
			else
				$tmpPth = "App/Models/${model}/${module}/Views/view.${filename}.php";
		} else {
			if(ASPM::IsEmptyNullOrWhiteSpace($module))
				$tmpPth = "App/Models/${model}/Views/tpls/${filename}.tpl";
			else
				$tmpPth = "App/Models/${model}/${module}/Views/tpls/${filename}.tpl";
		}
		return $tmpPth;
	}

	public function ValidPath($pth = null) {
		if(!isset($pth) || ASPM::IsEmptyNullOrWhiteSpace($pth)) return false;
		if(strpos($pth, "\\") === false) {
			return file_exists($pth);
		} else {
			$tmpArr = explode("\\", $pth);
			foreach($tmpArr as $i => $str) {
				if(ASPM::IsEmptyNullOrWhiteSpace($str))
					unset($tmpArr[$i]);

			}
			$pth = implode("/", $tmpArr);

			return file_exists($pth);
		}
	}

	public function ReadTpl($pth = null) {
		$tplContent = "";
		if($this->ValidPath($pth))
			$tplContent = file_get_contents($pth);

		return $tplContent;
	}

	private function BuildHead($append = false) {
		if((isset($this->CSSLnks) && count($this->CSSLnks) > 0) ||
			(isset($this->JSSrcs) && count($this->JSSrcs) > 0)) {

			$htmlTxt = "";

			foreach($this->CSSLnks as $href)
				$htmlTxt .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"${href}\">";

			foreach($this->JSSrcs as $src)
				$htmlTxt .= "<script type=\"text/javascript\" src=\"${src}\"></script>";

			$this->setHTMLHead($htmlTxt, $append);
		}
	}

	public function RenderSimple($options = array(), $args = array(), $csslnks = array(), $jssrcs = array()) {
		if(isset($csslnks) && count($csslnks) > 0 && isset($jssrcs) && count($jssrcs) > 0)
			$this->BuildHead(false);

		$toRender = "";
		$HTMLHEAD = $this->getHTMLHead();
		$PAGETITLE = $args["PTITLE"];
		$pth = $this->AssemblePath($args["MODEL"], $args["MODULE"], $args["VIEW"], false);
		ob_start();
		include("App/Views/htmlheadmeta.php");

		if($options["HEAD"])
			include("App/Views/mainheader.php");

		if($options["MENU"])
			include("App/Views/mainmenu.php");

		if($this->ValidPath($pth))
			include($pth);

		if($options["FOOT"])
			include("App/Views/mainfooter.php");

		include("App/Views/htmlfooterclose.php");

		$toRender = ob_get_contents();
		ob_end_clean();

		echo $toRender;
	}

	private function LoadViewFile($pth = null, $ONCE = false) {
		$PAGETITLE = $this->getPgTitle();
		$HTMLHEAD = $this->getHTMLHead();
		if($ONCE)
			include_once($pth);
		else
			include($pth);
	}

	public function ResponseInit() {
		$this->BuildHead(true);
		if(!isset($this->PgTitle) || ASPM::IsEmptyNullOrWhiteSpace($this->PgTitle))
			$this->setPgTitle();

		$this->setIsPrepared();
		$this->setIsProcessed();
	}

	public function ResponsePrep() {
		if(!($this->getHasTpl())) {
			$this->setProcssr();
			$this->setTplData();
			$this->setIsProcessed(true);
			$this->ResponseAssemble(true);
		} else {
			if(!isset($this->DisplayDat) || count($this->DisplayDat) < 1)
				return false;

			$tmpData = $this->ReadTpl($this->TplPath);
			if(!(ASPM::IsEmptyNullOrWhiteSpace($tmpData)))
				$this->setTplData($tmpData);
		}
		$this->setIsPrepared(true);
		return true;
	}

	public function RelayTemplateData() {
		$tmpData = $this->getTplData();
		if(!(ASPM::IsEmptyNullOrWhiteSpace($tmpData)))
			$this->Procssr->setContentToProcess($tmpData);
	}

	public function ConfigProcessor() {
		$this->RelayTemplateData();
		$this->Procssr->setTemplateVars($this->ExtractVars);

		$this->Procssr->InterpTemplateData();
	}


	public function ProcessViewData() {
		$bodyContent = "";
		foreach($this->DisplayDat as $Entity => $DATASET) {

			foreach($DATASET as $PROPERTY => $VALUE) {
				$this->setExtractVars(null, $PROPERTY, $VALUE);
			}

			$this->ConfigProcessor();
			$bodyContent = $this->Procssr->FilterVars();
			if(!(ASPM::IsEmptyNullOrWhiteSpace($bodyContent)))
				$this->setBodyContent($bodyContent, true);
		}
		$this->setIsProcessed(true);
	}


	public function ResponseAssemble($bypassTplData = false) {

		$ResponseToRender = "";
		ob_start();

		$this->LoadViewFile("App/Views/htmlheadmeta.php", false);

		if($this->Opts["HEAD"])
			$this->LoadViewFile("App/Views/mainheader.php", false);

		if($this->Opts["MENU"])
			$this->LoadViewFile("App/Views/mainmenu.php", false);

		if(!$bypassTplData)
			echo $this->getBodyContent();

		if($this->getHasView())
			$this->LoadViewFile($this->VwPath, false);

		if($this->Opts["FOOT"])
			$this->LoadViewFile("App/Views/mainfooter.php", false);

		$this->LoadViewFile("App/Views/htmlfooterclose.php", false);

		$ResponseToRender = ob_get_contents();
		ob_end_clean();

		return $ResponseToRender;
	}

}
?>
