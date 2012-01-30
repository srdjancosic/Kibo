<?php

class Node extends Functions {
	public $catId;
	
	function __construct($catId = 0) {
		$this->catId = $catId;
	}
	
	function getNodeValues($id, $lang_id) {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."node WHERE (id = '$id' OR ref_id = '$id') AND lang_id = '$lang_id'");
		$data = mysql_fetch_array($query);
		
		return $data;
	}
	
	function listNodesView($offset, $limit, $cat_id) {
		//$addonSQL = ($this->catId == 0) ? "" : " AND category = '".$this->catId."'";
		$addonSQL = ($cat_id == 0) ? "" : " AND category = '".$cat_id."'";
		if($cat_id != 0){
			$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."node WHERE ref_id = '0' $addonSQL ORDER BY ".$this->getOrderType()." LIMIT ".$offset.", ".$limit);
			$odd = 0;
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				if($odd % 3 == 0) {
					//echo "<br clear='all'>";
				}
				$odd++;
				?>
				<div class="box_1" id="item_<?= $data['id']; ?>">
					<div class="inner">
					<h3><?= stripslashes($data['name']); ?></h3>
					<?php 
						$lang_code = Database::getValue("lang_code", "languages", "id", $data['lang_id']);
						$catURL = Database::getValue("url", "category", "id", $data['category']);
						echo "/".$lang_code."/".$catURL."/".$data['url']; 
						?>
					<br /><small>Created on: </small><?= date("d.m.Y.", strtotime($data['date'])); ?>
					<br /><?= Database::getValue("name", "category", "id", $data['category']); ?>
					</div>
					<div class="buttons">
						<?php
						
						if(Functions::adminAllowed("content", "edit")) {
						?>
						<a class="handleContent"><img alt="" src="/kibocms/preset/assets/move.gif"></a> 
						<?php
						}
						
						if(Functions::adminAllowed("content", "edit")) {
						?>
						<a class="tooltip" title="Edit" href="nodeedit.php?id=<?= $data['id']; ?>&catId=<?= $this->catId; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
						<?php
						}
						if(Functions::adminAllowed("content", "delete")) {
						?>
						<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="nodework.php?action=delete&id=<?= $data['id']; ?>&catId=<?= $this->catId; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}
	}
	
	function listOrderTypes() {
		$orderType = $_SESSION['order_type_nodes'];
		if($orderType == "") $orderType = "`order` ASC";
		
		?>
		<option value="`order` DESC" <?= $orderType == "`order` DESC" ? "selected=\"selected\"" : ""; ?>>order DESC</option>
		<option value="`order` ASC" <?= $orderType == "`order` ASC" ? "selected=\"selected\"" : ""; ?>>order ASC</option>
		<option value="id DESC" <?= $orderType == "id DESC" ? "selected=\"selected\"" : ""; ?>>id DESC</option>
		<option value="id ASC" <?= $orderType == "id ASC" ? "selected=\"selected\"" : ""; ?>>id ASC</option>
		<?php
	}
	
	function getOrderType() {
		$orderType = $_SESSION['order_type_nodes'];
		if($orderType == "") $orderType = "`order` ASC";
		
		return $orderType;
	}
	
	function listCategorySelect($selected = 0, $lang_id = 0) { 
		
		if($lang_id == 0) $addSQL = "WHERE ref_id = '0'"; else $addSQL = "WHERE lang_id = '".$lang_id."'";
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category ".$addSQL);
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
			<?php
		}
	}
	
	function  listCategoryList(){
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category WHERE ref_id = '0'");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<li><a href="/kibocms/pages/node/index.php?catId=<?= $data['id'];?>"><?= $data['name']; ?></a></li>
			<?php
		}
	}

	function listCustomFields($categoryId, $nodeId, $lang_id) {
		/*
		$query = Database::execQuery("SELECT ccf.name AS name, cf.value AS value, cf.id AS id FROM category_custom_fields AS ccf
														LEFT JOIN custom_fields cf ON ccf.name = cf.name
														WHERE ccf.category = '$categoryId'
															AND cf.node = '$nodeId'
														");
		*/
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category_custom_fields WHERE category = '$categoryId'");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$subQuery = Database::execQuery("SELECT * FROM custom_fields WHERE name = '".$data['name']."' AND node = '".$nodeId."'");
			$row = mysql_fetch_array($subQuery, MYSQL_ASSOC);
			?>
			<p>
				<label><?= ucfirst(stripslashes($data['name'])); ?></label>
			<?php
				if($data['type'] == "text") {
			?>
				<input type="text" class="mf" value="<?= $row['value']; ?>" name="cfv_<?= $lang_id; ?>[<?= $data['name']; ?>]" />
			<?php
				} elseif ($data['type'] == "longtext") {
			?>
				<textarea name="cfv_<?= $lang_id; ?>[<?= $data['name']; ?>]" class="textarea"><?= $row['value']; ?></textarea>
			<?php
				} elseif ($data['type'] == "date") {
			?>
				<input type="text" class="sf date_picker" value="<?= $row['value']; ?>" name="cfv_<?= $lang_id; ?>[<?= $data['name']; ?>]" />
			<?php
				}
			?>
				
			</p>
			<?php
		}
		
	}
	
}

?>