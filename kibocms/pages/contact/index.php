<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "contact"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Contacts</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM p_contact ORDER BY lang_id");
		
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			?>
				<div class="box_1">
					<div class="inner">
						<h3><?= stripslashes($data['email']); ?></h3>
						<big><?= $db->getValue("name", "languages", "id", $data['lang_id']); ?></big>
					</div>
					<div class="buttons">
						<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete contact" href="work.php?action=delete&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
					</div>
				</div>
				<?php
			}
			
			?>
			
		</div>
	</div>
	
	<div id="sidebar">
		<h2>Actions</h2>
		<div id="accordion">
			<h3>New contact</h3>
			<div>
			<form method="POST" action="work.php">
				<input type="hidden" value="add" name="action">
				<p>
					<label>Email:</label>
					<input type="text" class="sf" name="email" />
				</p>
				<p>
					<label>Language:</label>
					<select name="lang_id" class="styled">
					<?php $query = $db->execQuery("SELECT * FROM languages ORDER BY id DESC");
						while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
							echo "<option value=\"".$data['id']."\">".$data['name']."</option>";
						}
						?>
					</select>
				</p>
				<p>
					<input type="submit" class="submit" value="Create a new contact" />
				</p>
			</form>
			</div>
		</div>
	</div>
	
</div>
</body>

</html>
