<?php
	require_once("../../library/config.php");

	function insert_categories ($new_lang_name, $new_lang_id ,$default_lang, $old_parent_id, $category_id){
		
		$categories_collection = new Collection("category");
				
		$categories = $categories_collection->getCollection("WHERE lang_id = '$default_lang' AND parent = '$old_parent_id'");
		
		foreach ($categories as $key => $category) {
			$newCategory = $category->doClone();
			$newCategory->name = $new_lang_name." ".$category->name;
			$newCategory->ref_id = $category->id;
			$newCategory->lang_id = $new_lang_id;
			$newCategory->parent = $category_id;
			
			$newCategory->Save();
			
			$category_custom_fields_collection = new Collection("category_custom_fields");
			$category_custom_fields = $category_custom_fields_collection->getCollection("WHERE category = '$category->id'");
			foreach ($category_custom_fields as $key1 => $category_custom_field) {
				$newCategory_custom_field = $category_custom_field->doClone();
				$newCategory_custom_field->category = $newCategory->id;
				
				$newCategory_custom_field->Save();
			}
			
			$nodes_collection = new Collection("node");
			$nodes = $nodes_collection->getCollection("WHERE lang_id = '$default_lang' AND category = '$category->id'");
			
			foreach ($nodes as $key1 => $node) {
				$newNode = $node->doClone();
				
				$newNode->name = $new_lang_name." ".$node->name;
				$newNode->category = $newCategory->id;
				$newNode->lang_id = $newCategory->lang_id;
				$newNode->ref_id = $node->id;
				
				$newNode->Save();
				
				$custom_fields_collection = new Collection("custom_fields");
				$custom_fields = $custom_fields_collection->getCollection("WHERE custom_fields.node = '$node->id'");
				
				foreach ($custom_fields as $key2 => $custom_field) {
					$newCustomField = $custom_field->doClone();
					
					$newCustomField->node = $newNode->id;
					
					$newCustomField->Save();
				}
				
			}	
			if($newCategory->is_parent == 1){
				insert_categories($new_lang_name, $new_lang_id, $default_lang, $category->id, $newCategory->id);
			}
		}
	}
	
	function insert_leaves($default_lang, $new_lang_id, $old_parent_id, $new_leaf){
		
		$leaves_collection = new Collection("leaves");
		$leaves = $leaves_collection->getCollection("WHERE lang_id = '$default_lang' AND parent = '$old_parent_id'");
		foreach ($leaves as $key => $leaf) {
			$newLeaf = $leaf->doClone();
			
			$newLeaf->ref_id = $leaf->id;
			$newLeaf->parent = $new_leaf;
			$newLeaf->lang_id = $new_lang_id;
			
			$newLeaf->Save();
			
			insert_leaves($default_lang, $new_lang_id, $leaf->id, $newLeaf->id);
		}	
	}
	
	$db = new Database();
	$f  = new Functions();
	
	$action = $f->getValue("action");
	
	$adminId = $f->loggedIn();
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
			
		case "new_language":
			
			$name = $f->getValue("name");
			$lang_code = $f->getValue("lang_code");
			$lang_code = strtolower($lang_code);
			
			if($name != "" && $lang_code != "") {
				
				$language = new View("languages");
				
				$language->name = $name;
				$language->lang_code = $lang_code;
				$language->active = "1";
				
				$language->Save();
				
				$last_insert = $language->id; // ovo je ID novog jezika koji si dodao (proveriti da li je uopste potrebno)
				
				$settings = new View("settings");
				
				$settings->lang_id = $language->id;
				
				$settings->Save();
				
				$default_lang= $f->getDefaultLanguage(); //ovo mi je ID difoltnog jezika od kojeg pravim kopije
				
				insert_categories($name, $language->id, $default_lang, 0, 0);
				
				insert_leaves ($default_lang, $language->id, 0, 0);
				
				
				//sredjivane tabele pages
				
				$pages_collection = new Collection("pages");
				$pages = $pages_collection->getCollection(" WHERE lang_id = '$default_lang'");
				
				foreach ($pages as $key => $page) {
					$newPage = $page->doClone();
					
					$newPage->name = $name." ".$page->name;
					$newPage->ref_id = $page->id;
					$newPage->lang_id = $language->id;
					
					$header = new View ("leaves", $page->header, "ref_id");
					$newPage->header = $header->id;
					
					$foooter = new View ("leaves", $page->footer, "ref_id");
					$newPage->footer = $foooter->id;
					
					$content = new View("leaves", $page->content, "ref_id");
					$newPage->content = $content->id;
					
					$newPage->Save();
					
					$page_leaves_collection = new Collection("pages_leaves");
					$page_leaves = $page_leaves_collection->getCollection("WHERE page_id = '$page->id'");
					foreach ($page_leaves as $key1 => $page_leaf) {
						$new_page_leaf = $page_leaf->doClone();
						
						$new_page_leaf->page_id = $newPage->id;
						echo "ovde sam prosao..<br />";
						$leaf = new View("leaves", $page_leaf->leaf_id, "ref_id");
						$new_page_leaf->leaf_id = $leaf->id;
						
						$destination = new View("leaves", $page_leaf->leaf_destination, "ref_id");
						$new_page_leaf->leaf_destination = $destination->id;
						
						$new_page_leaf->Save();
					}
				}
				
				
				$f->setMessage("New language created");
				$f->redirect("index.php");
			} else {
				$f->setMessage("You must enter language name and language code!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
			
			$language = new View("languages");
			$language->extend($_POST);
			
			if($language->name != "" && $language->lang_code != ""){
				
				if($language->default == 1){
					$old_default_language = new View("languages", "1", "default");
					$old_default_language->default = 0;
					$old_default_language->Save();
				}
				
				$language->Save();
				
				$f->setMessage("Changes saved!");
				$f->redirect("index.php");
				
			} else {
				$f->setMessage("You must enter language name and language code!", "error");
				$f->redirect("edit.php?id=".$id);
			}
			break;
		
		case "delete":
			
			$id = $f->getValue("id");
			$default = $db->getValue("default", "languages", "id", $id);
			if($default == 1) {
				$f->setMessage("You can not delete default language!", "error");
				$f->redirect("index.php");
			} else {
				$db->execQuery("DELETE FROM languages WHERE id = '$id'");
				$db->execQuery("DELETE FROM settings WHERE lang_id = '$id'");
				$db->execQuery("DELETE FROM category WHERE lang_id='$id'");
				$db->execQuery("DELETE FROM category_custom_fields WHERE category_custom_fields.category NOT IN (SELECT id FROM category)");
				$db->execQuery("DELETE FROM node WHERE lang_id = '$id'");
				$db->execQuery("DELETE FROM custom_fields WHERE custom_fields.node NOT IN (SELECT id FROM node)");
				$db->execQuery("DELETE FROM leaves WHERE lang_id='$id'");
				$db->execQuery("DELETE FROM pages WHERE lang_id='$id'");
				$db->execQuery("DELETE FROM pages_leaves WHERE page_id NOT IN (SELECT id FROM pages)");
				$f->setMessage("Language deleted!");
				$f->redirect("index.php");
			}
			
			break;
		case "settings":
			
			$lang_arr = $f->getAllLanguages();
			foreach ($lang_arr as $lang_id => $value) {
				$title = $f->getArrValue("title", $lang_id);
				$keywords = $f->getArrValue("keywords", $lang_id);
				$description = $f->getArrValue("description", $lang_id);
				$head_js = $f->getArrValue("head_js", $lang_id);
				$footer_js = $f->getArrValue("footer_js", $lang_id);
				$pagination_url = $f->getArrValue("pagination_url", $lang_id);
				$lang_id = $f->getArrValue("lang_id", $lang_id);
				
				$db->execQuery("UPDATE settings SET `site_title` = '$title',
													`site_keywords` = '$keywords',
													`site_description` = '$description',
													`head_js` = '$head_js',
													`pagination_url` = '$pagination_url',
													`footer_js` = '$footer_js'
													WHERE lang_id = '$lang_id'
				");
			}
			$f->redirect("index.php");
			
			break;
	}
?>