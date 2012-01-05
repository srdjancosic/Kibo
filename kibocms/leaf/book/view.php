<h3>Plugin book</h3>
<input type='hidden' id='pluginname' value='book' />

<p>
<label>Name:</label>
	<input type='text' class='text' id='c_name' value='$c_name' />
</p>

<p>
	<label>CSS class:</label>
	<input type='text' class='text' id='css' value='$c_css' />
	<span class='note'><br />* Leave blank for default</span>
</p>

<p>
	<label>Content:</label>
	<textarea id='content' class='textarea'>$c_content</textarea>
</p>

<p>
	<input type='button' value='Save' onclick='saveContent(\"plugin\", $leafId);' class='submit'>
	<input type='button' value='Remove' onclick='removeContent($leafId);' class='submit'>
</p>