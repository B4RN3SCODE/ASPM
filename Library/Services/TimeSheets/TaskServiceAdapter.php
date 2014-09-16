<?php
//namespace Services\TimeSheets;
require_once("TaskServiceAdapterUI.php");
#region comment
/*************************************************
 * TaskServiceAdapter
 * Using the Namespace's interface, it provides
 * the logic to the outlined methods, & ultimately
 * defining the way an object will be handled to &
 * from database entity to applicaiton model
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class TaskServiceAdapter implements TaskServiceAdapterUI {

#region Vars::PROPS

	private $_dbAdapt;
	private $dbTbl;

#endregion

#region CONSTRUCTOR

	public function TaskServiceAdapter(DBConn $db = null) {
		$this->_dbAdapt = (isset($db) && $db != null) ? $db : new DBConn();
		if(!$this->_dbAdapt->getIsLinked()) $this->_dbAdapt->Link();
		$this->dbTbl = "Task";
		$this->_dbAdapt->setTbl($this->dbTbl);
	}

#endregion


#region METHODS::QUERY::SELECT::FILTER
	public function GetAllByIncomplete() {
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("IsComplete = " => "0"));
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}

	public function GetAllByComplete() {
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("IsComplete = " => "1"));
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}

	public function GetTaskById($id) {
		if(gettype($id) != "integer" || $id < 0) return null;
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("Id = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}
	public function GetAllByProject($id) {
		if(gettype($id) != "integer" || $id < 0) return null;
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("ProjectID = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}

	public function GetAllByEmployee($id) {
		if(gettype($id) != "integer" || $id < 0) return null;
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("UserID = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}

#endregion

#region METHODS::QUERY::INSERT

	public function InsertNewTask(Task $task = null) {
		if($task == null || !($task instanceof Task)) return null;
		$props = array(
			"UserID,"	=>	$task->getUserID(),
			"ProjectID,"	=>	$task->getProjectID(),
			"Title,"	=>	$task->getTitle(),
			"Date,"	=>	$task->getDate(),
			"TargetHours,"	=>	$task->getTargetHours(),
			"ActualHours,"	=>	$task->getActualHours(),
			"Description,"	=>	$task->getDescription(),
			"IsComplete"	=>	$task->getIsComplete()
		);
		foreach($props as $k => $v) {
			if($v != null) {
				if($k == "IsComplete") {
					$_props[$k] = "$v";
				} else {
					if(gettype($v) == "string"){
						$_props[$k] = "'" . $v . "', ";
					} else {
						$_props[$k] = "$v, ";
					}
				}
			}
		}
		$this->_dbAdapt->IStatement($this->dbTbl, $_props);
		$tmp = $this->_dbAdapt->getLnk();
		$tmp->query($this->_dbAdapt->getQry());
		unset($tmp);
		return true;
	}

#endregion

#region METHODS::QUERY::UPDATE

	public function EditTaskTargetHours($id, $hours) {
		if($id == null || $id < 1 || $hours == null || $hours < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET TargetHours = ? WHERE Id = ?");
		$stmt->bind_param("di", $hours, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
	public function EditTaskActualHours($id, $hours) {
		if($id == null || $id < 1 || $hours == null || $hours < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET ActualHours = ? WHERE Id = ?");
		$stmt->bind_param("di", $hours, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
	public function EditTaskCompleteStatus($id, $bool) {
		if($id == null || $id < 1 || $bool == null || $bool < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET IsComplete = ? WHERE Id = ?");
		$stmt->bind_param("ii", $bool, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
	public function EditTaskProjectID($id, $pid) {
		if($id == null || $id < 1 || $pid == null || $pid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET ProjectID = ? WHERE Id = ?");
		$stmt->bind_param("ii", $pid, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
	public function EditTaskTitle($id, $t) {
		if($id == null || $id < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET Title = ? WHERE Id = ?");
		$stmt->bind_param("si", $t, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
	public function EditTaskDescription($id, $d) {
		if($id == null || $id < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET Description = ? WHERE Id = ?");
		$stmt->bind_param("si", $d, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
	public function EditTaskDate($id, $date) {
		if($id == null || $id < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET Date = ? WHERE Id = ?");
		$stmt->bind_param("si", $date, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditTaskUserID($id, $uid) {
		if($id == null || $id < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Task SET UserID = ? WHERE Id = ?");
		$stmt->bind_param("ii", $uid, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}
#endregion

#region METHODS::QUERY::DROP

	public function RemoveTask($taskID) {
		if($taskID == null) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare("DELETE FROM Task WHERE Id = ?");
		$tmpStmt->bind_param("i", $taskID);
		$tmpStmt->execute();
		$tmpStmt->close();
		return true;
	}

#endregion


}
?>
