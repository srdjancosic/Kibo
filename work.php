<?php
	require("library/config.php");

	$db = new Database();
	$f  = new Functions();
	
	$action = $f->getValue("action");
	
	switch ($action) {
		
		/**
		 * USER LOGIN
		 */
		case "change_password":
			$lozinka = $f->getValue("password"); 
			$lozinka_check = $f->getValue("password_check");
			$userID = $f->logedUser();
			if($lozinka == $lozinka_check && $userID > 0) {

				$lozinka = md5($lozinka);
				$db->execQuery("UPDATE c_users SET `password`='$lozinka' WHERE id='$userID'");
			}
			$f->redirect("/moji-podaci");

			break;
			
		case "check_fb_id":	
		
			$id = $f->getValue("id");
			
			$config = array();
			$config['appId'] = YOUR_APP_ID;
			$config['secret'] = YOUR_APP_SECRET;
			  
			$facebook = new Facebook($config);
			$access_token = $facebook->getAccessToken();
			
        	$readData = @file_get_contents('https://graph.facebook.com/me?access_token='.$access_token);
        	
        	if($readData) {	
				$fbuser = json_decode($readData);
				
				$email = $fbuser->email;
				$num = $db->numRows("SELECT * FROM c_users WHERE email='".$fbuser->email."' AND fbuser='0'");
				echo $num;
        	}		
		
			break;	
		
		case "check_email2":

			$email = $f->getValue("email");
			echo $db->numRows("SELECT * FROM c_users WHERE email='$email'");
			break;	
				
		case "login":

			$email = $f->getValue("email");
			$password = $f->getValue("password");
			$password = md5($password);

			$num = $db->numRows("SELECT * FROM c_users WHERE email = '$email' AND password='$password' AND approved = '1'");

			if($num == 1) {
				$query_user = $db->execQuery("SELECT * FROM c_users WHERE email = '$email' AND password='$password' AND approved = '1'");
				$data_user = mysql_fetch_array($query_user);

				$_SESSION['fbu'] = 0;
		        $_SESSION['fkk'] = 1;
		        $_SESSION["kibo_user"] = $data_user['id'];
		        $_SESSION["kibo_user_code"] = $data_user['code'];
		        $f->redirect("/");

			} else {
				$num = $db->numRows("SELECT * FROM c_users WHERE email = '$email' AND password='$password' AND approved = '0'");
				if($num == 1) {
					$email = str_replace(".", "-", $email);
					$email = str_replace("@", "-at-", $email);
					$f->redirect("registracija/greska2/$email/");	
				} else {
					$f->redirect("registracija/greska/");		
				}
			}
			
			break;
		
			
		case "register":
			$email = $f->getValue("email");
			$num = $db->numRows("SELECT * FROM c_users WHERE email = '$email' AND approved = '1'");

			if($num == 0) {
				$query = $db->execQuery("SELECT MAX(id) AS id FROM c_users WHERE fbuser = '0'");
			    $data = mysql_fetch_array($query, MYSQL_ASSOC);

			    $user_id = $data['id'];
			    $user_id += 1;

				$db->execQuery("INSERT INTO c_users (`id`) VALUES ('$user_id')");
				
				$user = new View("c_users", $user_id);
				$user->extend($_POST);
				$user->password = md5($user->password);
				$user->code = rand(0,999999999);
				$user->date_added = date("Y-m-d H:i:s");
				$user->group_id = 2;
				$user->Save();
	
				$pass_email = $password; 
				$password = md5($password);

				$wait_for_approval = $db->getValue("wait_for_approval", "config", "id", 1);
				$link = "";
				if($wait_for_approval == 1) {
					$link = DOMAIN . "work.php?action=approve_email&email=$user->email&code=$user->code";
					$link = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
				}
				include("library/phpmailer/class.phpmailer.php");
		
				$heder = file_get_contents("emails/header.html");
				$body = file_get_contents("emails/obavestenje.html");
				$footer = file_get_contents("emails/footer.html");
				
				$heder = str_replace("{naslovh2}", "Uspešna registracija", $heder);
				$heder = str_replace("{naslovh4}", "", $heder);
				$body = str_replace("{link}", "$link", $body);
				$file = $heder . $body . $footer;

				$configSiteEmail = $db->getValue("site_email", "config", "id", 1);
				$configSiteTitle = $db->getValue("site_title", "settings", "lang_id", 1);
				
				$mail = new PHPMailer();
				$mail->From = "$configSiteEmail";
				$mail->FromName = "$configSiteTitle";
				$mail->AddAddress($user->email);
				$mail->Subject = "Your account has been created";
				$mail->Body = $file;

				$mail->Send();	
				$f->redirect("strana/uspesna-registracija");

			} else {
				$f->redirect("/register");
			}
			
			
			break;	
			
		case "approve_email":
			
			$email = $f->getValue("email");
			$code = $f->getValue("code");

			$query = $db->execQuery("SELECT * FROM c_users WHERE email='$email' AND code='$code' AND approved='0'");
			$num = mysql_num_rows($query);
			
			if($num == 1) {
				$dateNow = date("Y-m-d H:i:s");
				$db->execQuery("UPDATE c_users SET `approved`='1', `date_approved` = '$dateNow' WHERE code='$code' AND email='$email'");
				$data = mysql_fetch_array($query);
				if($data['password'] != "" && $data['fullname'] != "") {
					$_SESSION['fbu'] = 0;
			        $_SESSION['fkk'] = 1;
			        $_SESSION["kibo_user"] = $data['id'];
			        $_SESSION["kibo_user_code"] = $data['code'];
				}
				
				$f->redirect("strana/uspesno-aktiviran-email");

			} else {
				$f->redirect("strana/greska-prilikom-aktivacije-emaila");
			}
			
			break;
			
		case "logout":
			
			$_SESSION['fbu'] = 0;
	        $_SESSION['fkk'] = 0;
	        $_SESSION["kibo_user"] = "";
	        $_SESSION["kibo_user_group"] = "";
	        
	        $FB = $f->getValue("fb");
	        if($FB) {
	        	$_SESSION['fb_'.YOUR_APP_ID.'_code'] = '';
	        	$_SESSION['fb_'.YOUR_APP_ID.'_access_token'] = '';
	        }

			$f->redirect("/");
			
			break;
		
		case "forgoten_password":
			
			$email = $f->getValue("email");
			
			$new_pass = rand(0,999999);
			$new_pass_md = md5($new_pass);
			
			
			include("phpmailer/class.phpmailer.php");
								
			$heder = file_get_contents("emails/header.html");
			$body = file_get_contents("emails/newsletter.html");
			$footer = file_get_contents("emails/footer.html");
			
			$heder = str_replace("{naslovh2}", "Zaboravljena lozinka", $heder);
			$heder = str_replace("{naslovh4}", "", $heder);
							
			$body = str_replace("{ponude}", "Vaša nova lozinka na SavaStojkov.com je: $new_pass.", $body);
			
			$file = $heder . $body . $footer;
			
			$mail = new PHPMailer();
			$mail->From = "$configSiteEmail";
			$mail->FromName = "$configSiteTitle";
			$mail->AddAddress($email);
			$mail->Subject = "Zaboravljena lozinka";
			$mail->Body = $file;
			
			$db->execQuery("UPDATE c_users SET `password`='$new_pass_md' WHERE email='$email' AND fbuser = '0'");
			
			$mail->Send();	
			
			$f->redirect("/strana/promenjena-lozinka");
			
			break;
			
	}
	
?>