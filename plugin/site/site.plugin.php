<?php

		function helloWorld() {
				
			$db = new Database();
			$f  = new Functions();
			
			$query = $db->execQuery("SELECT * FROM massages ORDER BY date DESC");
			
			while($data = mysql_fetch_array($query)) {
				echo '<div class="poruka">';
				echo "<strong>Od</strong>: ".$data['name']."<br /><strong>e-mail: </strong>".$data['mail']." <br /><strong>Datum: </strong>".$data['date'];
				echo "<br /><p>".$data['content']."</p>";
				echo '<form method="POST" action="/obrisi.php"><input type="hidden" name="id_zabr" value='.$data[id].'><input type="SUBMIT" value="Obrisi"></form>';
				echo '</div>';				
			}
		}
		
		function helloWorldEn() {
			echo "Hello, World!";
		}
?>