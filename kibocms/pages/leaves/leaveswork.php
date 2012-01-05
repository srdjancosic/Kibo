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
			$parent = $f->getValue("parent");
			$lang_id = $f->getValue("lang_id");
			
			if(strlen($name) != 0) {
				$css_class = $f->generateUrlFromText($name);
				$db->execQuery("INSERT INTO ".DB_PREFIX."leaves (`name`, `css_class`, `lang_id`, `parent`) 
												VALUES ('$name', '$css_class', '$lang_id', '$parent')");
				$last_insert = $db->insertId;
				
				$lang_arr = $f->getActiveLanguages();
				foreach ($lang_arr as $lang_id => $langName) {
					$parent_id = $f->getLeafIdByRefId($parent, $lang_id);
					$db->execQuery("INSERT INTO ".DB_PREFIX."leaves (`name`, `css_class`, `lang_id`, `parent`, `ref_id`) 
													VALUES ('$langName $name', '$css_class', '$lang_id', '$parent_id', '$last_insert')");
					
				}
				
				$f->setMessage("New element created!");
				$f->redirect("leavesedit.php?id=".$last_insert);
			} else {
				$f->setMessage("You must enter element name!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
			$lang_arr = $f->getAllLanguages();
			
			foreach ($lang_arr as $lang_id => $langName) {
			
				$name = $f->getArrValue("name", $lang_id);
				$parent = $f->getArrValue("parent", $lang_id);
				$css_class = $f->getArrValue("css_class", $lang_id);
				$css_id = $f->getArrValue("css_id", $lang_id);
				
				$user_group = implode("," , $_POST["user_groups"][$lang_id]);
				
				
				$id = $f->getArrValue("id", $lang_id);
				
				if(strlen($name) != 0) {
					$db->execQuery("UPDATE ".DB_PREFIX."leaves SET `name` = '$name',
														`css_class` = '$css_class', 
														`css_id` = '$css_id', 
														`parent` = '$parent',
														`user_group` = '$user_group', 
														`lang_id` = '$lang_id' WHERE id = '$id'");
					$f->setMessage("Element edited!");
				} else {
					$f->setMessage("You must enter element name!", "error");
				}
			}
			$f->redirect("index.php");
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."leaves WHERE id = '$id'");
			$db->execQuery("DELETE FROM ".DB_PREFIX."leaves WHERE ref_id = '$id'");
			
			$f->setMessage("Leaf deleted!");
			$f->redirect("index.php");
			
			break;
			
		case "sort":
			
			//$newOrder = $f->getValue("data");
			$items = $_POST['item'];
			
			if(count($items) > 0) {
				foreach ($items as $order => $leaf_id) {
					$db->execQuery("UPDATE ".DB_PREFIX."leaves SET 
															`order` = '$order'
															WHERE id = '$leaf_id'");
				}
			}
			break;
			
			
		case "addLeafContent":
			
			$leafId = $f->getValue("id");
			$lang_id = $f->getValue("langId");
			$contentSrc = $f->getValue("contentSrc");
			
			if($contentSrc == "leaf_listing" || $contentSrc == "leaf_node") {
				$c = new Category();
				$c_categories = $c->listCategoriesCheckbox($lang_id);
			}
			if(substr($contentSrc, 0, 11) == "leaf_plugin") {
				$c_plugin = substr($contentSrc, 12, strlen($contentSrc)+1);
				$contentSrc = "leaf/plugins"; //.strtolower(substr($contentSrc, 11, strlen($contentSrc)+1))."/view";
			} 
			if(substr($contentSrc, 0, 9) == "leaf_view") {
				$what = substr($contentSrc, 10, strlen($contentSrc));
				list($c_name, $c_plugin) = explode("||", $what);
				$contentSrc = "leaf/pluginviews";
			}
			$contentSrc = str_replace("_", "/", $contentSrc);
			
			require("../../".$contentSrc.".php");
			//$htmlOutput = file_get_contents("../".$contentSrc.".php");
			
			//eval("\$htmlOutput = \"$htmlOutput\";");
			
			//echo $htmlOutput;
			
			break;
		case "saveLeafContent":
			
			$leafId = $f->getValue("id");
			$contentName = $f->getValue("content_name");
			$content = $f->getValue("content");
			
			/*$db->execQuery("INSERT INTO leaves_content (`leaf_id`, `content`, `content_name`)
												VALUES ('$leafId', '$content', '$contentName')");
			*/
			$content = str_replace("{AND}", "&", $content);
			$db->execQuery("UPDATE ".DB_PREFIX."leaves SET `content` = '".$content."', `content_type` = '$contentName' WHERE id = '".$leafId."'");
			
			break;
		case "removeLeafContent":
			
			$leafId = $f->getValue("id");
			
			$db->execQuery("UPDATE ".DB_PREFIX."leaves SET `content` = '', `content_type` = '' WHERE id = '".$leafId."'");
			break;
	}
	
?>