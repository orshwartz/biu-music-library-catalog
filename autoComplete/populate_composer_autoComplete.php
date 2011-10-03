<?php
// This file generates options for composer autocomplete. It is a specialized
// processing because composer has two different records (Hebrew and English).

$host="localhost"; // Host name
$username="arhip"; // Mysql username
$password="12345"; // Mysql password
$db_name="library"; // Database name

$con = mysql_connect($host,$username,$password)   or die(mysql_error());
mysql_select_db($db_name, $con)  or die(mysql_error());

$q = strtolower($_GET["q"]);
if (!$q) return;

// Select matching records according to entered language
$q = iconv("UTF-8", "windows-1255", $q);
if (preg_match('/[à-ú]/',$q) > 0) {
	$field = "hebrew_composer";
	$sql = "select DISTINCT $field from records where ($field LIKE BINARY '%$q%')";
} else {
	$field = "composer";
	$sql = "select DISTINCT $field from records where ($field LIKE '%$q%')";
}
$rsd = mysql_query($sql);

// For each result
while($rs = mysql_fetch_array($rsd)) {

	// Retrieve received composer name variations
	$composer = $rs[$field];
	
	// Change the encoding of the received text so it
	// displays well in the options drop-down menu
	$composer = iconv("windows-1255", "UTF-8", $composer);

	echo stripslashes($composer)."\n";
}
?>
