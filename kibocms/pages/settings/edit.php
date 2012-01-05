<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$id = $f->getValue("id");
	
	$query = $db->execQuery("SELECT * FROM languages WHERE id = '$id'");
	$values = mysql_fetch_array($query, MYSQL_ASSOC);
	require("../../head.php"); 
	
	if(!$f->adminAllowed("settings", "edit")) {
		$f->redirect("index.php");
		die();
	}
?>
<link href="pages.css" type="text/css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="pages.js"></script>
</head>

<body>
<?php $currentPlace = "settings"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Edit a page</h1>
			
			<form method="POST" action="work.php">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="id" id="id" value="<?= $id; ?>">
				<p>
					<label>Language name:</label>
					<input type="text" class="text" id="name" name="name" value="<?= $values['name']; ?>">
				</p>
				<p>
					<label>Language code:</label>
					<input type="text" class="text" id="lang_code" name="lang_code" value="<?= $values['lang_code']; ?>">
				</p>
				<p>
					<label>Active:</label>
					<input type="checkbox" id="active" name="active" value="1" <?= ($values['active'] == 1) ? "checked=\"checked\"" : ""; ?>>
				</p>
				<p>
					<label>Default:</label>
					<input type="checkbox" id="default" name="default" value="1" <?= ($values['default'] == 1) ? "checked=\"checked\"" : ""; ?>>
				</p>
				<p class="submit">
					<input type="submit" value="Save changes" class="submit" />
					<input type="button" value="Cancel" class="button" onclick="location.href='index.php'" />
				</p>
			</form>
			
			
		</div>
	</div>
	
	
</div>
</body>

</html>
