<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	require("../../head.php"); 
	
	
	if(!$f->adminAllowed("content", "edit")) {
		$f->redirect("index.php");
		die();
	}
	
?>
<script language="javascript" src="node.js" type="text/javascript"></script>
<script type="text/javascript" src="/kibocms/ckeditor/ckeditor.js"></script>
<script language="javascript" src="/kibocms/kibofinder/kibofinder.js" type="text/javascript"></script>
<link href="node.css" type="text/css" rel="stylesheet">
</head>

<body>
<?php $currentPlace = "nodes"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
		<?php
			$f->getMessage();
		?>
		<h1>Edit a content</h1>
		<form method="POST" action="nodework.php">
			<div id="tabs">
			
			<ul>
			<?php
			$lang_arr = $f->getAllLanguages();
			foreach ($lang_arr as $lang_id => $langName) {
				echo "<li><a href=\"#sb_".$lang_id."\">".$langName."</a></li>";
			}
			
			echo "</ul>";
			
			foreach ($lang_arr as $lang_id => $langName) {
			
							
				$catId = $f->getValue("catId");
				$catId = ($catId == "") ? 0 : $catId;
				$n = new Node($catId);
				
				$id = $f->getValue("id");
				$categoryId = $db->getValue("category", "node", "id", $id);
				$categoryFolder = $db->getValue("url", "category", "id", $categoryId);
				
				$values = $n->getNodeValues($id, $lang_id);
				?>
			<div id="sb_<?= $lang_id; ?>">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="id[<?= $lang_id; ?>]" value="<?= $values['id']; ?>">
				<input type="hidden" name="catId" value="<?= $catId; ?>">
				<p>
					<label>Title:</label>
					<input type="text" class="text" id="name" name="name[<?= $lang_id; ?>]" value="<?= $values['name']; ?>">
				</p>
				<p>
					<label>URL:</label>
					<input type="text" class="text" id="url" name="url[<?= $lang_id; ?>]" value="<?= $values['url']; ?>">
				</p>
				<a href="#" class="showhide_setting" lang="<?= $lang_id; ?>">Show/Hide settings</a><br /><br />
				<div class= "add_h_and_f" id="node_setting_<?= $lang_id; ?>" style="display: none;">
					<p>
						<label>Content keywords</label>
						<textarea name="node_keywords[<?= $lang_id;?>]"><?= $values['node_keywords'];?></textarea>
					</p>
					<p>
						<label>Content description</label>
						<textarea name="node_description[<?= $lang_id;?>]"><?= $values['node_description'];?></textarea>
					</p>
				</div>
				<p>
					<label>Picture:</label>
					<input type="text" id="picture_<?= $lang_id; ?>" name="picture[<?= $lang_id; ?>]" class="text" value="<?= $values['picture']; ?>">
					<input type="button" class="submit" value="File manager" onclick="browseServer<?= $lang_id; ?>();">
				</p>
				 <script>
				 	function browseServer<?= $lang_id; ?>() {
				 		kiboFinderAlone('/kibocms/kibofinder/', 'picture_<?= $lang_id; ?>', '<?= $categoryFolder; ?>');
				 	}
				 </script>
				<p>
					<label>Short description:</label>
					<textarea id="short_desc" class="textarea" name="short_desc[<?= $lang_id; ?>]"><?= stripslashes($values['short_desc']); ?></textarea>
				</p>
				<p>
					<label>Body:</label>
					<textarea id="long_desc" class="textarea" name="long_desc[<?= $lang_id; ?>]"><?= stripslashes($values['long_desc']); ?></textarea>
				</p>
				<p>
					<label>Category:</label>
					<select id="category" name="category[<?= $lang_id; ?>]" class="styled">
						<?php
							$n->listCategorySelect($values['category'], $lang_id);
						?>
					</select>
				</p>
				<?php
					$n->listCustomFields($values['category'], $values['id'], $lang_id);
					
				?>
			</div>
				<?php
			} // end of lang arr
				?>
			</div>
			<p>
				<input type="submit" value="Save" class="submit">
				<input type="button" onclick="location.href='index.php?catId=<?= $catId; ?>'" value="Cancel" class="button">
			</p>
		</form>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	var shortdesc = CKEDITOR.replace( 'short_desc', {
		
		 toolbar :
			[
				['Source'],
				['Cut','Copy','Paste','PasteText','PasteFromWord'],
				['Undo','Redo','-','Find','Replace'],
				'/',
				['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
				['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				['Link','Unlink','Anchor'],
				['Image','Flash','Table','HorizontalRule','SpecialChar'],
				'/',
				['Styles','Format','Font','FontSize'],
				['TextColor','BGColor'],
				['Maximize']
			],
			width: 650,
			filebrowserBrowseUrl : '/kibocms/kibofinder/index.php',
	        filebrowserImageBrowseUrl : '/kibocms/kibofinder/index.php',
	        filebrowserFlashBrowseUrl : '/kibocms/kibofinder/index.php',
	} );
	var long_desc = CKEDITOR.replace( 'long_desc', {
		
		 toolbar :
			[
				['Source'],
				['Cut','Copy','Paste','PasteText','PasteFromWord'],
				['Undo','Redo','-','Find','Replace'],
				'/',
				['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
				['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				['Link','Unlink','Anchor'],
				['Image','Flash','Table','HorizontalRule','SpecialChar'],
				'/',
				['Styles','Format','Font','FontSize'],
				['TextColor','BGColor'],
				['Maximize']
			],
			width: 650,
			filebrowserBrowseUrl : '/kibocms/kibofinder/index.php',
	        filebrowserImageBrowseUrl : '/kibocms/kibofinder/index.php',
	        filebrowserFlashBrowseUrl : '/kibocms/kibofinder/index.php',
	} );
//]]>
</script>
</body>

</html>
