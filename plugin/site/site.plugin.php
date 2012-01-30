<?php

		function helloWorld() {
				
			$db = new Database();
			$f  = new Functions();
			
			$query = $db->execQuery("SELECT * FROM massages ORDER BY date DESC");
			
			while($data = mysql_fetch_array($query)) {
				echo '<div class="poruka">';
				echo "<strong>Od</strong>: ".$data['name']."<br /><strong>e-mail: </strong>".$data['mail']." <br /><strong>Datum: </strong>".$data['date'];
				echo "<br /><p>".$data['content']."</p>";
				echo '<form method="POST" action="/obrisi.php"><input type="hidden" name="id_zabr" value='.$data[id].'><input type="SUBMIT" value="Obrisi"></form>';
				echo '</div>';				
			}
		}
		
		
		function tagCloud() {
		global $db;
			$tmp = array();
		
			$query = $db->execQuery("SELECT name, url, count(`name`) AS br FROM tags GROUP BY lower(`name`) ORDER BY br DESC LIMIT 25");
			$i = 1;
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				if($i >= 1 && $i < 3) $font = 18;
				elseif ($i >= 3 && $i < 8) $font = 16;
				elseif ($i >= 8 && $i < 12) $font = 14;
				elseif ($i >= 12 && $i < 19) $font = 12;
				elseif ($i >= 19) $font = 10;
				
				$tmp[$data['url']] = array(0 => $font, 1 => $data['name']);
				$i++;
			}
			kShuffle($tmp);
		
			echo "<div class=\"tagCloud\">";
			foreach ($tmp as $key => $value) {
				echo "<a href=\"/taglist/:".$key."\" style=\"font-size: ".$value[0]."px;\">".$value[1]."</a> ";
			}
			echo "</div>";
		}
		
		function kShuffle(&$array) {
		    if(!is_array($array) || empty($array)) {
		        return false;
		    }
		    $tmp = array();
		    foreach($array as $key => $value) {
		        $tmp[] = array('k' => $key, 'v' => $value);
		    }
		    shuffle($tmp);
		    $array = array();
		    foreach($tmp as $entry) {
		        $array[$entry['k']] = $entry['v'];
		    }
		    return true;
		} 
		
		function listTasks($user_id = 0) {
			
			$db = new Database();
			$f  = new Functions();
  ?>
            
            
  			<div class="well right_content">
  			
  			<table class="bordered-table zebra-striped">
        		<thead>
          			<tr>
            			<th>#</th>
            			<th><input type="text" id="search_name" placeholder="Naslov"></th>
            			<th>
            				<select id="search_autor" class="small">
            					<option>Autor</option>
            					<?
            						$query = $db->execQuery("SELECT * FROM c_users");
            						while ($data = mysql_fetch_array($query, MYSQL_ASSOC)){
            							echo "<option value=\"".$data['name']."\">";
            							echo $data['name'];
            							echo "</option>";
            						}
            					?>
            				</select>
            			</th>
            			<th>
            				<select id="search_prioritet" class="small">
            					<option>Prioritet</option>
            					<option value="Visok">Visok</option>
            					<option value="Srednji">Srednji</option>
            					<option value="Nizak">Nizak</option>
            				</select>
            			</th>
            			<th>
            				<select id="search_kome" class="small">
            					<option>Kome</option>
            					<?
            						$query = $db->execQuery("SELECT * FROM c_users");
            						while ($data = mysql_fetch_array($query, MYSQL_ASSOC)){
            							echo "<option value=\"".$data['name']."\">";
            							echo $data['name'];
            							echo "</option>";
            						}
            					?>
            				</select>
            			</th>
            			<th>Akcije</th>
          			</tr>
        		</thead>
        		<tbody>
		            <?
		            	if($user_id == 0){
		            		$query = $db->execQuery("SELECT * FROM c_log ORDER BY status ASC, prioritet DESC,id DESC");
		            		
		            	} else {
		            		
		            		$query = $db->execQuery("SELECT * FROM c_log WHERE kome LIKE '%$user_id%' ORDER BY status ASC, prioritet DESC,id DESC");
		            	}
	            		while($data = mysql_fetch_array($query, MYSQL_ASSOC)){
	            			echo "<tr id=\"row_".$data['id']."\">";
	            				echo "<td>";
	            					echo $data['id'];
	            				echo "</td>";
	            				echo "<td>";
	            					echo "<a href=\"/beleska.php?id=".$data['id']."\">".$data['naslov']."</a>";
	            				echo "</td>";
	            				echo "<td>";
	            					echo "<span class=\"label default\">";
	            					echo $db->getValue("name", "c_users", "id", $data['autor']);
	            					echo "</span>";
	            				echo "</td>";
	            				echo "<td>";
	            					switch ($data['prioritet']) {
	            						case "1":
	            							echo "<span class=\"label notice\">";
	            							echo "Nizak";
	            							echo "</span>";
	            						break;
	            						case "2":
	            							echo "<span class=\"label warning\">";
	            							echo "Srednji";
	            							echo "</span>";
	            						break;
	            						case "3":
	            							echo "<span class=\"label important\">";
	            							echo "Visok";
	            							echo "</span>";
	            						break;
	            					}
	            				echo "</td>";
	            				echo "<td>";
	            					
	            					$kome = explode(":", $data['kome']);
									foreach($kome as $key => $user_id) {
										?>
										<span class="label default">
										<?
										echo $db->getValue("name", "c_users", "id", $user_id)."</span>";
									}

	            				echo "</td>";
	            				echo "<td>";
	            					if($data['autor'] == USER_ID){
	            						?>
	            							<a class="remove btn small" logid="<?= $data['id']; ?>" href="#">
	            								Ukloni
											</a>
	            						<?
	            					} else {
	            						?>
	            							<a class="disabled btn small" logid="<?= $data['id']; ?>" href="#">
	            								Ukloni
											</a>
	            						<?
	            					}
	            				if($data['status']== 0){
	            					if($data['autor'] == USER_ID){
	            							?>
	            							<a class="zatvori btn small" logid="<?= $data['id']; ?>" href="#">
	            								Zatvori
											</a>
	            					<?
	            					}else{	            					
	            						?>
	            							<a class="disabled btn small" logid="<?= $data['id']; ?>" href="#">
	            								Zatvori
											</a>
	            						<?
	            					}
	            				}
	            				echo "</td>";
	            			echo "</tr>";
		            	}
					?>
		        </tbody>
		    </table>
		    <?
  			echo "</div>";
		}
?>