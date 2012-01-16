<?php
	require("library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	
	$query = $db->execQuery("show tables");
	while($data = mysql_fetch_array($query)){
		if($data[0]!='admins' && $data[0]!='constants' && $data[0]!= 'field_types' && $data[0]!='settings' && $data[0]!='config' && $data[0] != 'languages')
		$db->execQuery("TRUNCATE $data[0];");
	}
	
	$f->redirect("index.php");
	
?>