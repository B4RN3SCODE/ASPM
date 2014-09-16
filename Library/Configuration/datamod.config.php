<?php
/*************************************************
 * datamod.config.mod.php
 * This configuration file containes definitions
 * for global data and other more widely needed
 * array objects.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
$GLOBALS["App"] = array(
	"DataBase"	=>	array(
		"DBConn"	=>	null,
		"TimeActive"	=>	null,
		"TypeOfQuery"	=>	null,
		"TableAccessed"	=>	null,
		"NoteableParams"	=> null,
	),
	"History"	=>	array(
		"SendingModel"	=>	null,
		"SendingModule"	=>	null,
		"SendingAction"	=>	null,
	),
	"Intention"	=>	array(
		"TargetModel"	=>	null,
		"TargetModule"	=>	null,
		"TargetAction"	=>	null,
		"CallbackModel"	=>	null,
		"CallbackModule"	=>	null,
		"CallbackAction"	=>	null,
	),
	"Instance"	=>	null,
	"User"	=>	null,
	"Error"	=>	null,
	"PageTitle"	=>	null,
	"Model"	=>	null,
	"Module"	=>	null,
	"View"	=>	null,
	"Controller"	=>	null,
	"ServiceAdapter"	=>	null,
	"Builder"	=>	null,
);
$GLOBALS["InitialSec"] = array(
	"_sk"	=>	null,
	"_dt"	=>	null,
	"AdmCd"	=>	null,
);
$GLOBALS["DocTypes"] = array(
	"pdf"	=>	"application/pdf",
	"avi"	=>	"video/x-msvideo",
	"sh"	=>	"application/x-sh",
	"c"		=>	"text/x-c",
	"css"	=>	"text/css",
	"cvs"	=>	"text/csv",
	"flv"	=>	"video/x-flv",
	"java"	=>	"text/x-java-source,java",
	"js"	=>	"application/javascript",
	"json"	=>	"application/json",
	"jpg"	=>	"image/jpeg",
	"jpeg"	=>	"image/jpeg",
	"png"	=>	"image/png",
	"txt"	=>	"text/plain",
	"html"	=>	"text/html",
);

?>
