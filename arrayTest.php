<?php
include_once("Library/Configuration/datamod.config.php");
include_once("Library/Configuration/base.config.php");
include_once("Library/Core/Data/DBConn.php");
echo "<html><head></head><body><pre>";
$ob = new DBConn();
$ob = (array)$ob;
var_dump($ob);
echo "<br /><br />";
$ArrPlacer = array();
$ArrPlacer["keys"] = array();
$ArrPlacer["vals"] = array();
$tmpKeys = array_keys($ob);
foreach($tmpKeys as $keyName) {
	if(gettype($ob[$keyName]) == "array") continue;
	else $ArrPlacer["keys"][] = $keyName;
}
$tmpKeys = array();
foreach($ArrPlacer["keys"] as $kn) {
	$valued = $ob[$kn];
	if($valued === false) $valued = "false";
	elseif($valued == null) $valued = "null";
	elseif(gettype($valued) == "object" || gettype($valued) == "array") {
		unset($ArrPlacer["keys"][$kn]);
		continue;
	}
	$tmpKeys[] = $kn;
	$ArrPlacer["vals"][] = (string)$valued;
}
if(count($ArrPlacer["keys"]) == count($ArrPlacer["vals"]) || count($tmpKeys) == count($ArrPlacer["vals"])) $tmpKeys = array();
else {
	foreach($ArrPlacer["keys"] as $checkKey) {
		if($checkKey == "" || gettype($checkKey) == "array" ||
			gettype($checkKey) == "object" || gettype($ob[$checkKey]) == "array" ||
			gettype($ob[$checkKey]) == "object") unset($ArrPlacer["keys"][$checkKey]);
	}
}
foreach(array_keys($ob) as $verifKey) {
	if(!array_key_exists($verifKey, $ArrPlacer["keys"]) && !in_array($verifKey, $ArrPlacer["keys"])) {
		foreach(array_keys($ob[$verifKey]) as $subKey) {
			if(gettype($subKey) != "array" && gettype($ob[$verifKey][$subKey]) != "array" &&
				gettype($subKey) != "object" && gettype($ob[$verifKey][$subKey]) != "object") {
					$ArrPlacer["keys"][] = (string)$subKey;
					$ArrPlacer["vals"][] = (string)$ob[$verifKey][$subKey];
					$tmpKeys[] = (string)$subKey;
			}
		}
	}
}
//$NEWARR = array_combine($ArrPlacer["keys"], $ArrPlacer["vals"]);
var_dump($ArrPlacer["keys"]);
echo "<br /><br />";
var_dump($ArrPlacer["vals"]);
echo "</pre></body></html>";
?>
