<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
	
?>
<?php require("../../head.php"); ?>
<script type="text/javascript" src="tables.js"></script>
<link href="tables.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php $currentPlace = "tables"; require("../../header.php"); ?>

	<div id="bgwrap">
		<div id="content">
			<div id="main">
				<?php
					$f->getMessage();
					$table_name = "c_".$f->getValue("name");
				?>
				<h1>Edit table: <?= $f->getValue("name");?></h1>
				<div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">
					<div class="half">
						<input type="hidden" value="<?= $table_name?>" id="table_name">
						<p>
							<label>Column name:</label>
							<input type="text" id="column_name" class="text">
						</p>
						<p>
							<label>Select column type:</label>
							<select id="columne_type">
								<option id="o1" value = "-1" selected="selected">Please select</option>
								<option value = "INT">INT</option>
								<option value = "VARCHAR">VARCHAR</option>
								<option value = "BIGINT">BIGINT</option>
								<option value = "DATE">DATE</option>
								<option value = "DATETIME">DATETIME</option>
								<option value = "LONGTEXT">LONGTEXT</option>
							</select>
						</p>
						<p id="length1" style="display: none;">
							<label>Length:</label>
							<input type="text" id="length" class="text">
		 				</p>
		 				<p>
							<input type="checkbox" value="1" id="use_default">
							Use default value
						</p>
						<p id="default" style="display: none;">
							<label>Default value:</label>
							<input type="text" id="default_value" class="text">
						</p>
		 				<p>
		 				<input type="button" class="submit" value="Add column" id="add_columne">
		 				</p>
	 				</div>
	 				<div class="half">
		 				<h2>
						Columnes in the table:
						<img border="0" style="display: none;" id="loader" alt="" src="/kibocms/preset/assets/loading.gif">
						</h2>
						<div id="columnes">
							<ul class="columne_list">
								<?php
									$query = mysql_query("SHOW COLUMNS FROM $table_name");
									while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									?>
										<li id="column_<?= $data['Field']?>" class="columne">
											<label><?= $data['Field']?></label>
											<div class="buttons_1" id="buttons_<?= $data['Field'];?>">
												<a href="#" id="remove_<?= $data['Field']; ?>" onclick="removeColumne('<?= $data['Field']; ?>','<?= $table_name;?>');">
												<img src="/kibocms/preset/actions_small/Trash.png">
												<a href="#" class="editCol" name="<?= $data['Field']?>" table_name="<?= $table_name;?>">
												<img src="/kibocms/preset/actions_small/Pencil.png" >
												</a>
											</div>
										</li>
									<?							
									}
								?>
							</ul>
						</div>
					</div>
					<script>
						$("select#columne_type").change(function() {
							var selected = $(this).val();
							
							if(selected == "VARCHAR") {
								$("p#length1").show();
							}else
								$("p#length1").hide();							
						});
						
						$("input#use_default").change(function() {
							var selected = $(this).val();
							
							if(document.getElementById("use_default").checked==true) {
								$("p#default").show();						
							}else{
								$("p#default").hide();
							}
						});
						
						
					</script>
				</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>