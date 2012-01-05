<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$l = new Leaves();
	$id = $f->getValue("id");
	
?>

<?php require("../../head.php"); ?>
<link href="menu.css" type="text/css" rel="stylesheet" />
<script src="jquery.ui.nestedSortable.js" type="text/javascript"></script>

</head>

<body>
<?php $currentPlace = "menu"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Edit Menu Content</h1>
			<br clear="all">
			<br clear="all">
			
			<div id="tabs">
				<ul>
				<?php $lang_arr = $f->getAllLanguages();
				foreach ($lang_arr as $lang_id => $langName) {
					echo "<li><a href=\"#sb_".$lang_id."\">".$langName."</a></li>";
				}
				?>
				</ul>
			
			<?php
				foreach ($lang_arr as $lang_id => $langName) {
			?>
				<div id="sb_<?= $lang_id; ?>" class="tabs_content">
					<div class="half">
						<p>
							<label>Section:</label>
							<select id="section_<?= $lang_id; ?>" langid="<?= $lang_id; ?>">
								<option value="-1">Please select</option>
								<option value="categories">Category</option>
								<option value="pages">Pages</option>
								<option value="home">Index page</option>
								<option value="link">URL</option>
								<option disabled value="-1">-----</option>
								<?php
								$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."category WHERE lang_id = '$lang_id' ORDER BY id ASC, parent ASC");
								while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									echo "<option value=\"nodes:".$data['id']."\">".$data['name']."</option>";
								}
								?>
							</select>
						</p>
						<p id="parent_<?= $lang_id; ?>">
							<label>Parent:</label>
							<select id="parent_item_<?= $lang_id; ?>">
								<option value="none">None</option>
								
								<?php
								$data = $l->getLeavesValues($id, $lang_id);
								$menu_items = $data['content'];
								if($menu_items != ""){
									$menu_items_arr = unserialize($menu_items);
									foreach($menu_items_arr as $key => $value) {
										list($item_type, $item_id) = explode(":", $value[0]);
										switch ($item_type) {
											case "category": $title = $db->getValue("name", "category", "id", $item_id); break;
											case "page": $title = $db->getValue("name", "pages", "id", $item_id); break;
											case "node": $title = $db->getValue("name", "node", "id", $item_id); break;
											case "home": $title = $item_id; break;
											case "link": list($title, $url) = explode("||", $item_id);
										}
										echo "<option value=\"".$item_type.":".$item_id."\">".$title."</option>";
									}
								}
								
								?>
								
							</select>
						</p>
						<p id="section_item_<?= $lang_id; ?>">
							<label>Section item:</label>
							<select id="item_<?= $lang_id; ?>">
								<option value="-1">Empty</option>
							</select>
						</p>
						<p id="link_name_<?= $lang_id; ?>" style="display: none;"> 
							<label>Link name:</label>
							<input type="text" class="sf" id="link_name_input_<?= $lang_id; ?>" />
						</p>
						
						<p id="link_url_<?= $lang_id; ?>" style="display: none;"> 
							<label>Link URL:</label>
							<input type="text" class="sf" id="link_url_input_<?= $lang_id; ?>" />
						</p>
						
						<p>
							<input type="button" class="submit" value="Add to menu" id="submit_<?= $lang_id; ?>">
						</p>
					</div>
					
					<div class="half">
						<h2>
							Menu items
							<img src="/kibocms/preset/assets/loading.gif" id="loader2_<?= $lang_id; ?>" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">
						</h2>
						<ul id="sortable_<?= $lang_id; ?>" class="sortable">
							<?php
								$data = $l->getLeavesValues($id, $lang_id);
								$menu_items = $data['content'];
								if($menu_items != ""){
									$menu_items_arr = unserialize($menu_items); //explode(";", $menu_items);
									
									foreach ($menu_items_arr as $key2 => $menu_item) {
										$parent = "";
										foreach($menu_item as $key => $value) {
											
											list($item_type, $item_id) = explode(":", $value);
											$title = "";
											switch ($item_type) {
												case "category": $title = $db->getValue("name", "category", "id", $item_id); break;
												case "page": $title = $db->getValue("name", "pages", "id", $item_id); break;
												case "node": $title = $db->getValue("name", "node", "id", $item_id); break;
												case "home": $title = $item_id; break;
												case "link": list($title, $url) = explode("||", $item_id);
											}
											//$title = base64_decode($title, );
											if($key == 0) {
												$parent = $item_type.":".$item_id;
												echo "<li id=\"item_$item_type:$item_id\">".
													 strip_tags($title).
													 "<img src=\"/kibocms/preset/assets/move.gif\" class=\"handler\" />".
													 "<img src=\"/kibocms/preset/actions_small/Trash.png\" onclick=\"javascript:removeItem('item_$item_type:$item_id', '$lang_id', '".$data['id']."');\" />". 
													 "<ul>";
												
											} elseif ($key > 0) {
												echo "<li id=\"parent_item_".$parent."[".$item_type.":".$item_id."]\">".
													 strip_tags($title).
													 //"<img src=\"/kibocms/preset/assets/move.gif\" class=\"handler\" />".
													 "<img src=\"/kibocms/preset/actions_small/Trash.png\" onclick=\"javascript:removeItem('parent_item_".$parent."[".$item_type.":".$item_id."]', '$lang_id', '".$data['id']."');\" />". 
													 "</li>";
											}
											if($key == count($menu_item) - 1) {
												echo "</ul>".
													 "</li>";
											}
										}
									}
								
								}
							?>
						</ul>
					</div>
					
					<script>
					$(document).ready(function() {
					
						$('#sortable_<?= $lang_id; ?>').sortable({
							placeholder: "ui-state-highlight",
							handle: ".handler",
							nested:'ul',
							items: 'li',
							update: function(event, ui) {
								
								order = $(this).sortable('serialize');
								
								$("#loader2_<?= $lang_id; ?>").show();
								
								$.ajax({
									url: 'work.php',
									data: 'action=sort&'+order+"&lang_id=<?= $lang_id; ?>&menu_id=<?= $data['id']; ?>",
									type: 'POST',
									async: false,
									success: function(data) {
										$("#loader2_<?= $lang_id; ?>").hide();
									}
								});
								
							}
						}); 
						$( "#sortable_<?= $lang_id; ?>" ).disableSelection();
						
						
						
						$("select#section_<?= $lang_id; ?>").change(function() {
							var selected = $(this).val();
							var lang_id = $(this).attr("langid");
							
							if(selected == "-1") {
								$("p#section_item_<?= $lang_id; ?>").show();
								$("p#link_name_<?= $lang_id; ?>").hide();
								$("p#link_url_<?= $lang_id; ?>").hide();
								$("select#item_<?= $lang_id; ?>").html("<option value=\"-1\">Empty</option>");
							}
							else if(selected == "home") {
								$("p#section_item_<?= $lang_id; ?>").hide();
								$("p#link_name_<?= $lang_id; ?>").show();
								$("p#link_url_<?= $lang_id; ?>").hide();
							} 
							else if(selected == "pages") {
								$("p#section_item_<?= $lang_id; ?>").show();
								$("p#link_name_<?= $lang_id; ?>").hide();
								$("p#link_url_<?= $lang_id; ?>").hide();
								$.ajax({
									url: 'work.php',
									data: 'action=listPages&lang_id='+lang_id,
									type: 'POST',
									success: function(data) {
										$("select#item_<?= $lang_id; ?>").html(data);
									}
								});
							} 
							else if(selected == "link") {
								$("p#section_item_<?= $lang_id; ?>").hide();
								$("p#link_name_<?= $lang_id; ?>").show();
								$("p#link_url_<?= $lang_id; ?>").show();
							}
							else if(selected == "categories") {
								$("p#section_item_<?= $lang_id; ?>").show();
								$("p#link_name_<?= $lang_id; ?>").hide();
								$("p#link_url_<?= $lang_id; ?>").hide();
								$.ajax({
									url: 'work.php',
									data: 'action=listCategories&lang_id='+lang_id,
									type: 'POST',
									success: function(data) {
										$("select#item_<?= $lang_id; ?>").html(data);
									}
								});
							} else if (selected.substr(0, 5) == "nodes") {
								var arr = selected.split(":");
								var catId = arr[1];
								$("p#link_name_<?= $lang_id; ?>").hide();
								$("p#link_url_<?= $lang_id; ?>").hide();
								$("p#section_item_<?= $lang_id; ?>").show();
								$.ajax({
									url: 'work.php',
									data: 'action=listNodes&lang_id='+lang_id+'&category_id='+catId,
									type: 'POST',
									success: function(data) {
										$("select#item_<?= $lang_id; ?>").html(data);
									}
								});
							}
							
							
						});
						
						$("#submit_<?= $lang_id; ?>").live("click", function() {
							if($("select#section_<?= $lang_id; ?>").val() == "home") {
								
								var title = $("input#link_name_input_<?= $lang_id; ?>").val();
								var item = "home:"+title;
							} else if($("select#section_<?= $lang_id; ?>").val() == "link") {
								var title = $("input#link_name_input_<?= $lang_id; ?>").val();
								var link = $("input#link_url_input_<?= $lang_id; ?>").val();
								var item = "link:"+title+"||"+link;
							} else {
								var item = $("select#item_<?= $lang_id; ?>").val();
								var title = $("select#item_<?= $lang_id; ?> option:selected").text();
							}
							if(item != "-1") {
							
								// get parent, if selected append new item inside parent, else append to main list
								var parent = "item_" + $("#parent_item_<?= $lang_id; ?>").val();
								
								if(parent != "item_none") {
									
									$("#sortable_<?= $lang_id; ?>").find("li[id="+parent+"]").find("ul").append("<li id=\"parent_"+parent+"["+item+"]\">"+
																						title+
																						//"<img src=\"/kibocms/preset/assets/move.gif\" class=\"handler\" />"+
																						"<img src=\"/kibocms/preset/actions_small/Trash.png\" onclick=\"javascript:removeItem('parent_"+parent+"["+item+"]', '<?= $lang_id; ?>', '<?= $data['id']; ?>');\" />"+
																						"</li>");
								} else {
									$("#sortable_<?= $lang_id; ?>").append("<li id=\"item_"+item+"\">"+
																			title+
																			"<img src=\"/kibocms/preset/assets/move.gif\" class=\"handler\" />"+
																			"<img src=\"/kibocms/preset/actions_small/Trash.png\" onclick=\"javascript:removeItem('item_"+item+"', '<?= $lang_id; ?>', '<?= $data['id']; ?>');\" />"+
																			"<ul></ul>"+
																			"</li>");
									$("#parent_item_<?= $lang_id; ?>").append("<option value=\""+item+"\">"+title+"</option>");
								}
								
								var order = $("#sortable_<?= $lang_id; ?>").sortable('serialize');
								
								$("#loader2_<?= $lang_id; ?>").show();
								
								$.ajax({
									url: 'work.php',
									data: 'action=sort&'+order+"&lang_id=<?= $lang_id; ?>&menu_id=<?= $data['id']; ?>",
									type: 'POST',
									async: false,
									success: function(data) {
										$("#loader2_<?= $lang_id; ?>").hide();
									}
								});
							}
						});
						
						
					});
					
						
					function removeItem(id, lang_id, menu_id) {
						if(confirm("Are you sure to remove this item from menu?")) {

							//remove from parent <ul>
							$("#parent_item_"+lang_id+" option").each(function() {
								var value = "item_"+$(this).attr("value");
								if( value == id ) {
									$(this).remove();
								}
							});
							
							id = id.replace(/\[/g, "\\[");
							id = id.replace(/\]/g, "\\]");
							id = id.replace(/\:/g, "\\:");
							id = id.replace(/\|/g, "\\|");
							id = id.replace(/\ /g, "\\ ");
							
							//$("#sortable_"+lang_id+" li#"+id).css("border", "3px solid red");
							
							$("#sortable_"+lang_id+" li#"+id).remove(); 

							
							var order = $("#sortable_"+lang_id).sortable('serialize');
								
							$("#loader2_"+lang_id).show();
							
							$.ajax({
								url: 'work.php',
								data: 'action=sort&'+order+"&lang_id="+lang_id+"&menu_id="+menu_id,
								type: 'POST',
								async: false,
								success: function(data) {
									$("#loader2_"+lang_id).hide();
								}
							});
							
						}
					}
					</script>
				</div>
			<?php
			}
			?>
			</div> <!-- end of tabs -->
		</div>
		
	</div>
	
</div>
</body>

</html>
