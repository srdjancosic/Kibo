<h2>Content view</h2>

<p>
<label>Name:</label>
	<input type='text' class='sf' id='c_name_<?= $lang_id; ?>' value='<?= $c_name; ?>' />
</p>

<p>
	<label>Display header:</label>
	<input type='text' class='sf' id='display_title_<?= $lang_id; ?>' value='<?= $c_display_header; ?>' />
</p>

<p>
	<label>CSS class:</label>
	<input type='text' class='sf' id='css_<?= $lang_id; ?>' value='<?= $c_css; ?>' />
</p>


<p>
	<label>Content:</label>
	<textarea id='content_c_<?= $lang_id; ?>' class='textarea'><?= stripslashes($c_content); ?></textarea>
</p>

<p>
	<label>Limit per page:</label>
	<input type='text' class='sf' id='limit_<?= $lang_id; ?>' value='<?= $c_limit; ?>' />
</p>

<p>
	<label>Order by field:</label>
	<input type='text' class='sf' id='orderbyfield_<?= $lang_id; ?>' value='<?= $c_orderbyfield; ?>' />
</p>
<p>
	<label>Select categories:</label>
	<?= $c_categories; ?>
</p>
<p>
	<label>Ordering type:</label>
	<input type='text' class='sf' id='ordertype_<?= $lang_id; ?>' value='<?= $c_ordertype; ?>' />
	<span class='note'><br>* ASC, DESC, RAND()</span>
</p>
<p>
	<label>Pagination:</label>
	<input type='text' class='sf' id='pagination_c_<?= $lang_id; ?>' value='<?= $c_pagination; ?>' />
	<span class='note'><br />* more:value, none</span>
</p>

<p>
	<input type='button' value='Save' onclick='saveContent("node", "<?= $leafId; ?>", "<?= $lang_id; ?>");' class='submit'>
	<input type='button' value='Remove' onclick='removeContent("<?= $leafId; ?>", "<?= $lang_id; ?>");' class='button'>
</p>