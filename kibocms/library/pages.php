<?php

class Pages extends Functions {
	
	function getPageValues($id, $lang_id) {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."pages WHERE (id = '$id' OR ref_id = '$id') AND lang_id = '$lang_id'");
		$data = mysql_fetch_array($query);
		
		return $data;
	}
	
	function listPagesView() {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."pages WHERE ref_id = '0' ORDER BY id DESC");
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
					/<?= $data['url']; ?>
					<br />Header: <?= Database::getValue("name", "leaves", "id", $data['header']); ?>
					<br />Content: 
					<?= Database::getValue("name", "leaves", "id", $data['content']); ?>
					<br />Footer: 
					<?= Database::getValue("name", "leaves", "id", $data['footer']); ?>
				</div>
				<div class="buttons">
					<?php if(Functions::adminAllowed("pages", "edit")) { ?>
					<a class="tooltip" title="Edit" href="pagesedit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
					<?php }
						if(Functions::adminAllowed("pages", "delete")) { ?>
					<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="pageswork.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}
	
	function listLeavesSelect($selected = 0) {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
			<?php
		}
	}

	function listLeaves($id, $destination = 0) {
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."pages_leaves WHERE page_id = '$id' 
																   AND leaf_destination = '$destination'
																   ORDER BY `order` ASC");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<li id="item_<?= $data['leaf_id']; ?>" class="plid_<?= $data['id']; ?>">
				<label>Leaf name:</label>
				<input type="text" value="<?= Database::getValue("name", "leaves", "id", $data['leaf_id']); ?>" readonly class="sf">
				<a href="#" id="remove_<?= $data['id']; ?>" onclick="removeLeaf('<?= $data['id']; ?>');">
					<img src="/kibocms/preset/actions_small/Trash.png"/>
				</a>
				<a href="/kibocms/pages/leaves/leavesedit.php?id=<?= $data['leaf_id']; ?>">
					<img src="/kibocms/preset/actions_small/Pencil.png" />
				</a>
				<img src="/kibocms/preset/assets/move.gif" border="0" alt="Move" class="handler" >
			</li>
			<?php
		}
		
	}
	
	function listLeavesView($header, $content, $footer, $lang_id = 0) {
		$notIn = $header.",".$content.",".$footer;
		if($content != 0) {
			$subquery = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE parent = '$content' AND lang_id = '$lang_id'");
			while($subdata = mysql_fetch_array($subquery, MYSQL_ASSOC)) {
				$notIn .= ", ".$subdata['id'];
			}
		}
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE id NOT IN (".$notIn.") AND lang_id = '$lang_id'");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
		?>
		<div class="leaf_content lc_<?= $lang_id; ?> tooltip" title="Drag me to drop zone to add" leafid="<?= $data['id']; ?>">
			<h3><?= $data['name']; ?></h3>
		</div>
		<?php
		}
	}
	
	function listCategorySelect($selected = 0) {
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category");
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
			<?php
		}
	}
	
	//	content_leaf_destination
	function listLeavesDestination($pageId) {
		$content_leaf 		= Database::getValue("content", "pages", "id", $pageId);
		$content_leaf_name 	= Database::getValue("name", "leaves", "id", $content_leaf);
		
		$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE parent = '$content_leaf'");
		?>
		<option value="<?= $content_leaf; ?>"><?= $content_leaf_name; ?></option>
		<?php
		while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
			<option value="<?= $data['id']; ?>"><?= $data['name']; ?></option>
			<?php
		}
	}
	
}

?>