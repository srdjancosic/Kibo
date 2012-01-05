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
			<h1>Newsletter subscribers</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."p_newsletter_users ORDER BY `id` DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				
				?>
				<div class="box_1">
					<div class="inner">
						<h3><?= $data['email']; ?></h3>
					</div>
					<div class="buttons">
						<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete" href="work.php?action=delete_user&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
					</div>
				</div>
				<?php

			}
			?>
			
		</div>
	</div>
	
</div>
</body>

</html>
