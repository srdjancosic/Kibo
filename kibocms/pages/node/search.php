<?php
require("../../library/config.php");

$db = new Database();
$f = new Functions();


$term = $f->getValue('term');//retrieve the search term that autocomplete sends

$qstring = "SELECT name ,id FROM test WHERE name LIKE '%".$term."%'";
$result = $db->execQuery($qstring);//query the database for entries containing the term

while ($row = mysql_fetch_array($result,MYSQL_ASSOC))//loop through the retrieved values
{
		$row['value']=htmlentities(stripslashes($row['name']));
		$row['id']=(int)$row['id'];
		$row_set[] = $row;//build an array
}
echo json_encode($row_set);//format the array into json data

?>