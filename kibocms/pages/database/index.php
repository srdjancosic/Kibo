<?php
	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
	
	require("../../head.php");
?>
	</head>
	<body>
<?php 
		$currentPlace = "database";
		require("../../header.php");
?>
		<div id="bgwrap">
			<div id="content">
				<div id="main">
<?php				
					$f->getMessage();
?>
					<h1>Your database backups
					<img src="/kibocms/preset/assets/loading.gif" id="loader" border="0" alt="" style="display: none; margin-bottom: -4px; width: 14px; height:14px;">	
					</h1>		
<?php					
					//$folder = $_SERVER['DOCUMENT_ROOT']."/kibocms/pages/database/backup";
					$folder = "C:\\Documents and Settings\\Administrator\\Desktop\\kibocms\\Kibo\\kibocms\\pages\\database\\backup";
					
					$folder_list = scandir($folder);
					
					foreach ($folder_list as $key => $file) {
						
						//if ($file != "." && $file != ".." && is_file($folder."/". $file)) {
							//$path = $folder."/".$file;
						if ($file != "." && $file != ".." && is_file($folder."\\". $file)) {
							$path = $folder."\\".$file;
?>
							<div class="box_1">
								<div class="inner">
									<h3><?= $file; ?></h3>
								</div>
								<div class="buttons">
									<?php
										if(Functions::adminAllowed("database", "import")) { ?>
									<a class="tooltip" title="Import this backup" href="databasework.php?action=import_database&file=<?= $file; ?>"><img alt="" src="/kibocms/preset/actions_small/Import.png"></a> 
									<?php } 
										if(Functions::adminAllowed("database", "empty")) { ?>
									<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete this backup" href="databasework.php?action=delete_backup&file=<?= $file; ?>"><img alt="" src="/kibocms/preset/actions_small/Remove-from-database.png"></a>
									<?php } ?>
								</div>
							</div>
<?php
						}
					}
?>
					
				</div>
			</div>
			<div id="sidebar">
				<h2>Database actions:</h2>
				<ul>
<?php
					if($f->adminAllowed("database", "export")){
						echo "<li><a id=\"export_database\" href=\"exportDatabase.php\" onclick=\"return confirm('Are you sure?');\">Export database</li>";
					}
					if($f->adminAllowed("database", "empty")){
						echo "<li><a href=\"emptyDatabase.php\" onclick=\"return confirm('Are you sure?');\">Empty database</li>";
					}
					if($f->adminAllowed("database", "import")){
						
					}
?>
				</ul>
			</div>
		</div>
	</body>
<?	
?>