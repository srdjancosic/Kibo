<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
?>
<?php require("../../head.php"); ?>

</head>

<body>
<?php $currentPlace = "tables"; require("../../header.php"); ?>
	<div id="bgwrap">
	<div id="content">
		<div id="main">
			<?php
				$f->getMessage();
			?>
			<h1>Tables</h1>
			<?php
				$query = $db->execQuery("show tables");
				while($data = mysql_fetch_array($query)){
					list($first, $last) = explode('_',$data['0']);
					if($first == 'c' ){?>
						<div class="box_1">
							<div class="inner">
								<h3><?= $last; ?></h3>							
							</div>
							<div class="buttons">
								<?php
									if($f->adminAllowed("tables", "view")){?>
										<a class="tooltip" title="View content" href="tableview.php?name=<?= $last;?>"><img lt="" src="/kibocms/preset/actions_small/lupa.png"></a> 
								<?php }
									if($f->adminAllowed("tables", "edit")) {?>
										<a class="tooltip" title="Edit" href="tableedit.php?name=<?= $last; ?>"><img alt="" src="/kibocms/preset/actions_small/Pencil.png"></a> 
								<?php }
									if($f->adminAllowed("tables", "delete")) { ?>
										<a class="tooltip" onclick="return confirm('Are you sure?');" title="Delete" href="tablework.php?action=delete&name=<?= $last; ?>"><img alt="" src="/kibocms/preset/actions_small/Trash.png"></a>
								<?php } ?>
							</div>
						</div>
						<?
					}
				}
			?>
		</div>
	</div>
		<?php 
		if($f->adminAllowed("tables", "delete")) { ?>
		<div id="sidebar">
		<h2>New table</h2>
		<form method="POST" action="tablework.php">
			<input type="hidden" name="action" value="add_table">	
			<p>
				<label>Table name:</label>
				<input type="text" class="text" id="name" name="name">
			</p>
			<p>
				<input type="submit" value="Next" class="submit">
			</p>
		</form>
		</div>
		<? } ?>
	</div>
</body>

</html>