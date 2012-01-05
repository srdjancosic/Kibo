<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
	
?>
<?php require("../../head.php"); ?>
<style>.defaultState {min-height: 28px;}</style>
</head>

<body>
<?php $currentPlace = "user_groups"; require("../../header.php"); ?>

<div id="bgwrap">
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>User groups</h1>
			
			<?php
				$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`user_groups` ORDER BY id DESC");
				while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				?>
					<div class="box_1">
						<div class="inner">
							<h3><?= $data['name']; ?></h3>				
						</div>
						<div class="buttons">
							<?php
							if($f->adminAllowed("user_groups", "delete")) { ?>
							<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="work.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
							<?
							 } 
						echo "</div>";
					echo '</div>';
				}
			?>
		</div>
	</div>
	<?php 
		if($f->adminAllowed("user_groups", "add")) {
	?>
	<div id="sidebar">
		<h2>New user group</h2>
		<form method="POST" action="work.php">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
	
			<p>
				<label>User group name:</label>
				<input type="text" class="text" id="name" name="name">
			</p>
			<p>
				<input type="submit" value="Next" class="submit">
			</p>
		</form>
	</div>
	<?
	}
	?>
</div>
</body>
</html>