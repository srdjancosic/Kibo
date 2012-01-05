<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$p = new Pages();
	$l = new Leaves();
	$lang_id = $f->getDefaultLanguage();
?>

<?php require("../../head.php"); ?>
<style>
textarea {
	height: 40px;
}
label{
	width: 140px;
}
</style>

</head>

<body>
<?php $currentPlace = "settings"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Languages</h1>
			<br clear="all">
			<br clear="all">
			<?php
			$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."languages ORDER BY `default` DESC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				
				?>
				<div class="box_1">
					<div class="inner">
						<h3>
							<?= $data['name']; ?>
					 		<?= ($data['default'] == 1) ? "&nbsp;<small>(default)</small>" : ""; ?>
					 	</h3>
					</div>
					<div class="buttons">
						<?php 
						if($f->adminAllowed("settings", "edit")) {
						?>
						<a class="tooltip" title="Edit language" href="edit.php?id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Pencil.png" /></a>
						<?php }
						if($f->adminAllowed("settings", "delete")) {
						?>
						<a class="tooltip delete" onclick="return confirm('Are you sure?');" title="Delete a language" href="work.php?action=delete&id=<?= $data['id']; ?>"><img src="/kibocms/preset/actions_small/Trash.png" /></a>
						<?php } ?>
					</div>
				</div>
				<?php

			}
			
			if($f->adminAllowed("settings", "settings")) {
			?>
			<h1>Config settings</h1>
			<div class="tabs" style="padding: 10px;">
				<? 
					$group = $db->getValue("unlogged_user_group", DB_PREFIX."config", "id", "1");
					$approval = $db->getValue("wait_for_approval", DB_PREFIX."config", "id", "1");
					$fb_reg = $db->getValue("allow_fb_registration", "config", "id", "1");
					$site_email = $db->getVAlue("site_email", "config", "id", "1");
				?>
				<form action="configwork.php" method="POST">
					<p>
						<label >Unlogged users group:</label>
						<select id="unlogged_user_group" name="unlogged_user_group" class="styled">
							<?php
								if($group == 0){
									?>
									<option value="0" selected="selected">-----</option>
									<?}
								$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`user_groups`");
									while($data = mysql_fetch_array($query, MYSQL_ASSOC)){?>
										<option value=<?= $data['id']?> <?= ($data['id'] == $group) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option><?
									}
							?>
						</select>
					</p>
					<p>
						<label>Site email</label>
						<input type="text" name="site_email" value=<?= $site_email?>>
					</p>
					<p>
						<input type="checkbox"  value='1' name="wait_for_approval" <?= ($approval == 1) ? "checked" : "";?>>Wait for approval
					</p>
					<p>
						<input type="checkbox"  value='1' name="allow_fb_registration" <?= ($fb_reg == 1) ? "checked" : "";?>>Allow fb registration
					</p>
						<input class="submit" type="submit" value="Save changes">
				</form>
			</div>
			<h1>Site settings</h1>
			<div class="tabs">
				<ul>
				<?php
					$lang_arr = $f->getAllLanguages();
					foreach ($lang_arr as $lang_id => $lang_name) {
						echo "<li><a href=\"#sb_".$lang_id."\">".$lang_name."</a></li>";
					}				
				?>
				</ul>
				<form method="POST" action="work.php">
				<input type="hidden" name="action" value="settings">
				<?php foreach ($lang_arr as $lang_id => $lang_name) {
					$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."settings WHERE lang_id = '".$lang_id."'");
					$values = mysql_fetch_array($query, MYSQL_ASSOC);
				?>
				<div id="sb_<?= $lang_id; ?>">
					<input type="hidden" value="<?= $lang_id; ?>" name="lang_id[<?= $lang_id; ?>]">
					<p>
						<label>Site title</label>
						<input type="text" class="sf" name="title[<?= $lang_id; ?>]" value="<?= $values['site_title']; ?>">
					</p>
					<p>
						<label>Pagination URL</label>
						<input type="text" class="sf" name="pagination_url[<?= $lang_id; ?>]" value="<?= $values['pagination_url']; ?>">
					</p>
					<p>
						<label>Site keywords</label>
						<textarea name="keywords[<?= $lang_id; ?>]"><?= $values['site_keywords']; ?></textarea>
					</p>
					<p>
						<label>Site description</label>
						<textarea name="description[<?= $lang_id; ?>]"><?= $values['site_description']; ?></textarea>
					</p>
					<p>
						<label>Head additional source code</label>
						<textarea style="height: 300px;" name="head_js[<?= $lang_id; ?>]"><?= stripslashes($values['head_js']); ?></textarea>
					</p>
					<p>
						<label>Footer additional source code</label>
						<textarea style="height: 300px;" name="footer_js[<?= $lang_id; ?>]"><?= stripslashes($values['footer_js']); ?></textarea>
					</p>
					
				</div>
				<?php
				}
				?>
					<p>
						<input type="submit" value="Save changes" class="submit" />
					</p>
				</form>
			</div>
			<script>$(".tabs").tabs();</script>
			<?php } ?>
		</div>
	</div>
	
	
	<?php 
	if($f->adminAllowed("settings", "add")) {
	?>
	<div id="sidebar">
		<h2>New language</h2>
		<form method="POST" action="work.php">
			<input type="hidden" value="new_language" name="action">
			<p>
				<label>Name:</label>
				<input type="text" name="name" id="lang_name" class="text">
			</p>
			<p>
				<label>Short code:</label>
				<input type="text" id="lang_code" name="lang_code" class="text">
			</p>
			<p class="submit">
				<input type="submit" value="Create" class="submit" />
			</p>
				
			<script>
			$("#lang_name").live("blur", function() {
				if($("#lang_code").val() == "") {
					var tmp = $(this).val();
					$("#lang_code").val(tmp.substr(0, 3).toLowerCase());
				}
			});
			
			</script>
		</form>
	</div>
	<?php } ?>
</div>
</body>

</html>
