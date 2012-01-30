<?php
	require("library/config.php");
	
	$db = new Database();
	$f  = new Functions();
	
	
	$tables = array();
    $data = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($data))
    {
      $tables[] = $row[0];
    }
	
	foreach($tables as $table)
	 	{
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
  				while($row = mysql_fetch_row($result))
  				{
    				$return.= 'INSERT INTO '.$table.' VALUES(';
   					for($j=0; $j<$num_fields; $j++) 
    				{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) {
							$return.= '"'.$row[$j].'"' ; 
						} else {
							$return.= '""';
						}
						if ($j<($num_fields-1)) {
							$return.= ','; 
						}
		    		}
		    		$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}
			  
			  //save file
			  $file = 'db-backup-'.DB_BASE.date('Ymd').'.sql';
			  $handle = fopen($file,'w+');
			  fwrite($handle,$return);
			  fclose($handle);
			  
		$f->setMessage('Database exported!');
		// $f->redirect("download.php");
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		
		if(readfile($file))
		$f->redirect("index.php");
?>