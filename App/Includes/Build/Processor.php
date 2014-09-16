<?php
/***********************************************************
 * Processor
 * Sets up a utility to 'compile' and process data between
 * a view object, templates, etc.. Acts like a simple PHP
 * templating engine.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ***********************************************************/
class Processor {

	public $ContentToProcess;
	public $ContentToRender;
	protected $TemplateVars = array();

	public function Processor($content2proc = null) {
		if(isset($content2proc) && !(ASPM::IsEmptyNullOrWhiteSpace($content2proc)))
			$this->ContentToProcess = $content2proc;
		else
			$this->ContentToProcess = "";

		$this->ContentToRender = "";
		$this->TemplateVars = array();
	}


	public function getContentToProcess() { return $this->ContentToProcess; }
	public function getContentToRender() { return $this->ContentToRender; }
	public function getTemplateVars() { return $this->TemplateVars; }

	public function setContentToProcess($str = "") {
		$this->ContentToProcess = $str;
	}

	private function setContentToRender($str = "") {
		$this->ContentToRender = $str;
	}

	public function setTemplateVars($vars = array()) {
		$this->TemplateVars = $vars;
	}

	public function InterpTemplateData() {
		$beforeStr = $this->getContentToProcess();
		if(ASPM::IsEmptyNullOrWhiteSpace($beforeStr))
			return null;

		$afterStr = preg_replace("~(%%([A-Za-z0-9]*)%%)~", "$2", $beforeStr);
		$this->setContentToProcess($afterStr);

		return true;
	}

	public function FilterVars() {
		$tmpStr = $this->ContentToProcess;
		foreach($this->TemplateVars as $VARNAME => $REPLACEVAL) {
			$tmpStr = str_replace($VARNAME, $REPLACEVAL, $tmpStr);
		}

		$this->setContentToRender($tmpStr);
		return $tmpStr;

	}


}
?>
