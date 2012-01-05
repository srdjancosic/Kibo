<li id="field_<?= $field_id; ?>">
	<?
		$name = $db->getValue("name", "form_fields", "id", $field_id);
		
	?>
	<div style="width: 80px; float:left; height: 100%;">
	<label style="width: 80px; margin-top: 7px;"><?= $name; ?></label>
	</div>
	<div style="width: 150px; float:left;">
	<button type="button"><?= $title; ?></button> 
	</div>	
	<a href="#" id="remove_<?= $field_id; ?>" onclick="removeField('<?= $field_id; ?>');">
		<img src="/kibocms/preset/actions_small/Trash.png">
	</a>
	<a href="#" id="edit_<?= $field_id; ?>" onclick="editField('<?= $field_id; ?>');">
		<img src="/kibocms/preset/actions_small/Pencil.png" >
	</a>
	<img src="/kibocms/preset/assets/move.gif" border="0" alt="Move" class="handler">
	
</li>