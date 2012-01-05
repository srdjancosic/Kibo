<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$p = new Pages();
	$l = new Leaves();
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>
<script type="text/javascript" src="pages.js"></script>
</head>

<body>
<?php $currentPlace = "pages"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Pages</h1>
			<label>Quick search:</label>
			<input type="text" id="search_input">
			<br clear="all">
			<?php
				$p->listPagesView();
			?>
			
			
			
		</div>
	</div>
	<?php 
	if($f->adminAllowed("pages", "add")) {
	?>
	<div id="sidebar">
		<h2>New page</h2>
		<form method="POST" action="pageswork.php">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
			<p>
				<label>Page name:</label>
				<input type="text" class="text" id="name" name="name">
			</p>
			<p>
				<label>Header:</label>
				<select id="header2" name="header" class="styled">
					<option value="0">-----</option>
					<?php
						$l->listLeavesSelect(0, $lang_id);
					?>
				</select>
			</p>
			<p>
				<label>Content:</label>
				<select id="content2" name="content" class="styled">
					<option value="0">-----</option>
					<?php
						$l->listLeavesSelect(0, $lang_id);
					?>
				</select>
			</p>
			<p>
				<label>Footer:</label>
				<select id="footer2" name="footer" class="styled">
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
	<?php } ?>
</div>
</body>

</html>
