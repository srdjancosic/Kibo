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
		case "add":
			
			$email = $f->getValue("email");
			$lang_id = $f->getValue("lang_id");
			
			$db->execQuery("INSERT INTO p_contact (`email`, `lang_id`)
										VALUES ('$email', '$lang_id')");
			
			$f->setMessage("New contact created!");
			$f->redirect("index.php");
			
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM p_contact WHERE id = '$id'");
			$f->setMessage("Contact deleted!");
		
			$f->redirect("index.php");
			
			break;
			
	}
?>