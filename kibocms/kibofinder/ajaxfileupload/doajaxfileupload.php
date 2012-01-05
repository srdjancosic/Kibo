<?php
	require("../functions.php");

	$error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';
	$destination = $_REQUEST['destination'];
	$newFileName = $_REQUEST['new_file_name'];
	$newFileName = ($newFileName == "") ? "upload" : $newFileName;
	$picture_ext = array("jpg", "jpeg", "png", "gif");
	
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{
			$current = "../".$destination;
			
			$tmp_name = $_FILES[$fileElementName]['tmp_name'];
			$getExt = explode ('.', $_FILES[$fileElementName]['name']);
			$file_ext = $getExt[count($getExt)-1];
			$file_ext = strtolower($file_ext);
			
			//$file_ext = getExt($_FILES[$fileElementName]['name']);
			
			$rand_name = $newFileName."-".rand(0, 999);	
			$name = $rand_name.".".$file_ext;
			
			if(move_uploaded_file($tmp_name, $current."/".$name)) {
				
				if(in_array($file_ext, $picture_ext)) {
					
					$dims = getFolderChildsArray($current);
					
					$k = cropImage("100", "100", $current."/".$name, $file_ext, $current."/_thumb/".$name);
					
					for($i=0; $i< count($dims); $i++) {
					
						$tmp_dest = $dims[$i][0];
						$tmp_x = $dims[$i][1];
						$tmp_y = $dims[$i][2];
							
						cropImage($tmp_x, $tmp_y, $current."/".$name, $file_ext, $tmp_dest."/".$name);
						
					}
					
				}	
				$msg .= $name; // $_FILES['fileToUpload']['name'] . " ";
				//$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
				//for security reason, we force to remove all uploaded file
			
			}
			
			//@unlink($_FILES[$fileElementName]);		
	}		
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "'\n";
	echo "}";
	
?>