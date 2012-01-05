<h2>HTML Content</h2>

<p>
<label>Name:</label>
	<input type='text' class='text' id='c_name_<?= $lang_id; ?>' value='<?= $c_name; ?>' />
</p>
<p>
	<label>CSS class:</label>
	<input type='text' class='text' id='css_<?= $lang_id; ?>' value='<?= $c_css; ?>' />
</p>

<p>
	<label>Content:</label>
	<textarea id='content_c_<?= $lang_id; ?>' class='textarea'><?= stripslashes($c_content); ?></textarea>
</p>

<p>
	<input type='button' value='Save' onclick='saveContent("html", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>