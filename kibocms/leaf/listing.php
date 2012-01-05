<h2>List categories</h2>

<p>
<label>Name:</label>
	<input type='text' class='text' id='c_name_<?= $lang_id; ?>' value='<?= $c_name; ?>' />
</p>
<p>
	<label>CSS class:</label>
	<input type='text' class='text' id='css_<?= $lang_id; ?>' value='<?= $c_css; ?>' />
</p>

<p>
	<label>Select categories:</label>
	<?= $c_categories; ?>
</p>

<p>
	<input type='button' value='Save' onclick='saveContent("listing", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>