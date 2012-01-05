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
			
			$question = $f->getValue("question");
			$lang_id = $f->getValue("lang_id");
			//$lang_id = $db->getValue("lang_id", "p_groups", "id", $group_id);
			
			$db->execQuery("INSERT INTO p_poll (`name`, `group_id`, `lang_id`, `active`)
										VALUES ('$question', '$group_id', '$lang_id', '0')");
			$last_insert = $db->insertId;
			if($f->getValue("active") == 1) {
				$db->execQuery("UPDATE p_poll SET active = '0' WHERE lang_id = '$lang_id'");
				$db->execQuery("UPDATE p_poll SET active = '1' WHERE id = '$last_insert'");
			}
			$f->setMessage("New poll created!");
			$f->redirect("index.php");
			
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM p_poll WHERE id = '$id'");
			$f->setMessage("Poll deleted!");
		
			$f->redirect("index.php");
			
			break;
			
		case "add_answer":
			
			$answer = $f->getValue("answer");
			$poll_id = $f->getValue("poll_id");
			
			$db->execQuery("INSERT INTO p_poll_answers (`name`, `poll_id`, `votes`) VALUES ('$answer', '$poll_id', '0')");
			$f->setMessage("New answer added!");
			$f->redirect("index.php");
			
			break;
		case "deleteAnswer":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM p_poll_answers WHERE id = '$id'");
			$f->setMessage("Answer deleted!");
		
			$f->redirect("index.php");
			
			break;
		case "makeActive":
			
			$id = $f->getValue("id");
			$lang_id = $db->getValue("lang_id", "p_poll", "id", $id);
			$db->execQuery("UPDATE p_poll SET `active` = '0' WHERE `lang_id` = '$lang_id'");
			$db->execQuery("UPDATE p_poll SET `active` = '1' WHERE `id` = '$id'");
			$f->setMessage("Poll activated!");
			$f->redirect("index.php");
			
			break;
	}
?>