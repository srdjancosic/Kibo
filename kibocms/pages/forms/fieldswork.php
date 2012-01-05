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
			$field_type = $f->getValue("field_type");
			$validation = $f->getValue("validation");			
			$colomn = $f->getValue("colomn");
			$is_necessery = $f->getValue("necessery");
			
			if(strlen($name) != 0) {
				$css_class = $f->generateUrlFromText($name);
				$lang_arr = $f->getAllLanguages();
				$def_lang =$f->getDefaultLanguage();
				$db->execQuery("INSERT INTO ".DB_PREFIX."registration_fields (`name`, `css_class`, `field_type`, `validation`, `responding_column`, `is_necessery`, `lang_id`, `ref_id` ) 
												VALUES ('$name', '$css_class', '$field_type', '$validation', '$colomn', '$is_necessery', '$def_lang', 0)");
				$last_insert = $db->insertId;
				foreach ($lang_arr as $lang_id => $langName) {
					if($lang_id != $def_lang)
						$db->execQuery("INSERT INTO ".DB_PREFIX."registration_fields (`name`, `css_class`, `field_type`, `validation`, `responding_column`, `is_necessery`, `lang_id`, `ref_id`) 
												VALUES ('$name', '$css_class', '$field_type', '$validation', '$colomn', '$is_necessery', '$lang_id', '$last_insert')");
				}
				
				$f->setMessage("New field created!");
				$f->redirect("fieldsedit.php?id=".$last_insert);
			} else {
				$f->setMessage("You must enter field name!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
						
				
				$name = $f->getValue("name");
				$field_type = $f->getValue("field_type");
				$validation = $f->getValue("validation");
				$css_class = $f->getValue("css_class");
				$colomn = $f->getValue("colomn");
				
				
				$id = $f->getValue("id");
				
				
				if(strlen($name) != 0) {
					$db->execQuery("UPDATE ".DB_PREFIX."`registration_fields` SET `name` = '$name',
														`css_class` = '$css_class', 
														`responding_column` = '$colomn',
														`field_type` = '$field_type', 
														`validation` = '$validation' WHERE `id` = '$id'");
					$f->setMessage("Field edited!");
				} else {
					$f->setMessage("You must enter field name!", "error");
				}
			
			$f->redirect("index.php");
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."`registration_fields` WHERE `id` = '$id'");
			$db->execQuery("DELETE FROM ".DB_PREFIX."`registration_field_value` WHERE `field_id` = '$id'");
						
			$f->setMessage("Field deleted!");
			$f->redirect("index.php");
			
		break;
		case "add_type":
			$id= $f->getValue("id");
			$name = $f->getValue("type_name");
						
			$db->execQuery("INSERT INTO ".DB_PREFIX."registration_field_types (`name`) 
												VALUES ('$name')");
						
			$f->setMessage("Field type created!");
			$f->redirect("fieldsedit.php?id= $id");
			
		break;	
		
	}
	
?>