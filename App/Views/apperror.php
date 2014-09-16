<?php
if(!isset($APPERRMSG) || empty($APPERRMSG)) $APPERRMSG = "Could not fetch error message....";
echo <<< ERRPG
<html><head><title>Application Error</title></head><body><div align="center" style="margin-top: 10%"><span style="color: red; font-size: 1.3em; font-weight: 600; text-decoartion: underline;">Application Error</span><br /><span style="color: #6F2223;">${APPERRMSG}</span><div><a href="http://localhost:8081/ArborSolutionsProjectManagement/index.php">Try this again</a></div></div></body></html>
ERRPG;
?>
