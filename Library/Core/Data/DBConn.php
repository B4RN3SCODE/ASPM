<?php
#region comment
/*************************************************
 * DBConn
 * Main database class
 *
 * @author			Arbor Solutions, Inc.
 * @website			http://www.arbsol.com
 * @copyright		2014 (C) Arbor Solutions, Inc.
 * @developer		Tyler J Barnes, tbarnes@arbsol.com
 ****************************************************/
#endregion
class DBConn {

#region Vars::PROPS

	// track if object is connected
	public $IsLinked;

	// Database type
	protected $DB_TYPE;

	// Holds the host, port, username, password
	private $AuthData = array();

	// Database name and table name
	protected $Targets = array();

	// Link to the connection's reference
	protected $DbLinkRef;

	// SQL to execute
	protected $Query;

	// Results of the Query statement
	protected $SQLResults;

	// Connection error | authentication error | databse, tabel, or data location error | query error
	protected $IErrors = array();

#endregion

#region CONSTRUCTOR

	public function DBConn() {

		$this->IsLinked = false;
		$this->DB_TYPE = DB_DFLT_TYP;
		$this->AuthData["host"] = DB_HOST;
		$this->AuthData["user"] = DB_UNAME;
		$this->AuthData["pass"] = DB_PASS;
		$this->Targets["db"] = DB_NAME;
		$this->Targets["tbl"] = null;
		$this->DbLinkRef = null;
		$this->Query = "";
		$this->SQLResults = null;
		$this->IErrors["connect"] = "";
		$this->IErrors["auth"] = "";
		$this->IErrors["target"] = "";
		$this->IErrors["query"] = "";
	}

#endregion

#region METHODS::CONFIGS

	/**************************************
	 * Link
	 * Attempts to connect to the database
	 * and sets the link to reference the
	 * reference if successful.
	 ************************************/
	public function Link() {

		// remove existing link
		$this->IsLinked = false;
		$this->DbLinkRef = null;

		// create new link with other object data
		$this->DbLinkRef = new mysqli($this->AuthData["host"], $this->AuthData["user"], $this->AuthData["pass"], $this->Targets["db"]);
		$this->setIsLinked(true);
		return true;

		// error number means there was an error connecting
		if($this->DbLinkRef->connect_errno) {

			$this->IErrors["connect"] .= "Initial connection failed when instantiating mysql object. Details: " . $this->DbLinkRef->connect_error . TXT_NL;

			$this->DbLinkRef = null;
			$this->setIsLinked(false);
			return false;
		}
	}


	/***********************************************
	 * Kill
	 * Either resets the database reference or
	 * destroys the current instance / object.
	 *
	 * @param	bool: false = reset | true = destroy
	 **********************************************/
	public function Kill($bool = false) {

		$this->DbLinkRef = null;
		$this->setIsLinked(false);

		if($bool) {
			unset($this);
		}
	}

	/******************************
	 * resetQ
	 * Resets the Query to nothing
	 ******************************/
	public function resetQ() {
		$this->Query = null;
		$this->Query = "";
	}

	/******************************
	 * resetRslt
	 * Resets the SQL reslts to nothing
	 ******************************/
	public function resetRslt() {
		$this->SQLResults = null;
	}

#endregion

#region ACCESSORS::GETTERS

	// connection status
	public function getIsLinked() { return $this->IsLinked; }
	// database name
	public function getDb() { return $this->Targets["db"]; }
	// table name
	public function getTbl() { return $this->Targets["tbl"]; }
	// reference link
	public function getLnk() { return $this->DbLinkRef; }
	// current query stmt or string
	public function getQry() { return $this->Query; }
	// result reference
	public function getResRef() { return $this->SQLResults; }

	/**************************
	 * getErr
	 * Gets a specified
	 * error log.
	 *
	 * @param type: log to get
	 **************************/
	public function getErr($type = null) {

		if($type == null) {

			// combine all logs if type is null - concatenate
			$str = "All DBConn Errors:" . TXT_NL;

			foreach($this->IErrors as $title => $data) {
				$str .= "[$title]: $data" . TXT_NL;
			}

			$str .= "-----END-----" . TXT_NL;
			return $str;
		}

		return "Database connection error [$type]: " . $this->IErrors[$type] . TXT_NL;
	}

#endregion

#region ACCESSORS::SETTERS

	/*******************************************
	 * setDb
	 * Sets a new database, and tests it with a
	 * select call.
	 *
	 * @param	db: database name
	 *******************************************/
	public function setDb($db = null) {
		if($db == null) return null;
		$this->Targets["db"] = $db;
		$this->DbLinkRef->select_db($db);
	}

	/*******************************************
	 * setTbl
	 * Sets the table name
	 *
	 * @param	tbl: table name to set
	 *******************************************/
	public function setTbl($tbl = null) {
		$this->Targets["tbl"] = $tbl;
	}

	/*******************************************
	 * setIsLinked
	 * Sets the connection status
	 *
	 * @param bool: true for connected
	 *******************************************/
	public function setIsLinked($bool) {
		if($bool != true && $bool != false) return null;
		$this->IsLinked = $bool;
	}

	/*******************************************
	 * setLnk
	 * Injection of a new refernce link to query
	 * from - useful for dependency injection
	 *
	 * @param	ref: the reference to the link
	 *******************************************/
	public function setLnk(mysqli &$ref) {
		$this->DbLinkRef = $ref;
		if($test = $this->DbLinkRef->query("SELECT DATABASE()")) {

			return $this;
		} else {
			$this->Kill(false);
		}
	}

	/*******************************************
	 * setQry
	 * Sets the Quer
	 *
	 * @param stmt: the statement to set
	 *******************************************/
	public function setQry($stmt) {
		$this->Query = $stmt;
	}

	/*******************************************
	 * setRslt
	 * Sets the SQL results
	 *
	 * @param data: the data set of the result
	 *******************************************/
	public function setRslt(mysqli_result $data) {
		$this->SQLResults = $data;
	}

#endregion

#region METHODS::SQLDATA

	/*************************************
	 * getAll
	 * Gets all data from a query result
	 * set -> like fetch_all()
	 *************************************/
	public function getAll() {
		if(!isset($this->SQLResults) || empty($this->SQLResults) || $this->SQLResults == null) {
			$this->IErrors["query"] .= "Could not fetch all data with no reselts." . TXT_NL;
			return null;
		}
		return $this->SQLResults->fetch_all(MYSQLI_ASSOC);
	}


	/*************************************
	 * getRow
	 * Gets the data in the next row of a
	 * query result set.
	 *************************************/
	public function getRow() {
		if($row = $this->SQLResults->fetch_assoc()) {
			return $row;
		}
		return null;
	}

#endregion

#region METHODS::QUERIES

	/*****************************************
	 * QQuery
	 * Pretty much just ->query() just formed
	 * to fit the class better
	 ****************************************/
	public function QQuery() {

		if($sent = $this->DbLinkRef->query($this->Query)) {
			$this->setRslt($sent);
			return $sent;
		}

		$this->IErrors["query"] .= "Statement is not ready to send.  Something went wrong." . TXT_NL;
		return null;
	}

	/***************************************
	 * Statement builders
	 * SStatement = SELECT
	 * IStatement = INSERT
	 * UStatement = UPDATE
	 ***************************************/
	public function SStatement(array $s, $f = null, $j = null, array $w = null) {

		if($s == null || count($s) < 1 || $f == null || empty($f)) {
			$this->IErrors["query"] .= "Invalid values passed to select builder." . TXT_NL;
			return null;
		}

		$sql = "SELECT ";

		foreach($s as $index => $string) { $sql .= $string; }

		$sql .= " FROM $f ";

		if($j != null) $sql .= " $j ";

		if(isset($w) && !empty($w) && $w != null) {
			$sql .= "WHERE ";
			foreach($w as $column => $toBind) {
				$sql .= "$column " . "$toBind ";
			}
		}

		$this->setQry($sql);
		return $this->Query;
	}


	public function IStatement($tbl = null, array $data = null) {

		if($tbl == null || empty($tbl) || $data == null || !isset($data) || empty($data)) return null;

		$sql = "INSERT INTO $tbl (";

		foreach($data as $property => $val) {
			$sql .= $property . TXT_SPC;
		}

		$sql .= ") VALUES (";

		foreach($data as $property => $val) {
			$sql .= $val . TXT_SPC;
		}

		$sql .= ")";

		$this->setQry($sql);
		return $this->Query;
	}



	public function UStatement($tbl = null, array $setdata = null, array $condition = null) {

		if($tbl == null || $setdata == null || $condition == null || empty($tbl)) return null;

		$sql = "UPDATE $tbl SET ";

		foreach($setdata as $column => $value) {
			$sql .= $column . " = " . $value . TXT_SPC;
		}


		$sql .= "WHERE ";

		foreach($condition as $varWithSign => $right) {
			$sql .= "$varWithSign $right ";
		}

		$this->setQry($sql);
		return $this->Query;
	}

#endregion

}
?>
