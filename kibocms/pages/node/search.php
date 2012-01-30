<?php
require("../../library/config.php");

$db = new Database();
$f = new Functions();


$term = $f->getValue('term');//retrieve the search term that autocomplete sends

$qstring = "SELECT distinct name FROM tags WHERE name LIKE '%".$term."%'";
$result = mysql_query($qstring);//query the database for entries containing the term
$row_set = array();
while ($row = mysql_fetch_array($result, MYSQL_ASSOC))//loop through the retrieved values
{
		//$row['value']=htmlentities(stripslashes($row['name']));
		//$row['id']=(int)$row['id'];
		$row_set[] = htmlentities(stripslashes($row['name']));
}
echo json_encode($row_set);//format the array into json data

?>