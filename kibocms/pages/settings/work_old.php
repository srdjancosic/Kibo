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
					while($data_cf=mysql_fetch_array($c_filds)){
						$pom_cf_name=$data_cf['name'];
						$pom_cf_type=$data_cf['type'];
						$db->execQuery("INSERT INTO category_custom_fields (`name`,`type`, `category`)
										VALUES ('$pom_cf_name', '$pom_cf_type', '$pom_copy_id')");
					}
					//obradjujem sve kategorije kojima je ova roditelj
					if($pom_is_parent==1){
						upisi_decu($name, $last_insert, $defolt_lang, $pom_id, $pom_copy_id);
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
				$lang_id = $f->getArrValue("lang_id", $lang_id);
				
				$db->execQuery("UPDATE settings SET `site_title` = '$title',
													`site_keywords` = '$keywords',
													`site_description` = '$description',
													`head_js` = '$head_js',
													`footer_js` = '$footer_js'
													WHERE lang_id = '$lang_id'
				");
			}
			$f->redirect("index.php");
			
			break;
	}
?>