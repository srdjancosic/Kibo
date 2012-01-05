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
	$currentPlace = "newsletter";
	require("../../header.php");
	$id = $f->getValue("id");
	$query = $db->execQuery("SELECT * FROM p_newsletter WHERE id = '".$id."'");
	$values = mysql_fetch_array($query, MYSQL_ASSOC);
?>
<div id="bgwrap">
	<div id="content">
		<div id="main">

<?php
	$f->getMessage();
?>

<h1>Edit or send newsletter</h1>
	<form method="POST" action="work.php">
		<input type="hidden" name="action" value="edit">
		<input type="hidden" name="id" value="<?= $values['id']; ?>">
		<p>
			<label>Title</label>
			<input type="text" class="mf" name="title" value="<?= $values['title']; ?>" />
		</p>
		<p>
			<label>Body</label>
			<textarea id="content_t" name="body"><?= stripslashes($values['body']); ?></textarea>
		</p>
		
		<script type="text/javascript">
		//<![CDATA[
			var content = CKEDITOR.replace( 'content_t', {
				
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
					filebrowserBrowseUrl : '/kibocms/kibofinder/',
			        filebrowserImageBrowseUrl : '/kibocms/kibofinder/',
			        filebrowserFlashBrowseUrl : '/kibocms/kibofinder/',
			} );
		//]]>
		</script>
		<p>
			<label>Language:</label>
			<select name="lang_id" class="styled">
				<?php 
				$query = $db->execQuery("SELECT * FROM languages ORDER BY id");
				while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
					$checked = ($data['id'] == $values['lang_id']) ? "selected=\"selected\"" : "";
					echo "<option ".$checked." value=\"".$data['id']."\">".$data['name']."</option>";
				}
				?>
			</select>
		</p>
		<p>
			<input type="submit" value="Save" class="submit">
			<input type="button" onclick="location.href='index.php'" value="Cancel" class="button">
		</p>
	</form>
	
	</div>	
</div>

<div id="sidebar">
	<form action="work.php">
		<input type="hidden" value="send" name="action">
		<input type="hidden" value="<?= $id; ?>" name="id">
		<h2>Send to:</h2>
		<p>
			<label>User group</label>
			<select name="group_id" class="styled">
				<?php
				$query = $db->execQuery("SELECT * FROM p_groups ORDER BY lang_id");
				while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
					echo "<option value=\"".$data['id']."\">".$data['name']." (".$db->getValue("name", "languages", "id", $data['lang_id']).")</option>";
				}
				?>
			</select>
			<br clear="all">
			<span class="note">*If you made some changes to body or title, please save first and then send!</span>
		</p>
		<p>
			<input type="submit" class="submit" value="Send" />
		</p>
	</form>
</div>

</div>
</body>

</html>