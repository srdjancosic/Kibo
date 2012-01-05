<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	$l = new Leaves();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>
<style>.box_1, .box_1 div.inner { min-height: 20px; }</style>
</head>

<body>
<?php $currentPlace = "menu"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Menu</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE content_type = 'menu' AND lang_id = '$lang_id' ORDER BY id ASC, parent ASC");
			$odd = 0;
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				//if($odd % 3 == 0) echo "<br clear='all' />";
				$odd++;
				?>
				<div class="box_1">
					<div class="inner">
						<h3><?= stripslashes($data['name']); ?></h3>
					</div>
					<div class="buttons">
					<?php if($f->adminAllowed("menu", "edit")) { ?>
						<a class="tooltip" title="Edit" href="edit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
					<?php } ?>
					</div>
				</div>
				<?php
			}
			?>
			
		</div>
		</div>
		<div id="sidebar">
			<h2>New menu</h2>
			<form method="POST" action="work.php">
				<input type="hidden" name="action" value="add">
				<input type="hidden" name="lang_id" value="<?= $lang_id; ?>">
		
				<p>
					<label>Element name:</label>
					<input type="text" class="text" id="name" name="name">
				</p>
				<p>
					<label>Parent element:</label>
					<select id="parent" name="parent" class="styled">
						<option value="0">-----</option>
						<?php
							$l->listLeavesSelect();
						?>
					</select>
				</p>
				<p>
					<input type="submit" value="Next" class="submit">
				</p>
			</form>
		</div>
	
	
</div>
</body>

</html>
