<?php
/******************************************
 * Only Entry point for the application.
 * This will inherit and include everything
 * else that is needed.
 *****************************************/

include_once("Library/Core/Base/ASPM.php");
$app = new ASPM();

$app->SessionActivate();
$app->Boot();
?>
