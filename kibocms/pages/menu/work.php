<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();

	$action = $f->getValue("action");
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		
		case "add":
			
			$name = $f->getValue("name");
			$parent = $f->getValue("parent");
			$lang_id = $f->getValue("lang_id");
			$content_type = "menu";
			
			if(strlen($name) != 0) {
				$css_class = $f->generateUrlFromText($name);
				$db->execQuery("INSERT INTO ".DB_PREFIX."leaves (`name`, `css_class`, `lang_id`, `parent`, `content_type`) 
												VALUES ('$name', '$css_class', '$lang_id', '$parent', '$content_type')");
				$last_insert = $db->insertId;
				
				$lang_arr = $f->getActiveLanguages();
				foreach ($lang_arr as $lang_id => $langName) {
					$parent_id = $f->getLeafIdByRefId($parent, $lang_id);
					$db->execQuery("INSERT INTO ".DB_PREFIX."leaves (`name`, `css_class`, `lang_id`, `parent`, `ref_id`, `content_type`) 
													VALUES ('$langName $name', '$css_class', '$lang_id', '$parent_id', '$last_insert', '$content_type')");
					
				}
				
				$f->setMessage("New menu created!");
				$f->redirect("edit.php?id=".$last_insert);
			} else {
				$f->setMessage("You must enter menu name!", "error");
				$f->redirect("index.php");
			}
			
			break;	
			
		case "listCategories":
			
			$lang_id = $f->getValue("lang_id");
			$query = $db->execQuery("SELECT * FROM category WHERE lang_id = '$lang_id' ORDER BY id DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				echo "<option value=\"category:".$data['id']."\">".stripslashes($data['name'])."</option>";
			}
			
			break;
		
		case "listPages":
			
			$lang_id = $f->getValue("lang_id");
			$query = $db->execQuery("SELECT * FROM pages WHERE lang_id = '$lang_id' ORDER BY id DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				echo "<option value=\"page:".$data['id']."\">".stripslashes($data['name'])."</option>";
			}
			
			break;
		case "listNodes":
			
			$lang_id = $f->getValue("lang_id");
			$category_id = $f->getValue("category_id");
			$query = $db->execQuery("SELECT * FROM node WHERE category = '$category_id' AND lang_id = '$lang_id' ORDER BY id DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				echo "<option value=\"node:".$data['id']."\">".stripslashes($data['name'])."</option>";
			}
			
			break;
		case "sort":
			$items = $_POST['item'];
			$childs = $_POST['parent_item'];
			$menu_id = $f->getValue("menu_id");
			
			//var_dump($items);
			
			//die();
			
			if(count($items) > 0 && $items[0][0] != "") {
				$new_order = array();
				foreach ($items as $order => $value) {
					$new_order[$order][0] = $value;
					$j = 1;
					if(count($childs) > 0) {
						foreach ($childs as $key => $child) {
							list($parent, $child_value,) = explode("[", $child);
							if($value == $parent) {
								$new_order[$order][$j] = substr($child_value, 0, strlen($child_value)-1);
								$j++;
							}
						}
					}
				}
				
				echo serialize($new_order);
				//print_r( json_encode(array_map("base64_encode", $new_order)) );
				$new_order_string = implode(";", $new_order);
				/*
				$old_content = $db->getValue("content", "leaves", "id", $menu_id);
				list($arg_1, $arg_2, $menu_items) = explode("|:|", $old_content);
				$new_content = $arg_1."|:|".$arg_2."|:|".$new_order_string;
				*/
				
			
				$new_content = serialize($new_order);
				$db->execQuery("UPDATE leaves SET content = '".$new_content."' WHERE id = '$menu_id'");
				
			} else {
				$db->execQuery("UPDATE leaves SET content = '' WHERE id = '$menu_id'");
			}
			break;
	}
	
?>