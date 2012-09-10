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
if (preg_match('/[�-�]/',$q) > 0) {
	$field = "hebrew_composer";
	$sql = "select DISTINCT $field from records where ($field LIKE BINARY '%$q%')";
} else {
	$field = "composer";
	$sql = "select DISTINCT $field from records where ($field LIKE '%$q%')";
}
$rsd = mysql_query($sql);

// For each result
$idx_of_not_starting_with_qry = 0;
while ($rs = mysql_fetch_array($rsd, MYSQL_ASSOC)) {

	// Retrieve received composer name variations
	$composer = $rs[$field];
	
	// Change the encoding of the received text so it
	// displays well in the options drop-down menu and prepare
	// it for display on that menu
	$composer_dropdown_fmt = iconv("windows-1255", "UTF-8", $composer);
	$composer_dropdown_fmt = stripslashes($composer_dropdown_fmt)."\n";

	// If current result begins with the query (we want the results beginning
	// with the query to appear first on the drop-down list options)
	if (stripos($composer, $q) === 0) {
		
		// Add to drop-down menu
		echo $composer_dropdown_fmt;
	} else {
		
		// Save in array for later; Used to output after the results
		// beginning with the query
		$results_contain_not_at_start[$idx_of_not_starting_with_qry] =
			$composer_dropdown_fmt;
		++$idx_of_not_starting_with_qry;
	}
}

// For each result containing the query without beginning with the
// query
foreach ($results_contain_not_at_start as $cur_result)
{
	// Add result to drop-down menu
	echo $cur_result;
}
?>
