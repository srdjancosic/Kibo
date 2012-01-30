<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	$parent = $f->getValue("parent");
	$parent = ($parent == "") ? 0 : $parent;
	$c = new Category();
	$c->parent = $parent;
	
?>
<?php require("../../head.php"); ?>
<script type="text/javascript" src="category.js"></script>
</head>

<body>
<?php $currentPlace = "categories"; require("../../header.php"); ?>
<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			
			<?php
				$f->getMessage();
			?>
			<h1>Categories</h1>
			
			<label>Parent category: </label>
			<select id="select_category" class="styled">
				<option value="0">None</option>
				<?php
					$c->listCategoriesSelect($parent, $lang_id);
				?>
			</select>
			
			<input type="text" id="search_input">
			<br clear="all">
				<?php
					$c->listCategoriesView();
				?>
			
		</div>  <!-- main -->
	</div>  <!-- content -->
	
	<?php 
	if($f->adminAllowed("categories", "add")) {
	?>
	<div id="sidebar">
	
		<h2>Create a new category</h2>
		<div id="accordion">
			<!-- new node -->
			<div>
				<form method="POST" action="categorywork.php">
					<input type="hidden" name="action" value="add">
					<input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
					<p>
						<label>Category name:</label>
						<input type="text" class="sf" id="name" name="name">
					</p>
					<p>
						<label>Href:</label>
						<input type="text" class="sf" id="href" name="href">
						<span class="field_desc">*leave blank for default link</span>
					</p>
					<p>
						<label>Parent category:</label>
						<select id="parent" name="parent" class="styled">
							<option value="0">-----</option>
							<?php
								$c->listCategoriesSelect(0 ,$lang_id);
							?>
						</select>
					</p>
					<p>
						<label>Page view:</label>
						<select id="page_id" name="page_id" class="styled">
							<option selected value="0">-----</option>
							<?php
								$c->listPagesSelect("", $lang_id);
							?>
						</select>
					</p>
					<p>
						<label>Create folder:</label>
						<select id="has_dimensions" name="has_dimensions" class="styled">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</p>
					<p>
						<input type="submit" value="Save" class="submit">
					</p>
				</form>
			</div>  <!-- accordian #1 -->
		</div> <!-- accordian -->
		
	</div>  <!-- sidebar -->
	<?php } ?>
</div>  <!-- bgwrap -->

</body>
</html>