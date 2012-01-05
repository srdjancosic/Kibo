<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "admins"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Administrators</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."admins ORDER BY `id` DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				
				?>
				<div class="box_1">
					<div class="inner">
						<h3><?= $data['username']; ?></h3>
					</div>
					<div class="buttons">
					<?php if($f->adminAllowed("admins", "edit")) { ?>
						<a class="tooltip" title="Edit administrator" href="edit.php?id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Pencil.png" /></a>
					<?php }
						if($f->adminAllowed("admins", "delete")) { ?>
						<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete administrator" href="work.php?action=delete&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
					<?php } ?>
					</div>
				</div>
				<?php

			}
			?>
			
		</div>
	</div>
	<?php if($f->adminAllowed("admins", "add")) { ?>
	<div id="sidebar">
		<h2>New administrator</h2>
		<form method="POST" action="work.php">
			<input type="hidden" value="new_admin" name="action">
			<p>
				<label>Username:</label>
				<input type="text" name="username" id="lang_name" class="text">
			</p>
			<p>
				<label>Password:</label>
				<input type="text"  name="password" class="text">
			</p>
			<p class="submit">
				<input type="submit" value="Create" class="submit" />
			</p>
		</form>
	</div>
	<?php } ?>
</div>
</body>

</html>
