<?php
/**************
 * THIS INDEX PG
 * IS FOR TESTING
 ****************/
include("Library/Core/Base/ASPM.php");
//include("Library/Core/Base/Viewer.php");

$rendData = array(
	array("FirstName" => "Tyler", "LastName" => "Barnes", "NickName" => "DontHaveOne"),
	array("FirstName" => "Sarah", "LastName" => "Weemhoff", "NickName" => "SarahSoda")
);
$v = new Viewer(null, $rendData, null, null, "This is a TEST", "Home", "", null, "tmp", false, false);
$v->ResponseInit();
$v->ResponsePrep();
$v->ConfigProcessor();
$v->ProcessViewData();


$a = $v->ResponseAssemble(false);
echo $a;



//var_dump(implode("/", explode("\\", __FILE__)));

//echo "<html><head></head><body><pre>";

//include_once("Library/Core/Base/Viewer.php");
//$obj = new Viewer(array(), null, false, null, false, array(), "TESTING PAGE TITLE", array());
//var_dump($obj);
//$obj->RenderAll();

//echo "</pre></body></html>";
//include_once("Library/Configuration/base.config.php");
//include_once("Library/Core/Data/DBConn.php");
//include_once("App/Includes/Build/Processor.php");
//$a = new Processor();
//$a->setTemplatedObject(new DBConn());
//$yesno = $a->PreProcess();
//echo "<br /><br />";
//var_dump($a);
//echo "<br /><br />";
//echo ($yesno) ? "DID COMPLETE PROC" : "DID NOT COMPLETE PROC";
//echo "<br /><br />";
//$forms = array(
	//"VAR"	=>	"~(%%([A-Za-z0-9]*)%%)~",
	//"PROP"	=>	"~(%\{%([A-Za-z0-9]*)%\}%)~",
	//"MTHD"	=>	"~(%\[\[%([A-Za-z0-9]*)%\]\]%)~",
//);

//$str = "<html><head></head><body>%%Name%%<div>%{%UserName%}%<span></span>%[[%GetSome%]]%</div></body></html><html><head></head><body>%%Name%%<div>%{%UserName%}%<span></span>%[[%GetSome%]]%</div></body></html>";
//preg_match($forms["VAR"], $str, $m);
//$str = preg_replace("~(\[\[([A-za-z0-9]*)\]\:\\$([A-Za-z0-9]*)\])~", "$2::$3", $str);
//echo $str;

//echo "</pre></body></html>";


//include_once("Library/Services/TimeSheets/ProjectServiceAdapter.php");
//$db = new DBConn();
//$db->Link();
//$adapt = new ProjectServiceAdapter($db);
//$rend["Project"] = $adapt->GetProjectByUserID(2);
//foreach($rend["Project"] as $obj => $props) {
	//echo $props["Title"] . "<br /><br />";
//}


//include_once("Library/Configuration/base.config.php");
//include_once("Library/Core/Data/DBConn.php");
//$ob = new DBConn();
//$ob->Link();
//include_once("App/Includes/Build/ElementBuilder.php");
//require_once("Library/Services/Users/UserServiceAdapter.php");
//$GLOBALS["App"]["ServiceAdapter"] = new UserServiceAdapter($ob);
//$da = $GLOBALS["App"]["ServiceAdapter"]->GetAllUsers();
//$DROP = ElementBuilder::BuildDropDownList(null, $da, null, array(), 'ID', 'name', 'class', null, true, array());
//echo $DROP;

?>
