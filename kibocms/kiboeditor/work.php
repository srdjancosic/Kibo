<?php
	
	$base = "../../css/";

	$action = $_POST['action'];
	
	switch ($action) {	
		case "loadFile":
			
			$file = $_POST['file'];
			
			if($fileContent = file_get_contents($file)) {
				echo $fileContent;
			} else {
				echo "-1";
			}
			
			break;
		case "saveFile":
			
			$file = $_POST['file'];
			$data = $_POST['content'];
			
			$fileContent = file_put_contents($file, stripslashes($data));
			
			
			break;
	}