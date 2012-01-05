<h2>Plugin view</h2>

<p>
<label>Plugin:</label>
	<input type='text' class='text' id='c_plugin_<?= $lang_id; ?>' value='<?= $c_plugin; ?>' />
</p>
<p>
<label>Method name:</label>
	<input type='text' class='text' id='c_name_<?= $lang_id; ?>' value='<?= $c_name; ?>' />
</p>
<p>
	<input type='button' value='Save' onclick='saveContent("pluginView", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>