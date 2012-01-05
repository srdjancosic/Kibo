<?php

	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$adminId = $f->loggedIn();
	
	$action = $f->getValue("action");
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		case "add":
			$name = $f->getValue("name");
						
			if(strlen($name) != 0) {
				
				$db->execQuery("INSERT INTO ".DB_PREFIX."`user_groups` (`name`) 
												VALUES ('$name')");
				$last_insert = $db->insertId;
								
				$f->setMessage("New user group created!");
				$f->redirect("index.php");
			} else {
				$f->setMessage("You must enter user group name!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."`user_groups` WHERE `id` = '$id'");
									
			$f->setMessage("User group deleted!");
			$f->redirect("index.php");
			
		break;
		
	}
	
?>