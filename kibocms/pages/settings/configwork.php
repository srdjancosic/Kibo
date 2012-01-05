<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$unlogged_user_group = $f->getValue("unlogged_user_group");
	$wait_for_approval = $f->getValue("wait_for_approval");
	$site_email = $f->getValue("site_email");
	$allow_fb_registration = $f->getValue("allow_fb_registration");
	
	if($db->numRows("SELECT * FROM ".DB_PREFIX."`config` WHERE `id` = '1'") == 0){
		$db->execQuery("INSERT INTO ".DB_PREFIX."`config` (`id`, `unlogged_user_group`, `wait_for_approval`, `allow_fb_registration`, `site_email`)
															VALUES ('1', '$unlogged_user_group','$wait_for_approval', '$allow_fb_registration', '$site_email')");
	}
	else
		$db->execQuery("UPDATE ".DB_PREFIX."`config` SET `unlogged_user_group` = '$unlogged_user_group',
													 `wait_for_approval` = '$wait_for_approval',
													 `allow_fb_registration` = '$allow_fb_registration',
													 `site_email` = '$site_email' WHERE `id`='1'");
	
	$f->redirect("index.php");
	
?>