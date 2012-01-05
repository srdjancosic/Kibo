<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	require("../../head.php"); 
	
	
	if(!$f->adminAllowed("reg_fields", "edit")) {
		$f->redirect("index.php");
		die();
	}
?>
	<script src="/ui/jquery.ui.core.js"></script>
	<script src="/ui/jquery.ui.widget.js"></script>
	<script src="/ui/jquery.ui.mouse.js"></script>
	<script src="/ui/jquery.ui.draggable.js"></script>
	<script src="/ui/jquery.ui.droppable.js"></script>
	<script src="/ui/jquery.ui.sortable.js"></script>
	<script language="javascript" type="text/javascript" src="leaf.js"></script>
	<link href="leaf.css" type="text/css" rel="stylesheet">
</head>

<body>
<?php $currentPlace = "registration_fields"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main" style="float:left;">
		<?php
				$f->getMessage();
			?>
		<h1>Edit a field</h1>
		
			<?php
				
				
				$id = $f->getValue("id");
					
				
					
					
				$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`registration_fields` WHERE `id` = '$id'");
				$values = mysql_fetch_array($query, MYSQL_ASSOC);//vrednost tenutnog polja
					
			?>
				<!-- subpage 1 -->
				<div class="half">
						<form method="POST" action="fieldswork.php">
						<input type="hidden" name="action" value="edit">
						<input type="hidden" name="id" value="<?= $values['id']; ?>">	
						<p>
							<label>Field name:</label>
							<input type="text" class="sf" id="name" name="name" value="<?= $values['name']; ?>">
						</p>
						<p>
							<label>CSS class name:</label>
							<input type="text" class="sf" id="css_class" name="css_class" value="<?= $values['css_class']; ?>">
						</p>
						<p>
							<label>Connect to column:</label>
							<select id="colomn" name="colomn" class="styled">
							<?php
								$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`users` ");
								$data = mysql_fetch_array($query, MYSQL_ASSOC);
								foreach ($data as $field => $value){?>
									<option value=<?= $field?> <?= ($field == $values['responding_column']) ? "selected=\"selected\"" : ""; ?>><?= $field; ?></option><?							
								}
							?>
							</select>
						</p>
						<p>
							<label>Field type:</label>
							<select id="field_type" name="field_type" class="styled">
								<?php
									
									$query1 = $db->execQuery("SELECT * FROM ".DB_PREFIX."`registration_field_types`");
									while($data1 = mysql_fetch_array($query1, MYSQL_ASSOC)){?>
										<option value=<?= $data1['name']?> <?= ($data1['name'] == $values['field_type']) ? "selected=\"selected\"" : ""; ?>><?= $data1['name']; ?></option><?
									}
								?>
							</select>
						</p>
						<p>
							<label>Field type:</label>
							<select id="field_type" name="field_type" class="styled">
								<?php
									
									$query1 = $db->execQuery("SELECT * FROM ".DB_PREFIX."`registration_field_types`");
									while($data1 = mysql_fetch_array($query1, MYSQL_ASSOC)){?>
										<option value=<?= $data1['name']?> <?= ($data1['name'] == $values['field_type']) ? "selected=\"selected\"" : ""; ?>><?= $data1['name']; ?></option><?
									}
								?>
							</select>
						</p>	
						<p>
							<label>Validation:</label>
							<input type="text" class="sf" id="validation" name="validation" value="<?= $values['validation']; ?>">
						</p>
						<p>
							<input type="submit" value="Save" class="submit">
							<input type="button" onclick="location.href='index.php'" value="Cancel" class="button">
						</p>
						</form>
					</div><!-- kraj dela za editovanje polja -->
					<div class="half">
						<?php //ispisuje sve vrednosti datog polja na svim jezicima
							$resultCount = $db->numRows("SELECT * FROM ".DB_PREFIX."`registration_field_value` WHERE `field_id` = '$id'");
							if($resultCount != 0) {
							$lang_arr = $f->getAllLanguages();
								foreach ($lang_arr as $lang_id => $langName) {
									$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`registration_field_value` WHERE `field_id` = '$id' AND `lang_id`='$lang_id'");
									while($data = mysql_fetch_array($query, MYSQL_ASSOC)){
										echo '<div class="box_1">'; //jedna vrednost
											echo '<div class="inner">'; 
												echo '<h3>'.$data['label'].'</h3><br />';
												echo '<small>css_id:'.$data['css_id'].'</small>';
												echo '<small>Language:'.$langName.'</small><br />';
												echo '<small>Value:'.$data['value'].'</small><br />';
											echo '</div>';
											echo '<div class="buttons">';?>
													<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="fieldsValuework.php?action=delete&id=<?= $data['id']; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
													<?
											echo '</div>';
										echo'</div>';
									} //kraj ispisa vrednosti
								}
						}						
						?>
						<h2 style="margin-top: 40px;">Add value</h2> <!-- dodavanje nove vrednosti -->
						<div class="portlete_content" style="background: none repeat scroll 0 0 #F8F8F8; border: 1px solid #E3E3E3; padding: 30px 10px 10px; width: 400px;">
							<form method="POST" action="fieldsValuework.php">
								<input type="hidden" name="action" value="add">
								<input type="hidden" name="field_id" value="<?= $values['id']; ?>">
								<p>
									<label>css_id:</label>
									<input type="text" class="taxt" id="css_id" name="css_id">
								</p>
								<p>
									<label>Language:</label>
									<select id="lang_id" name="lang_id" class="styled">
									<?php
										$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`languages` WHERE `active`='1'");
										while ($data = mysql_fetch_array($query, MYSQL_ASSOC)){
											echo '<option value="'.$data['id'].'">'.$data['name'].'</option>';							
										}
									?>
									</select>
								</p>
									
								<p>
									<label>Label:</label>
									<input type="text" class="text" id="label" name="label">
								</p>
								<p>
									<label>Value:</label>
									<input type="text" class="text" id="value" name="value">
								</p>
								<p>
									<input type="submit" value="Save" class="submit">
								</p>
							</form>
						</div> <!--  kraj dodavanja nove vrednosti -->					
					</div><!-- end of second half div -->		
				</div><!-- end of main -->
				</div><!-- end of contnt div -->
				<div id="sidebar"> <!-- dodavanje nove vrste polja type -->
					<h2>New field type</h2>
					<form method="POST" action="fieldswork.php?id="<?= $id;?>">
						<input type="hidden" name="action" value="add_type">
						<input type="hidden" name="id" value=<?= $id;?>>	
						<p>
							<label>Type name:</label>
							<input type="text" class="text" id="type_name" name="type_name">
						</p>
						<p>
							<input type="submit" value="Next" class="submit">
						</p>
					</form>
				</div>
			
		</div><!-- end of bgwrap -->
	</div>
	
	
	
</body>

</html>
