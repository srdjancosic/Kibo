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
		case "addFieldType":

			$type = $f->getValue("type");
			$title = $f->getValue("title");
			$form_id = $f->getValue("form_id");
			$name = $f->getValue("name");
			$label = $f->getValue("label");
			
			$db->execQuery("INSERT INTO form_fields (`form_id`, `field_type`, `name`, `label`) VALUES ('$form_id', '$type', '$name', '$label')");
			$field_id = $db->insertId;
			switch($type) {
				case "text": require("fields/textbox.php"); break;
				case "password": require("fields/password.php"); break;
				case "textarea": require("fields/textarea.php"); break;
				case "select": require("fields/select.php"); break;
				case "select_multiple": require("fields/selectmultiple.php"); break;
				case "checkbox": require("fields/checkbox.php"); break;
				case "radiobutton": require("fields/radiobutton.php"); break;
				case "button": require("fields/button.php"); break;
				case "hidden": require("fields/hidden.php"); break;
				case "datapicker": require("fields/datapicker.php"); break;
				case "colorpicker": require("fields/colorpicker.php"); break;
				case "fileupload": require("fields/fileupload.php"); break;
			}
			
			break;
		case "deleteFieldType":
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM form_fields WHERE id = '".$id."'");
			
			break;
		case "editFieldType":
			
			$id = $f->getValue("id");
			$query = $db->execQuery("SELECT * FROM form_fields WHERE id = '$id'");
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			
			require("edit.php");
			break;
			
		case "editFieldTypeOptions":
			
			
			$id = $f->getValue("id");
			//...
			$view = new View("form_fields", $id);
			$view->extend($_POST);
			$view->validation = str_replace("{PLUS}", "+", $view->validation);
			$view->Save();
			//$db->execQuery("UPDATE form_fields SET ... WHERE id = '$id'");
			
			break;
		case "add_form":
			
			$name= $f->getValue("name");
			$table_name = $f->getValue("table_name");
			$form_action = $f->getVAlue("form_action");
			$file_upload = $f->getValue("file_upload");
			$submit_value = $f->getValue("submit_value");
			$submit_class = $f->getValue("submit_class");
			$submit_id = $f->getValue("submit_id");
			$identificator = $f->getValue("identificator");
			
			if(strlen($name) != 0){
				$db->execQuery("INSERT INTO `forms` (`name`, `table_name`,`action`,`file_upload`, `submit_value`, `submit_class`, `submit_id`, `identificator`)
											VALUES('$name', '$table_name', '$form_action', '$file_upload', '$submit_value', '$submit_class', '$submit_id', '$identificator')");
				$last_insert = $db->insertId;
				$f->setMessage("New page created!");
				$f->redirect("formedit.php?id=".$last_insert);
			}else {
				$f->setMessage("You must enter forn name, action and tebel to connect!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit_form":
			
			$id = $f->getValue("id");
			$name= $f->getValue("name");
			$table_name = $f->getValue("table_name");
			$form_action = $f->getVAlue("form_action");
			$file_upload = $f->getValue("file_upload");
			$submit_value = $f->getValue("submit_value");
			$submit_class = $f->getValue("submit_class");
			$submit_id = $f->getValue("submit_id");
			$identificator = $f->getValue("identificator");
			
			if(strlen($name) != 0){
				$db->execQuery("UPDATE `forms` SET `name` = '$name',
													`table_name` = '$table_name',
													`action` = '$form_action',
													`file_upload` = '$file_upload',
													`submit_value` = '$submit_value',
													`submit_class` = '$submit_class',
													`submit_id` = '$submit_id',
													`identificator` = '$identificator' WHERE `id`='$id'");
				$f->setMessage("Form edited!");
			} else {
				$f->setMessage("You must enter form name!", "error");
			}
			
			$f->redirect("index.php");	
			
			break;
			
		case "delete":
			$id = $f->getValue("id");
			
			$db-> execQuery("DELETE FROM forms WHERE `id` = '$id'");
			if($db->numRows("SELECT * FROM form_fields WHERE `id` = '$id'") != 0){
				$db-> execQuery("DELETE FROM form_fiels WHERE `form_id` = '$id'"); 
			}
			$f->setMessage("Form deleted!");
			$f->redirect("index.php");
			
			break;	
		case "sort":
			
			$form_id = $f->getValue("form_id");
			
			$items = $_POST['field'];
			
			if(count($items) > 0) {
				foreach ($items as $order => $field_id) {
					$db->execQuery("UPDATE ".DB_PREFIX."form_fields SET
															`ordering` = '$order'
															WHERE form_id = '$form_id' AND id = '$field_id'");
				}
			}
			break;
	}
?>