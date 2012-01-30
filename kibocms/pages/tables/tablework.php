<?php

	require_once("../../library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	$adminId = $f->loggedIn();
	
	$action = $f->getValue("action");
	
	switch ($action) {
		default:
			$f->redirect("index.php");
			break;
		case "add_table":
			
			$name= $f->getValue("name");
			$table_name ="c_".$name;
			if(strlen($name) != 0){
				$db->execQuery("CREATE TABLE $table_name (id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY(id))");
				$f->setMessage("New table created!");
				$f->redirect("tableedit.php?name=".$name);
			}else {
				$f->setMessage("You must enter table name!", "error");
				$f->redirect("index.php");
			}
			break;
		case "delete":
			$name = $f->getValue("name");
			$db->execQuery("DROP TABLE c_".$name);
			$f->setMessage("Table deleted!");
			$f->redirect("index.php");
			break;
		case "deleteColumn":
			$column_name = $f->getValue("column_name");
			$table_name = $f->getValue("table_name");
			$db->execQuery("ALTER TABLE `$table_name` DROP `$column_name`");
			break;
		case "saveColumn":
			$column_name = $f->getValue("column_name");
			$table_name = $f->getValue("table_name");
			$name = $f->getValue("new_name");
			$name = $f->generateUrlFromText1($name);
			$type = $f->getValue("type");
			$length = $f->getValue("length");
			$default_value = $f->getValue("default_value");
			$use_default = $f->getValue("use_default");
			
			if(strlen($name) != 0 && $type !="-1"){
				$sql = "ALTER TABLE `$table_name` CHANGE `$column_name` `$name` $type";
				if($type =="INT" || $type=="DATE" || $type=="DATETIME" || $type=="BIGINT"){
					$sql .= " NOT NULL ";
				}elseif($type=="LONGTEXT"){
					$sql .= " CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
				}elseif($type =="VARCHAR"){
					if($length==""){
						$sql .= " CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
					}else{
						$sql .= "($length) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
					}
				}
				if($use_default == '1' && $default_value !="")
					$sql .= " DEFAULT '$default_value'";
					
				$db->execQuery($sql);
				?>
				<?
			}else {
				$f->setMessage("You must enter column name!", "error");
			}
			
			break;
		case "addColumne":
			$table_name = $f->getValue("table_name");
			$name = $f->getValue("name");
			$name = $f->generateUrlFromText1($name);
			$type = $f->getValue("type");
			$length = $f->getValue("length");
			$default_value = $f->getValue("default_value");
			$use_default = $f->getValue("use_default");
			
			
			if(strlen($name) != 0 && $type !="-1"){
				$sql = "ALTER TABLE `$table_name` ADD `$name` $type";
				if($type =="INT" || $type=="DATE" || $type=="DATETIME" || $type=="BIGINT"){
					$sql .= " NOT NULL ";
				}elseif($type=="LONGTEXT"){
					$sql .= " CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
				}elseif($type =="VARCHAR"){
					if($length==""){
						$sql .= " CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
					}else{
						$sql .= "($length) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL ";
					}
				}
				if($use_default == '1' && $default_value !="")
					$sql .= " DEFAULT '$default_value'";
					
				$db->execQuery($sql);
				?>
				<li id="column_<?= $name?>" class="columne">
					<label><?= $name?></label>
					<div class="buttons_1" id="buttons_<?= $name;?>">
						<a href="#" id="remove_<?= $dataname; ?>" onclick="removeColumne('<?= $name; ?>','<?= $table_name;?>');">
						<img src="/kibocms/preset/actions_small/Trash.png">
						<a href="#" class="editCol" name="<?= $name?>" table_name="<?= $table_name;?>">
						<img src="/kibocms/preset/actions_small/Pencil.png" >
						</a>
					</div>
				</li>
				<?
			}else {
				$f->setMessage("You must enter column name!", "error");
			}
			break;
		case "reloadColumn":
			
			$table_name = $f->getValue("table_name");
			$name = $f->getValue("name");
			
			$query = mysql_query("SHOW COLUMNS FROM $table_name");
			while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				if($data['Field']==$name){
					?>
					<div class="col_details" id="col_setting_<?= $name;?>">
						<?
							list($d_type, $d_length) = explode("(", $data['Type']);
							list($d_length, $rest) = explode(")", $d_length);
						?>
						<p>
							<label>Column name:</label>
							<input type="text" readonly="" id="column_name_<?= $data['Field']; ?>" value="<?= $data['Field'];?>" class="text">
						</p>
						<p>
							<label>Select column type:</label>
							<select id="columne_type_<?= $data['Field']; ?>">
								<option value = "INT" <?= ($d_type=="int") ? "selected=\"selected\";" : "" ?>>INT</option>
								<option value = "VARCHAR" <?= ($d_type=="varchar") ? "selected=\"selected\";" : "" ?>>VARCHAR</option>
								<option value = "BIGINT" <?= ($d_type=="bigint") ? "selected=\"selected\";" : "" ?>>BIGINT</option>
								<option value = "DATE" <?= ($d_type=="date") ? "selected=\"selected\";" : "" ?>>DATE</option>
								<option value = "DATETIME" <?= ($d_type=="datetime") ? "selected=\"selected\";" : "" ?>>DATETIME</option>
								<option value = "LONGTEXT" <?= ($d_type=="longtext") ? "selected=\"selected\";" : "" ?>>LONGTEXT</option>
							</select>
						</p>
						<p>
							<label>Length:</label>
							<input type="text" id="length_<?= $data['Field']?>" value="<?= $d_length;?>" class="text">
						</p>
						<p>
							<input type="checkbox" value="1" id="use_default_<?= $data['Field']?>" <?= ($data['Default']!="")? "checked" : ""?>>
							Use default value
						</p>
						<p>
							<label>Default value:</label>
							<input type="text" id="default_value_<?= $data['Field'];?>" value="<?= $data['Default']?>" class="text">
						</p>
						<p style="margin: 0px">
							<input class="submit" type="button" onclick="saveColumne('<?= $data['Field'];?>', '<?= $table_name; ?>')" value="Save">
							<input class="button" type="button" value="Cancel" onclick="cancelColumne('<?= $data['Field'];?>')">
						</p>
					</div>
					<?
				} 
			}	
			break;
		case  "none":
			$name = $f->getValue("column_name");
			break;
		case "deleteRow":
			$id = $f->getValue("id");
			$table_name = $f->getValue("table_name");
			
			$db->execQuery("DELETE FROM `$table_name` WHERE id='$id'");
			list($first, $last)=explode("_", $table_name);
			$f->redirect("tableview.php?name=".$last);
			break;
		case "add_row":
			$table_name= $f->getValue("table_name");
			$query = mysql_query("SHOW COLUMNS FROM `$table_name`");
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			$i=1;
			$sql = "INSERT INTO ".$table_name." VALUES (NULL";
			while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$data1=$f->getValue($data['Field']);
				$sql = $sql.", '".$data1."'";
			}
			$sql=$sql.")";
			$db->execQuery($sql);
			list($first, $last)=explode("_", $table_name);
			$f->redirect("tableview.php?name=".$last);
			break;
		case "truncate":
			$name = $f->getValue('name');
			
			$db->execQuery("TRUNCATE ".$name);
			
			$f->redirect("index.php");
			break;	
	}
?>