<div id="header">
	<div id="top">
		<div class="logo">
			<a href="/kibocms/" title="Home" class="tooltip"><img src="/kibocms/preset/assets/logo.png" alt="Kibo CMS" /></a> 
		</div>
		
		<div class="meta">
			<ul>
				<li>
					<a href="/kibocms/logout.php">Logout</a>
				</li>
				<li>
					<?php
					if($f->adminAllowed("code_editor", "edit")){
					?>
						<a href="/kibocms/emptyDatabase.php" onclick="return confirm('Are you sure?');">Empty database</a>
					<?
					}
					?>
				</li>
				<?php
	        	if($f->adminAllowed("settings", "view")) {
	        	?>
	        		<li><a href="/kibocms/pages/settings/">Settings</a></li>
	       		 <?php
	        	}
	        		if($f->adminAllowed("admins", "view")) {
	        	?>
	        		<li><a href="/kibocms/pages/admins/">Admins</a></li>
	        	<?php 
	        		}
	        	?>
				<li>
					<?php if($f->adminAllowed("code_editor", "edit")) { ?><a href="#" onclick="kiboEditorAlone('/kibocms/kiboeditor/');">Code editor</a><?php } ?>
					<a href="#" onclick="kiboFinderAlone('/kibocms/kibofinder/', '');">File manager</a>
				</li>
			</ul>
		</div>
	</div>
	<div id="navbar">
	    <ul class="nav">
	    	<?php
	    	$cat_menu = ($currentPlace == "categories") ? "class=\"active\"" : "";
	    	$nodes_menu = ($currentPlace == "nodes") ? "class=\"active\"" : "";
	    	$leaves_menu = ($currentPlace == "leaves") ? "class=\"active\"" : "";
	    	$layouts_menu = ($currentPlace == "layouts") ? "class=\"active\"" : "";
	    	$pages_menu = ($currentPlace == "pages") ? "class=\"active\"" : "";
	    	$plugins_menu = ($currentPlace == "plugins") ? "class=\"active\"" : "";
	    	$settings_menu = ($currentPlace == "settings") ? "class=\"active\"" : "";
	    	$admins_menu = ($currentPlace == "admins") ? "class=\"active\"" : "";
	    	$html_menu = ($currentPlace == "html") ? "class=\"active\"" : "";
	    	$menu_menu = ($currentPlace == "menu") ? "class=\"active\"" : "";
	    	$poll_menu = ($currentPlace == "polls") ? "class=\"active\"" : "";
	    	$contact_menu = ($currentPlace == "contact") ? "class=\"active\"" : "";
	    	$users_menu = ($currentPlace == "users") ? "class=\"active\"" : "";
	    	$user_groups_menu = ($currentPlace == "user_groups") ? "class=\"active\"" : "";
	    	$forms_menu = ($currentPlace == "forms") ? "class=\"active\"" : "";
	    	$tables_menu = ($currentPlace == "tables") ? "class=\"active\"" : "";
	    	$newsletter_menu = ($currentPlace == "newsletter") ? "class=\"active\"" : "";
	    	?>
	        <li><a <?= $cat_menu; ?> href="/kibocms/pages/category/">Categories</a></li>
	        <li><a <?= $nodes_menu; ?> href="/kibocms/pages/node/">Articles</a>
	        	<ul>
	        		<?
	        		$nodes_submenu = new Node();
	        		$nodes_submenu->listCategoryList();
	        		?>
	        	</ul>
	        </li>
	        <li><a <?= $leaves_menu; ?> href="/kibocms/pages/leaves/">Elements</a></li>
	        <?php /*<li><a <?= $layouts_menu; ?> href="/kibocms/pages/layouts/">Layouts</a></li> */ ?>
	        <li><a <?= $pages_menu; ?> href="/kibocms/pages/pages/">Pages</a></li>
	        
	        <?php
	        if($f->adminAllowed("html", "edit")) {
	        ?>
	        	<li><a <?= $html_menu; ?> href="/kibocms/pages/html/">HTML Content</a></li>
	        <?php
	        }
	        if($f->adminAllowed("menu", "edit")) {
	        ?>
	        	<li><a <?= $menu_menu; ?> href="/kibocms/pages/menu/">Menu</a></li>
	        <?php
	        }
	        /*<li><a <?= $plugins_menu; ?> href="/kibocms/plugin/">Plugins</a></li> */ ?>
	        <?php
	        if($f->adminAllowed("user_groups", "edit")) {
	        ?>
	        	<li><a <?= $user_groups_menu; ?> href="/kibocms/pages/user_groups/">User groups</a></li>
	        <?
	        }
	        if($f->adminAllowed("forms", "edit")) {
	        ?>
	        	<li><a <?= $forms_menu; ?> href="/kibocms/pages/forms/">Forms</a></li>
	        <?php
	        }
	        if($f->adminAllowed("tables", "edit")) {
	        ?>
	        	<li><a <?= $tables_menu; ?> href="/kibocms/pages/tables/">Tables</a></li>
	        <?php
	        }
	        ?>     
	    </ul>
	</div>
</div>
