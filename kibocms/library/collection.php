<?php

class Collection extends Functions {
	
	public $db, $f, $_limit = "", $_offset = "";
	public $totalCount; // all the records
	public $resultCount; // count of records with limit sql
	private $table;
	
	function __construct($table) {
		$this->db = Database::__construct();
		$this->f  = Functions::__construct();
		
		$this->table = $table;
	}
	
	function __destruct() {
		unset($this->db, $this->f);
	}
	
	function getCollection($whereSQL = "") {
		
		$limitSQL = "";
		$limitSQL = ($this->_limit != "") ? " LIMIT ".$this->_limit. ", ".$this->_offset : "";
		
		$this->totalCount = Database::numRows("SELECT * FROM `".$this->table."`");
		$query = Database::execQuery("SELECT * FROM `".$this->table."` $whereSQL" . $limitSQL);
		$this->resultCount = mysql_num_rows($query);
		
		$result = array();
		if($this->resultCount !=0 ){
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$result[] = new View($this->table, $data['id']);
			}
		}
		return $result;
		
	}
	
	function getCollectionCustom($SQL) {
		
		$query = Database::execQuery($SQL);
		$this->resultCount = mysql_num_rows($query);
		$this->totalCount  = $this->resultCount;
		
		$result = array();
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$result[] = new View($this->table, $data['id']);
		}
		
		return $result;
		
	}
}