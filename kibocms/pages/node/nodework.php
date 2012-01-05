<?php

	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	$n  = new Node();

	$adminId = $f->loggedIn();
	
	$action = $f->getValue("action");
	$catId = $f->getValue("catId");
	$catURL = ($catId == 0 || $catId == "") ? "" : "catId=".$catId;
	switch ($action) {
		default:
			$f->redirect("index.php?".$catURL);
			break;
		case "add":
			$name = $f->getValue("name");
			$category = $f->getValue("category");
			$lang_id = $f->getValue("lang_id");
			
			if(strlen($name) != 0) {
				$url = $f->generateUrlFromText($name);
				$dateNow = date("Y-m-d H:i:s");
				$db->execQuery("INSERT INTO ".DB_PREFIX."node (`name`, `url`, `date`, `lang_id`, `category`) 
												VALUES ('$name', '$url', '$dateNow', '$lang_id', '$category')");
				$last_insert = $db->insertId;
				$lang_arr = $f->getActiveLanguages();
				foreach ($lang_arr as $lang_id => $langName) {
					
					$category = $f->getCategoryByLang($category, $lang_id);
					$db->execQuery("INSERT INTO ".DB_PREFIX."node (`name`, `url`, `date`, `lang_id`, `category`, `ref_id`) 
													VALUES ('$langName $name', '$url', '$dateNow', '$lang_id', '$category', '$last_insert')");
				}
				
				
				$f->setMessage("New content created!");
			} else {
				$f->setMessage("You must enter content title!", "error");
			}
			$f->redirect("nodeedit.php?id=".$last_insert."&".$catURL);
			break;
		case "edit":
			
			
			$lang_arr = $f->getAllLanguages();
			foreach ($lang_arr as $lang_id => $langName) {
				
				$name = $f->getArrValue("name", $lang_id);
				$picture = $f->getArrValue("picture", $lang_id);
				$url = $f->getArrValue("url", $lang_id);
				$short_desc = $f->getArrValue("short_desc", $lang_id);
				$long_desc = $f->getArrValue("long_desc", $lang_id);
				$category = $f->getArrValue("category", $lang_id);
				$id = $f->getArrValue("id", $lang_id);
				$node_keywords = $f->getArrValue("node_keywords", $lang_id);
				$node_description = $f->getArrValue("node_description", $lang_id);
				
				if(strlen($name) != 0) {
					$db->execQuery("UPDATE ".DB_PREFIX."node SET `name` = '$name',
													`picture` = '$picture',
													`short_desc` = '$short_desc', 
													`long_desc` = '$long_desc', 
													`url` = '$url', 
													`category` = '$category', 
													`node_keywords` = '$node_keywords',
													`node_description` = '$node_description',
													`lang_id` = '$lang_id' WHERE id = '$id'");
					if(count($_POST['cfv_'.$lang_id]) > 0) {
						foreach ($_POST['cfv_'.$lang_id] as $key => $value) {
							$exist = $db->numRows("SELECT * FROM ".DB_PREFIX."custom_fields WHERE node = '".$id."' AND `name` = '".$key."'");
							if($exist == 0) {
								$db->execQuery("INSERT INTO ".DB_PREFIX."custom_fields (`name`, `value`, `node`) VALUES
																		  ('$key', '$value', '$id')");
							} else {
								$db->execQuery("UPDATE ".DB_PREFIX."custom_fields SET `value` = '$value' WHERE node ='$id' AND `name` = '$key'");
							}
						}
					}
					
					$f->setMessage("Content edited!");
				} else {
					$f->setMessage("You must enter content title!", "error");
				}
				
			} // end of foreach
			
			$f->redirect("index.php?".$catURL);
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."node WHERE id = '$id'");
			$db->execQuery("DELETE FROM ".DB_PREFIX."node WHERE ref_id = '$id'");
			
			$f->setMessage("Content deleted!");
			$f->redirect("index.php?".$catURL);
			break;
		case "addCustomField":
			
			$name = $f->getValue("name");
			$value = $f->getValue("value");
			$id = $f->getValue("id");
			
			if(strlen($name) > 0 && strlen($value) > 0) {
				$db->execQuery("INSERT INTO ".DB_PREFIX."custom_fields (`name`, `value`, `node`)
													VALUES ('$name', '$value', '$id')");
			}
			
			$n->listCustomFields($id);
			break;
		case "editCustomField":
			
			$name = $f->getValue("name");
			$value = $f->getValue("value");
			$id = $f->getValue("id");
			
			if(strlen($name) > 0 && strlen($value) > 0) {
				$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."custom_fields WHERE node = '$id' AND name = '$name'");
				if(mysql_num_rows($query) == 1) {
					$db->execQuery("UPDATE ".DB_PREFIX."custom_fields SET `value` = '$value' WHERE `node` = '$id' AND name = '$name'");
				} else {
					$db->execQuery("INSERT INTO ".DB_PREFIX."custom_fields (`name`, `value`, `node`)
														VALUES ('$name', '$value', '$id')");
				}
			}
			
			//$n->listCustomFields($id);
			break;
		case "removeCustomField":
			$id = $f->getValue("id");
			$nodeId = $db->getValue("node", "custom_fields", "id", $id);
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."custom_fields WHERE id = '$id'");
			
			$n->listCustomFields($nodeId);
			
			break;
		case "sort":
			
			//$newOrder = $f->getValue("data");
			$items = $_POST['item'];
			
			if(count($items) > 0) {
				foreach ($items as $order => $leaf_id) {
					$db->execQuery("UPDATE ".DB_PREFIX."node SET 
															`order` = '$order'
															WHERE id = '$leaf_id'");
				}
			}
			break;
		case "changeOrderType":
			
			$orderType = $f->getValue("orderType");
			$catId = $f->getValue("catId");
			$_SESSION['order_type_nodes'] = $orderType;
			$f->redirect("index.php?catId=".$catId);
			
			break;
	}
	
?>