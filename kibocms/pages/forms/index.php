<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
	
?>
<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "forms"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Forms</h1>
			
			
			<br clear="all">
			<?php
				if($db->numRows("SELECT * FROM forms") != '0'){
					$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`forms` ORDER BY id DESC");
					while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
					?>
						<div class="box_1">
							<div class="inner">
								<h3><?= $data['name']; ?></h3>
								<small>table:</small> <?= ($data['table_name'] != "") ? $data['table_name'] : ""; ?>
								<br />
								<small>action:</small> <?= ($data['action'] != "") ? $data['action'] : ""; ?>
								
							</div>
							<div class="buttons">
								<?php
									  if($f->adminAllowed("forms", "edit")) {?>
								<a class="tooltip" title="Edit" href="formedit.php?id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
								<?php }
									  if($f->adminAllowed("forms", "delete")) { ?>
								<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="formwork.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
								<?php } ?>
							</div>
							<?
						echo '</div>';
					}
				}
			?>
		</div>
	</div>
	<?php 
	if($f->adminAllowed("forms", "add")) {
	?>
	<div id="sidebar">
		<h2>New form</h2>
		<form method="POST" action="formwork.php">
			<input type="hidden" name="action" value="add_form">	
			<p>
				<label>Form name:</label>
				<input type="text" class="text" id="name" name="name">
			</p>
			<p>
				<label>Identificator</label>
				<input type="text" class="text" id="identificator" name="identificator">
			</p>
			<p>
				<label>Connect to table:</label>
				<select id="table" name="table_name" class="styled">
				<?php
					$query = $db->execQuery("show tables");
					while($data = mysql_fetch_array($query)){
						list($first, $last) = explode('_',$data['0']);
						if($first == 'c' ){?>
						<option value="<?= $data['0']?>" ><?= $last; ?></option>
					<?
						}
					}
				?>
				</select>
			</p>
			<p>
				<label>Form action:</label>
				<input type="text" class="text" id="action" name="form_action">
			</p>
			<p>
				<label>Submit value:</label>
				<input type="text" class="text" id="submit_value" name="submit_value">
			</p>
			<p>
				<label>Submit class:</label>
				<input type="text" class="text" id="submit_class" name="submit_class">
			</p>
			<p>
				<label>Submit identificator:</label>
				<input type="text" class="text" id="submit_id" name="submit_id">
			</p>
			<p>
				<input type="checkbox" name="file_upload" value="1">File upload
			</p>			
			<p>
				<input type="submit" value="Next" class="submit">
			</p>
		</form>
		
	</div>
	<?php
	}
	?>
	</div>
	
	

</body>

</html>