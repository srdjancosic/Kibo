<?php

class Leaves extends Functions {
	
	public $parent;
	
	function listLeavesSelect($selected = 0, $lang_id = 0, $id) {
		
		if($lang_id != 0) $addSQL = " WHERE lang_id = '".$lang_id."'";
	
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves $addSQL");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			if($id != $data['id']) {
				?>
				<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
				<?php
			}
		}
	}

	function listLeavesSelectEdit($selected = 0, $lang_id = 0) {
	
		if($lang_id != 0) $addSQL = " WHERE lang_id = '".$lang_id."'";
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves $addSQL");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
			<?php
		}
	}
	
	function listLeavesView($selected = 0) {
		
		$addonSQL = ($this->parent != "" && $this->parent != 0) ? " AND parent = '$this->parent'" : "";
	
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE ref_id = '0' $addonSQL ORDER BY id DESC, parent ASC");
		$odd = 0;
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			if($odd % 3 == 0) {
			//	echo "<br clear='all'>";
			}
			$odd++;
			?>
			<div class="box_1">
				<div class="inner">
					<h3><?= $data['name']; ?></h3>
					<small>Parent:</small> <?= Database::getValue("name", "leaves", "id", $data['parent']); ?>
					<br />
					<?= ($data['css_class'] != "") ? ".".$data['css_class'] : ""; ?>
					<br />
					<?= ($data['css_id'] != "") ? "#".$data['css_id'] : ""; ?>
				</div>
				<div class="buttons">
					<a class="tooltip" title="Show childs" href="index.php?parent=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/lupa.png"></a>
					<?php if(Functions::adminAllowed("elements", "edit")) { ?>
					<a class="tooltip" title="Edit" href="leavesedit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
					<?php } 
						  if(Functions::adminAllowed("elements", "delete")) { ?>
					<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="leaveswork.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}

	function getLeavesValues($id, $lang_id) {
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE (id = '$id' OR ref_id = '$id') AND lang_id = '$lang_id'");
		$data = mysql_fetch_array($query);
		
		return $data;
	}
	
	function getLeafContent($leafId, $content, $type, $lang_id) {
	global $c;
		?>
		<script>
		$(document).ready(function() {
			$( ".placeholder_<?= $lang_id; ?>" ).droppable("disable");
		});
		</script>
		<?php
		
			switch ($type) {
				case "html":
					// get values for this leaf widget
					list($c_name, $c_display_header, $c_css, $c_content) = explode("|:|", $content);
					break;
				case "listing":
					list($c_name, $c_display_header, $c_css, $c_categories) = explode("|:|", $content);
					$c->selected = $c_categories;
					
					$c_categories = $c->listCategoriesCheckbox($lang_id);
					break;
				case "node":
					list($c_name, $c_display_header, $c_css, $c_categories, $c_content, $c_limit, $c_orderbyfield, $c_ordertype, $c_pagination) = explode("|:|", $content);$c->selected = $c_categories;
					
					$c_categories = $c->listCategoriesCheckbox($lang_id);
					break;
				case "menu":
					$c_content = $content;
					
					break;
				case "slider":
					list($c_name, $c_css, $c_album) = explode("|:|", $content);
					
					break;
				case "filelist":
					list($c_folder, $c_content) = explode("|:|", $content);
					
					break;
				case "form":
					$c_form = $content;
					break;
				case "plugin":
					$c_plugin = $content;
					$type = "plugins";
					break;
				case "pluginView":
					list($c_name, $c_plugin) = explode("|:|", $content);
					$type = "pluginviews";
					break;
			}
		include("../../leaf/".$type.".php");
		
	}
}

?>