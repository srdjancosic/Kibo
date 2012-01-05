<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "users"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Users</h1>
			<br clear="all">
			<br clear="all">
			<?php
			
			$qu = $db->execQuery("SELECT * FROM p_groups ORDER BY id DESC");
			while ($row = mysql_fetch_array($qu, MYSQL_ASSOC)) {
				
				$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."p_users WHERE `group_id` = '".$row['id']."' ORDER BY `id` DESC");
				if(mysql_num_rows($query) > 0) {
					echo "<h2>".$row['name']." (".$db->getValue("name", "languages", "id", $row['lang_id']).")</h2>";
					
					while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
						
						?>
						<div class="box_1">
							<div class="inner">
								<h3><?= $data['first_name']." ".$data['last_name']; ?></h3>
								<?= $data['email']; ?>
							</div>
							<div class="buttons">
								<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete user" href="work.php?action=delete&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
							</div>
						</div>
						<?php
					}
					echo "<br clear=\"all\">";
					echo "<br clear=\"all\">";
				}
			}
			
			?>
			
		</div>
	</div>
	
</div>
</body>

</html>
