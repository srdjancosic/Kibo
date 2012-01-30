<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$catId = $f->getValue("catId");
	$catId = ($catId == "") ? 0 : $catId;
	$n = new Node($catId);
	$lang_id = $f->getDefaultLanguage();
	
	function writeSubCategories ($id){
		$db1 = new Database();
		$f1  = new Functions();
		$catId = $f1->getValue("catId");
		echo "<ul style=\"padding: 0px 25px;\" id=\"sub_".$id."\">";
			$query1 = $db1->execQuery("SELECT * FROM category WHERE parent = '$id'");
			while ($data1 = mysql_fetch_array($query1, MYSQL_ASSOC)){
				if($data1['id']== $catId)
					$class="class=\"active\"";
				else $class= "";
				$id=$data1['id'];
				$num = $db1->numRows("SELECT * FROM node WHERE category = '$id'");
				echo "<li> <a ".$class."href=\"index.php?catId=".$data1['id']."\">".$data1['name']." (".$num.")</a></li>";
				if($data1['is_parent']==1)
					writeSubCategories($data1['id']);
			}
		echo "</ul>";
	}
	
?>
<?php require("../../head.php"); ?>
<script type="text/javascript" src="node.js"></script>
<style>
.ui-state-highlight { 
	width: 220px; 
	height: 140px;  
	border: 1px solid #fcefa1; 
	background: #fbf9ee; 
	float: left;
}
#morearticles {
	clear: both;
}
</style>
</head>

<body>
<?php $currentPlace = "nodes"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
		<?php
			$f->getMessage();
			if($catId != 0){
		?>
		<h1>
			Articles in category "<?= $db->getValue("name", "category", "id", $catId);?>"
			<img src="/kibocms/preset/assets/loading.gif" id="loader2_<?= $lang_id; ?>" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">
		</h1>
		<?  }
			else {?>
			<h1>
			Please select the category
			<img src="/kibocms/preset/assets/loading.gif" id="loader2_<?= $lang_id; ?>" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">
			</h1>
			<?
			}
			?>
		
		<?php if($catId != 0) { ?>
		<input type="hidden" value="<?= $catId; ?>" id="cat_id">
		<label>Order by:</label>
		<select id="order_nodes" class="styled">
			<?php
				$n->listOrderTypes();
			?>
		</select>
		<?php
		}
		?>
		
		<br clear="all">
		<br clear="all">
			<div id="sortable_<?= $lang_id; ?>">
			<?php
				$limit=20;
				$n->listNodesView(0, $limit, $catId);
			?>
			<?
				$count = $db->numRows("SELECT * FROM node WHERE ref_id = '0' AND category = '$catId'");
				if($count>$limit){
			?>
				</div>
				<div id="morearticles">
					<input class="submit" id="showMore" type="button" onclick="showMore(<?= $limit;?>,<?= $limit;?>,<?= $lang_id;?>, <?= $catId;?>, <?= $count;?>);" value="Show more">
			<?
				}
			?>
			</div>
		</div>
	</div>
	
	<?php
	if($catId != 0) {
	?>
	<script>
	$('#sortable_<?= $lang_id; ?>').sortable({
		placeholder: "ui-state-highlight",
		handle: ".handleContent",
		update: function(event, ui) {
			order = $(this).sortable('serialize');
			//var newOrder = $(this).attr("id") + "::" + order;
			$("#loader2_<?= $lang_id; ?>").show();
			
			$.ajax({
				url: 'nodework.php',
				data: 'action=sort&'+order+"&lang_id=<?= $lang_id; ?>",
				type: 'POST',
				async: false,
				success: function(data) {
					$("#loader2_<?= $lang_id; ?>").hide();
				}
			});
		}
	}); 
	$( "#sortable_<?= $lang_id; ?>" ).disableSelection();
	</script>
	<?php
	}
	?>
	
	<div id="sidebar">
	
	<?php 
	if($f->adminAllowed("content", "add")) {
	?>
		<h2>Create a new article</h2>
		<form method="POST" action="nodework.php">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
			<p>
				<label>Title:</label>
				<input type="text" class="text" id="name" name="name">
			</p>
			<p>
				<label>Category:</label>
				<select id="category" name="category" class="styled">
					<?php
						$n->listCategorySelect($catId);
					?>
				</select>
			</p>
			<p>
				<input type="submit" value="Next" class="submit">
			</p>
		</form>
		<?php
	}
		echo "<h2>Categories</h2>";

		echo "<ul>";
		$query= $db->execQuery("SELECT * FROM category WHERE parent = '0' AND lang_id = '$lang_id'");
		while($data = mysql_fetch_array($query, MYSQL_ASSOC)){
			if($data['id']== $catId)
				$class="class=\"active\"";
			else $class= "";
			$id = $data['id'];
			$num = $db->numRows("SELECT * FROM node WHERE category = '$id'");
			echo "<li><a ".$class." href=\"index.php?catId=".$data['id']."\">".$data['name']." (".$num.")</a></li>";
			if($data['is_parent']==1)
				writeSubCategories($data['id']);
		}
		echo "</ul>";
		?>
	</div>
</div>
</body>

</html>
