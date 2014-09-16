<?php
#region comment
/****************************************************
 * TimeSheet
 * Defines the domain data layer for a TimeSheet
 * object.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class TimeSheet {

#region Vars::PROPS

	private $Id;
	protected $UserID;
	protected $BillingRate;
	protected $PayWeekEnding;
	protected $TotalTargetHours;
	protected $TotalActualHours;
	protected $Submitted;

#endregion

#region CONSTRUCTOR

	public function TimeSheet($id = null, $uid = null, $bill = null, $wknd = null, $trg = null, $actl = null, $sub = false) {
		$this->Id = $id;
		$this->UserID = $uid;
		$this->BillingRate = $bill;
		$this->PayWeekEnding = $wknd;
		$this->TotalTargetHours = $trg;
		$this->TotalActualHours = $actl;
		$this->Submitted = $sub;
	}

#endregion

#region ACCESSORS::GETTERS

	public function getId() { return $this->Id; }
	public function getUserID() { return $this->UserID; }
	public function getBillingRate() { return $this->BillingRate; }
	public function getPayWeekEnding() { return $this->PayWeekEnding; }
	public function getTotalTargetHours() { return $this->TotalTargetHours; }
	public function getTotalActualHours() { return $this->TotalActualHours; }
	public function getSubmitted() { return $this->Submitted; }

#endregion

#region ACCESSORS::SETTERS

	public function setId($i) {
		$this->Id = (isset($i) && $i > 0) ? $i : null;
	}

	public function setUserID($i) {
		if(!isset($i) || $i < 1) return null;
		$this->UserID = $i;
	}

	public function setBillingRate(float $rate) {
		if(!isset($rate) || empty($rate) || $rate == null) return null;
		$this->BillingRate = (float)$rate;
	}

	public function setPayWeekEnding($date) {
		if(!isset($date) || empty($date) || $date == null) return null;
		$this->PayWeekEnding = $date;
	}

	public function setTotalTargetHours(float $h) {
		if(!isset($h) || empty($h) || $h == null) return null;
		$this->TotalTargetHours = $h;
	}

	public function setTotalActualHours(float $h) {
		if(!isset($h) || empty($h) || $h == null) return null;
		$this->TotalActualHours = $h;
	}

	public function setSubmitted($bool) {
		if($bool != true && $bool != false) return null;
		$this->Submitted = $bool;
	}

#endregion

}
?>
