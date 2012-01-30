<?php
	require("library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	define("HOME", "/kibocms/index.php");
	
	if($_SESSION['auid'] != 0) {
		$f->redirect(HOME);
		die();
	}
	
	$username = $f->getValue("login_username");
	$password = $f->getValue("login_password");
	
	
	if($username != "" && $password != "") {
		
		$checkCode = $f->passwordGeneration();
		$passwordMD5 = md5($password);
		
		
		$query = $db->execQuery("SELECT id FROM admins WHERE username = '$username' AND password = '".$passwordMD5."'");
		if(mysql_num_rows($query) == 0) {
			$f->setMessage("Wrong username or password! Try again!", "error");
			$f->redirect("join.php");
			die();
		}
		$data = mysql_fetch_array($query, MYSQL_ASSOC);
		$kuid = $data['id'];
		
		//echo "UPDATE users SET check_code = '$checkCode' WHERE id = '$kuid'";
		
		$db->execQuery("UPDATE admins SET check_code =  '".$checkCode."' WHERE id =  '".$kuid."'");
		$_SESSION['auid'] = $kuid;
		$_SESSION['auid_cc'] = $checkCode;
		
		if($_SESSION['last_url'] != ""){
			$last_url = $_SESSION['last_url'];
			unset($_SESSION['last_url']);
			$f->redirect($last_url); 
		}
		else 
			$f->redirect("index.php");
	}
	
?>