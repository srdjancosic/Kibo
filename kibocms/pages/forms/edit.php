<input type="hidden" value="<?= $id; ?>" id="_field_id" />
<?php 
	$view = new View("form_fields", $id);
?>

<p>
	<label>Label</label>
	<input type="text" value="<?= $view->label; ?>" id="_label" />
</p>
<p>
	<label>Name</label>
	<input type="text" value="<?= $view->name; ?>" id="_name" />
</p>
<p>
	<label>Table field</label>
	<select id="_table_field">
		<option value="0">Select table field</option>
		<?php
		$table_name = $db->getValue("table_name", "forms", "id", $view->form_id);
		$query = mysql_query("SHOW COLUMNS FROM $table_name");
		while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$selected = ($view->table_field == $data['Field']) ? "selected=\"selected\"" : "";
			echo "<option $selected value=\"".$data['Field']."\">".$data['Field']."</option>";
		}
		?>
	</select>
</p>
<p>
	<label>Required</label>
	<input type="checkbox" value="1" id="_required" <?= ($view->required == 1) ? "checked=\"checked\"" : ""; ?> />
</p>
<p>
	<label>Validation</label>
	<input type="text" value="<?= stripslashes($view->validation); ?>" id="_validation" />
</p>

<p>
	<label>Error message</label>
	<input type="text" value="<?= stripslashes($view->error_message); ?>" id="_error_message" />
</p>
<p>
	<label>Value</label>
	<textarea id="_value" style="width: 200px; clear: none;"><?= $view->value; ?></textarea>
	<br />
	<input type="checkbox" id="_from_table" value="1" <?= ($view->from_table == 1) ? "checked=\"checked\"" : ""; ?> />
	From table
</p>

<p>
	<label>Selected value</label>
	<input type="text" value="<?= $view->selected_value; ?>" id="_selected_value" />
</p>

<p>
	<label>Constant value</label>
	<select id="_constant">
		<option value="">Select constant</option>
		<?php
		
		$query = mysql_query("SELECT * FROM `constants`");
		while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$selected = ($view->constant == $data['name']) ? "selected=\"selected\"" : "";
			echo "<option $selected value=\"".$data['name']."\">".$data['name']."</option>";
		}
		?>
	</select>
</p>

<p>
	<label>CSS #</label>
	<input type="text" value="<?= $view->identificator; ?>" id="_identificator" />
</p>

<p>
	<label>CSS Class</label>
	<input type="text" value="<?= $view->class; ?>" id="_class" />
</p>

<p>
	<label>Hint</label>
	<input type="text" value="<?= $view->hint; ?>" id="_hint" />
</p>

<p>
	<label>&nbsp;</label>
	<input type="submit" value="Save" id="save_button" class="submit" />
	<input type="button" class="button" value="Cancel" id="cancel_button" />
</p>