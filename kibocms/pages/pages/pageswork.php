<?php

	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	$p  = new Pages();
	$c  = new Category();
	$n  = new Node();

	$adminId = $f->loggedIn();
	
	$action = $f->getValue("action");
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		case "add":
			$name = $f->getValue("name");
			$category = $f->getValue("category");
			$header = $f->getValue("header");
			$content = $f->getValue("content");
			$footer = $f->getValue("footer");
			$lang_id = $f->getValue("lang_id");
			
			if(strlen($name) != 0) {
				$url = $f->generateUrlFromText($name);
				$db->execQuery("INSERT INTO ".DB_PREFIX."pages (`name`, `url`, `category`, `header`, `content`, `footer`, `lang_id`) 
												VALUES ('$name', '$url', '$category', '$header', '$content', '$footer', '$lang_id')");
				
				$last_insert = $db->insertId;
				
				$lang_arr = $f->getActiveLanguages();
				foreach ($lang_arr as $lang_id => $langName) {
					$header = $f->getLeafIdByRefId($header, $lang_id);
					$content = $f->getLeafIdByRefId($content, $lang_id);
					$footer = $f->getLeafIdByRefId($footer, $lang_id);
					
					$db->execQuery("INSERT INTO ".DB_PREFIX."pages (`name`, `url`, `category`, `header`, `content`, `footer`, `lang_id`, `ref_id`) 
												VALUES ('$langName $name', '$url', '$category', '$header', '$content', '$footer', '$lang_id', '$last_insert')");
				}
				
				$f->setMessage("New page created!");
				$f->redirect("pagesedit.php?id=".$last_insert);
			} else {
				$f->setMessage("You must enter page name!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
			
			$lang_arr = $f->getAllLanguages();
			
			
			foreach ($lang_arr as $lang_id => $langName) {
			
				$name = $f->getArrValue("name", $lang_id);
				$url = $f->getArrValue("url", $lang_id);
				$category = $f->getArrValue("category", $lang_id);
				$header = $f->getArrValue("header", $lang_id);
				$content = $f->getArrValue("content", $lang_id);
				$footer = $f->getArrValue("footer", $lang_id);
				$id = $f->getArrValue("page_id", $lang_id);
				$add_footer = $f->getArrValue("add_footer", $lang_id); 
				$add_head = $f->getArrValue("add_head", $lang_id);
				$page_title = $f->getArrValue("page_title", $lang_id);
				$page_description = $f->getArrValue("page_description", $lang_id);
				$page_keywords = $f->getArrValue("page_keywords", $lang_id);
				
				if(strlen($name) != 0) {
					
					
					$db->execQuery("UPDATE ".DB_PREFIX."pages SET `name` = '$name',
													`header` = '$header', 
													`content` = '$content', 
													`footer` = '$footer', 
													`url` = '$url', 
													`category` = '$category',
													`add_head` = '$add_head',
													`add_footer` = '$add_footer',
													`page_title` = '$page_title',
													`page_description` = '$page_description',
													`page_keywords` = '$page_keywords',
													`lang_id` = '$lang_id' WHERE id = '$id'");
					$f->setMessage("Page edited!");
				} else {
					$f->setMessage("You must enter page name!", "error");
				}
			}
			$f->redirect("index.php");
			
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."pages WHERE id = '$id'");
			$db->execQuery("DELETE FROM ".DB_PREFIX."pages_leaves WHERE page_id = '$id'");
			$query = $db->execQuery("SELECT id FROM ".DB_PREFIX."pages WHERE ref_id = '$id'");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$db->execQuery("DELETE FROM ".DB_PREFIX."pages_leaves WHERE page_id = '".$data['id']."'");
			}
			$db->execQuery("DELETE FROM ".DB_PREFIX."pages WHERE ref_id = '$id'");
			
			
			$f->setMessage("Page deleted!");
			$f->redirect("index.php");
			break;
		case "addLeaf":
			
			$id 				= $f->getValue("id");
			$leafDestination 	= $f->getValue("leafDestination");
			$pageId 			= $f->getValue("pageId");
			
			if($id > 0) {
				$db->execQuery("INSERT INTO ".DB_PREFIX."pages_leaves (`page_id`, `leaf_destination`, `leaf_id`)
													VALUES ('$pageId', '$leafDestination', '$id')");
			}
			
			$p->listLeaves($pageId, $leafDestination);
			break;
		case "removeLeaf":
			$id = $f->getValue("id");
			$pageId = $db->getValue("page_id", "pages_leaves", "id", $id);
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."pages_leaves WHERE id = '$id'");
			
			//$p->listLeaves($pageId);
			
			break;
		case "sort":
			
			//$newOrder = $f->getValue("data");
			$pageId = $f->getValue("pageId");
			$destination = $f->getValue("dest");
			
			
			list(,$leafDestination) = explode("_", $destination);
			$items = $_POST['item'];
			
			if(count($items) > 0) {
				foreach ($items as $order => $leaf_id) {
					$db->execQuery("UPDATE ".DB_PREFIX."pages_leaves SET `leaf_destination` = '$leafDestination',
															`order` = '$order'
															WHERE page_id = '$pageId' 
															AND leaf_id = '$leaf_id'");
				}
			}
			break;
	}
	
?>