<?php
/*************************************************
 * ClassExtensionMapper.php
 * Maps extensions for classes
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
class ClassExtensionMapper {

	public static $MODLIST = array("Home", "TimeSheets", "DiscussionBoard", "Logs", "Messages", "Profiles", "Users", "NetWork");
	public static $MAPPER = array(

		"ModelModule"	=>	array(

			"Home"	=>	array(0 => "UserLogin", 1 => ""),
			"TimeSheets"	=>	array(0 => "Task", 1 => "Project", 2 => "TimeSheet"),
		),

		"ModelController"	=>	array(

			"Home"	=>	"HomeController",
			"TimeSheets"	=>	"TimeSheetController",
			"Users"	=>	"UserController",
			"Calendar"	=>	"CalendarController",
			"Tools"	=>	"ToolController",
			"Profiles"	=>	"ProfileController",
			"Messages"	=>	"MessageController",
			"Feed"	=>	"CompanyFeedController",
			"Documents"	=>	"DocumentController",
			"NetWork"	=>	"NetWorkController",
		),

	);

	public function ClassExtensionMapper() {
	}


	public function ModelExists($mod = null) {
		if(gettype($mod) != "string") return false;
		return in_array($mod, self::$MODLIST);
	}

	public function MapControllerName($model) {
		if(!isset($model) || empty($model)) return null;
		$result = ClassExtensionMapper::$MAPPER["ModelController"][$model];
		if(strpos($result, "Controller") === false) return false;
		return $result;
	}

	public function MapModuleList($model) {
		if(!isset($model) || empty($model)) return null;
		$result = ClassExtensionMapper::$MAPPER["ModelModule"][$model];
		if(gettype($result) != "array") return false;
		return $result;
	}

	public function CanDoAction($action = null, $controller = null) {
		return method_exists($controller, $action);
	}
}
?>
