<?php
	require_once("../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	//$kuid = $f->loggedIn();
	
?>

<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<link href="style.css" type="text/css" rel="stylesheet">
	
	<script type='text/javascript' src='../../js/jquery-1.4.2.min.js'></script>
	<script type="text/javascript" src="kiboeditor.js"></script>
	
	<link rel="stylesheet" href="./codeMirror/lib/codemirror.css">
    <script src="./codeMirror/lib/codemirror.js"></script>
    <script src="./codeMirror/mode/css/css.js"></script>
    
    <link rel="stylesheet" href="./codeMirror/mode/css/css.css">
    
    <script src="./jquery.treeview.js"></script>
    <link rel="stylesheet" href="./jquery.treeview.css" />
</head>

<body>
	<ul id="browser" class="filetree">
	<?php
		$CssFiles = scandir("../../css/");
	?>
		<li><span class="folder">CSS</span>
			<ul>
			<?php
			for($i=2; $i<count($CssFiles); $i++) {
			?>
				<li><span class="file" link="../../css/<?= $CssFiles[$i]; ?>"><?= $CssFiles[$i]; ?></span></li>
			<?php
			}
			?>
			</ul>
		</li>
	<?php
		$CssFiles = scandir("../../plugin/");
	?>
		<li><span class="folder">Plugin</span>
			<ul>
			
			<?php
			
			for($i=2; $i<count($CssFiles); $i++) {
			?>
				<li><span class="folder"><?= $CssFiles[$i]; ?></span>
					<ul>
					<?php
					$PFiles = scandir("../../plugin/".$CssFiles[$i]);
					for($j=2; $j<count($PFiles); $j++) {
					?>
						<li><span class="file" link="../../plugin/<?= $CssFiles[$i]; ?>/<?= $PFiles[$j]; ?>"><?= $PFiles[$j]; ?></span></li>
					<?php
					}
					?>
					</ul>
				</li>
			<?php
			}
			?>
			</ul>
		</li>
		
	</ul>


	<div id="folderContent">
		<form>
		<textarea id="fileContent" name="fileContent"></textarea>
		<br clear="all">
		<a href="#" id="saveButton">Save</a>
		<a href="#" id="closeButton">Close</a>
		<input type="hidden" value="" id="dest">
		
		
		<button type="button" class="searchButton" onclick="search()">Search</button>
		<input type="text" id="query">
		
		</form>
	</div>



<script>	
	$("#browser").treeview(); 
</script>
</body>

</html>