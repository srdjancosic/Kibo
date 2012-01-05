<?php
	require("functions.php");
	$base = "../../upload";

	$action = $_POST['action'];
	
	switch ($action) {
		
		default:
			break;
		
		case "save_new_thumb":
			
			$pic = $_POST['pic'];
			$dest = $_POST['dest'];
			$n_w = $_POST['n_w'];
			$n_h = $_POST['n_h'];
			$n_x = $_POST['n_x'];
			$n_y = $_POST['n_y'];
			$bg = $_POST['bg'];
			
			$rgb = hex2rgb($bg);
			
			
			$tmp = explode(".", $pic);
			$stype = $tmp[count($tmp) -1];
			$stype = strtolower($stype);
			
			list(,,,$folder,$imageName) = explode("/", $pic);
			
			$destination = "../../upload/".$folder."/";
			
			if($dest != "_thumb") {
				list($crop_w, $crop_h) = explode("x", $dest);
			} else {
				$crop_w = 100;
				$crop_h = 100;
			}
			$image = "";
			switch($stype) {
				case 'gif':
					$image = imagecreatefromgif($pic);
				break;
				case 'jpg': case 'jpeg':
					$image = imagecreatefromjpeg($pic);
				break;
				case 'png':
					$image = imagecreatefrompng($pic);
				break;
			}
			
			list($width, $height) = getimagesize($pic);
			$image_p = imagecreatetruecolor($n_w, $n_h);
			
			
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $n_w, $n_h, $width, $height);
			// Output
			
			$tmp_name = rand(100000000, 999999999).".".$stype;
			
			switch ($stype) {
				case 'gif':
					imagegif($image_p, $destination.$tmp_name, 100);
				break;
				case 'jpg': case 'jpeg':
					imagejpeg($image_p, $destination.$tmp_name, 100);
				break;	
				case 'png':
					imagepng($image_p, $destination.$tmp_name, 100);
				break;
			}
			
			$width = $n_w; $height = $n_h;
			$image_p2 = imagecreatetruecolor($crop_w, $crop_h);
			
			$color2 = imagecolorallocate($image_p2, $rgb['red'], $rgb['green'], $rgb['blue']);
			//imagefill($image_p2, 0, 0, $color2);
			
			
			//$image = imagecreatefromjpeg($destination.$tmp_name);
			$tmpImage = $destination.$tmp_name;
			switch($stype) {
				case 'gif':
					$image = imagecreatefromgif($tmpImage);
				break;
				case 'jpg': case 'jpeg':
					$image = imagecreatefromjpeg($tmpImage);
				break;
				case 'png':
					$image = imagecreatefrompng($tmpImage);
				break;
			}
			
			imagefilledrectangle($image_p2, 0, 0, $crop_w, $crop_h, $color2);
			
			
			imagecopyresampled($image_p2, $image, 0, 0, -$n_x, -$n_y, $crop_w, $crop_h, $crop_w, $crop_h);
			switch ($stype) {
				case 'gif':
					imagegif($image_p2, $destination.$dest."/".$imageName, 90);
				break;
				case 'jpg': case 'jpeg':
					imagejpeg($image_p2, $destination.$dest."/".$imageName, 90);
				break;	
				case 'png':
					imagepng($image_p2, $destination.$dest."/".$imageName, 90);
				break;
			}
			
			
			unlink($tmpImage);
			imagedestroy($image);
			imagedestroy($image_p);
			imagedestroy($image_p2);
			break;
	}
?>