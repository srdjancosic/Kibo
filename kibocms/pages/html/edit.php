<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$l = new Leaves();
	$id = $f->getValue("id");
	
	
?>
<?php require("../../head.php"); ?>
	<script type="text/javascript" src="/kibocms/ckeditor/ckeditor.js"></script>
</head>

<body>
<?php
	$currentPlace = "html";
	require("../../header.php");
?>
<div id="bgwrap">
	<div id="content">
		<div id="main">

<?php
	$f->getMessage();
?>

<h1>Edit HTML content</h1>
	<form method="POST" action="work.php">
		<input type="hidden" name="action" value="save">
		
		<div id="tabs">
			<ul>
			<?php 
			$lang_arr = $f->getAllLanguages();
			foreach ($lang_arr as $lang_id => $langName) {
				echo "<li><a href=\"#sb_".$lang_id."\">".$langName."</a></li>";
			}
			?>
			</ul>
			<?php foreach ($lang_arr as $lang_id => $langName) {
				$values = $l->getLeavesValues($id, $lang_id); 
			?>
			<div class="tabs_content" id="sb_<?= $lang_id; ?>">
				
				<input type="hidden" name="leaf_id[<?= $lang_id; ?>]" value="<?= $values['id']; ?>">
				<p>
				<?php
					if($values['content'] != "") {
						list($c_name, $c_display_header, $c_css, $c_content) = explode("|:|", $values['content']);
						?>
						<input type="hidden" name="css_class[<?= $lang_id; ?>]" value="<?= $c_css; ?>">
						<input type="hidden" name="display_header[<?= $lang_id; ?>]" value="<?= $c_display_header; ?>">
						<input type="hidden" name="name[<?= $lang_id; ?>]" value="<?= $c_name; ?>">
						<?php
						echo "<textarea id='content_t_$lang_id' name='content_t[$lang_id]'>".stripslashes($c_content)."</textarea>";
					}
				?>
				</p>
			</div>
			<script type="text/javascript">
			//<![CDATA[
				var content = CKEDITOR.replace( 'content_t_<?= $lang_id; ?>', {
					
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
						filebrowserBrowseUrl : './kibofinder/',
				        filebrowserImageBrowseUrl : './kibofinder/',
				        filebrowserFlashBrowseUrl : './kibofinder/',
				} );
			//]]>
			</script>
			<?php
			}
			?>
		</div> <!-- tabs -->
		
		<p>
			<input type="submit" value="Save" class="submit">
			<input type="button" onclick="location.href='index.php'" value="Cancel" class="button">
		</p>
	</form>
	
	</div>
</div>

</div>
</body>

</html>