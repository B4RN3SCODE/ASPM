<?php
class tst {
	public $str;
	public $vs = array();
	function tst() {}
	function setVs($a, $b) {
		$this->vs[$a] = $b;
	}
	function setS($s) {
		$this->str = preg_replace("/(%%([A-Za-z0-9]*)%%)/", "$$2", $s);
	}
	function getS() {
		foreach($this->vs as $k => $i) {
			$this->str = str_replace($k, $i, $this->str);
		}
		return $this->str;
	}
}
?>

