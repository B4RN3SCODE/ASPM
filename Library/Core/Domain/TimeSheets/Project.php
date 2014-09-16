<?php
//namespace Core\Domain\TimeSheet;
#region comment
/*************************************************
 * PROJECT : Defines the domain data layer for a
 * project
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class Project {

#region Vars::PROPS
	protected $Id;
	protected $UserID;
	protected $ClientID;
	protected $Title;
	protected $Description;
	protected $TotalWorkHours;
	protected $TotalEstimateHours;
	protected $DateCreated;
#endregion

#region CONSTRUCTOR
	public function Project($id = null, $uid = null, $cid = null, $title = null, $desc = null, $wrk = null, $est = null, $date = null) {
		$this->Id = $id;
		$this->UserID = $uid;
		$this->ClientID = $cid;
		$this->Title = $title;
		$this->Description = $desc;
		$this->TotalWorkHours = $wrk;
		$this->TotalEstimateHours = $est;
		$this->DateCreated = $date;
	}
#endregion

#region ACCESSORS::GETTERS

	public function getId() { return $this->Id; }
	public function getUserID() { return $this->UserID; }
	public function getClientID() { return $this->ClientID; }
	public function getTitle() { return $this->Title; }
	public function getDescription() { return $this->Description; }
	public function getTotalWorkHours() { return $this->TotalWorkHours; }
	public function getTotalEstimateHours() { return $this->TotalEstimateHours; }
	public function getDateCreated() { return $this->DateCreated; }

#endregion

#region ACCESSORS::SETTERS

	public function setId($id) {
		if($id == null || $id < 1) return null;
		$this->Id = $id;
	}
	public function setUserID($id) {
		if($id == null || $id < 0) return null;
		$this->UserID = $id;
	}
	public function setClientID($id) {
		if($id == null || $id < 0) return null;
		$this->ClientID = $id;
	}
	public function setTitle($t) {
		if($t == null) return null;
		$this->Title = $t;
	}
	public function setDescription($d) {
		if($d == null) return null;
		$this->Description = $d;
	}
	public function setTotalWorkHours($h) {
		if($h == null || $h < 0) return null;
		$this->TotalWorkHours = $h;
	}
	public function setTotalEstimateHours($h) {
		if($h == null || $h < 0) return null;
		$this->TotalEstimateHours = $h;
	}
	public function setDateCreated($d) {
		if($d == null || strlen($d) < 1) return null;
		$this->DateCreated = $d;
	}

#endregion

}
?>
