<?php
//namespace Core\Domain\Clients;
/*************************************************
 * CLIENT
 * Defines the domain data layer for a client
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
class Client {

	public $Id;
	public $Name;
	public $Alias;
	public $ContactName;
	public $Phone;
	public $Country;
	public $StateProv;
	public $City;
	public $ZipPostal;
	public $DateAdded;
	public $AddedByUserID;
	public $OnSiteEmployee;

	public function Client($primaryKeyId = null, $organizationsFullName = null, $nicknameOrAlias = null, $contactName = null,
							$phoneNumber = null, $country = null, $st8Prov = null, $cityName = null, $zipPost = null,
							$addedOn = null, $addedByUsrId = null, $eployeeThere = null) {

		$this->Id = $primaryKeyId;
		$this->Name = $organizationsFullName;
		$this->Alias = $nicknameOrAlias;
		$this->ContactName = $contactName;
		$this->Phone = ASPM::VerifyPhoneNumberFormat($phoneNumber);
		$this->Country = $country;
		$this->StateProv = $st8Prov;
		$this->City = $cityName;
		$this->ZipPostal = (string)$zipPost;
		$this->DateAdded = $addedOn;
		$this->AddedByUserID = $addedByUsrId;
		$this->OnSiteEmployee = $eployeeThere;
	}

	public function getId() { return $this->Id; }
	public function getName() { return $this->Name; }
	public function getAlias() { return $this->Alias; }
	public function getContactName() { return $this->ContactName; }
	public function getPhone() { return $this->Phone; }
	public function getCountry() { return $this->Country; }
	public function getStateProv() { return $this->StateProv; }
	public function getCity() { return $this->City; }
	public function getZipPostal() { return (string)$this->ZipPostal; }
	public function getDateAdded() { return $this->DateAdded; }
	public function getAddedByUserID() { return $this->AddedByUserID; }
	public function getOnSiteEmployee() { return $this->OnSiteEmployee; }

	public function setId($int = 0) {
		$this->Id = $int;
	}
	public function setName($n = null) {
		if(!isset($n) || is_null($n) || $n == null || strlen($n) < 4) return null;
		$this->Name = $n;
	}
	public function setAlias($a = null) {
		if(!isset($a) || is_null($a) || $a == null || strlen($a) < 4) return null;
		$this->Alias = $a;
	}
	public function setContactName($contact = "") {
		$this->ContactName = $contact;
	}
	public function setPhone($num = null) {
		if(ASPM::VerifyPhoneNumberFormat($num) === false) return null;
		$this->Phone = ASPM::VerifyPhoneNumberFormat($num);
	}
	public function setCountry($countryName = null) {
		$this->Country = $countryName;
	}
	public function setStateProv($st8prv = null) {
		$this->StateProv = $st8prv;
	}
	public function setCity($cit = null) {
		$this->City = $cit;
	}
	public function setZipPostal($zipp = null) {
		if($zipp != null && gettype($zipp) != "integer" && gettype($zipp) != "string") return null;
		$this->ZipPostal = (string)$zipp;
	}
	public function setDateAdded($date = null) {
		if($date == null || ASPMDateTime::ValidDBDate($date)) $this->DateAdded = $date;
	}
	public function setAddedByUserID($int) {
		if(isset($int) && gettype($int) == "integer" && $int > 0) $this->AddedByUserID = $int;
	}
	public function setOnSiteEmployee($int) {
		if((isset($int) && gettype($int) == "integer" && $int > 0) || $int == null) $this->OnSiteEmployee = $int;
	}
}
?>
