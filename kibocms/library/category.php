<?php

class Category extends Functions {
	
	public $selected, $parent;
	
	function __construct() {
		
	}
	
	function listCategoriesSelect($selected = 0, $lang_id) {
	global $id; 
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category WHERE lang_id = '$lang_id'" );
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			if($id != $data['id']) {
				?>
				<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
				<?php
			}
		}
	}
	
	function listPagesSelect($selected = "", $lang_id) {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."pages WHERE lang_id = '$lang_id'");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<option value="<?= $data['url']; ?>" <?= ($data['url'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
			<?php
		}
	}
	
	// used in widgets
	function listCategoriesCheckbox($lang_id) {
		
		//if($selected != "") {
			$selectedArray = array();
			//try {
			$selectedArray = explode(",", $this->selected); 
			//} catch (Exception $e) {}
			
			$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category");
			$str = "";
			
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$isSelected = (in_array($data['id'], $selectedArray) == true) ? "checked=\"checked\"" : "";
				
				$str .= "<input type=\"checkbox\" class=\"cat_".$lang_id."\" name=\"selectedCategories\" "
					 . "value=\"".$data['id']."\" "
					 .$isSelected .">".$data['name']." ";
			}
		//}
		return $str;
	}
	
	function listCategoriesView($selected = 0) {
		$addonSQL = ($this->parent != "" && $this->parent != 0) ? " AND `parent`= '".$this->parent."'" : "";
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category WHERE ref_id = '0' ".$addonSQL." ORDER BY `id` DESC");
		$odd = 0;
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			if($odd % 3 == 0) {
				//echo "<br clear='all'>";
			}
			$odd++;
			?>
			<div class="box_1">
				<div class="inner">
				<h3><?= stripslashes($data['name']); ?></h3>
				/<?= ($data['href'] == "") ? $data['url'] : $data['href']; ?>
				
				<br /><?= Database::getValue("name", "category", "id", $data['parent']); ?>
				</div>
				<div class="buttons">
					<a class="tooltip" title="View nodes" href="/kibocms/pages/node/?catId=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/lupa.png"></a>
					
					<?php 
					if(Functions::adminAllowed("categories", "edit")) {
					?>
					<a class="tooltip" title="Edit" href="categoryedit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
					<?php }
					if(Functions::adminAllowed("categories", "delete")) {
					?>
					<a class="tooltip" title="Delete" onclick="return confirm('Are you sure?');" href="categorywork.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
					<?php
					}
					?>
				</div>
			</div>
			<?php
		}
	}

	function listCategoriesViewBackEnd($selected = 0) {
		$addonSQL = ($this->parent != "" && $this->parent != 0) ? " AND `parent`= '".$this->parent."'" : "";
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category WHERE ref_id = '0' ".$addonSQL." ORDER BY `id` DESC");
		$odd = 0;
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			if($odd % 3 == 0) {
				//echo "<br clear='all'>";
			}
			$odd++;
			?>
			<div class="box_1">
				<div class="inner">
				<h3><?= strip_tags(stripslashes($data['name'])); ?></h3>
				/<?= ($data['href'] == "") ? $data['url'] : $data['href']; ?>
				
				<br /><?= Database::getValue("name", "category", "id", $data['parent']); ?>
				</div>
				<div class="buttons">
					<a class="tooltip" title="View nodes" href="/kibocms/pages/node/?catId=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/lupa.png"></a>
					
					<?php 
					if(Functions::adminAllowed("categories", "edit")) {
					?>
					<a class="tooltip" title="Edit" href="categoryedit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
					<?php }
					if(Functions::adminAllowed("categories", "delete")) {
					?>
					<a class="tooltip" title="Delete" onclick="return confirm('Are you sure?');" href="categorywork.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
					<?php
					}
					?>
				</div>
			</div>
			<?php
		}
	}

	function getCategoryValues($id, $lang_id = 0) {
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category WHERE (id = '$id' OR ref_id = '$id') AND lang_id = '$lang_id'");
		$data = mysql_fetch_array($query);
		
		return $data;
	}
	
	function listCategoryCustomFieldsView($category = 0) {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category_custom_fields WHERE category = '$category'");
		$odd = 1;
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			if($odd == 1) {
				$class = "class=\"odd\"";
				$odd = 0;
			} else {
				$class = "";
				$odd = 1;
			}
			?>
			<tr <?= $class; ?>>
				<td><?= $data['id']; ?></td>
				<td><?= ucfirst($data['name']); ?></td>
				<td><?= ucfirst($data['type']); ?></td>
				<td>
					<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="categorywork.php?action=deleteCustomField&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
				</td>
			</tr>
			<?php
		}
	}
}

?>