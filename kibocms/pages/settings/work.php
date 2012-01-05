<?php
	require_once("../../library/config.php");

	function upisi_decu ($name, $copy_lang_id ,$lang_id, $parent_id, $copy_id){
		$db = new Database();
		$query1 = $db->execQuery("SELECT * FROM category WHERE lang_id='".$lang_id."' AND parent='".$parent_id."'");
		
		while($data1 = mysql_fetch_array($query1)) {
			$pom_name=$name." ".$data1['name'];
			$pom_url=$data1['url'];
			$pom_href=$data1['href'];
			$pom_ref_id=$data1['id'];
			$pom_is_parent=$data1['is_parent'];
			$db->execQuery("INSERT INTO category (`name`, `url`, `href`, `ref_id`, `lang_id`, `is_parent`, `parent`) 
							VALUES ('$pom_name', '$pom_url', '$pom_href', '$pom_ref_id', '$copy_lang_id', '$pom_is_parent', '$copy_id')");
			$pom_copy_id = $db->insertId;
					
			$node=$db->execQuery("SELECT * FROM node WHERE lang_id='".$lang_id."' AND category='".$pom_ref_id."'");
			while($data_node = mysql_fetch_array($node)){
				$pom_node_name = $name." ".$data_node['name'];
				$pom_node_picture = $data_node['picture'];
				$pom_node_short_desc = $data_node['short_desc'];
				$pom_node_long_desc = $data_node['long_desc'];
				$pom_node_category = $pom_copy_id;
				$pom_node_date = $data_node['date'];
				$pom_node_lang_id = $copy_lang_id;
				$pom_node_url = $data_node['url'];
				$pom_node_ref_id = $data_node['id'];
				$db->execQuery("INSERT INTO node (`name`, `picture`, `short_desc`, `long_desc`, `category`, `date`, `lang_id`, `url`, `ref_id`)
								VALUES('$pom_node_name', '$pom_node_picture', '$pom_node_short_desc', '$pom_node_long_desc', '$pom_node_category',
										'$pom_node_date', '$pom_node_lang_id', '$pom_node_url', '$pom_node_ref_id')");
												
				$pom_node_id = $db->insertId;//id kopije noda na novom jeziku
				$cf_dump = $db->execQuery("SELECT * FROM custom_fields WHERE custom_fields.node='".$pom_node_ref_id."'");
				while($cf=mysql_fetch_array($cf_dump)){
					$pom_cf1_name=$cf['name'];
					$pom_cf1_value=$cf['value'];
					$db->execQuery("INSERT INTO custom_fields (`name`, `value`, `node`)
									VALUES ('$pom_cf1_name', '$pom_cf1_value', $pom_node_id)");
				}
			}
					
			$c_filds=$db->execQuery("SELECT * FROM category_custom_fields WHERE category='".$pom_ref_id."'");
			while($data_cf=mysql_fetch_array($c_filds)){
				$pom_cf_name=$data_cf['name'];
				$pom_cf_type=$data_cf['type'];
				$db->execQuery("INSERT INTO category_custom_fields (`name`,`type`, `category`)
								VALUES ('$pom_cf_name', '$pom_cf_type', '$pom_copy_id')");
			}
					
			if($pom_is_parent==1){
				upisi_decu($name, $copy_lang_id, $lang_id, $pom_ref_id, $pom_copy_id);
			}
		}
	}
	
	function upisi_podelement($defolt_lang, $last_insert, $pom_ref_id, $pom_copy_id){
		$db = new Database();
		$query1 = $db->execQuery("SELECT * FROM leaves WHERE lang_id='".$defolt_lang."' AND parent='".$pom_ref_id."' AND ref_id=0");
		
		while($data1 = mysql_fetch_array($query1)){
			$pom_name=$data1['name'];
			$pom_css_class = $data1['css_class'];
			$pom_css_id = $data1['css_id'];
			$pom_content_type = $data1['content_type'];
			$pom_ref_id = $data1['id'];//id leaves-a koji je original na difolt jeziku
			$pom_order= $data1['order'];
			$pom_content = $data1['content'];
			$db->execQuery("INSERT INTO leaves (`name`, `css_class`, `css_id`, `lang_id`, `parent`, `ref_id`, `content_type`, `content`, `order`)
							VALUES ('$pom_name', '$pom_css_class', '$pom_css_id', '$last_insert', '$pom_copy_id', '$pom_ref_id', '$pom_content_type', '$pom_content', '$pom_order')");
			$pom_copy_id_new = $db->insertId;
			upisi_podelement($defolt_lang, $last_insert, $pom_ref_id, $pom_copy_id_new);
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
				$db->execQuery("INSERT INTO languages (`name`, `lang_code`, `active`) VALUES ('$name', '$lang_code', '1')");
				$last_insert = $db->insertId; // ovo je ID novog jezika koji si dodao
				$db->execQuery("INSERT INTO settings (`lang_id`) VALUES ('$last_insert')");
				$defolt_lang=$f->getDefaultLanguage(); //ovo mi je ID difoltnog jezika od kojeg pravim kopije
				//upit koji vadi sve kategorije koje nemaju roditelja
				$query = $db->execQuery("SELECT * FROM category WHERE lang_id='".$defolt_lang."' AND parent=0");
				while($data = mysql_fetch_array($query)) {
					$pom_id = $data['id'];
					$pom_name = $name." ".$data['name'];
					$pom_url = $data['url'];
					$pom_href = $data['href'];
					$pom_ref_id = $data['id']; //id originalne kategorije
					$pom_is_parent = $data['is_parent'];
					$db->execQuery("INSERT INTO category (`name`, `url`, `href`, `ref_id`, `lang_id`, `is_parent`, `parent`) 
									VALUES ('$pom_name', '$pom_url', '$pom_href', '$pom_ref_id', '$last_insert', '$pom_is_parent', 0)");
					$pom_copy_id = $db->insertId; //id novokreirane kopije (kategorija u novom jeziku)
					//upiujem sve cast fildove iz ove kategorije
					$c_filds=$db->execQuery("SELECT * FROM category_custom_fields WHERE category='".$pom_ref_id."'");
					while($data_cf = mysql_fetch_array($c_filds)){
						$pom_cf_name = $data_cf['name'];
						$pom_cf_type = $data_cf['type'];
						$db->execQuery("INSERT INTO category_custom_fields (`name`,`type`, `category`)
										VALUES ('$pom_cf_name', '$pom_cf_type', '$pom_copy_id')");
					}
					
					$node=$db->execQuery("SELECT * FROM node WHERE lang_id='".$defolt_lang."' AND category='".$pom_ref_id."'");
					while($data_node = mysql_fetch_array($node)){
						$pom_node_name = $name." ".$data_node['name'];
						$pom_node_picture = $data_node['picture'];
						$pom_node_short_desc = $data_node['short_desc'];
						$pom_node_long_desc = $data_node['long_desc'];
						$pom_node_category = $pom_copy_id;
						$pom_node_date = $data_node['date'];
						$pom_node_lang_id = $last_insert;
						$pom_node_url = $data_node['url'];
						$pom_node_ref_id = $data_node['id'];
						$db->execQuery("INSERT INTO node (`name`, `picture`, `short_desc`, `long_desc`, `category`, `date`, `lang_id`, `url`, `ref_id`)
										VALUES('$pom_node_name', '$pom_node_picture', '$pom_node_short_desc', '$pom_node_long_desc', '$pom_node_category',
												'$pom_node_date', '$pom_node_lang_id', '$pom_node_url', '$pom_node_ref_id')");
						$pom_node_id = $db->insertId;//id kopije noda na novom jeziku
						$cf_dump = $db->execQuery("SELECT * FROM custom_fields WHERE custom_fields.node='".$pom_node_ref_id."'");
						while($cf=mysql_fetch_array($cf_dump)){
							$pom_cf1_name=$cf['name'];
							$pom_cf1_value=$cf['value'];
							$db->execQuery("INSERT INTO custom_fields (`name`, `value`, `node`)
											VALUES ('$pom_cf1_name', '$pom_cf1_value', $pom_node_id)");
						}
					}
					//obradjujem sve kategorije kojima je ova roditelj
					if($pom_is_parent==1){
						upisi_decu($name, $last_insert, $defolt_lang, $pom_id, $pom_copy_id);
					}
				}
				//kopiranje leaves (onih koji nemaju roditeljske)
				$query= $db->execQuery("SELECT * FROM leaves WHERE parent=0 AND lang_id='".$defolt_lang."'");
				while($data=mysql_fetch_array($query)){
					$pom_name=$data['name'];
					$pom_css_class = $data['css_class'];
					$pom_css_id = $data['css_id'];
					$pom_lang_id = $last_insert;
					$pom_content_type= $data['content_type'];
					$pom_order = $data['order'];
					$pom_content= $data['content'];
					$pom_ref_id = $data['id'];//id leaves-a koji je original na difolt jeziku
					$db->execQuery("INSERT INTO leaves (`name`, `css_class`, `css_id`, `lang_id`, `parent`, `ref_id`, `content_type`, `order`,`content`)
									VALUES ('$pom_name', '$pom_css_class', '$pom_css_id', '$pom_lang_id', 0, '$pom_ref_id', '$pom_content_type', '$pom_order', '$pom_content')");
					$pom_copy_id = $db->insertId;
					upisi_podelement($defolt_lang, $pom_lang_id, $pom_ref_id, $pom_copy_id); //difoltni jezik, novi jezik, id originalnog elementa koji je kopiran, id kopije
				}
				
				//sredjivane tabele pages
				$query=$db->execQuery("SELECT * FROM pages WHERE lang_id='".$defolt_lang."'");
				while($data=mysql_fetch_array($query)){
					$pom_name=$name." ".$data['name'];
					$pom_url=$data['url'];
					$pom_ref_id=$data['id'];
					$pom_header = $data['header'];
					$pom_content= $data['content'];
					$pom_footer = $data['footer'];
					//hvatanje novog hedera
					$query1=$db->execQuery("SELECT * FROM leaves WHERE ref_id='$pom_header'");
					$data1=mysql_fetch_array($query1);
					$pom_header=$data1['id'];
					//hvatanje novog contenta
					$query2=$db->execQuery("SELECT * FROM leaves WHERE ref_id='$pom_content'");
					$data2=mysql_fetch_array($query2);
					$pom_content=$data2['id'];
					//hvatanje novof footera
					$query3=$db->execQuery("SELECT * FROM leaves WHERE ref_id='$pom_footer'");
					$data3=mysql_fetch_array($query3);
					$pom_footer=$data3['id'];
					$db->execQuery("INSERT INTO pages (`name`, `url`, `lang_id`, `header`, `footer`,`content`, `ref_id`)
									VALUES ('$pom_name', '$pom_url', '$last_insert', '$pom_header','$pom_footer', '$pom_content', '$pom_ref_id')");
				}
				
				//srdjivanje table pages_leaves
				$query = $db->execQuery("SELECT * FROM pages_leaves WHERE page_id IN (SELECT id FROM pages WHERE lang_id='".$defolt_lang."')");
				while($data=mysql_fetch_array($query)){
					$pom_page_id = $data['page_id'];
					$pom_leaf_id = $data['leaf_id'];
					$pom_leaf_destination= $data['leaf_destination'];
					$pom_order = $data['order'];
					
					$query1=$db->execQuery("SELECT * FROM pages WHERE ref_id='$pom_page_id'");
					$data1=mysql_fetch_array($query1);
					$pom_page_id=$data1['id'];
					
					$query2=$db->execQuery("SELECT * FROM leaves WHERE ref_id='$pom_leaf_id'");
					$data2=mysql_fetch_array($query2);
					$pom_leaf_id=$data2['id'];
					
					$query3=$db->execQuery("SELECT * FROM leaves WHERE ref_id='$pom_leaf_destination'");
					$data3=mysql_fetch_array($query3);
					$pom_leaf_destination=$data3['id'];
					
					$db->execQuery("INSERT INTO pages_leaves (`page_id`, `leaf_id`, `leaf_destination`, `order`)
									VALUES ('$pom_page_id', '$pom_leaf_id', '$pom_leaf_destination', '$pom_order')");
				}
				
				$f->setMessage("New language created");
				$f->redirect("index.php");
			} else {
				$f->setMessage("You must enter language name and language code!", "error");
				$f->redirect("index.php");
			}
			
			break;
		case "edit":
			
			$name = $f->getValue("name");
			$lang_code = $f->getValue("lang_code");
			$active = $f->getValue("active");
			$default = $f->getValue("default");
			$id = $f->getValue("id");
			
			if($name != "" && $lang_code != "") {
				
				if($default == 1) {
					$db->execQuery("UPDATE languages SET `default` = '0'"); 
				}
				$db->execQuery("UPDATE languages SET `name` = '$name',
													 `lang_code` = '$lang_code',
													 `active` = '$active',
													 `default` = '$default'
													 WHERE id = '$id'");
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