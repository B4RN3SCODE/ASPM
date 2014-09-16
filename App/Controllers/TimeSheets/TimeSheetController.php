<?php
include_once("Library/Services/TimeSheets/ProjectServiceAdapter.php");
include_once("Library/Services/TimeSheets/TaskServiceAdapter.php");
include_once("Library/Services/TimeSheets/TimeSheetServiceAdapter.php");
/********************************************
 * Controller for the TimeSheet model and
 * related modules.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
class TimeSheetController extends ASPMController {

	// appropriate adapters
	// see Library/Services/[Model Name]/...
	protected $_projectServiceAdpt;
	protected $_taskServiceAdpt;
	protected $_timesheetServiceAdpt;

	/*******************************
	 * Setup
	 * This instantiates a new adapter
	 * object or objects and updates
	 * the global variable accordingly
	 *********************************/
	protected function SetUpController() {
		$this->_projectServiceAdpt = new ProjectServiceAdapter($GLOBALS["App"]["DataBase"]["DBConn"]);
		$this->_taskServiceAdpt = new TaskServiceAdapter($GLOBALS["App"]["DataBase"]["DBConn"]);
		$this->_timesheetServiceAdpt = new TimeSheetServiceAdapter($GLOBALS["App"]["DataBase"]["DBConn"]);
	}

	public function index() {
		$this->ListTimeSheets();
		return null;
	}


	public function ListTimeSheets() {
		//$tmp = BasicAuthService::EntryPointCheck();
		$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
		$render["TimeSheet"] = $this->_timesheetServiceAdpt->GetTimeSheetByUserID($_SESSION["User"]->getUID());
		include_once("App/Models/TimeSheets/TimeSheet/index.php");
	}


	public function LoadTimeSheet($forEdit = false) {
		//$tmp = BasicAuthService::EntryPointCheck();
		$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
		if(!isset($_REQUEST["TimeSheetId"]) || intval($_REQUEST["TimeSheetId"]) < 1) {
			$this->index();
			return null;
		} else {
			$queryId = (int)$_REQUEST["TimeSheetId"];
			$render["TimeSheet"] = $this->_timesheetServiceAdpt->GetTimeSheetById($queryId);
			$render["Project"] = $this->_projectServiceAdpt->GetProjectByTimeSheet($queryId);
			if($render["TimeSheet"] == null) {
				unset($render);
				$this->index();
				return null;
			} else include_once("App/Models/TimeSheets/TimeSheet/index.php");
		}
	}

	public function CreateNewTimeSheet() {
		//$tmp = BasicAuthService::EntryPointCheck();
		include_once("App/Modules/TimeSheets/TimeSheet/Views/view.create.php");
	}


	public function SaveNewTimeSheet() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_REQUEST["BillingRate"]) || !isset($_REQUEST["PayWeekEnding"]) || !isset($_REQUEST["TotalTargetHours"]) ||
			!isset($_REQUEST["TotalActualHours"]) || strlen($_REQUEST["BillingRate"]) < 1 || strlen($_REQUEST["PayWeekEnding"]) < 5 ||
			strlen($_REQUEST["TotalActualHours"]) < 1) {

			$GLOBALS["App"]["Error"] = "Insufficient TimeSheet Data.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			return null;
		}

		$tmpTimeSheet = new TimeSheet(null, $_SESSION["User"]->getUID(), (float)$_REQUEST["BillingRate"], $_REQUEST["PayWeekEnding"], (float)$_REQUEST["TotalTargetHours"], (float)$_REQUEST["TotalActualHours"], false);
		$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
		$inserted = $this->_timesheetServiceAdpt->InsertNewTimeSheet($tmpTimeSheet);

		if($inserted === false) {
			$GLOBALS["App"]["Error"] = "Could not insert new TimeSheet. Unknown data-related Error.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
		} else return $inserted;
	}


	public function EditTimeSheet() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_REQUEST["TimeSheetId"]) || intval($_REQUEST["TimeSheetId"]) < 1) {
			$this->index();
			return null;
		} else {
			$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
			$_REQUEST["TimeSheetId"] = ($_REQUEST["TimeSheetId"] != "") ? $_REQUEST["TimeSheetId"] : "0";
			$this->LoadTimeSheet(true);
		}
	}

	public function SaveTimeSheetEdits() {
		//$tmp = BasicAuthService::EntryPointCheck();

		$tmpTimeSheet = new TimeSheet((int)$_REQUEST["TimeSheetId"], $_SESSION["User"]->getUID(), (float)$_REQUEST["BillingRate"], $_REQUEST["PayWeekEnding"], (float)$_REQUEST["TotalTargetHours"], (float)$_REQUEST["TotalActualHours"], false);
		$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
		$tmpSetup = array(
					"EditUserID"				=>	array( 0 => $tmpTimeSheet->getId(), 1 => $tmpTimeSheet->getUserID() ),
					"EditBillingRate"			=>	array( 0 => $tmpTimeSheet->getId(), 1 => $tmpTimeSheet->getBillingRate() ),
					"EditPayWeekEnding"			=>	array( 0 => $tmpTimeSheet->getId(), 1 => $tmpTimeSheet->getPayWeekEnding() ),
					"EditTotalTargetHours"		=>	array( 0 => $tmpTimeSheet->getId(), 1 => $tmpTimeSheet->getTotalTargetHours() ),
					"EditTotalActualHOurs"		=>	array( 0 => $tmpTimeSheet->getId(), 1 => $tmpTimeSheet->getTotalActualHours() ),
					"EditSubmitState"			=>	array( 0 => $tmpTimeSheet->getId(), 1 => false )
		);

		$failures = array();
		$GLOBALS["App"]["Error"] = "";
		foreach($tmpSetup as $updateMethod => $parameters) {
			$success = $this->_timesheetServiceAdpt->$updateMethod($parameters[0], $parameters[1]);
			if(!$success) {

				$stringd = "++ ${updateMethod} failed with parameters: ${parameters[0]} and ${parameters[1]} ++";
				$GLOBALS["App"]["Error"] .= $stringd;
				$failures[] = $stringd;
			}
		}

		$this->PostEditCallBack(((count($failures) == 0) ? null : $failures));

	}

	public function DeleteTimeSheet($id = null) {
		//$tmp = BasicAuthService::EntryPointCheck();
		if((!isset($_REQUEST["TimeSheetId"]) || intval($_REQUEST["TimeSheetId"]) < 1) && (!isset($id) || $id == null)) {
			$GLOBALS["App"]["Error"] = "Not enough data provided to delete project";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			return null;
		}
		$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
		$removed = false;
		if(isset($id) && $id != null && $id > 0) {
			$removed = $this->_timesheetServiceAdpt->RemoveTimeSheet($id);
		} else {
			$removed = $this->_timesheetServiceAdpt->RemoveTimeSheet((int)$_REQUEST["TimeSheetId"]);
		}

		return $removed;
	}


	public function SubmitTimeSheet() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_REQUEST["TimeSheetId"]) || intval($_REQUEST["TimeSheetId"]) < 1) {
			$GLOBALS["App"]["Error"] = "Could not locate TimeSheet to update.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			return null;
		}
		$GLOBALS["App"]["ServiceAdapter"] = $this->_timesheetServiceAdpt;
		$updated = $this->_timesheetServiceAdpt->EditSubmitState((int)$_REQUEST["TimeSheetId"], 1);

		if($updated === false) {
			$GLOBALS["App"]["Error"] = "Cannot submit TimeSheet. Data is truncated or incomplete.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
		} else return $updated;

	}


	public function ListProjects() {
		//$tmp = BasicAuthService::EntryPointCheck();
		$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
		$render["Project"] = $this->_projectServiceAdpt->GetProjectByUserID($_SESSION["User"]->getUID());
		$this->Processor->setDefaults(array("Head" => true, "Menu" => true, "Foot" => false));
		$this->Processor->setPageAdditions("http://localhost:8081/ArborSolutionsProjectManagement/public/style/spec/project.css", false, "CSS");
		$this->Processor->SetUpPaths($this->getModel(), $this->getModule(), "list");
		$this->Processor->setDisplayData(array("Project" => $render["Project"]));
		$this->Processor->RenderView();
	}


	public function LoadProject() {
		//$tmp = BasicAuthService::EntryPointCheck();
		$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
		if(!isset($_REQUEST["ProjectId"]) || intval($_REQUEST["ProjectId"]) < 1) {
			$this->ListProjects();
			return null;
		} else {
			$queryId = (int)$_REQUEST["ProjectId"];
			$render["Project"] = $this->_projectServiceAdpt->GetProjectById($queryId);
			$render["Task"] = $this->_taskServiceAdpt->GetAllByProject($queryId);
			if($render["Project"] == null || $render["Task"] == null) {
				unset($render);
				$this->index();
				return null;
			} else include_once("App/Models/TimeSheets/Project/index.php");
		}
	}

	public function CreateNewProject() {
		//$tmp = BasicAuthService::EntryPointCheck();
		include_once("App/Modules/TimeSheets/Project/Views/view.create.php");
	}


	public function AddNewProjectToTimeSheet() {
		if(!isset($_REQUEST["ProjectId"]) || !isset($_REQUEST["TimeSheetId"]) ||
			intval($_REQUEST["ProjectId"]) < 1 || intval($_REQUEST["TimeSheetId"]) < 1) {

			$GLOBALS["App"]["Error"] = "Cannot Add Project to TimeSheet!";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			return null;
		}
		$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
		return $this->_projectServiceAdpt->LinkProjectToTimeSheet((int)$_REQUEST["ProjectId"], (int)$_REQUEST["TimeSheetId"]);
	}

	public function EditProject() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_REQUEST["ProjectId"]) || intval($_REQUEST["ProjectId"]) < 1) {
			$this->index();
			return null;
		} else {
			$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
			$_REQUEST["ProjectId"] = ($_REQUEST["ProjectId"] != "") ? $_REQUEST["ProjectId"] : "0";
			$this->LoadProject(true);
		}
	}

	public function SaveProjectEdits() {
		//$tmp = BasicAuthService::EntryPointCheck();

		$tmpProject = new Project((int)$_REQUEST["ProjectId"], $_SESSION["User"]->getUID(), (int)$_REQUEST["ClientID"], $_REQUEST["Title"], $_REQUEST["Description"], (float)$_REQUEST["TotalWorkHours"], (float)$_REQUEST["TotalEstimateHours"], $_REQUEST["DateCreated"]);
		$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
		$tmpSetup = array(
					"EditProjectUserId"					=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getUserID() ),
					"EditProjectClientId"				=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getClientID() ),
					"EditProjectTitle"					=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getTitle() ),
					"EditProjectDescription"			=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getDescription() ),
					"EditProjectTotalWorkHours"			=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getTotalWorkHours() ),
					"EditProjectTotalEstimateHours"		=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getTotalEstimateHours() ),
					"EditProjectDateCreated"			=>	array( 0 => $tmpProject->getId(), 1 => $tmpProject->getDateCreated() )
		);

		$failures = array();
		$GLOBALS["App"]["Error"] = "";
		foreach($tmpSetup as $updateMethod => $parameters) {
			$success = $this->_projectServiceAdpt->$updateMethod($parameters[0], $parameters[1]);
			if(!$success) {

				$stringd = "++ $updateMethod failed with parameters: $parameters[0] and $parameters[1] ++";
				$GLOBALS["App"]["Error"] .= "++ $stringd ++";
				$failures[] = $stringd;
			}
		}

		$this->PostEditCallBack(((count($failures) == 0) ? null : $failures));

	}

	public function DeleteProject($id = null) {
		//$tmp = BasicAuthService::EntryPointCheck();
		if((!isset($_REQUEST["ProjectId"]) || intval($_REQUEST["ProjectId"]) < 1) && (!isset($id) || $id == null)) {
			$GLOBALS["App"]["Error"] = "Not enough data provided to delete project";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			return null;
		}
		$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
		$removed = false;
		if(isset($id) && $id != null && $id > 0) {
			$removed = $this->_projectServiceAdpt->RemoveProject($id);
		} else {
			$removed = $this->_projectServiceAdpt->RemoveProject((int)$_REQUEST["ProjectId"]);
		}

		return $removed;
	}


	public function SubmitNewProject() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_REQUEST["ClientID"]) || !isset($_REQUEST["Title"]) || !isset($_REQUEST["Description"]) || !isset($_REQUEST["TotalWorkHours"]) ||
			!isset($_REQUEST["TotalEstimateHours"]) || !isset($_REQUEST["DateCreated"]) || strlen($_REQUEST["Title"]) < 3 || strlen($_REQUEST["Description"]) < 8 ||
			strlen($_REQUEST["TotalWorkHours"]) < 1) {

			$GLOBALS["App"]["Error"] = "Insufficient Project Data: Cannot submit.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			return null;
		}

		$tmpProject = new Project(null, $_SESSION["User"]->getUID(), (int)$_REQUEST["ClientID"], $_REQUEST["Title"], $_REQUEST["Description"], (float)$_REQUEST["TotalWorkHours"], (float)$_REQUEST["TotalEstimateHours"], ((strlen($_REQUEST["DateCreated"]) > 5) ? $_REQUEST["DateCreated"] : date("Y-m-d H:i:s")));
		$GLOBALS["App"]["ServiceAdapter"] = $this->_projectServiceAdpt;
		$inserted = $this->_projectServiceAdpt->InsertNewProject($tmpProject);

		if($inserted === false) {
			$GLOBALS["App"]["Error"] = "Refused to submit new project. Insufficient data";
			$this->SubmitErrorRedirect();
		} else return $inserted;
	}


	public function AddTaskToProject() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_SESSION["User"]) || $_SESSION["User"]->getUID() < 1) {
			$GLOBALS["App"]["Error"] = "Error with log-in state: User Not Logged In.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
		}

		if(!isset($_REQUEST["ProjectID"]) || !isset($_REQUEST["Title"]) || !isset($_REQUEST["Date"]) ||
				!isset($_REQUEST["TargetHours"]) ||  !isset($_REQUEST["ActualHours"]) || !isset($_REQUEST["Description"]) ||
				!isset($_REQUEST["IsComplete"])) {

			$GLOBALS["App"]["Error"] = "Insufficient Task data: Cannot complete submission.";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
		}

		$tmpTask = (intval($_REQUEST["ProjectID"]) > 0 && strlen($_REQUEST["Title"]) > 1 &&
					strlen($_REQUEST["Date"]) > 1 && ((float)$_REQUEST["TargetHours"] > 0) &&
					((float)$_REQUEST["ActualHours"] > 0) && strlen($_REQUEST["Description"]) > 1 &&
					($_REQUEST["IsComplete"] == "true" || $_REQUEST["IsComplete"] == "false")) ? new Task(null, $_SESSION["User"]->getUID(), intval($_REQUEST["ProjectID"]), $_REQUEST["Title"], $_REQUEST["Date"], (float)$_REQUEST["TargetHours"], (float)$_REQUEST["ActualHours"], $_REQUEST["Description"], ($_REQUEST["IsComplete"] == "true") ? true : false) : false;

		if($tmpTask === false) $this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
		else {
			$GLOBALS["App"]["ServiceAdapter"] = $this->_taskServiceAdpt;
			$inserted = $this->_taskServiceAdpt->InsertNewTask($tmpTask);
			if($inserted == true) {
				$_REQUEST["ProjectId"] = (string)$tmpTask->getProjectID();
				$this->LoadProject();
			} else {
				$GLOBALS["App"]["Error"] = "Problem saving new task for Project with ID [ " . (($tmpTask->getProjectID() > 0) ? $tmpTask->getProjectID() : "GET_ID_ERR") . " ]";
				$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
			}
		}
	}


	public function RemoveTaskFromProject() {
		//$tmp = BasicAuthService::EntryPointCheck();
		if(!isset($_REQUEST["TaskId"]) || intval($_REQUEST["TaskId"]) < 1) {
			$GLOBALS["App"]["Error"] = "Problem removing task. ID said to be [ " . $_REQUEST["TaskId"] . " ]";
			$this->SubmitErrorRedirect($_SESSION["User"]->getUID(), date("Y-m-d H:i:s"), $this->getModel(), $this->getModule(), $this->getAction(), $GLOBALS["App"]["Error"]);
		}
		$GLOBALS["App"]["ServiceAdapter"] = $this->_taskServiceAdpt;
		return $this->_taskServiceAdpt->RemoveTask((int)$_REQUEST["TaskId"]);
	}

}
?>
