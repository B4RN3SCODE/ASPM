<?php
//namespace Services\TimeSheets;
include("Library/Core/Domain/TimeSheets/Task.php");
#region comment
/*************************************************
 * TaskServiceAdapterUI
 * Interface for the Task Service Adapter
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
interface TaskServiceAdapterUI {

#region METHODS::QUERY::SELECT::FILTER
	public function GetAllByIncomplete();
	public function GetAllByComplete();
	public function GetTaskById($id);
	public function GetAllByProject($id);
	public function GetAllByEmployee($id);
#endregion

#region METHODS::QUERY::INSERT
	public function InsertNewTask(Task $task = null);
#endregion

#region METHODS::QUERY::UPDATE
	public function EditTaskTargetHours($id, $hours);
	public function EditTaskActualHours($id, $hours);
	public function EditTaskCompleteStatus($id, $bool);
	public function EditTaskProjectID($id, $pid);
	public function EditTaskTitle($id, $t);
	public function EditTaskDescription($id, $d);
	public function EditTaskDate($id, $date);
	public function EditTaskUserID($id, $uid);
#endregion

#region METHODS::QUERY::DROP
	public function RemoveTask($taskID);
#endregion
}
?>
