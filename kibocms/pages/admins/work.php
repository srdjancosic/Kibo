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
			
		case "new_admin":
			
			$username = $f->getValue("username");
			$password = $f->getValue("password");
			$passwordMD5 = md5($password);
			
			if($username != "" && $password != "") {
				$db->execQuery("INSERT INTO admins (`username`, `password`) VALUES ('$username', '$passwordMD5')");
				
				$f->setMessage("New administrator created");
				$f->redirect("index.php");
			} else {
				$f->setMessage("You must enter administrators username and password!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
			
			$username = $f->getValue("username");
			$password = $f->getValue("password");
			
			$id = $f->getValue("id");
			
			if($username != "") {
				if($password != "") {
					$passwordMD5 = md5($password);
					$addonSQL = " `password` = '$passwordMD5',";
				}
				
				$actions["categories"] 	= ($_POST['categories'] == "") 	? array() : $_POST['categories'];
				$actions["content"] 	= ($_POST['content'] == "") 	? array() : $_POST['content'];
				$actions["elements"] 	= ($_POST['elements'] == "") 	? array() : $_POST['elements'];
				$actions["pages"] 		= ($_POST['pages'] == "") 		? array() : $_POST['pages'];
				$actions["settings"] 	= ($_POST['settings'] == "") 	? array() : $_POST['settings'];
				$actions["admins"] 		= ($_POST['admins'] == "") 		? array() : $_POST['admins'];
				$actions["html"] 		= ($_POST['html'] == "") 		? array() : $_POST['html'];
				$actions["menu"] 		= ($_POST['menu'] == "") 		? array() : $_POST['menu'];
				$actions["user_groups"] = ($_POST['user_groups'] == "") ? array() : $_POST['user_groups'];
				$actions["forms"] 		= ($_POST['forms'] == "")		? array() : $_POST['forms'];
				$actions["database"] 	= ($_POST['database'] == "")	? array() : $_POST['database'];
				$actions["tables"] 		= ($_POST['tables'] == "")		? array() : $_POST['tables'];
				$actions["code_editor"] = ($_POST['code_editor'] == "") ? array() : $_POST['code_editor'];
				
				$actions_json = json_encode($actions);
				
				$db->execQuery("UPDATE admins SET `username` = '$username',
													$addonSQL
													 `actions` = '$actions_json'
													 WHERE id = '$id'");
				$f->setMessage("Changes saved!");
				$f->redirect("index.php");
				
			} else {
				$f->setMessage("You must enter username and password!", "error");
				$f->redirect("edit.php?id=".$id);
			}
			
			break;
		case "delete":
			
			$id = $f->getValue("id");
			$count = $db->numRows("SELECT * FROM admins");
			if($count == 1) {
				$f->setMessage("You can't delete this administrator because this is the only one!", "error");
			} else {
				$db->execQuery("DELETE FROM admins WHERE id = '$id'");
				$f->setMessage("Administrator deleted!");
			}
			$f->redirect("index.php");
			
			break;
	}
?>