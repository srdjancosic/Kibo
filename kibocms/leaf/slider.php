<h2>Image slider</h2>
<script type="text/javascript" src="/kibofinder/kibofinder.js"></script>
<p>
<label>Name:</label>
	<input type='text' class='text' id='c_name' value='<?= $c_name; ?>' />
</p>

<p>
	<label>CSS class:</label>
	<input type='text' class='text' id='css' value='<?= $c_css; ?>' />
</p>

<p>
	<label>Content:</label>
	<textarea id='content_c' class='textarea'><?= $c_album; ?></textarea>
	<input type="button" value="File manager" onclick="browseServer();">
	<script>
		function browseServer() {
			kiboFinderAlone("/kibofinder/", "content_c");
		}
	</script>
</p>

<p>
	<input type='button' value='Save' onclick='saveContent("slider", "<?= $leafId; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>");' class='submit'>
</p>