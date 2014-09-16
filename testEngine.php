<?php
include_once("testProc.php");
$a = array(
	"var1" => "THIS IS A SAMPLE",
	"var2" => "yet another string",
	"var3" => "........done"
);
$str = "<html><head></head><body>";
$o = new tst();
$o->setS(file_get_contents("tmp.tpl"));
ob_start();
foreach($a as $indx => $s) {
	$o->setVs($indx, $s);
}
$str .= $o->getS();
$str .= ob_get_clean();
$str .= "</body></html>";
echo $str;
?>
