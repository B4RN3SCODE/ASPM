<?php
#NOTE: I may not even bother keeping this class - other than the DropDown Builder at the end

/**************************************************************
 * ElementBuilder :
 * Creates very basic HTML elements to render
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes
 * @contact			tbarnes@arbsol.com
 ************************************************/
class ElementBuilder {


	public static function BuildDropDownList($servAdpt = null, array $data = array(), $Method = null,
													array $MethodParams = array(), $id = null, $name = null, $class = null,
													$style = null, $defaultSel = true, array $otherAttrb = array()) {

		if((!isset($id) && !isset($name)) || (empty($id) && empty($name))) return null;

		if((!isset($servAdpt) || $servAdpt == null) && (!isset($data) || $data == null || count($data) < 1)) {

			if(!isset($Method) || $Method == null || count($MethodParams) == 0 || empty($MethodParams)) return null;

			if($GLOBALS["App"]["ServiceAdapter"] == null || strpos($GLOBALS["App"]["ServiceAdapter"], $GLOBALS["App"]["Controller"]->getModule()) === false) {

				$tmp = $GLOBALS["App"]["Controller"]->getModule() . "ServiceAdapter";
				$_serviceAdpt = new $tmp($GLOBALS["App"]["DataBase"]["DBConn"]);

			} else {

				$_serviceAdpt = $GLOBALS["App"]["ServiceAdapter"];

				if(method_exists($_serviceAdpt, $Method)) {

					if(count($MethodParams) == 1) $data = $_serviceAdpt->$Method($MethodParams[0]);
					else $data = $_serviceAdpt->$Method($MethodParams[0], $MethodParams[1]);
				}
			}
		}

		$ddl = "<select id=\"${id}\" name=\"${name}\" class=\"${class}\"";
		if(isset($style) && !empty($style)) $ddl .= " style=\"${style}\"";
		if(isset($otherAttrb) && count($otherAttrb) > 0) {
			foreach($otherAttrb as $atr => $val) {
				$ddl .= " ${atr}=\"${val}\"";
			}
		}
		$ddl .= ">";
		if($defaultSel) $ddl .= "<option value=\"0\" selected>--select--</option>";

		foreach($data as $key => $val) {
			$iID = (array_key_exists("Id", $val)) ? $val["Id"] : $val["UID"];
			if(array_key_exists("Name", $val)) $iTxt = $val["Name"];
			elseif(array_key_exists("Title", $val)) $iTxt = $val["Title"];
			elseif(array_key_exists("UName", $val)) $iTxt = $val["UName"];
			elseif(array_key_exists("Type", $val)) $iTxt = $val["Type"];
			else {
				foreach($val as $k => $v) {
					if(strlen($v) > 4) $iTxt = $v;
				}
			}
			if(strlen($iTxt) < 2) continue;
			elseif(strlen($iTxt) > 13) $iTxt = substr($iTxt, 0, 8) . "...";
			$ddl .= "<option value=\"${iID}\" data-option=" . md5("$iID$iTxt") . ">${iTxt}</option>";
		}

		$ddl .= "</select>";
		return $ddl;
	}


}
?>
