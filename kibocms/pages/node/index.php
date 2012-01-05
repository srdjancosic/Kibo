<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$catId = $f->getValue("catId");
	$catId = ($catId == "") ? 0 : $catId;
	$n = new Node($catId);
	$lang_id = $f->getDefaultLanguage();
	
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
</style>
</head>

<body>
<?php $currentPlace = "nodes"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
		<?php
			$f->getMessage();
		?>
		<h1>
			Content
			<img src="/kibocms/preset/assets/loading.gif" id="loader2_<?= $lang_id; ?>" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">
		</h1>
		
		<label>Filter by: </label>
		<select id="select_category" class="styled">
			<option value="0">All categories</option>
			<?php
				$n->listCategorySelect($catId);
			?>
		</select>
		
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
				$n->listNodesView();
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
	
	
	
	<?php 
	if($f->adminAllowed("content", "add")) {
	?>
	<div id="sidebar">
		<h2>Create a new content</h2>
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
						$n->listCategorySelect();
					?>
				</select>
			</p>
			<p>
				<input type="submit" value="Next" class="submit">
			</p>
		</form>
	</div>
	<?php } ?>
</div>
</body>

</html>
