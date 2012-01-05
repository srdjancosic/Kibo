<?php

	class Plugin extends Functions {
		
		function __construct() {
			
		}
		
		function ezip($zip, $folderName, $hedef = '') {
		 	
		 	
			$root = "installs/".$folderName."/";
			
			$zip = zip_open($root . $zip);
			while($zip_icerik = zip_read($zip)) {
				$zip_dosya = zip_entry_name($zip_icerik);
				if(strpos($zip_dosya, '.')) {
					$hedef_yol = $root . $hedef . '/'.$zip_dosya;
					touch($hedef_yol);
					$yeni_dosya = fopen($hedef_yol, 'w+');
					fwrite($yeni_dosya, zip_entry_read($zip_icerik));
					fclose($yeni_dosya); 
				} else {
					@mkdir($root . $hedef . '/'.$zip_dosya);
				}
			}
			
			return true;
		}
		
		function listPluginsView() {
			$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."plugins ORDER BY id DESC");
			$odd = 1;
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				if($odd == 1) {
					$class = "class=\"odd\"";
					$odd = 0;
				} else {
					$class = "";
					$odd = 1;
				}
				?>
				<tr <?= $class; ?>>
					<td><?= $data['id']; ?></td>
					<td><?= ucfirst($data['name']); ?></td>
					<td>
						<a class="tooltip" title="<?= $data['name']; ?>" href="/kibocms/plugin/<?= $data['url']; ?>">/<?= $data['url']; ?></a></td>
				</tr>
				<?php
			}
		}
		
	}

?>