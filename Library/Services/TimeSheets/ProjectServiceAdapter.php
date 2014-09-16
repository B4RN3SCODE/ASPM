<?php
//namespace Services\TimeSheets;
require_once("ProjectServiceAdapterUI.php");
/*************************************************
 * ProjectServiceAdapter
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
class ProjectServiceAdapter implements ProjectServiceAdapterUI {

	private $_dbAdapt;
	private $dbTbl;

	public function ProjectServiceAdapter(DBConn $db = null) {
		$this->_dbAdapt = (isset($db) && $db != null) ? $db : new DBConn();
		if(!$this->_dbAdapt->getIsLinked()) $this->_dbAdapt->Link();
		$this->dbTbl = "Project";
		$this->_dbAdapt->setTbl($this->dbTbl);
	}

	public function GetProjectById($id) {
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

	public function GetProjectByTimeSheet($id) {
		if(gettype($id) != "integer" || $id < 0) return null;
		$this->_dbAdapt->SStatement(array(0 => "*"), "TimeSheetProject", null, array("TSID = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}

	public function GetProjectByUserID($id) {
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

	public function InsertNewProject(Project $p = null) {
		if($p == null || !($p instanceof Project)) return null;
		$props = array(
			"UserID,"	=>	$p->getUserID(),
			"ClientID,"	=>	$p->getClientID(),
			"Title,"	=>	$p->getTitle(),
			"Description,"	=>	$p->getDescription(),
			"TotalWorkHours,"	=>	$p->getTotalWorkHours(),
			"TotalEstimateHours,"	=>	$p->getTotalEstimateHours(),
			"DateCreated"	=>	$p->getDateCreated()
		);
		foreach($props as $k => $v) {
			if($v != null) {
				if($k == "DateCreated") {
					$_props[$k] = "'" . $v . "'";
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

	public function LinkProjectToTimeSheet($pid, $tsid) {
		if($pid == null || $pid < 1 || $tsid == null || $tsid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("INSERT INTO TimeSheetProject (PID, TSID) VALUES (?, ?)");
		$stmt->bind_param("ii", $pid, $tsid);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectUserId($id, $uid) {
		if($id == null || $id < 1 || $uid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET UserID = ? WHERE Id = ?");
		$stmt->bind_param("ii", $uid, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectClientId($id, $cid) {
		if($id == null || $id < 1 || $cid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET ClientID = ? WHERE Id = ?");
		$stmt->bind_param("ii", $cid, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectTitle($id, $name) {
		if($id == null || $id < 1 || strlen($name) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET Title = ? WHERE Id = ?");
		$stmt->bind_param("si", $name, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectDescription($id, $d) {
		if($id == null || $id < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET Description = ? WHERE Id = ?");
		$stmt->bind_param("si", $d, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectTotalWorkHours($id, $h) {
		if($id == null || $id < 1 || $h < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET TotalWorkHours = ? WHERE Id = ?");
		$stmt->bind_param("di", $h, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectTotalEstimateHours($id, $h) {
		if($id == null || $id < 1 || $h < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET TotalEstimateHours = ? WHERE Id = ?");
		$stmt->bind_param("di", $h, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditProjectDateCreated($id, $d) {
		if($id == null || $id < 1 || !(ASPMDateTime::ValidDBDate($d))) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Project SET DateCreated = ? WHERE Id = ?");
		$stmt->bind_param("si", $d, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function RemoveProject($id) {
		if($id == null) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare("DELETE FROM Project WHERE Id = ?");
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$tmpStmt->close();
		return true;
	}
}
?>
