<?php
require_once('db_common.php');

$q = strtolower($_GET["q"]);
if (!$q) return;

//mysql_query("SET NAMES 'utf8'"); // Enable Hebrew queries
/*
$sql = 			"select DISTINCT composer, hebrew_composer from records where composer LIKE '%$q%'";
$sql = $sql . 	" OR ";
$sql = $sql . 	"hebrew_composer LIKE BINARY '%$q%'";
*/
/*$let = 'ωμ';
$sql = "select composer, hebrew_composer from records where hebrew_composer like binary '%" . $let . "%' group by hebrew_composer";
*/
$searchQry = "select * from records where ";
$searchQry = $searchQry . " (hebrew_composer like binary '%" . $q . "%' or second_author like binary '%" . $q . "%')";
$sql = $searchQry;

$rsd = mysql_query($sql);

$len = mysql_num_rows($rsd);
echo "<script>alert(\"$sql jjjjjjjjjjjjjjjjj $len \");</script>";

while ($rs = mysql_fetch_array($rsd)) {
	
	$cname = $rs['composer'] . " - " . $rs['hebrew_composer'];
	//echo "<script>alert(\" $cname \");</script>";
	echo "$cname\n";
}
?>