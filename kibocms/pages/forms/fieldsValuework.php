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
			$css_id = $f->getValue("css_id");
			$field_id = $f->getValue("field_id");
			$lang_id = $f->getValue("lang_id");
			$label = $f->getValue("label");
			$value = $f->getValue("value");
			
			if(strlen($label) != 0) {
				
				$db->execQuery("INSERT INTO ".DB_PREFIX."registration_field_value (`label`, `css_id`, `field_id`, `lang_id`, `value`) 
												VALUES ('$label', '$css_id', '$field_id', '$lang_id', '$value')");
				
				
				$f->setMessage("New field value created!");
				$f->redirect("fieldsedit.php?id=".$field_id);
			} else {
				$f->setMessage("You must enter field value label!", "error");
				$f->redirect("fieldsedit.php?id=".$field_id);
			}
			
			break;
		case "delete":
			
			$id = $f->getValue("id");
			$field_id = $db->getValue("field_id", "registration_field_value", "id", $id);
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."`registration_field_value` WHERE `id` = '$id'");
									
			$f->setMessage("Field value deleted!");
			$f->redirect("fieldsedit.php?id=".$field_id);
			
		break;
			
		
	}	
?>