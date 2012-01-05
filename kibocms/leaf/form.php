<h2>Form</h2>

<p>
	<label>Form ID</label>
	<select id='c_form_<?= $lang_id; ?>'>
	<?php 
	$query = mysql_query("SELECT * FROM forms");
	while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$selected = ($data['id'] == $c_form) ? "selected=\"selected\"" : "";
		echo "<option $selected value=\"".$data['id']."\">".$data['name']."</option>";
	}
	?>
	</select>
	
</p>
<p>
	<input type='button' value='Save' onclick='saveContent("form", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>