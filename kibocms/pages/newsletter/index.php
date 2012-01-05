<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "newsletter"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Newsletter templates</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."p_newsletter ORDER BY `id` DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				
				?>
				<div class="box_1">
					<div class="inner">
						<h3><?= $data['title']; ?></h3>
						<big><?= $db->getValue("name", "languages", "id", $data['lang_id']); ?></big>
					</div>
					<div class="buttons">
						<a class="tooltip edit" title="Edit or send template" href="edit.php?id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Pencil.png" /></a>
						<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete template" href="work.php?action=delete&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
					</div>
				</div>
				<?php

			}
			?>
			
		</div>
	</div>
	
	
	<div id="sidebar">
		<h2>New template</h2>
		<form method="POST" action="work.php">
			<input type="hidden" value="add" name="action" />
			<p>
				<label>Title:</label>
				<input type="text" name="title" class="mf" />
			</p>
			<p>
				<label>Language:</label>
				<select name="lang_id" class="styled">
					<?php $query = $db->execQuery("SELECT * FROM languages ORDER BY id");
					while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
						echo "<option value=\"".$data['id']."\">".$data['name']."</option>";
					}
					?>
				</select>
			</p>
			<p>
				<input class="submit" type="submit" value="Next" />
			</p>
		</form>
	</div>
	
</div>
</body>

</html>
