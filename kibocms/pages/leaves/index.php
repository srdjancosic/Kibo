<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$parent = $f->getValue("parent");
	$l = new Leaves();
	$l->parent = $parent;
	
	$lang_id = $f->getDefaultLanguage();
?>
<?php require("../../head.php"); ?>
<script type="text/javascript" src="leaf.js"></script>
</head>

<body>
<?php $currentPlace = "leaves"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Elements</h1>
			
			<label>Filter by:</label>
			<select id="select_category" class="styled">
				<option value="0">All</option>
				<optgroup label="Parents">
					<?php $l->listLeavesSelectEdit($parent, $lang_id); ?>
				</optgroup>
			</select>
			
			<input type="text" class="sf tooltip" id="category_name_filter" title="Search">
			<br clear="all">
			<?php
				$l->listLeavesView();
			?>
		</div>
	</div>
	
	<?php 
	if($f->adminAllowed("elements", "add")) {
	?>
	<div id="sidebar">
		<h2>New element</h2>
		<form method="POST" action="leaveswork.php">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
	
			<p>
				<label>Element name:</label>
				<input type="text" class="text" id="name" name="name">
			</p>
			<p>
				<label>Parent element:</label>
				<select id="parent" name="parent" class="styled">
					<option value="0">-----</option>
					<?php
						$l->listLeavesSelect(0, $lang_id);
					?>
				</select>
			</p>
			<p>
				<input type="submit" value="Next" class="submit">
			</p>
		</form>
		
	</div>
	<?php
	}
	?>
</div>
</body>

</html>