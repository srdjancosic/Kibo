<h2>Plugin view</h2>

<p>
<label>Plugin:</label>
	<input type='text' class='text' id='c_plugin' value='<?= $c_plugin; ?>' />
</p>
<p>
	<input type='button' value='Save' onclick='saveContent("plugin", "<?= $leafId; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>");' class='submit'>
</p>