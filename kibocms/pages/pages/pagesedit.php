<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$p = new Pages();
	$l = new Leaves();
	
	$id = $f->getValue("id");
	
	
	require("../../head.php"); 
	
	if(!$f->adminAllowed("categories", "add")) {
		$f->redirect("index.php");
		die();
	}
	
?>
<link href="pages.css" type="text/css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="pages.js"></script>
</head>

<body>
<?php $currentPlace = "pages"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Edit a page</h1>
			
			<form method="POST" action="pageswork.php">
				<input type="hidden" name="action" value="edit">
				
				<div id="tabs">
					<ul>
						<?php
						$lang_arr = $f->getAllLanguages();
						foreach ($lang_arr as $lang_id => $langName) {
							echo "<li><a href=\"#sb_".$lang_id."\">$langName</a></li>";
						}
						?>
					</ul>
				
					<?php
					foreach ($lang_arr as $lang_id => $langName) {
						
						$values = $p->getPageValues($id, $lang_id);
					?>
					<input type="hidden" id="page_id" name="page_id[<?= $lang_id; ?>]" value="<?= $values['id']; ?>">
					<!-- subpage 1 -->
					<div id="sb_<?= $lang_id; ?>" class="tabs_content">
						<div class="half">
						<p>
							<label>Page name:</label>
							<input type="text" class="sf" id="name" name="name[<?= $lang_id; ?>]" value="<?= $values['name']; ?>">
						</p>
						<p>
							<label>URL:</label>
							<input type="text" class="sf" id="url" name="url[<?= $lang_id; ?>]" value="<?= $values['url']; ?>">
						</p>
						<p>
							<label>Header:</label>
							<select id="header2" name="header[<?= $lang_id; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$l->listLeavesSelectEdit($values['header'], $lang_id);
								?>
							</select>
						</p>
						<p>
							<label>Content:</label>
							<select id="content2" name="content[<?= $lang_id; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$l->listLeavesSelectEdit($values['content'], $lang_id);
								?>
							</select>
						</p>
						<p>
							<label>Footer:</label>
							<select id="footer2" name="footer[<?= $lang_id; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$l->listLeavesSelectEdit($values['footer'], $lang_id);
								?>
							</select>
						</p>
						
						<h2 style="width: 440px;"><br />Additional page settings</h2>
						<a href="#" class="showhide_setting" lang="<?= $lang_id; ?>">Show/Hide settings</a><br /><br />
						<div class= "add_h_and_f" id="page_setting_<?= $lang_id; ?>" style="display: none;">
							<p>
								<label>Page title</label>
								<input type="text" class="sf" name="page_title[<?= $lang_id; ?>]" value="<?= $values['page_title']; ?>">
							</p>
							<p>
								<label>Page keywords</label>
								<textarea style="width: 390px; margin: 0px; height: 40px;" name="page_keywords[<?= $lang_id;?>]"><?= $values['page_keywords'];?></textarea>
							</p>
							<p>
								<label>Page description</label>
								<textarea style="width: 390px; margin: 0px; height: 40px;" name="page_description[<?= $lang_id;?>]"><?= $values['page_description'];?></textarea>
							</p>
							<p>
								<label>Head additional source code</label>
								<textarea style="height: 150px; width: 390px; margin: 0px;" name="add_head[<?= $lang_id; ?>]"><?= stripslashes($values['add_head']); ?></textarea>
							</p>
							<p>
								<label>Footer additional source code</label>
								<textarea style="height: 150px; width: 390px; margin: 0px;" name="add_footer[<?= $lang_id; ?>]"><?= stripslashes($values['add_footer']); ?></textarea>
							</p>
								
						</div>
						</div>
						<!-- end of subpage 1 -->
						
						<!-- subpage 2 -->
						<div class="half">
						<?php
							if($values['content'] != 0) {
							?>
							<input type="hidden" id="page_id_<?= $lang_id; ?>" name="id" value="<?= $values['id']; ?>">
							
							<h2>Created elements</h2>
							<input type="text" class="search_elements_input tooltip" title="Search created elements" lang="<?= $lang_id; ?>"  />
							<br /><br />
							<a href="#" class="showhide_all" lang="<?= $lang_id; ?>">Show/Hide elements</a><br />
							<div class="elements_available" id="elements_available_<?= $lang_id; ?>" style="display:none;">
							<?php 
								$p->listLeavesView($values['header'], $values['content'], $values['footer'], $lang_id); 
							?>
							</div>
							<h2>
							<br />
							Drop zone 
									<img src="/kibocms/preset/assets/loading.gif" border="0" alt="" id="loader_<?= $lang_id; ?>" style="display: none;">
							</h2>
							<div class="portlete clear">
								
								<?php								
								$page_query = $db->execQuery("SELECT * FROM ".DB_PREFIX."leaves WHERE parent = '".$values['content']."'");
								while ($data = mysql_fetch_array($page_query, MYSQL_ASSOC)) {
									
									echo "<div class=\"leaf_destination\" id=\"".$data['id']."\">";
										echo "<h2>".$data['name']."</h2>";
										echo "<ul class=\"sortable sortable_".$lang_id."\" id=\"id_".$data['id']."\">";
										$p->listLeaves($values['id'], $data['id']);
										echo "</ul>";
									echo "</div>";
									
								}
								?>
								<br clear="all">
							</div>
							
							<script>
								$('.sortable_<?= $lang_id; ?>').sortable({
									placeholder: "ui-state-highlight2",
									connectWith: '.sortable_<?= $lang_id; ?>',
									update: function(event, ui) {
										order = $(this).sortable('serialize');
										//var newOrder = $(this).attr("id") + "::" + order;
										pageId = $("#page_id_<?= $lang_id; ?>").val();
										$("#loader_<?= $lang_id; ?>").show();
										$.ajax({
											url: 'pageswork.php',
											data: 'action=sort&'+order+'&pageId='+pageId+'&dest='+$(this).attr("id"),
											type: 'POST',
											async: false,
											success: function(data) {
												$("#loader_<?= $lang_id; ?>").hide();
											}
										});
									}
								}); 
								$( ".sortable_<?= $lang_id; ?>" ).disableSelection();
								
								$( ".lc_<?= $lang_id; ?>" ).draggable({
									appendTo: "#sb_<?= $lang_id; ?>",
									helper: "clone"
								});
								
								$('.sortable_<?= $lang_id; ?>').droppable({
									activeClass: "ui-state-default2",
									hoverClass: "ui-state-hover2",
									accept: "div",
									drop: function(event, ui) {
										
										leafId = ui.helper.attr("leafid");
										
										leafDestination = $(this).parent().attr("id");
										
										pageId = $("#page_id_<?= $lang_id; ?>").val();
										addLeaf(pageId, leafId, leafDestination, <?= $lang_id; ?>);
									}
								}); 
							</script>
							
							
							
						<?php
						} else {
							echo "<p>&nbsp;&nbsp;There is no content element for this page!</p>";
						}
						?>
						</div> <!-- end of half -->
						  
					</div> 
					<?php
					} // end foreach lang
					?>
					<!-- end of subpage 2 -->
				</div> <!-- end of tabs -->
				
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
