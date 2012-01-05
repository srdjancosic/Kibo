<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	$c = new Category();
	
	$id = $f->getValue("id");
	
	require("../../head.php"); 
	
	if(!$f->adminAllowed("categories", "edit")) {
		$f->redirect("index.php");
		die();
	}
	?>
<script language="javascript" src="category.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	$('#tabs').tabs();
});
</script>
<link href="category.css" type="text/css" rel="stylesheet">
</head>

<body>
<?php $currentPlace = "categories"; require("../../header.php"); ?>

<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<h1>Edit category</h1>
			
		<form method="POST" action="categorywork.php">
			<input type="hidden" name="action" value="edit">
			
			
			<div id="tabs">
				<ul>
				<?php
				$lang_query = $db->execQuery("SELECT * FROM languages WHERE active = '1' ORDER BY `default` DESC");
				while ($lang_arr = mysql_fetch_array($lang_query, MYSQL_ASSOC)) {
				?>
					<li><a href="#lang_tab_<?= $lang_arr['id']; ?>"><?= $lang_arr['name']; ?></a></li>
				<?php
				}
				?>
				</ul>
			
			<?php
			$lang_query = $db->execQuery("SELECT * FROM languages WHERE active = '1' ORDER BY `default` DESC");
			while ($lang_arr = mysql_fetch_array($lang_query, MYSQL_ASSOC)) {
			
			$values = $c->getCategoryValues($id, $lang_arr['id']);	
			?>
				<div id="lang_tab_<?= $lang_arr['id']; ?>" class="tabs_content">
					<input type="hidden" id="lang_id_<?= $lang_arr['id']; ?>" name="lang_id[]" value="<?= $lang_arr['id']; ?>">
					<input type="hidden" id="catId" name="id[<?= $lang_arr['id']; ?>]" value="<?= $values['id']; ?>">
					
					<!-- subpage 1 -->
					<div class="half">
						<h1>Category info</h1>
						<p>
							<label>Category name:</label>
							<input type="text" class="text" id="name" name="name[<?= $lang_arr['id']; ?>]" value="<?= $values['name']; ?>">
						</p>
						<p>
							<label>URL:</label>
							<input type="text" class="text" id="url" name="url[<?= $lang_arr['id']; ?>]" value="<?= $values['url']; ?>">
						</p>
						<p>
							<label>Href:</label>
							<input type="text" class="text" id="href" name="href[<?= $lang_arr['id']; ?>]" value="<?= $values['href']; ?>">
						</p>
						<p>
							<label>Parent category:</label>
							<select id="parent" name="parent[<?= $lang_arr['id']; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$c->listCategoriesSelect($values['parent']);
								?>
							</select>
						</p>
						<p>
							<label>Page view:</label>
							<select id="page_id" name="page_id[<?= $lang_arr['id']; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$c->listPagesSelect($values['page_id'], $lang_arr['id']);
								?>
							</select>
						</p>
						<p>
							<label>Single page view:</label>
							<select id="page_single" name="page_single[<?= $lang_arr['id']; ?>]" class="styled">
								<option value="0">-----</option>
								<?php
									$c->listPagesSelect($values['page_single'], $lang_arr['id']);
								?>
							</select>
						</p>
					</div>
						<!-- end of subpage 1 -->
						
						<!-- subpage 2 -->
					<div class="half">
						<h1>Category custom fields</h1>
						<p>
							<label>Name:</label>
							<input type="text" class="text" id="field_name_<?= $values['id']; ?>" />
						</p>
						<p>
							<label>Type:</label>
							<select id="field_type_<?= $values['id']; ?>" class="styled">
								<option value="text">Text</option>
								<option value="date">Date</option>
								<option value="longtext">Long text</option>
							</select>
						</p>
						<p>
							<input type="button" onclick="addCategoryCustomField(<?= $values['id']; ?>);" class="submit tiny" value="Add custom field" />
							<span class="note"><img src="/kibocms/preset/assets/loading.gif" id="loader_<?= $values['id']; ?>" border="0" style="display:none;" alt=""></span>
						</p>
						
						<h2>Custom fields for this category</h2>
						<table class="fullwidth">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Type</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody class="table_<?= $values['id']; ?>">
							<?php
								$c->listCategoryCustomFieldsView($values['id']);
							?>
							</tbody>
						</table>
					</div> <!-- end of sb2 -->
					<h2 style="width: 440px;"><br />Category settings</h2>
					<a href="#" class="showhide_setting" lang="<?= $lang_arr['id']; ?>">Show/Hide settings</a><br /><br />
					<div class= "add_h_and_f" id="category_setting_<?= $lang_arr['id']; ?>" style="display: none;">
						<p>
							<label>Category keywords</label>
							<textarea  name="category_keywords[<?= $lang_arr['id'];?>]"><?= $values['category_keywords'];?></textarea>
						</p>
						<p>
							<label>Category description</label>
							<textarea name="category_description[<?= $lang_arr['id'];?>]"><?= $values['category_description'];?></textarea>
						</p>
					</div>
				</div>
			<?php 
						} // end of language while loop
			?>
			</div>
			<p>
				<br clear="all">
				<input type="submit" value="Save" class="submit">
				<input type="button" value="Cancel" onclick="location.href='index.php'" class="button">
			</p>
		</form>
		
		</div>
	</div>
</div>
</body>

</html>
