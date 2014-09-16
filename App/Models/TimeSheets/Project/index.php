<?php
include_once("App/Views/htmlheadmeta.php");
include_once("App/Views/mainheader.php");
include_once("App/Views/mainmenu.php");
if(isset($GLOBALS["App"]["View"]) && strlen($GLOBALS["App"]["View"]) > 1 && file_exists($GLOBALS["App"]["View"]))
	include_once($GLOBALS["App"]["View"]);
else
	ASPM::Fold("Error Listing Projects");

include_once("App/Views/mainfooter.php");
echo <<< FOOTR
				<!---------------------------------------------------------------------
				-------      An       Arbor        Solutions      Production    -------
				-------    software  designed  to  help  your  business  and    -------
				-------                    organization grow                    -------
				---------------------------------------------------------------------!>
</html>
FOOTR;
?>
