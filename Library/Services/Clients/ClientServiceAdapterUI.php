<?php
//namespace Services\Clients;
include("Library/Core/Domain/Clients/Client.php");
/*************************************************
 * ClientServiceAdapterUI
 * Interface for the Client Service Adapter
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
interface ClientServiceAdapterUI {

	public function GetClients();
	public function GetClientById($id);
	public function InsertNewClient(Client $c = null);
	public function EditName($id, $name);
	public function EditAlias($id, $a);
	public function EditContactName($id, $cn);
	public function EditPhone($id, $num);
	public function EditCountry($id, $c);
	public function EditStateProv($id, $st8prov);
	public function EditCity($id, $c);
	public function EditZipPostal($id, $zipPost);
	public function EditDateAdded($id, $d);
	public function EditAddedByUserID($cid, $uid);
	public function EditOnSiteEmployee($cid, $eid);
	public function RemoveClient($id);

}
?>
