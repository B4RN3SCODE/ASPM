<?php
include_once("App/Views/htmlheadmeta.php");

$viewer = $GLOBALS["App"]["Instance"]->Controller->getProcessor();

if($viewer->Defaults["Head"]) include_once("App/Views/mainheader.php");
else echo "<body><div class=\"header\"><table id=\"hdCols\"><tr><td class=\"hdOuter\"><a href=\"http://www.arbsol.com/\" target=\"_blank\"><img alt=\"Arbor Solutions, Inc. Logo\" src=\"http://localhost:8081/ArborSolutionsProjectManagement/public/img/common/logo.png\" id=\"hdLogo\" style=\"display:none;\"/></a></td><td class=\"hdMid\"><div class=\"hidden hdSpacer\"></div></td><td class=\"hdOuter\"><span class=\"hdText\"></span></td></tr></table><div class=\"hdStrip\"></div></div>";

if($viewer->Defaults["Menu"]) include_once("App/Views/mainheader.php");
else echo "<div class=\"navbar\" style=\"display:none;\"><div class=\"linkUser\"><img alt=\"Site Home\" src=\"http://localhost:8081/ArborSolutionsProjectManagement/public/img/ico/site.home.png\" id=\"homeIco\" /><a href=\"#\">Tyler Barnes</a></div><div class=\"userMenu\"><div class=\"menuList\"></div></div></div>";

if(isset($GLOBALS["App"]["View"]) && strlen($GLOBALS["App"]["View"]) > 1 && (file_exists($GLOBALS["App"]["View"])))
	include_once($GLOBALS["App"]["View"]);

if($viewer->Defaults["Foot"]) include_once("App/Views/mainfooter.php");
else echo "<div class=\"footMainContainer\" style=\"display:none;\"><div class=\"footContent\"><table><tr><td align=\"center\"><a href=\"http://www.arbsol.com\" target=\"_blank\"></a></td><td align=\"center\"><a href=\"#\"></a></td><td align=\"center\"><a href=\"#\"></a></td></tr><tr><td><span class=\"footParagraph\"></span></td><td><span class=\"footParagraph\"></span></td><td><span class=\"footParagraph\"></span></td></tr></table></div></div>";

echo "<input type=\"hidden\" value=\"GeneralLog:" . date("Ymd") . "\" /></body>";
echo <<< FOOTR


				<!---------------------------------------------------------------------
				-------      An       Arbor        Solutions      Production    -------
				-------    software  designed  to  help  your  business  and    -------
				-------                    organization grow                    -------
				---------------------------------------------------------------------!>


</html>
FOOTR;

?>
