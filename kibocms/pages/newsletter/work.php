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
			$title = $f->getValue("title");
			$lang_id = $f->getValue("lang_id");
			
			$db->execQuery("INSERT INTO p_newsletter (`title`, `lang_id`) VALUES ('$title', '$lang_id')");
			$f->redirect("edit.php?id=".$db->insertId);
			
			break;
		case "edit":
			$title = $f->getValue("title");
			$lang_id = $f->getValue("lang_id");
			$id = $f->getValue("id");
			$body = $f->getValue("body");
			
			$db->execQuery("UPDATE p_newsletter SET `title` = '$title', `body` = '$body', `lang_id` = '$lang_id' WHERE id = '$id'");
			$f->redirect("edit.php?id=".$id);
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM p_newsletter WHERE id = '$id'");
			$f->setMessage("Template deleted!");
		
			$f->redirect("index.php");
			
			break;
		case "delete_user":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM p_newsletter_users WHERE id = '$id'");
			$f->setMessage("User deleted!");
		
			$f->redirect("users.php");
			
			break;
		case "send":
			
			$group_id = $f->getValue("group_id");
			$id = $f->getValue("id");
			
			$receivers = array();
			
			$query = $db->execQuery("SELECT * FROM p_newsletter_users WHERE group_id = '$group_id'");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$receivers[] = $data['email'];
			}
			
			$subject = $db->getValue("title", "p_newsletter", "id", $id);
			$body = $db->getValue("body", "p_newsletter", "id", $id);
			$body = str_replace("/upload/", "http://optix.kibocloud.com/upload/", stripslashes($body));
			
			$sender = "newsletter@optix.rs";
			
			$absolutePath = "/home/kiboclou/domains/kibocloud.com/public_html/optix/kibocms/pages/newsletter/Zend";
			require("Zend/Mail.php");
			$mail = new Zend_Mail();
            $mail->setBodyHTML($body,'UTF-8');
            $mail->setFrom($sender);
            $mail->setSubject($subject);
            
            foreach ($receivers as $key => $receiver) {
            	$mail->addTo($receiver);
            	echo "Sending to: ".$receiver."<br />";
            }
            
			
            
            
            
            
            
            $mail->send();
			$f->setMessage("Template has been sent successfully!");
            $f->redirect("index.php", 10);
            
			break;
	}
?>