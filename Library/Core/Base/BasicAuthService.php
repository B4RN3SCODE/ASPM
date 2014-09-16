<?php
/***********************************************************
 * BasicAuthService
 * Basic measures for makeing sure the user can view the page
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ***********************************************************/
class BasicAuthService {

	public static function EntryPointCheck() {
		if(!isset($_SESSION["ASPMGO"]) || $_SESSION["ASPMGO"] == false) {
			return "Must log in and start a session";
		}
		if(!isset($_SESSION["User"]) || !isset($_SESSION["LIStamp"]) || !isset($_SESSION["DataToken"]) || $_SESSION["User"] == null || $_SESSION["User"]->getUID() < 1 || $_SESSION["LIStamp"] == null || $_SESSION["LIStamp"] < 1) {
			return "Cannot validate user data... try to log in again.";
		}
		if(!isset($GLOBALS["App"]["User"]) || !isset($GLOBALS["InitialSec"]["_dt"]) || $GLOBALS["App"]["User"]->getUID() < 1 || strlen($GLOBALS["InitialSec"]["_dt"]) < 1) {
			return "User does not seem to be logged in.";
		}
		return true;
	}
}
?>
