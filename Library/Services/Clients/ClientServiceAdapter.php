<?php
//namespace Services\Clients;
require_once("ClientServiceAdapterUI.php");
/*************************************************
 * ClientServiceAdapter
 * Uses the adpt interface and provides logic to the
 * defined methods.
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
class ClientServiceAdapter implements ClientServiceAdapterUI {

	private $_dbAdapt;
	private $dbTbl;

	public function ClientServiceAdapter(DBConn $db = null) {
		$this->_dbAdapt = (isset($db) && $db != null) ? $db : new DBConn();
		if(!$this->_dbAdapt->getIsLinked()) $this->_dbAdapt->Link();
		$this->dbTbl = "Client";
		$this->_dbAdapt->setTbl($this->dbTbl);
	}

	public function GetClients() {
		$this->_dbAdapt->SStatement(array(0 => "*"), $this->dbTbl, null, null);
		$this->_dbAdapt->QQuery();
		return $this->_dbAdapt->getAll();
	}

	public function GetClientById($id) {
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

	public function InsertNewClient(Client $c = null) {
		if($c == null || !($c instanceof Client)) return false;
		$props = array(
			"Name,"	=>	$c->getName(),
			"Alias,"	=>	$c->getAlias(),
			"ContactName,"	=> $c->getContactName(),
			"Phone,"	=>	$c->getPhone(),
			"Country,"	=>	$c->getCountry(),
			"StateProv,"	=>	$c->getStateProv(),
			"City,"	=>	$c->getCity(),
			"ZipPostal,"	=>	$c->getZipPostal(),
			"DateAdded,"	=>	$c->getDateAdded(),
			"AddedByUserID,"	=>	$c->getAddedByUserID(),
			"OnSiteEmployee"	=>	$c->getOnSiteEmployee()
		);
		foreach($props as $k => $v) {
			if($v != null) {
				if($k == "OnSiteEmployee")
					$_props[$k] = "${v}";
				else {
					if(gettype($v) == "string")
						$_props[$k] = "'" . $v . "', ";
					else
						$_props[$k] = "${v}, ";
				}
			}
		}
		$this->_dbAdapt->IStatement($this->dbTbl, $_props);
		$tmp = $this->_dbAdapt->getLnk();
		$tmp->query($this->_dbAdapt->getQry());
		unset($tmp);
		return true;
	}

	public function EditName($id, $name) {
		if($id == null || $id < 1 || strlen($name) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET Name = ? WHERE Id = ?");
		$stmt->bind_param("si", $name, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditAlias($id, $a) {
		if($id == null || $id < 1 || strlen($a) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET Alias = ? WHERE Id = ?");
		$stmt->bind_param("si", $a, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditContactName($id, $cn) {
		if($id == null || $id < 1 || strlen($cn) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET ContactName = ? WHERE Id = ?");
		$stmt->bind_param("si", $cn, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditPhone($id, $num) {
		if($id == null || $id < 1 || ASPM::VerifyPhoneNumberFormat($num) === false) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET Phone = ? WHERE Id = ?");
		$stmt->bind_param("di", $num, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditCountry($id, $c) {
		if($id == null || $id < 1 || strlen($c) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET Country = ? WHERE Id = ?");
		$stmt->bind_param("si", $c, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditStateProv($id, $st8prov) {
		if($id == null || $id < 1 || strlen($st8prov) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET StateProv = ? WHERE Id = ?");
		$stmt->bind_param("si", $st8prov, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditCity($id, $c) {
		if($id == null || $id < 1 || strlen($c) < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET City = ? WHERE Id = ?");
		$stmt->bind_param("si", $c, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditZipPostal($id, $zipPost) {
		if($id == null || $id < 1 || strlen($zipPost) < 1 || strlen($zipPost) > 14) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET ZipPostal = ? WHERE Id = ?");
		$stmt->bind_param("si", $zipPost, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditDateAdded($id, $d) {
		if($id == null || $id < 1 || strlen($d) < 1 || !(ASPMDateTime::ValidDBDate($d))) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET DateAdded = ? WHERE Id = ?");
		$stmt->bind_param("si", $d, $id);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditAddedByUserID($cid, $uid) {
		if($cid == null || $cid < 1 || $uid == null || $uid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET AddedByUserID = ? WHERE Id = ?");
		$stmt->bind_param("ii", $uid, $cid);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function EditOnSiteEmployee($cid, $eid) {
		if($cid == null || $cid < 1 || $eid == null || $eid < 1) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$stmt = $tmp->prepare("UPDATE Client SET OnSiteEmployee = ? WHERE Id = ?");
		$stmt->bind_param("ii", $eid, $cid);
		$stmt->execute();
		$stmt->close();
		unset($tmp);
		unset($stmt);
		return true;
	}

	public function RemoveClient($id) {
		if($id == null) return false;
		$tmp = $this->_dbAdapt->getLnk();
		$tmpStmt = $tmp->prepare("DELETE FROM Client WHERE Id = ?");
		$tmpStmt->bind_param("i", $id);
		$tmpStmt->execute();
		$tmpStmt->close();
		return true;
	}
}
?>

