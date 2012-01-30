<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$id = $f->getValue("id");
	
	$query = $db->execQuery("SELECT * FROM admins WHERE id = '$id'");
	$values = mysql_fetch_array($query, MYSQL_ASSOC);
 	require("../../head.php"); 
 	if(!$f->adminAllowed("admins", "edit")) {
 		$f->redirect("index.php");
 		die();
 	}
 	?>
<link href="pages.css" type="text/css" rel="stylesheet">
<script language="javascript" type="text/javascript" src="pages.js"></script>
</head>

<body>
<?php $currentPlace = "admins"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
				
				$actions_json = $values['actions'];
				$actions = json_decode($actions_json, true);
			?>
			<h1>Edit administrator</h1>
			
			<form method="POST" action="work.php">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="id" id="id" value="<?= $id; ?>">
				<p>
					<label>Username:</label>
					<input type="text" class="text" name="username" value="<?= $values['username']; ?>">
				</p>
				<p>
					<label>New password:</label>
					<input type="text" class="text" name="password" value="">
					<span class="note">*leave blank if you do not want to change current password</span>
				</p>
				<p>
					<label>Categories</label>
					<input type="checkbox" <?= in_array("add", $actions["categories"]) ? 'checked="checked"' : ''; ?> name="categories[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("edit", $actions["categories"]) ? 'checked="checked"' : ''; ?> name="categories[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("delete", $actions["categories"]) ? 'checked="checked"' : ''; ?> name="categories[3]" value="delete" /> Delete
				</p>
				<p>
					<label>Content</label>
					<input type="checkbox"  <?= in_array("add", $actions["content"]) ? 'checked="checked"' : ''; ?> name="content[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("edit", $actions["content"]) ? 'checked="checked"' : ''; ?> name="content[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("delete", $actions["content"]) ? 'checked="checked"' : ''; ?> name="content[3]" value="delete" /> Delete
				</p>
				<p>
					<label>Elements</label>
					<input type="checkbox"  <?= in_array("add", $actions["elements"]) ? 'checked="checked"' : ''; ?> name="elements[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("edit", $actions["elements"]) ? 'checked="checked"' : ''; ?> name="elements[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("delete", $actions["elements"]) ? 'checked="checked"' : ''; ?> name="elements[3]" value="delete" /> Delete
				</p>
				<p>
					<label>Pages</label>
					<input type="checkbox"  <?= in_array("add", $actions["pages"]) ? 'checked="checked"' : ''; ?> name="pages[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("edit", $actions["pages"]) ? 'checked="checked"' : ''; ?> name="pages[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("delete", $actions["pages"]) ? 'checked="checked"' : ''; ?> name="pages[3]" value="delete" /> Delete
				</p>
				<p>
					<label>Settings</label>
					<input type="checkbox"  <?= in_array("add", $actions["settings"]) ? 'checked="checked"' : ''; ?> name="settings[1]" value="add" /> Add languages
					<input type="checkbox"  <?= in_array("edit", $actions["settings"]) ? 'checked="checked"' : ''; ?> name="settings[2]" value="edit" /> Edit languages
					<input type="checkbox"  <?= in_array("delete", $actions["settings"]) ? 'checked="checked"' : ''; ?> name="settings[3]" value="delete" /> Delete languages
					<input type="checkbox"  <?= in_array("settings", $actions["settings"]) ? 'checked="checked"' : ''; ?> name="settings[4]" value="settings" /> Edit site settings
					<input type="checkbox"  <?= in_array("view", $actions["settings"]) ? 'checked="checked"' : ''; ?> name="settings[5]" value="view" /> View link
				</p>
				<p>
					<label>Admins</label>
					<input type="checkbox"  <?= in_array("add", $actions["admins"]) ? 'checked="checked"' : ''; ?> name="admins[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("edit", $actions["admins"]) ? 'checked="checked"' : ''; ?> name="admins[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("delete", $actions["admins"]) ? 'checked="checked"' : ''; ?> name="admins[3]" value="delete" /> Delete
					<input type="checkbox"  <?= in_array("view", $actions["admins"]) ? 'checked="checked"' : ''; ?> name="admins[4]" value="view" /> View link
				</p>
				<p>
					<label>Code editor</label>
					<input type="checkbox"  <?= in_array("edit", $actions["code_editor"]) ? 'checked="checked"' : ''; ?> name="code_editor[1]" value="edit" /> Edit
				</p>
				<p>
					<label>HTML Content</label>
					<input type="checkbox"  <?= in_array("edit", $actions["html"]) ? 'checked="checked"' : ''; ?> name="html[1]" value="edit" /> Edit
				</p>
				<p>
					<label>Main menu</label>
					<input type="checkbox"  <?= in_array("edit", $actions["menu"]) ? 'checked="checked"' : ''; ?> name="menu[1]" value="edit" /> Edit
				</p>
				<p>
					<label>User groups</label>
					<input type="checkbox"  <?= in_array("edit", $actions["user_groups"]) ? 'checked="checked"' : ''; ?> name="user_groups[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("add", $actions["user_groups"]) ? 'checked="checked"' : ''; ?> name="user_groups[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("delete", $actions["user_groups"]) ? 'checked="checked"' : ''; ?> name="user_groups[3]" value="delete" /> Delete
				</p>
				<p>
					<label>Forms</label>
					<input type="checkbox"  <?= in_array("edit", $actions["forms"]) ? 'checked="checked"' : ''; ?> name="forms[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("add", $actions["forms"]) ? 'checked="checked"' : ''; ?> name="forms[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("delete", $actions["forms"]) ? 'checked="checked"' : ''; ?> name="forms[3]" value="delete" /> Delete
				</p>
				<p>
					<label>Database</label>
					<input type="checkbox"  <?= in_array("export", $actions["database"]) ? 'checked="checked"' : ''; ?> name="database[2]" value="export" /> Export
					<input type="checkbox"  <?= in_array("import", $actions["database"]) ? 'checked="checked"' : ''; ?> name="database[1]" value="import" /> Import
					<input type="checkbox"  <?= in_array("empty", $actions["database"]) ? 'checked="checked"' : ''; ?> name="database[3]" value="empty" /> Empty
				</p>
				<p>
					<label>Tables</label>
					<input type="checkbox"  <?= in_array("edit", $actions["tables"]) ? 'checked="checked"' : ''; ?> name="tables[2]" value="edit" /> Edit
					<input type="checkbox"  <?= in_array("add", $actions["tables"]) ? 'checked="checked"' : ''; ?> name="tables[1]" value="add" /> Add
					<input type="checkbox"  <?= in_array("delete", $actions["tables"]) ? 'checked="checked"' : ''; ?> name="tables[3]" value="delete" /> Delete
					<input type="checkbox"  <?= in_array("view", $actions["tables"]) ? 'checked="checked"' : ''; ?> name="tables[4]" value="view" /> View content
				</p>
				<p class="submit">
					<input type="submit" value="Save changes" class="submit" />
					<input type="button" value="Cancel" class="button" onclick="location.href='index.php'" />
				</p>
				
			</form>
			
			
		</div>
	</div>
	
	
</div>
</body>

</html>
