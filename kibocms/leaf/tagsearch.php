<h2>Tag search resuts</h2>
<p>
	<label>Content:</label>
	<textarea id='content_c_<?= $lang_id; ?>' class='textarea'><?= stripslashes($c_tagsearch); ?></textarea>
</p>
<p>
	<input type='button' value='Save' onclick='saveContent("tagsearch", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>