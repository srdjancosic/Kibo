<?php
	require("../../library/config.php");
	$db = new Database();
	$f = new Functions();
	
	$lang_id = $f->getDefaultLanguage();
	
?>
<?php require("../../head.php"); ?>
<link href="tables.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php $currentPlace = "tables"; require("../../header.php"); ?>

	<div id="bgwrap">
		<div id="content">
			<div id="main" style="margin-left: 30px;">
				<?php
					$f->getMessage();
					$table_name = "c_".$f->getValue("name");
				?>
				<h1>View table: <?= $f->getValue("name");?></h1>
				<div class="table_wrap">
				<form action="tablework.php" method="POST">
				<table class="fullwidth">
					<thead>
						<tr>
							<?
								$query = mysql_query("SHOW COLUMNS FROM $table_name");
								while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									echo "<td>";
									echo $data['Field'];
									echo "</td>";
								}
							?>
						</tr>
					</thead>
					<tbody>
					<?
						$query = $db->execQuery("SELECT * FROM $table_name");
						while($data = mysql_fetch_array($query, MYSQL_NUM)){
					?>
					<tr id="<?= $data["0"];?>">
						<?
							foreach ($data as $key => $value){
								echo "<td>";
								echo $value;
								echo "</td>";								
							}
						?>
							<td>
								<div class="buttons">
									<a class="tooltip" href="tablework.php?action=deleteRow&id=<?=$data['0']?>&table_name=<?= $table_name?>" title="Delete" onclick="return confirm('Are you sure?');" >
										<img src="/kibocms/preset/actions_small/Trash.png" alt="" style="margin-top: 8px;">
									</a>
								</div>
							</td>
					</tr>
					<?
					}
					?>
					</tbody>
					<thead>
						<tr>
							<?
								$query = mysql_query("SHOW COLUMNS FROM $table_name");
								while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									echo "<td>";
									echo $data['Field'];
									echo "</td>";
								}
							?> 
						</tr>
					</thead>
						<tr id="fields">
							<input type="hidden" value="add_row" name="action">
							<input type="hidden" name="table_name" value="<?= $table_name;?>">
							<?
							$query = mysql_query("SHOW COLUMNS FROM $table_name");
							$data=mysql_fetch_array($query, MYSQL_ASSOC);
							?>
							<td></td>
							<?
							while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
									echo "<td>";
									if($data['Type']=="longtext"){
										?>
										<textarea style="width: 142px; height: 30px; clear: none; float:left;" name="<?= $data['Field']?>" id="<?=$data['Field']?>"></textarea>
										<?
									}else{
										list($d_type, $d_length) = explode("(", $data['Type']);
										if($d_type=="int") $style="width: 50px;"
										?>
										<input type="text" class="text" name="<?= $data['Field'];?>" style="<?= $style;?>" id="<?= $data['Field']?>">
										<?
										$style="";
									}
									echo "</td>";
								}
							?>  
						</tr>
					
				</table>
				
				
					<input class="submit" type="submit" value="Insert" style="margin-top: 0px; clear:left;">
				</form>
				</div>
			</div>
		</div>
	</div>

</body>
</html>