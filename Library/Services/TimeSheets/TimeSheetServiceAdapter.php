<?php
//namespace Services\TimeSheets;
require_once("TimeSheetServiceAdapterUI.php");
#region commone
/*************************************************
 * TimeSheetServiceAdapter
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
class TimeSheetServiceAdapter implements TimeSheetServiceAdapterUI {

#region Vars::PROPS
	protected $_dbAdapt;
	protected $dbTbl;
#endregion

#region CONSTRUCTOR
	public function TimeSheetServiceAdapter(DBConn $db = null) {
		$this->_dbAdapt = (isset($db) && $db != null) ? $db : new DBConn();
		if(!$this->_dbAdapt->getIsLinked()) $this->_dbAdapt->Link();
		$this->dbTbl = "TimeSheet";
		$this->_dbAdapt->setTbl($this->dbTbl);
	}
#endregion

#region QUERY::FILTERS::SELECTS

	public function GetTimeSheetById($id) {
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

	public function GetTimeSheetByUserID($id) {
		if(gettype($id) != "integer" || $id < 1) return null;
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("UserID = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}

	public function GetTimeSheetByPayWeekEnding($date) {
		if(gettype($date) != "string" || strlen($date) < 1) return null;
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, array("PayWeekEnding = " => "?"));
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare($this->_dbAdapt->getQry());
		$tmpStmt->bind_param("s", $date);
		$tmpStmt->execute();
		$this->_dbAdapt->setRslt($tmpStmt->get_result());
		$tmpStmt->close();
		return $this->_dbAdapt->getAll();
	}

#endregion

#region QUERY::INSERT

	public function InsertNewTimeSheet(TimeSheet $ts = null) {
		if($ts == null || !($ts instanceof TimeSheet)) return null;

		$props = array(
			"UserID,"	=>	$wg->getUserID(),
			"BillingRate,"	=>	$wg->getBillingRate(),
			"PayWeekEnding,"	=>	$wg->getPayWeekEnding(),
			"TotalTargetHours,"	=>	$wg->getTotalTargetHours(),
			"TotalActualHours,"	=>	$wg->getTotalActualHours(),
			"Submitted"	=>	$wg->getSubmitted()
		);

		foreach($props as $k => $v) {
			if($v != null) {
				if($k == "Submitted") {
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

#region QUERY::UPDATES

	public function EditUserID($id, $uid) {
		if($id == null || $id < 1 || $uid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE TimeSheet SET UserID = ? WHERE Id = ?");
		$stmt->bind_param("ii", $uid, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditBillingRate($id, float $r) {
		if($id == null || $id < 1 || $r < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE TimeSheet SET BillingRate = ? WHERE Id = ?");
		$stmt->bind_param("di", $r, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditPayWeekEnding($id, $d) {
		if($id == null || $id < 1 || strlen($d) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE TimeSheet SET PayWeekEnding = ? WHERE Id = ?");
		$stmt->bind_param("si", $d, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditTotalTargetHours($id, $h) {
		if($id == null || $id < 1 || $h < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE TimeSheet SET TotalTargetHours = ? WHERE Id = ?");
		$stmt->bind_param("di", $h, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditTotalActualHours($id, $h) {
		if($id == null || $id < 1 || $h < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE TimeSheet SET TotalActualHours = ? WHERE Id = ?");
		$stmt->bind_param("di", $h, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditSubmitState($tid, $bool) {
		if($tid == null || ($bool != true && $bool != false)) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE TimeSheet SET Submitted = ? WHERE Id = ?");
		$stmt->bind_param("ii", $bool, $tid);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

#endregion

#region QUERY::DROP

	public function RemoveTimeSheet($id) {
		if($id == null) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare("DELETE FROM TimeSheet WHERE Id = ?");
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$tmpStmt->close();
		return true;
	}

#endregion

}
?>
