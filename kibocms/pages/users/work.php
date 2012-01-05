<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$action = $f->getValue("action");
	
	$adminId = $f->loggedIn();
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM p_users WHERE id = '$id'");
			$f->setMessage("User deleted!");
		
			$f->redirect("index.php");
			
			break;
	}
?>