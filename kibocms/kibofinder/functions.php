<?php
		function getFileOptionsHTML($current, $file) {
			$fileExt = getExt($file);
			$fileExtIcon = getExtIcon($fileExt);
			
			echo "<h3 class='name'><span>".$file."</span> <small>(";
			echo "<a href='#' class='renameFile'>Rename</a>)</small>";
			echo "</h3>";
			
			echo "<div class=\"fileInfoContent\">";
			if(is_file($current."/_thumb/".$file)) {
				echo "<img src=\"".$current."/_thumb/".$file."?".rand(0,1111)."\">";
				
				$dirs = getFolderChildsArray($current);
				echo "<h3>Dimenzije:</h3>";
				echo "<a href=\"#\" class=\"insertFile\" link=\"".$current."/_thumb/".$file."\">Thumb</a>";
				echo "<a href=\"#\" class=\"editImage\" link=\"".$current."/".$file."\" file=\"".$file."\" folder=\"_thumb\">Edit dimension</a>";
				echo "<a href=\"#\" class=\"previewImage\" link=\"".$current."/_thumb/".$file."\">Preview thumb</a>";
				if(count($dirs) > 0) {
					
					for($i=0; $i<count($dirs); $i++) {
						$tmp_dir = $dirs[$i][0];
						$tmp_x = $dirs[$i][1];
						$tmp_y = $dirs[$i][2];
						if(is_file($tmp_dir."/".$file)) {
							echo "<a href=\"#\" class=\"insertFile\" link=\"".$tmp_dir."/".$file."\">".$tmp_x." x ".$tmp_y."</a>";
							echo "<a href=\"#\" class=\"editImage\" link=\"".$current."/".$file."\" file=\"".$file."\" folder=\"".$tmp_x."x".$tmp_y."\">Edit dimension</a>";
							echo "<a href=\"#\" class=\"previewImage\" link=\"".$tmp_dir."/".$file."\">Preview</a>";
						}
					}
				}
			}
			echo "<a href=\"#\" class=\"insertFile\" link=\"".$current."/".$file."\">Izaberi ovaj fajl</a>";
			?>
			<br clear="all">
			<h3>File path:</h3>
			<input type="text" class="text" value="<?= str_replace("../../", "/", $current)."/".$file; ?>" readonly style="width: 200px;" />
			
			<h3>Remove file</h3>
			<a href="#" class="removeFile" link="<?= $file; ?>">Remove file</a>
			<?php
			
			echo "</div>";
		}

		function getFolderChildsHTML($current) {
			$dh = opendir($current);
			
			while ($file = readdir($dh)) {
				$filePath = $current."/".$file;
				if($file != "." && $file != ".." && $file != "_thumb") {
					if(!is_file($filePath)) {
						echo "<img src=\"file_icon/folder.png\"> ".$file." - ";
						echo "<a href=\"#\" class=\"removeDimension\" link=\"$filePath\">remove</a>";
						echo "<br clear=\"all\">";
					}
				}
			}
			?>
			<br clear="all">
			<label>Width:</label>
			<input type="text" id="new_x" class="text"> 
			<br clear="all" />
			<label>Height:</label>
			<input type="text" id="new_y" class="text">
			
			<input type="button" value="Dodaj dimenziju" id="new_dimmension" class="button">
			<input type="button" value="Zatvori" id="close_dimmension" class="button">
			<?php
			
		}
		
		function getFolderChildsArray($current) {
			$dh = opendir($current);
			$dim = array();
			$i = 0;
			while ($file = readdir($dh)) {
				$filePath = $current."/".$file;
				
				if($file != "." && $file != ".." && $file != "_thumb") {
					
					if(is_dir($filePath)) {
						$dim[$i] = array();
						array_push($dim[$i], $filePath);
						list($x, $y) = explode("x", $file);
						array_push($dim[$i], $x);
						array_push($dim[$i], $y);
						$i++;
					}
				}
			}
			return $dim;
			/**
			 * Array (  [0] => Array ( 
			 * 					[0] => ../upload/slike/140x100 
			 * 					[1] => 140 
			 * 					[2] => 100 ) 
			 * 			[1] => Array ( 
			 * 					[0] => ../upload/slike/400x300 
			 * 					[1] => 400 
			 * 					[2] => 300 ) 
			 * 		 ) 
			 */
			
		}
		
		function setFolderChild($current, $x, $y) {
			
			if(mkdir($current."/".$x."x".$y))
				return 1;
			else return 0;
			
		}

		function cropImage($nw, $nh, $source, $stype, $dest) {
			
			$size = getimagesize($source);
			$w = $size[0];
			$h = $size[1];
			
			switch($stype) {
				case 'gif':
					$simg = imagecreatefromgif($source);
				break;
				case 'jpg':
					$simg = imagecreatefromjpeg($source);
				break;
				case 'png':
					$simg = imagecreatefrompng($source);
				break;
				case 'GIF':
					$simg = imagecreatefromgif($source);
				break;
				case 'JPG':
					$simg = imagecreatefromjpeg($source);
				break;
				case 'PNG':
					$simg = imagecreatefromjpeg($source);
				break;
			}
			$dimg = imagecreatetruecolor($nw, $nh);
			$wm = $w/$nw;
			$hm = $h/$nh;
	
			$h_height = $nh/2;
			$w_height = $nw/2;
	
			if($w> $h) {
				$adjusted_width = $w / $hm;
				$half_width = $adjusted_width / 2;
				$int_width = $half_width - $w_height;
				$poiu = imagecopyresampled($dimg, $simg, -$int_width, 0, 0, 0, $adjusted_width, $nh, $w, $h);
			} elseif(($w <$h) || ($w == $h)) {
				$adjusted_height = $h / $wm;
				$half_height = $adjusted_height / 2;
				$int_height = $half_height - $h_height;
				$poiu = imagecopyresampled($dimg, $simg, 0, -$int_height, 0, 0, $nw, $adjusted_height, $w, $h);
			} else {
				$poiu = imagecopyresampled($dimg, $simg, 0, 0, 0, 0, $nw, $nh, $w, $h);
			}
			$poiu = imagejpeg($dimg,$dest);
	     }
		
		function getExt($file) {
			$getExt = explode ('.', $file);
			$file_ext = $getExt[count($getExt)-1];
			$file_ext = strtolower($file_ext);
			return $file_ext;
		}
	     
		function getExtIcon($ext) {
			$folder = "file_icon/";
			switch ($ext) {
				case "jpg": case "jpeg": case "png": case "gif": return $folder."jpg.png"; break;
				case "doc": case "docx": case "odt": 			 return $folder."doc.png"; break;
				case "ppt": case "pptx":  			 return $folder."ppt.png"; break;
				case "xls": case "xlsx":  			 return $folder."xls.png"; break;
				case "fla": case "swf":  			 return $folder."fla.png"; break;
				case "pdf": return $folder."pdf.png"; break;
				case "pdf": return $folder."pdf.png"; break;
				default: return $folder."all.png"; break;
			}
		}
		
		function hex2rgb($hexStr) {
			$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		    $rgbArray = array();
		    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		        $colorVal = hexdec($hexStr);
		        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
		        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
		        $rgbArray['blue'] = 0xFF & $colorVal;
		    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
		        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
		        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
		        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		    } else {
		        return false; //Invalid hex color code
		    }
		    return $rgbArray;
		}
?>