<h2>File list</h2>

<p>
	<label>Folder</label>
	<input type='text' class='text' id='c_folder_<?= $lang_id; ?>' value='<?= $c_folder; ?>' />
</p>
<p>
	<label>Content:</label>
	<textarea id='content_c_<?= $lang_id; ?>' class='textarea'><?= stripslashes($c_content); ?></textarea>
</p>
<p>
	<input type='button' value='Save' onclick='saveContent("filelist", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>