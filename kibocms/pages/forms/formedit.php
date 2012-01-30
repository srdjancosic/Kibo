<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
	
?>
<?php require("../../head.php"); ?>
<script type="text/javascript" src="forms.js"></script>
<link href="forms.css" type="text/css" rel="stylesheet" />
</head>

<body>
<?php $currentPlace = "forms"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();

				$form_id = $f->getValue("id");
				$form = new View("forms", $form_id);
			
			?>
			<h1>Form edit: <?= $form->name; ?></h1>
			<input type="hidden" value="<?= $form_id; ?>" id="form_id" />
			
			<br clear="all">
			<p>
			<label>Name:</label>
			<input type="text" class="text" id="pre_def_name">
			</p>
			<p>
			<label>Label:</label>
			<input type="text" class="text" id="pre_def_label">
			</p>
			<p>
				<label>Select field type:</label>
				<select id="field_type">
					<?php
					$collection = new Collection("field_types");
					$fieldType = $collection->getCollection();
					
					foreach ($fieldType as $key => $field) {
						echo "<option value=\"".$field->type."\">".$field->name."</option>";
					}
					?>
				</select>
				<input type="button" class="submit" value="Add field" id="add_to_form" />
			</p>
			<br clear="all">
			
			<h2>
			Fields in the list
			<img border="0" style="display: none;" id="loader" alt="" src="/kibocms/preset/assets/loading.gif">
			</h2>
			<div id="field_list">
				<ul class="sortable">
					<?php
					$collection_field_list = new Collection("form_fields");
					$field_list = $collection_field_list->getCollection("WHERE form_id = '$form_id' ORDER BY `ordering`");
					foreach($field_list as $key => $field) {
						$field_id = $field->id;
						$pre_def_name = $field->name;
						$title = $db->getValue("name", "field_types", "type", $field->field_type);
						switch ($field->field_type) {
							case "text": require ("fields/textbox.php"); break;
							case "password": require ("fields/password.php"); break;
							case "textarea": require("fields/textarea.php"); break;
							case "select": require("fields/select.php"); break;
							case "select_multiple": require("fields/selectmultiple.php"); break;
							case "checkbox": require("fields/checkbox.php"); break;
							case "radiobutton": require("fields/radiobutton.php"); break;
							case "button": require("fields/button.php"); break;
							case "hidden": require("fields/hidden.php"); break;
							case "datapicker": require("fields/datapicker.php"); break;
							case "colorpicker": require("fields/colorpicker.php"); break;
							case "fileupload": require("fields/fileupload.php"); break;
						}
						?>
						
						<?php
					}
					?>
				</ul>
			</div>
			
			
			<div id="field_edit">
			</div>
			
		</div>
	</div>
	<div id="sidebar">
		<h2>Edit form</h2>
		<form method="POST" action="formwork.php">
			<input type="hidden" name="action" value="edit_form">	
			<input type="hidden" name="id" value="<?= $form_id; ?>">
			<p>
				<label>Form name:</label>
				<input type="text" class="text" id="name" name="name" value="<?= $form->name;?>">
			</p>
			<p>
				<label>Identificator</label>
				<input type="text" class="text" id="identificator" name="identificator" value="<?= $form->identificator; ?>">
			</p>
			<p>
				<label>Connect to table:</label>
				<select id="table" name="table_name" class="styled">
				<?php
					$query = $db->execQuery("show tables");
					while($data = mysql_fetch_array($query)){
						list($first, $last) = explode('_',$data['0']);
						if($first == 'c' ){?>
						<option value="<?= $data['0']?>" <?= ($form->table_name == $data[0]) ? "selected=\"selected\"" : "" ?> se><?= $last; ?></option>
					<?
						}
					}
				?>
				</select>
			</p>
			<p>
				<label>Form action:</label>
				<input type="text" class="text" id="action" name="form_action" value="<?= $form->action;?>">
			</p>
			<p>
				<label>Submit value:</label>
				<input type="text" class="text" id="submit_value" name="submit_value" value="<?= $form->submit_value;?>">
			</p>
			<p>
				<label>Submit class:</label>
				<input type="text" class="text" id="submit_class" name="submit_class" value="<?= $form->submit_class;?>">
			</p>
			<p>
				<label>Submit identificator:</label>
				<input type="text" class="text" id="submit_id" name="submit_id" value="<?= $form->submit_id;?>">
			</p>
			<p>
				<input type="checkbox" name="file_upload" value="1" <? if($form->file_upload == 1) echo "checked" ?>>File upload
			</p>			
			<p>
				<input type="submit" value="Save" class="submit">
			</p>
		</form>
		
	</div>
	</div>
	
	

</body>

</html>