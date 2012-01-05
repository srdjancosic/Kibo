<?php
	require("functions.php");
	$base = "../../upload";

	$action = $_POST['action'];
	
	switch ($action) {
		
		default:
			break;
			
		case "loadFolderList":
			$current = $_POST['current'];
			$dh = opendir($base);
			echo "<select id=\"folder_list\">";
			
			$selected = ($current == "") ? " selected=\"selected\"" : "";
			echo "<option value=\"root\" ".$selected.">root</option>";
			
			while($file = readdir($dh)) {
				if($file != "." && $file != "..") {
					
					if(is_dir($base."/".$file)) {
						$selected = (strtolower($base."/".$file) == strtolower($current)) ? "selected=\"selected\"" : "";
						echo "<option value=\"".$base."/".$file."\" ".$selected."> - ".$file."</option>";
					}
					
				}
			}
			echo "</select>";
			
			break;
		case "createFolder":
			$name = strip_tags(stripslashes($_POST['name']));
			
			if(mkdir($base."/".$name) && mkdir($base."/".$name."/_thumb")) {
				echo $base."/".$name;
			} else {
				echo 0;
			}
			break;
		case "removeFolder":
			
			$current = strip_tags(stripslashes($_POST['current']));
			if(rmdir($current."/_thumb")) {
				if(rmdir($current)) {
					echo 1;
				}
			}
			else {
				echo 0;
			}
			
			break;
		case "getFolderContent":
			
			$current = strip_tags(stripslashes($_POST['current']));
			//$current .= "/_thumb";
			$dh = opendir($current);
			
			while($file = readdir($dh)) {
				if($file != "." && $file != "..") {
					if(is_file($current."/".$file)) {
						
						$e = getExt($file);
						$eF= getExtIcon($e);
						
						$img = (is_file($current."/_thumb/".$file)) ? $current."/_thumb/".$file : $eF;
						
						
						
						echo "<a href=\"#\" id=\"fileOptions\" class=\"tooltip\" title=\"$file\" file=\"".$file."\">";
						echo "<img src=\"".$img."?r=".rand(0,9)."\">";
						
						$fileName = (strlen($file) > 13) ? substr($file, 0, 13)."..." : $file;
						
						echo "<br>".$fileName."";
						echo "</a>";
					}
					
				}
			}
			
			break;
		case "loadContent":
			
			$options = $_POST['options'];
			$current = $_POST['current'];
			
			switch ($options) {
				case "folderOptions":
					getFolderChildsHTML($current);
					break;
				case "fileOptions":
					$file = $_POST['file'];
					getFileOptionsHTML($current, $file);
					break;
			}
			
			break;
		case "createDimmension":
			
			$new_x = $_POST['new_x'];
			$new_y = $_POST['new_y'];
			$current = $_POST['current'];
			
			echo setFolderChild($current, $new_x, $new_y);
			
			break;
		case "removeDimension":
			
			$path = $_POST['file'];
			
			if(rmdir($path)) {
				echo "1";
			} else {
				//echo "0";
			}
			
			break;
		case "renameFile": 
		
			$file = $_POST['file'];
			$new = $_POST['new'];
			$folder = $_POST['folder'];
		
			if(rename($folder."/".$file, $folder."/".$new) && rename($folder."/_thumb/".$file, $folder."/_thumb/".$new)) {
				$folders = getFolderChildsArray($folder);
				for($i=0; $i<count($folders); $i++) {
					rename($folder."/".$folders[$i][0]."/".$file, $folder."/".$folders[$i][0]."/".$new);
				}
				echo "1";
			} else echo "-1";
			
			break;
		case "removeFile":
			
			$file = $_POST['file'];
			$folder = $_POST['folder'];
			
			if(unlink($folder."/".$file) && unlink($folder."/_thumb/".$file)) {
				$folders = getFolderChildsArray($folder);
				for($i=0; $i<count($folders); $i++) {
					unlink($folder."/".$folders[$i][0]."/".$file);
				}
				echo "1";
			} else echo "-1";
			
			
			break;
	}
?>