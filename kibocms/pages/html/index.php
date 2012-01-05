<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>
<style>.box_1, .box_1 div.inner { min-height: 20px; }</style>
</head>

<body>
<?php $currentPlace = "html"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>HTML content</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE content_type = 'html' AND lang_id = '$lang_id' ORDER BY id ASC, parent ASC");
			$odd = 0;
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				if($odd % 3 == 0) echo "<br clear='all' />";
				$odd++;
				?>
				<div class="box_1">
					<div class="inner">
						<h3><?= stripslashes($data['name']); ?></h3>
					</div>
					<div class="buttons">
					<?php if($f->adminAllowed("html", "edit")) { ?>
						<a class="tooltip" title="Edit" href="edit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
					<?php } ?>
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
