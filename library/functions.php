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
				$takenValue = $_POST[$value];
			} else if($REQUEST_METHOD == 'GET') {
				$takenValue = $_GET[$value];
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
				<div class="notification <?= $type; ?>" style="cursor: auto;"> 
					<div class="text"> 
						<p>
							<strong><?= ucfirst($type); ?>!</strong> 
							<?= $string; ?>
						</p> 
					</div> 
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
		
		function cutText($str, $length = 200) {
			$cont = (strlen($str) > $length) ? "..." : "";
			return substr($str, 0, $length).$cont;
		}
		
		function listCategoriesMenu($parent = 0) {
			$query = Database::execQuery("SELECT * FROM ".DB_PREFIX."category WHERE `parent` = '$parent' ORDER BY `id` ASC");
    		while($data = mysql_fetch_array($query, MYSQL_ASSOC)) {
		    	?>
		        <li>
		        	<a href="/kiboeasy/pages/category/index.php?id=<?= $data['id']; ?>"><?= ucfirst($data['name']); ?></a>
			        <?php
		        	if($data['is_parent'] == 1) {
		        	?>
		        		<ul>
		        		<?php 
		        			$this->listCategoriesMenu($data['id']);
		        		?>
		        		</ul>
		        		<?php
		        	}
		        	?>
		        </li>
		        <?php
    		} // end of while
		}
		
		function getDefaultLanguage() {
			$query = Database::execQuery("SELECT * FROM languages WHERE `default` = '1' AND `active` = '1'");
			$data = mysql_fetch_array($query, MYSQL_ASSOC);
			
			return $data['id'];
		}
	
		function countAllLanguages() {
			$db = new Database();
			return $db->numRows("SELECT * FROM languages WHERE `active` = '1'");
		}
		
		// user interaction
		
		function logedUser(){
	    	$userId = ($_SESSION["kibo_user"] == "") ? 0 : $_SESSION["kibo_user"];
	        $check_code = $_SESSION["kibo_user_code"];
	        $dateNow = date("Y-m-d H:i:s");
	        
	        $query = Database::execQuery("SELECT * FROM c_users WHERE id = '$userId' AND approved = '1' AND code = '$check_code'", $link);
	        $resultCount = mysql_num_rows($query);
		    
	        $allow_fb_registration = Database::getValue("allow_fb_registration", "config", "id", 1);
	        
	        if($resultCount == 1) {
	        	
	        	$_SESSION['fkk'] = 1;
	        	
	            return $userId;
	        } elseif ($resultCount == 0 && $allow_fb_registration == 1) {
	        	
	        	$config = array();
				$config['appId'] = YOUR_APP_ID;
				$config['secret'] = YOUR_APP_SECRET;
				  
				$facebook = new Facebook($config);
				$access_token = $facebook->getAccessToken();
	        	$readData = @file_get_contents('https://graph.facebook.com/me?access_token='.$access_token);
	        	if($readData) {
	        		
					$fbuser = json_decode($readData);
					
	        		$_SESSION['fbu'] = 1;
	        		$_SESSION['fkk'] = 0;
	        		
	        		$postoji = Database::numRows("SELECT * FROM c_users WHERE id = '".$fbuser->id."'");
	        		if($postoji == 0) {
		        		$date_ins = date("Y-m-d");
					    $date_app = date("Y-m-d");
					    list(,,$birth_year) = explode("/", $fbuser->birthday);
					    $gender = ($fbuser->gender == "male") ? 0 : 1;
					    
					    $check_code = $this->passwordGeneration();
					    $avatar = "http://graph.facebook.com/".$fbuser->id."/picture";
					    
					    Database::execQuery("INSERT INTO c_users (`id`, `fullname`, `email`, `password`, `newsletter`, `approved`, `date_added`, `date_approved`, `code`, `fbuser`, `avatar`) 
					    								VALUES ('$fbuser->id', '$fbuser->name', '$fbuser->email', '', '1', '1', '$date_app', '$date_app', '$check_code', '1', '$avatar')");
	        		}
	        		return $fbuser->id;
	        	}
	        	
	        	$_SESSION['fbu'] = 0;
		        $_SESSION['fkk'] = 0;
	            return 0;
	        } 
	        return 0;
	    }
		
	    function getUserGroup() {
	    global $userId;
	    
	    	if($userId == 0) {
	    		return Database::getValue("unlogged_user_group", "config", "id", 1);
	    	} else {
	    		return Database::getValue("group_id", "c_users", "id", $userId);
	    	}
	    	
	    }
	    
	    function createPagination($url_link, $page, $resultCount, $limit, $lang_id, $prevLink = "<", $nextLink = ">") {

	    	$totalPages = ceil($resultCount / $limit);
			if($totalPages > 1) {	
			
				$PAGE_URL_NAME = Database::getValue("pagination_url", "settings", "lang_id", $lang_id);
				$output .= "<div class=\"paging\">";
				
		        $firstPage = ($page == 1) ? 0 : 1;
		        $lastPage  = ($page == $totalPages) ? 0: $totalPages;
	        	$prevPage = ($page - 1 <= 0) ? 0 : $page - 1;
	        	$nextPage = ($page+1 <= $totalPages) ? $page+1 : 0;
	        	
		        if($page < 6) {
		        	$i = 1;
		        	$j = 1;
		        	$countTill = ($totalPages > 8) ? 8 : $totalPages;
		        } else {
		        	$i = $page - 4;
		        	$j = $i;
		        	$countTill = ($page + 4 <= $totalPages) ? $page+4 : $totalPages;
		        }
		        // printing previous page link
		        if($prevPage != 0) {
		        	$output .= "<div class=\"prev\"><a href=\"".$url_link."$PAGE_URL_NAME/".$prevPage."\">$prevLink</a></div>";
		        } else {
		        	$output .= "<div class=\"prev\">&nbsp;</div>";
		        }
				// printing middle pages
				$output .= "<div class=\"pages\">";
					if($j > 2) {
		            	$output .= "<a href=\"".$url_link."$PAGE_URL_NAME/1\">1</a> ";
		            	$output .= "<a href=\"javascript:void(0);\">...</a> ";
		            } else if($j == 2) {
		            	$output .= "<a href=\"".$url_link."$PAGE_URL_NAME/1\">1</a> ";
		            }
			        for($i; $i <= $countTill; $i++) {
			            if($page == $i) {
			            	$output .= "<a href=\"javascript:void(0);\" class='active_page'>".$i."</a> ";
			            } else {
			            	$output .= "<a href=\"".$url_link."$PAGE_URL_NAME/".$i."\">".$i."</a> ";
			            }
			        }
			        if($countTill == $totalPages - 1) {
		            	$output .= "<a href=\"".$url_link."$PAGE_URL_NAME/$totalPages\">$totalPages</a> ";
			        } else if($countTill < $totalPages) {
			        	$output .= "<a href=\"javascript:void(0);\">...</a> ";
		            	$output .= "<a href=\"".$url_link."$PAGE_URL_NAME/$totalPages\">$totalPages</a> ";
			        }
				$output .= "</div>";
			    // printing next page link
			    if($nextPage != 0) {
					$output .= "<div class=\"next\"><a href=\"".$url_link."$PAGE_URL_NAME/".$nextPage."\">$nextLink</a></div>";
				} else {
					$output .= "<div class=\"next\">&nbsp;</div>";
				}
		        $output .= "</div>";
		        
		        return $output;
			}
	    }
	} // end of class
	
	
	
?>