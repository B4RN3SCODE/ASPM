<?php
//namespace Core\Domain\TimeSheet;
#region comment
/*************************************************
 * TASK : Defines the domain data layer for a task
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class Task {

#region Vars::PROPS
	private $Id;
	protected $UserID;
	protected $ProjectID;
	protected $Title;
	protected $Date;
	protected $TargetHours;
	protected $ActualHours;
	protected $Description;
	protected $IsComplete;
#endregion

#region CONSTRUCTOR
	public function Task($id = null, $uid = null, $projID = null, $title = null, $d = null, $thr = null, $acthr = null, $desc = null, $complt = null) {
		$this->Id = $id;
		$this->UserID = $uid;
		$this->ProjectID = $projID;
		$this->Title = $title;
		$this->Date = $d;
		$this->TargetHours = $thr;
		$this->ActualHours = $acthr;
		$this->Description = $desc;
		$this->IsComplete = $complt;
	}
#endregion

#region ACCESSORS::GETTERS

	public function getId() { return $this->Id; }
	public function getUserID() { return $this->UserID; }
	public function getProjectID() { return $this->ProjectID; }
	public function getTitle() { return $this->Title; }
	public function getDate() { return $this->Date; }
	public function getTargetHours() { return $this->TargetHours; }
	public function getActualHours() { return $this->ActualHours; }
	public function getDescription() { return $this->Description; }
	public function getIsComplete() { return $this->IsComplete; }

#endregion

#region ACCESSORS::SETTERS

	public function setId($id) {
		if($id == null || $id < 1) return null;
		$this->Id = $id;
	}
	public function setUserID($id) {
		if($id == null || $id < 1) return null;
		$this->UserId = $id;
	}
	public function setProjectID($id) {
		if($id == null || $id < 1) return null;
		$this->ProjectID = $id;
	}
	public function setTitle($t) {
		if($t == null || gettype($t) != "string") return null;
		$this->Title = $t;
	}
	public function setDate($d = null) {
		$this->Date = $d;
	}
	public function setTargetHours($h) {
		$this->TargetHours = $h;
	}
	public function setActualHours($h) {
		$this->ActualHours = $h;
	}
	public function setDescription($desc) {
		if($desc == null || gettype($desc) != "string") return null;
		$this->Description = $desc;
	}
	public function setIsComplete($bool) {
		if($bool != true && $bool != false) return null;
		$this->IsComplete = $bool;
	}

#endregion

}
?>
