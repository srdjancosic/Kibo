<?php
	require("library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	if($_SESSION['auid'] != 0) {
		$_SESSION['auid'] = 0;
		$_SESSION['auid_cc'] = "";
		$f->redirect("join.php");
		die();
	}
	
	$f->redirect("index.php");
	
?>