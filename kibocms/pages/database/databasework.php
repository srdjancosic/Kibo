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
		case "delete_backup":
				
				$file = $f->getValue("file");
				//$folder = $_SERVER['DOCUMENT_ROOT']."/kibocms/pages/database/backup";
				$folder = "C:\\Documents and Settings\\Administrator\\Desktop\\kibocms\\Kibo\\kibocms\\pages\\database\\backup";
				
				//unlick($folder."/".file);
				unlink($folder."\\".$file);
				
				$f->redirect("index.php");
			break;
		case "import_database":
				$file = $f->getValue("file");
				//$folder = $_SERVER['DOCUMENT_ROOT']."/kibocms/pages/database/backup";
				$folder = "C:\\Documents and Settings\\Administrator\\Desktop\\kibocms\\Kibo\\kibocms\\pages\\database\\backup";
				
				//$text = file_get_contents($folder."/".$file);
				$text = file_get_contents($folder."\\".$file);
				
				$query = explode(";--and of expresion", $text);
				
				for ($i = 0 ; $i<count($query) - 1; $i++) {
					if($query[$i] != ""){
						mysql_query($query[$i]) or die(mysql_error());
					}
				}
				$f->setMessage("Database imported!");
				$f->redirect("index.php");
			break;
	}
			
?>