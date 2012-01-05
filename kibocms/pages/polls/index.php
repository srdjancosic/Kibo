<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "polls"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Polls</h1>
			<br clear="all">
			<br clear="all">
			<?php
			
			$qu = $db->execQuery("SELECT * FROM p_poll ORDER BY `active` DESC");
			while ($row = mysql_fetch_array($qu, MYSQL_ASSOC)) {
				
				$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."p_poll_answers WHERE `poll_id` = '".$row['id']."' ORDER BY `id` DESC");
				echo "<h2>".stripslashes($row['name'])." [<small>".
						
						"".$db->getValue("name", "p_groups", "id", $row['group_id']).
						" - ".
						$db->getValue("name", "languages", "id", $row['lang_id']).
						"</small>] - ";
				echo ($row['active'] == 1) ? "Active" : "Not active";
				
				?>
				<?php if($row['active'] == 0) 
						echo "<a class=\"tooltip link\" title=\"Make this poll active for group: ".
						$db->getValue("name", "p_groups", "id", $row['group_id'])."\" href=\"work.php?action=makeActive&id=".$row['id']."\">".
						"<small>Make active</small></a>";
						
				?>
				&nbsp;&nbsp;&nbsp;<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete poll" href="work.php?action=delete&id=<?= $row['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
				</h2>
				<?php
				while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
					
					?>
					<div class="box_1">
						<div class="inner">
							<h3><?= stripslashes($data['name']); ?></h3>
							<big><?= $data['votes']." votes"; ?></big>
						</div>
						<div class="buttons">
							<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete answer" href="work.php?action=deleteAnswer&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
						</div>
					</div>
					<?php
				}
				echo "<br clear=\"all\">";
				echo "<br clear=\"all\">";
			
			}
			
			?>
			
		</div>
	</div>
	
	<div id="sidebar">
		<h2>Actions</h2>
		<div id="accordion">
			<h3>New poll</h3>
			<div>
			<form method="POST" action="work.php">
				<input type="hidden" value="add" name="action">
				<p>
					<label>Question:</label>
					<input type="text" class="sf" name="question" />
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
					<label>Make this active?</label>
					<input type="checkbox" name="active" value="1" />
				</p>
				<p>
					<input type="submit" class="submit" value="Create a new poll" />
				</p>
			</form>
			</div>
			<h3>New answer</h3>
			<div>
			<form method="POST" action="work.php">
				<input type="hidden" value="add_answer" name="action">
				<p>
					<label>Answer:</label>
					<input type="text" class="sf" name="answer" />
				</p>
				<p>
					<label>Poll:</label>
					<select name="poll_id" class="styled">
					<?php
					$query = $db->execQuery("SELECT * FROM p_poll ORDER BY id DESC");
					while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
						echo "<option value=\"".$data['id']."\">".$data['name']."</option>";
					}
					?>
					</select>
				</p>
				<p>
					<input type="submit" value="Add answer" class="submit" />
				</p>
			</form>
			</div>
		</div>
	</div>
	
</div>
</body>

</html>
