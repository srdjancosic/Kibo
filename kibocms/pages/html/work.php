<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();

	$action = $f->getValue("action");
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		
		case "save":
			
			$lang_arr = $f->getAllLanguages();
			foreach ($lang_arr as $lang_id => $langName) {
				$id = $f->getArrValue("leaf_id", $lang_id);
				$name = $f->getArrValue("name", $lang_id);
				$display_header = $f->getArrValue("display_header", $lang_id);
				$css_class = $f->getArrValue("css_class", $lang_id);
				$content = $f->getArrValue("content_t", $lang_id);
				
				$content_new = $name."|:|".$display_header."|:|".$css_class."|:|".$content;
				
				$db->execQuery("UPDATE ".DB_PREFIX."`leaves` SET `content` = '$content_new' WHERE id = '$id'");
			}
			$f->setMessage("Changes saved!");
			$f->redirect("index.php");
			
			break;
	}
	
?>