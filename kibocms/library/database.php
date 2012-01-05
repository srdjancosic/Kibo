<?php

class Database {
	
	public $insertId, $lastUsedQuery;
	public $i_user = 1;
	public $dbLink;
	
	function __construct() {
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("ERRor");
		mysql_query ("SET NAMES utf8 COLLATE utf8_general_ci", $link);
		mysql_select_db(DB_BASE, $link) or die(mysql_error($link));
		
		$this->dbLink = $link;
	}
	
	function execQuery($SQL) {
		
		$query = mysql_query($SQL) or die("Error executing SQL.");
		$this->lastUsedQuery = $query;
		
		if(substr($SQL, 0, 6) == "INSERT") {
			$this->insertId = mysql_insert_id();
		}
		
		return $query;
	}
	
	function getValue($field, $table, $key, $value) {
		
		$query = mysql_query("SELECT `$field` FROM ".DB_PREFIX."`$table` WHERE `$key` = '$value'") or die();
		$row = mysql_fetch_array($query);
		
		return $row["$field"];
	}
	
	function numRows($SQL) {
		$query = $this->execQuery($SQL);
		
		return mysql_num_rows($query);
	}
	
	
	
} // end of class


?>