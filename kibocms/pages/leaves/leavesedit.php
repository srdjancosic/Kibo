<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	require("../../head.php"); 
	
	
	if(!$f->adminAllowed("elements", "edit")) {
		$f->redirect("index.php");
		die();
	}
?>
	<script src="/ui/jquery.ui.core.js"></script>
	<script src="/ui/jquery.ui.widget.js"></script>
	<script src="/ui/jquery.ui.mouse.js"></script>
	<script src="/ui/jquery.ui.draggable.js"></script>
	<script src="/ui/jquery.ui.droppable.js"></script>
	<script src="/ui/jquery.ui.sortable.js"></script>
	<script language="javascript" type="text/javascript" src="leaf.js"></script>
	<link href="leaf.css" type="text/css" rel="stylesheet">
</head>

<body>
<?php $currentPlace = "leaves"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
		<h1>Edit a element</h1>
		<form method="POST" action="leaveswork.php">
			<input type="hidden" name="action" value="edit">
			<div id="tabs">
				<ul>
				<?php
				$lang_arr = $f->getAllLanguages();
				foreach ($lang_arr as $lang_id => $langName) {
				?>
					<li><a href="#sb_<?= $lang_id; ?>"><?= $langName; ?></a></li>
				<?php
				}
				?>
				</ul>
			
				<?php
				
				$l = new Leaves();
				$c = new Category();
				$n = new Node();
				$id = $f->getValue("id");
					
				foreach ($lang_arr as $lang_id => $langName) {
					
					
					$values = $l->getLeavesValues($id, $lang_id);
					
				?>
				<!-- subpage 1 -->
				<div id="sb_<?= $lang_id; ?>">
					
					<input type="hidden" name="action" value="edit">
					<input type="hidden" name="id[<?= $lang_id; ?>]" value="<?= $values['id']; ?>">
					<div class="half">
						<p>
							<label>Element name:</label>
							<input type="text" class="sf" id="name" name="name[<?= $lang_id; ?>]" value="<?= $values['name']; ?>">
						</p>
						<p>
							<label>CSS class name:</label>
							<input type="text" class="sf" id="css_class" name="css_class[<?= $lang_id; ?>]" value="<?= $values['css_class']; ?>">
						</p>
						<p>
							<label>CSS id:</label>
							<input type="text" class="sf" id="css_id" name="css_id[<?= $lang_id; ?>]" value="<?= $values['css_id']; ?>">
						</p>
						<p>
							<label>Parent element:</label>
							<select id="parent" name="parent[<?= $lang_id; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$l->listLeavesSelect($values['parent'], $lang_id, $values['id']);
								?>
							</select>
						</p>
						<p>
							<label>Show element for:</label>
							<br />
							<select multiple="multiple" size="3" name=user_groups[<?= $lang_id; ?>][] class="styled">
 								<option value="0" <? if(in_array("0" ,explode ( "," , $values['user_group']))) echo "selected=\"selected\""; ?>>Show to all</option>
 								<?php 
									$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."`user_groups`");
									while($data = mysql_fetch_array($query, MYSQL_ASSOC)){?>
										<option value="<?= $data['id']?>" <? if(in_array($data['id'] ,explode ( "," , $values['user_group']))) echo "selected=\"selected\"" ?>><?= $data['name'];?></option><?
									}
								?>		
							</select> 	
						</p>
					</div>
				
					<?php
					$resultCount = $db->numRows("SELECT * FROM ".DB_PREFIX."leaves WHERE parent = '".$values['id']."'");
					if($resultCount == 0) {
					?>
					
					<div class="half">
						<div class="leaf_content lc_<?= $lang_id; ?> tooltip" what="leaf_html" title="Drag me to drop zone to add me">
							<h3>HTML content</h3>
						</div>
						<div class="leaf_content lc_<?= $lang_id; ?> tooltip" what="leaf_listing" title="Drag me to drop zone to add me">
							<h3>Category listing</h3>
						</div>
						<div class="leaf_content lc_<?= $lang_id; ?> tooltip" what="leaf_node" title="Drag me to drop zone to add me">
							<h3>Content view</h3>
						</div>
						<div class="leaf_content lc_<?= $lang_id; ?> tooltip" what="leaf_menu" title="Drag me to drop zone to add me">
							<h3>Menu</h3>
						</div>
						<div class="leaf_content lc_<?= $lang_id; ?> tooltip" what="leaf_filelist" title="Drag me to drop zone to add me">
							<h3>File list</h3>
						</div>
						<div class="leaf_content lc_<?= $lang_id; ?> tooltip" what="leaf_form" title="Drag me to drop zone to add me">
							<h3>Forms</h3>
						</div>
						
						<?php
						/** PLUGINS **/
							$pluginQuery = $db->execQuery("SELECT * FROM ".DB_PREFIX."plugins WHERE status = '1'");
							while ($pluginData = mysql_fetch_array($pluginQuery, MYSQL_ASSOC)) {
								?>
								<div class="leaf_content lc_<?= $lang_id; ?>" what="leaf_plugin_<?= $pluginData['plugin_name']; ?>">
									<h3><?= $pluginData['name']; ?></h3>
								</div>
								<?php
							}
						?>
						
						<div class="leaf_content lc_<?= $lang_id; ?>" what="leaf_view" title="Drag me to drop zone to add me">
							<h3>Plugin methods</h3>
						</div>
						
						
						<div class="portlete clear" leafid="<?= $values['id']; ?>" langid="<?= $lang_id; ?>">
							<h2>
								Drop zone 
								<img src="/kibocms/preset/assets/loading.gif" id="loader_<?= $lang_id; ?>" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">
							</h2>
							<div class="portlete_content placeholder_<?= $lang_id; ?>">
							  <?php
							  	if($values['content'] != "") {
							  		$l->getLeafContent($values['id'], $values['content'], $values['content_type'], $lang_id);
							  	} else {
							  		echo "<br><br><br><br>";
							  	}
							  ?>
							</div>
						</div>
					<!-- end of portlets -->
						
						<script>
							$( ".lc_<?= $lang_id; ?>" ).draggable({
								appendTo: "#sb_<?= $lang_id; ?>",
								helper: "clone"
							});
							
							$( ".placeholder_<?= $lang_id; ?>" ).droppable({
								activeClass: "ui-state-default2",
								hoverClass: "ui-state-hover2",
								accept: "div",
								drop: function( event, ui ) {
									$( this ).empty(); //find( ".placeholder" )
									
									var contentSrc = ui.draggable.attr("what");
									var str = "";
									var leafId = $(this).parent().attr("leafId");
									var lang_id = $(this).parent().attr("langid");
									$("#loader_<?= $lang_id; ?>").show();
									$.ajax({
										url: 'leaveswork.php',
										data: 'action=addLeafContent&id='+leafId+'&contentSrc='+contentSrc+'&langId='+lang_id,
										type: 'POST',
										success: function(data) {
											$('.placeholder_<?= $lang_id; ?>').html("<div>"+data+"</div>");
											$("#loader_<?= $lang_id; ?>").fadeOut();
											$( ".placeholder_<?= $lang_id; ?>" ).droppable("disable");
										}
									});
								}
							});
						</script>
					
					
					</div> 
				<!-- end of subpage 2 -->
					<?php
					} else { // if there is sub elements, then re-order them
						?>
						<div class="half">
							<h1>
								Re-order child elements
								<img src="/kibocms/preset/assets/loading.gif" id="loader2_<?= $lang_id; ?>" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">
							</h1>
							<form>
							<ul class="sortable" id="sortable_<?= $lang_id; ?>">
							<?php
							$query = $db->execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE parent = '".$values['id']."' AND lang_id = '".$lang_id."' ORDER BY `order` ASC");
							while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
							
							?>
								<li id="item_<?= $data['id']; ?>" class="plid_<?= $data['id']; ?>">
									<p>
										<input type="text" value="<?= $data['name']; ?>" readonly class="text" style="width: 150px;">
										<img src="/kibocms/preset/actions_small/Trash.png" border="0" alt="Remove" id="remove_<?= $data['id']; ?>"  onclick="removeLeaf('<?= $data['id']; ?>');" class="tooltip remove" title="Remove element from page" >
										<img src="/kibocms/preset/assets/move.gif" border="0" alt="Move" class="handler" >
									</p>
								</li>
							<?php
							}
							?>
							</ul>
							</form>
							<script>
							$('#sortable_<?= $lang_id; ?>').sortable({
								placeholder: "ui-state-highlight",
								handle: ".handler",
								update: function(event, ui) {
									order = $(this).sortable('serialize');
									//var newOrder = $(this).attr("id") + "::" + order;
									$("#loader2_<?= $lang_id; ?>").show();
									
									$.ajax({
										url: 'leaveswork.php',
										data: 'action=sort&'+order+"&lang_id=<?= $lang_id; ?>",
										type: 'POST',
										async: false,
										success: function(data) {
											$("#loader2_<?= $lang_id; ?>").hide();
										}
									});
								}
							}); 
							$( ".sortable_<?= $lang_id; ?>" ).disableSelection();
							</script>	
						</div>
						<?php
					}
					?>
				</div>
			<?php
				}
			?>
			<p>
				<br clear="all">
				<input type="submit" value="Save" class="submit">
				<input type="button" onclick="location.href='index.php'" value="Cancel" class="button">
			</p>
		</form>
		</div>
	</div>
	
	
	
	
</div>
</body>

</html>
