<?php
// This file generates options for autocomplete using the given $field

$host="localhost"; // Host name
$username="arhip"; // Mysql username
$password="12345"; // Mysql password
$db_name="library"; // Database name

$con = mysql_connect($host,$username,$password)   or die(mysql_error());
mysql_select_db($db_name, $con)  or die(mysql_error());

$field = $_GET["field"];
if (!$field) return;
$q = strtolower($_GET["q"]);
if (!$q) return;

$q = iconv("UTF-8", "windows-1255", $q);
$sql = "SELECT DISTINCT $field FROM records WHERE $field LIKE BINARY '%$q%' UNION";
$sql = "$sql SELECT DISTINCT $field"."2 FROM records WHERE $field"."2 LIKE BINARY '%$q%' UNION";
$sql = "$sql SELECT DISTINCT $field"."3 FROM records WHERE $field"."3 LIKE BINARY '%$q%'"; 
$rsd = mysql_query($sql);

while ($rs = mysql_fetch_array($rsd, MYSQL_ASSOC)) {
	
	$fname = $rs[$field];
	$fname = iconv("windows-1255", "UTF-8", $fname);
	echo stripslashes($fname)."\n";
}
?>