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
					
					$category1 = $f->getCategoryByLang($category, $lang_id);
					$db->execQuery("INSERT INTO ".DB_PREFIX."node (`name`, `url`, `date`, `lang_id`, `category`, `ref_id`) 
													VALUES ('$langName $name', '$url', '$dateNow', '$lang_id', '$category1', '$last_insert')");
				}
				
				
				$f->setMessage("New article created!");
			} else {
				$f->setMessage("You must enter article title!", "error");
			}
			$f->redirect("nodeedit.php?id=".$last_insert."&catId=".$category);
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
					
					$f->setMessage("Article edited!");
				} else {
					$f->setMessage("You must enter article title!", "error");
				}
				
			} // end of foreach
			
			$f->redirect("index.php?".$catURL);
			break;
		case "delete":
			
			$id = $f->getValue("id");
			
			$db->execQuery("DELETE FROM ".DB_PREFIX."node WHERE id = '$id'");
			if($db->numRows("SELECT * FROM node WHERE ref_id ='$id'")>0){
				$query = $db->execQuery("SELECT * FROM node WHERE ref_id ='$id'");
				while($data = mysql_fetch_array($query, MYSQL_ASSOC)){
					$db->execQuery("DELETE FROM tags WHERE node_id = ".$data['id']);
				}
			}
			$db->execQuery("DELETE FROM ".DB_PREFIX."node WHERE ref_id = '$id'");
			$db->execQuery("DELETE FROM tags WHERE node_id = '$id'");
			
			
			$f->setMessage("Article deleted!");
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
		case "showMore":
			$limit= $f->getValue("limit");
			$catId = $f->getValue("catId");
			$lang_id = $f->getValue("lang_id");
			$offset = $f->getValue("offset");
			$n->listNodesView($offset, $limit, $catId);
			
			break;
			
		case "showMoreButton":
			$limit= $f->getValue("limit");
			$catId = $f->getValue("catId");
			$offset = $f->getValue("offset");
			$lang_id = $f->getValue("lang_id");
			$count = $f->getValue("count");
			if($db->numRows("SELECT * FROM node WHERE ref_id = '0' AND category = '$catId'")>$offset+$limit){
				?>
				<input class="submit" id="showMore" type="button" onclick="showMore(<?= $offset+$limit;?>,<?= $limit?>,<?= $lang_id;?>, <?= $catId;?>);" value="Show more">
				<?
			}
			break;
		case addTag:
			$node_id = $f->getValue('nodeId');
			$name = $f->getValue('name');
			$lang_id = $f->getValue('lang_id');
			$url = $f->generateUrlFromText($name);
			
			if($name !="" && $db->numRows("SELECT * FROM tags WHERE name= '$name' AND node_id = '$node_id'")== 0){
				$db->execQuery("INSERT INTO tags (node_id, name, url) VALUES('$node_id', '$name', '$url')");
				?>
				<li id="tag_<?= $db->insertId;?>">
					<label><?= $name;?></label>
					<a id="remove_id" onclick="removeTag('<?= $db->insertId;?>','<?= $node_id;?>', '<?= $lang_id;?>');" href="#">
						<img src="/kibocms/preset/actions_small/Trash.png">
					</a>
				</li>
			<?
			}
			break;
		case removeTag:
			$id = $f->getValue('id');
			$node_id = $f->getValue('node_id');
			$db->execQuery("DELETE FROM tags WHERE id='$id'");
			break;
	}
	
?>