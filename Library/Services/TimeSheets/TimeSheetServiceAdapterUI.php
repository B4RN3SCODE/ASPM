<?php
//namespace Services\TimeSheets;
include("Library/Core/Domain/TimeSheets/TimeSheet.php");
#region comment
/****************************************************
 * TimeSheetServiceAdapterUI
 * Interface for the TimeSheet Service Adapter
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
interface TimeSheetServiceAdapterUI {

#region METHODS::QUERY::DROP

	public function GetTimeSheetById($id);
	public function GetTimeSheetByUserID($id);
	public function GetTimeSheetByPayWeekEnding($date);

#endregion

#region METHODS::QUERY::INSERT

	public function InsertNewTimeSheet(TimeSheet $ts = null);

#endregion

#region METHODS::QUERY::UPDATES

	public function EditUserID($id, $uid);
	public function EditBillingRate($id, float $r);
	public function EditPayWeekEnding($id, $d);
	public function EditTotalTargetHours($id, $h);
	public function EditTotalActualHours($id, $h);
	public function EditSubmitState($tid, $bool);

#endregion

#region METHODS::QUERY::DROP
	public function RemoveTimeSheet($id);
#endregion
}
?>
