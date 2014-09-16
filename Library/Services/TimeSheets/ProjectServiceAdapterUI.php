<?php
//namespace Services\TimeSheets;
include("Library/Core/Domain/TimeSheets/Project.php");
#region comment
/*************************************************
 * ProjectServiceAdapterUI
 * Interface for the Project Service Adapter
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
interface ProjectServiceAdapterUI {

#region METHODS::QUERY::SELECT::FILTER
	public function GetProjectById($id);
	public function GetProjectByTimeSheet($id);
	public function GetProjectByUserID($id);
#endregion

#region METHODS::QUERY::INSERT
	public function InsertNewProject(Project $p = null);
	public function LinkProjectToTimeSheet($pid, $tsid);
#endregion

#region METHODS::QUERY::UPDATE
	public function EditProjectUserId($id, $uid);
	public function EditProjectClientId($id, $cid);
	public function EditProjectTitle($id, $name);
	public function EditProjectDescription($id, $d);
	public function EditProjectTotalWorkHours($id, $h);
	public function EditProjectTotalEstimateHours($id, $h);
	public function EditProjectDateCreated($id, $d);
#endregion

#region METHODS::QUERY::DROP
	public function RemoveProject($id);
#endregion
}
?>
