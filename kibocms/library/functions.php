<?php

	class Functions extends Database  {
		
		function redirect($link, $time = 0) {
			echo "<meta http-equiv='refresh' content='$time;URL=$link' />";
			die();
		}
		
		function stringCleaner($string) {
			$string 	= trim($string);
			$string 	= mysql_real_escape_string($string);
			return $string;
		}

		function getValue($value){
		global $HTTP_POST_VARS, $HTTP_GET_VARS, $_SERVER;
		
			$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
			
			if($REQUEST_METHOD == 'POST') {
				$takenValue = $HTTP_POST_VARS[$value];
			} else if($REQUEST_METHOD == 'GET') {
				$takenValue = $HTTP_GET_VARS[$value];
			}
			
			$takenValue = $this->stringCleaner($takenValue);
			
			return $takenValue;
		}
		
		function readLanguage() {
			
			if(isset($_GET['lang'])) {
				$lang = $_GET['lang'];
			} else {
				$lang = DEFAULT_LANG;
			}
			
			return $lang;
		}
		
		function mailValidator($email) {
			$result = true;
			if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
				$result = false;
			}
			return $result;
		}
		
		function passwordGeneration($length = 10, $possible = "0123456789") {
			$password = ""; 
			$i = 0; 
			while($i < $length) { 
				$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
				if(!strstr($password, $char)) { 
					$password .= $char;
					$i++;
				}
			}
			return $password;
		}
		
		function generateUrlFromText($strText) {
			$strText = str_replace("š", "s", $strText);
			$strText = str_replace("Š", "s", $strText);
			$strText = str_replace("ž", "z", $strText);
			$strText = str_replace("Ž", "z", $strText);
			$strText = str_replace("Č", "c", $strText);
			$strText = str_replace("č", "c", $strText);
			$strText = str_replace("Ć", "c", $strText);
			$strText = str_replace("ć", "c", $strText);
			$strText = str_replace("Đ", "dj", $strText);
			$strText = str_replace("đ", "dj", $strText);
			$strText = preg_replace('/[^A-Za-z0-9-]/', ' ', $strText);
			$strText = preg_replace('/ +/', ' ', $strText);
			$strText = trim($strText);
			$strText = str_replace(' ', '-', $strText);
			$strText = preg_replace('/-+/', '-', $strText);
			$strText = strtolower($strText);
			return $strText;
		}
		
		function generateUrlFromText1($strText) {
			$strText = str_replace("š", "s", $strText);
			$strText = str_replace("Š", "s", $strText);
			$strText = str_replace("ž", "z", $strText);
			$strText = str_replace("Ž", "z", $strText);
			$strText = str_replace("Č", "c", $strText);
			$strText = str_replace("č", "c", $strText);
			$strText = str_replace("Ć", "c", $strText);
			$strText = str_replace("ć", "c", $strText);
			$strText = str_replace("Đ", "dj", $strText);
			$strText = str_replace("đ", "dj", $strText);
			$strText = preg_replace('/[^A-Za-z0-9-]/', ' ', $strText);
			$strText = preg_replace('/ +/', ' ', $strText);
			$strText = trim($strText);
			$strText = str_replace(' ', '_', $strText);
			$strText = preg_replace('/-+/', '_', $strText);
			$strText = strtolower($strText);
			return $strText;
		}
	    
	    function recognizeURL($text) {
	    	$regEx 	= "((www\.|http\.|(www|http|https|ftp|news|file)+\:\/\/)([_.a-z0-9-]+\.[a-zA-Z0-9\/_:@=.+?,##%&~-]*[^.|\'|\# |!|\(|?|,| |>|< |;|\)]))"; 
	    	$text	= preg_replace($regEx, "<a href='$0'>$0</a>",  $text); 
			
	    	return $text;
		}
		
		function setMessage($string, $type = "success") {
			$_SESSION["message"] = $string;
			$_SESSION["messageType"] = $type;
		}
		
		function getMessage() {
			$string = $_SESSION["message"];
			$type = $_SESSION["messageType"];
			
			if(strlen($string) > 0) {
				unset($_SESSION["message"]);
				unset($_SESSION["messageType"]);
				?>
				<div class="message close <?= $type; ?>" style="display: block;">
					<p>
					<?= $string; ?>
					</p>
				</div>
				<?php
			}
		}
		
		function listLanguagesSelect($selected = 0) {
			$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."languages WHERE active = '1' ORDER BY name ASC");
			while ($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				?>
				<option value="<?= $data['id']; ?>" <?= ($data['id'] == $selected) ? "selected=\"selected\"" : ""; ?>><?= $data['name']; ?></option>
				<?php
			}
		}
		
		function getLanguageName($lang_id) {
			return  Database::getValue("name", "languages", "id", $lang_id);
		}
	
		function uploadFile() {
		global $file_size;
			$brojArgumenata = func_num_args();
		
			$nazivPolja = func_get_arg(0);
			if($brojArgumenata > 1)
				$targetdir 	= func_get_arg(1);
			else 
				$targetdir 	= 'content_files/';
			
			if($brojArgumenata > 2)
				$dozvEkst 	= func_get_arg(2);
			else 
				$dozvEkst 	= '';
			
			if($brojArgumenata > 3)
				$namePic = func_get_arg(3);
			
			if($brojArgumenata > 4)
				$destinacija = func_get_arg(4);
			else 
				$destinacija = array();
			// primer za destinaciju:
			// 		array(array("dodateSlike/male/", 150),
			//			  array("dodateSlike/srednje/", 250))
			if($brojArgumenata > 5)
				$cropOvanje = func_get_arg(5);
			else 
				$cropOvanje = array();
			// primer za kropovanje:
			//		array(array("cropSlike/male/", 50, 50),
			//			  array("cropSlike/srednje/", 75, 75),
			//			  array("cropSlike/velike/", 100, 100))
		
			if(isset($_FILES[$nazivPolja]) && $_FILES[$nazivPolja]['size'] > 0) {
				$tmp_name = $_FILES["$nazivPolja"]["tmp_name"];
				$file_type = $_FILES["$nazivPolja"]["type"];
				
				$getExt = explode ('.', $_FILES[$nazivPolja]['name']);
				$file_ext = $getExt[count($getExt)-1];
				
				$file_ext = strtolower($file_ext);
				
				$file_size = $_FILES[$nazivPolja]['size'];
				
				$niz = explode(",", $dozvEkst);
		
				if(!in_array($file_ext, $niz)) 
					die("Error: Only these picture extensions are allowed <strong>".$dozvEkst."</strong>");
				
				$rand_name = $namePic."-".rand(0, 999);	
				
				$name = $rand_name.".".$file_ext;
				$n = $targetdir.$name;
				
				if(count($destinacija) > 0) {
					for($i = 0; $i < count($destinacija); $i++) {
						$this->resizeImage($tmp_name, $file_type, $destinacija[$i][0], $destinacija[$i][1], $name);
					}
				}
				
				move_uploaded_file($tmp_name, $n);
				
				if(count($cropOvanje) > 0) {
					for($i = 0; $i < count($cropOvanje); $i++) {
						$this->cropImage($cropOvanje[$i][1], 
								  $cropOvanje[$i][2], 
								  $n, 
								  $file_ext, 
								  $cropOvanje[$i][0].$name);
					}
				}
				return $name;
			}
		}
		
		function resizeImage($file_tmp, $file_type, $targetdir, $ThumbWidth, $name, $Sheight = "auto") {
			if($file_type == "image/pjpeg" || $file_type == "image/jpeg") {
				$new_img = imagecreatefromjpeg($file_tmp);
			} elseif($file_type == "image/x-png" || $file_type == "image/png") {
				$new_img = imagecreatefromjpeg($file_tmp);
			}elseif($file_type == "image/gif") {
				$new_img = imagecreatefromgif($file_tmp);
			}
	
			if($Sheight == "auto") {
				list($width, $height) = getimagesize($file_tmp);
				$imgratio=$width/$height;
				if ($imgratio>1) {
					$newwidth = $ThumbWidth;
					$newheight = $ThumbWidth/$imgratio;
				} else {
					$newheight = $ThumbWidth;
					$newwidth = $ThumbWidth*$imgratio;
				}
			} else {
				list($width, $height) = getimagesize($file_tmp);
				$imgratio=$width/$height;
				if ($imgratio>1) {
					$newwidth = $ThumbWidth;
					$newheight = $Sheight;
				} else {
					$newheight = $Sheight;
					$newwidth = $ThumbWidth*$imgratio;
				}
			}
	
			if (function_exists(imagecreatetruecolor)){
				$resized_img = imagecreatetruecolor($newwidth,$newheight);
			} else {
				die("GRESKA: Uverite se da je verzija vase GD biblioteke 2+");
			}
			imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
			ImageJpeg ($resized_img, $targetdir.$name);
			ImageDestroy ($resized_img);
			ImageDestroy ($new_img);
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
					$simg = imagecreatefromjpeg($source);
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
				imagecopyresampled($dimg, $simg, -$int_width, 0, 0, 0, $adjusted_width, $nh, $w, $h);
			} elseif(($w <$h) || ($w == $h)) {
				$adjusted_height = $h / $wm;
				$half_height = $adjusted_height / 2;
				$int_height = $half_height - $h_height;
				imagecopyresampled($dimg, $simg, 0, -$int_height, 0, 0, $nw, $adjusted_height, $w, $h);
			} else {
				imagecopyresampled($dimg, $simg, 0, 0, 0, 0, $nw, $nh, $w, $h);
			}
			imagejpeg($dimg,$dest);
	     }

		function sizeOfFile($file_size) {
			if ($file_size >= 1048576){
				  $show_filesize = number_format(($file_size / 1048576),2) . " MB";
				}elseif ($file_size >= 1024){
				  $show_filesize = number_format(($file_size / 1024),2) . " KB";
				}elseif ($file_size >= 0){
				  $show_filesize = $file_size . " b";
				}else{
				  $show_filesize = "0 b";
		   }
		   return $show_filesize;
		}
		
		function getFolderChilds($current) {
			$dh = opendir($current);
			
			while ($file = readdir($dh)) {
				$filePath = $current."/".$file;
				if($file != "." && $file != ".." && $file != "_thumb") {
					if(!is_file($filePath)) {
						echo "- ".$file."";
						echo "<br>";
					}
				}
			}
		}
		
		function getArrValue($field, $id) {
			return addslashes($_POST[$field][$id]);
		}
		
		function getDefaultLanguage() {
			$query = Database::execQuery("SELECT * FROM languages WHERE `default` = '1' AND `active` = '1'");
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			
			return $data['id'];
		}
		
		function getActiveLanguages() {
			$query = Database::execQuery("SELECT * FROM languages WHERE `default` = '0' AND `active` = '1'");
			$languages = array();
			while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$languages[$data['id']] = $data['name'];
			}
			return $languages;
		}
		
		function getAllLanguages() {
			$query = Database::execQuery("SELECT * FROM languages WHERE `active` = '1' ORDER BY `default` DESC");
			$languages = array();
			while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$languages[$data['id']] = $data['name'];
			}
			return $languages;
		}
		
		function getCategoryRefId($categoryId) {
			$ref_id = Database::getValue("ref_id", "category", "id", $categoryId);
			if($ref_id == 0) 
				return $categoryId;
			else 
				return $ref_id;
		}
		
		function getCategoryByLang($categoryId, $langId) {
			$query = Database::execQuery("SELECT id FROM category WHERE ref_id = '$categoryId' AND lang_id = '$langId'");
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			return $data['id'];
		}
		
		function getLeafIdByRefId($refId, $langId) {
			$query = Database::execQuery("SELECT id FROM leaves WHERE ref_id = '$refId' AND lang_id = '$langId'");
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			
			return $data['id'];
		}
		
		/** loging **/
		function loggedIn() {
			$auid = 0;
			if($_SESSION['auid']==0) {
				$this->redirect("/kibocms/join.php");
				die();
			} else {
				$auid = $_SESSION['auid'];
				$cc = $_SESSION['auid_cc'];
				$cc_db = Database::getValue("check_code", "admins", "id", $auid);
				
				if($cc != $cc_db) {
					$_SESSION['auid'] = 0;
					$_SESSION['auid_cc'] = "";
					$this->redirect("/kibocms/join.php");
					die();
				}
				
			}
			return $auid;
		}
		
		function adminAllowed($section, $adminAction) {
		global $adminId;
			
			$allowed = Database::getValue("actions", "admins", "id", $adminId);
			$allowed = json_decode($allowed, true);
			
			return (in_array($adminAction, $allowed[$section]));
		
		}
	} // end of class
	
	
	
?>