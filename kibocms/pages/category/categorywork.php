<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	$cat= new Category();

	$adminId = $f->loggedIn();
	
	$action = $f->getValue("action");
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		case "add":
			$name = $f->getValue("name");
			$href = $f->getValue("href");
			$parent = $f->getValue("parent");
			$page_id = $f->getValue("page_id");
			$lang_id = $f->getDefaultLanguage();
			$has_dimensions = $f->getValue("has_dimensions");
			
			if(strlen($name) != 0) {
				$url = $f->generateUrlFromText($name);
				if($has_dimensions == "1") {
					if(mkdir($basePathForUpload."/".$url)) {
						mkdir($basePathForUpload."/".$url."/_thumb");
					} else { 
						$f->setMessage("Could not create directory for files!");
						$f->redirect("index.php");
						die();
					}
				}
				
				if($parent != 0) {
					$db->execQuery("UPDATE ".DB_PREFIX."category SET is_parent = '1' WHERE id = '$parent'");
				}
				
				$db->execQuery("INSERT INTO ".DB_PREFIX."category (`name`, `url`, `href`, `parent`, `lang_id`, `page_id`, `has_dimensions`) 
												VALUES ('$name', '$url', '$href', '$parent', '$lang_id', '$page_id', '$has_dimensions')");
				
				$ref_id = $db->insertId;
				$f->setMessage("New category created!");
				
				$languages = $f->getActiveLanguages();
				foreach ($languages as $langId => $langName) {
					
					$db->execQuery("INSERT INTO ".DB_PREFIX."category (`name`, `url`, `href`, `parent`, `lang_id`, `page_id`, `has_dimensions`, `ref_id`) 
													VALUES ('$langName $name', '$url', '$href', '$parent', '$langId', '$page_id', '$has_dimensions', '$ref_id')");
					
				}
				
				$f->redirect("categoryedit.php?id=".$ref_id);
				
			} else {
				$f->setMessage("You must enter category name!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
			/*$name = $f->getValue("name");
			$url = $f->getValue("url");
			$href = $f->getValue("href");
			$parent = $f->getValue("parent");
			$page_id = $f->getValue("page_id");
			$page_single = $f->getValue("page_single");
			$lang_id = $f->getValue("lang_id");
			$id = $f->getValue("id");
			$kiboeasy = $f->getValue("kiboeasy");
			$isParent = 0;
			*/
			
			$lang_arr = $f->getAllLanguages();
			
			foreach ($lang_arr AS $lang_id => $lang_name) {
			
				$name = $f->getArrValue("name", $lang_id);
				$url = $f->getArrValue("url", $lang_id);
				$href = $f->getArrValue("href", $lang_id);
				$parent = $f->getArrValue("parent", $lang_id);
				$page_id = $f->getArrValue("page_id", $lang_id);
				$page_single = $f->getArrValue("page_single", $lang_id);
				$id = $f->getArrValue("id", $lang_id);
				$kiboeasy = $f->getArrValue("kiboeasy", $lang_id);
				$category_keywords = $f->getArrValue("category_keywords", $lang_id);
				$category_description = $f->getArrValue("category_description", $lang_id);
				
				if(strlen($name) != 0) {
					if($parent != 0) {
						$db->execQuery("UPDATE ".DB_PREFIX."category SET is_parent = '1' WHERE id = '$parent'");
					} else {
						$old_parent = $db->getValue("parent", "category", "id", $id);
						$isParent = $db->getValue("is_parent", "category", "id", $old_parent);
						if($isParent != 0) {
							$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."category WHERE parent = '$old_parent'");
							if(mysql_num_rows($query) == 1) {
								$data = mysql_fetch_array($query, MYSQL_ASSOC);
								$db->execQuery("UPDATE ".DB_PREFIX."category SET is_parent = '0' WHERE id = '$old_parent'");
							}
						}
					}
					$db->execQuery("UPDATE ".DB_PREFIX."`category` SET `name` = '$name',
														`url` = '$url', 
														`href` = '$href', 
														`parent` = '$parent', 
														`page_id` = '$page_id',
														`page_single` = '$page_single',
														`kiboeasy` = '$kiboeasy',
														`category_keywords` ='$category_keywords',
														`category_description` = '$category_description',
														`lang_id` = '$lang_id' WHERE id = '$id'");
					$f->setMessage("Category edited!");
				} else {
					$f->setMessage("You must enter category name!", "error");
				}
			
			}
			$f->redirect("index.php");
			break;
		case "delete":
			
			$id = $f->getValue("id");
			$isParent = $db->getValue("is_parent", "category", "id", $id);
			$parent = $db->getValue("parent", "category", "id", $id);
			
			if($isParent == 0) {
				$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."category WHERE parent = '$parent'");
				if(mysql_num_rows($query) == 1) {
					$db->execQuery("UPDATE ".DB_PREFIX."category SET is_parent = '0' WHERE id = '$parent'");
				}
				$db->execQuery("DELETE FROM ".DB_PREFIX."category WHERE id = '$id'");
				$db->execQuery("DELETE FROM ".DB_PREFIX."category WHERE ref_id = '$id'");
				$f->setMessage("Category deleted!");
			} else {
				$f->setMessage("This category has children!", "error");
			}
			$f->redirect("index.php");
			
			break;
		case "addCustomField":
			$categoryId = $f->getValue("id");
			
			$field_name = $f->getValue("name");
			$field_type = $f->getValue("type");
			
			if($field_name != "" && $field_type != "") {
				$db->execQuery("INSERT INTO ".DB_PREFIX."category_custom_fields (`name`, `type`, `category`)
															VALUES ('$field_name', '$field_type', '$categoryId')");
				
				$f->setMessage("Category custom field created!");
			} else {
				$f->setMessage("You must specify field name and type!");
			}
			
			$cat->listCategoryCustomFieldsView($categoryId);
			
			break;
		case "deleteCustomField":
			
			$id = $f->getValue("id");
			$categoryId = $db->getValue("category", "category_custom_fields", "id", $id);
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."category_custom_fields WHERE id = '$id'");
			
			$f->setMessage("Category custom field deleted!");
			
			$ref_id = $f->getCategoryRefId($categoryId);
			
			$f->redirect("categoryedit.php?id=".$ref_id."");
			
			break;
			
		case "createDimmension":
			
			$new_x = $f->getValue('new_x');
			$new_y = $f->getValue('new_y');
			$catId = $f->getValue('catId');
			$catName = $db->getValue("name", "category", "id", $catId);
			$current = $basePathForUpload."/".$catName;
			
			if(mkdir($current."/".$new_x."x".$new_y))
				$f->getFolderChilds($current);
			else 
				echo 0;
			
			break;
		case "removeDimension":
			
			$path = $_POST['file'];
			
			if(rmdir($path)) {
				echo "1";
			} else {
				//echo "0";
			}
			
			break;
	}
	
?>