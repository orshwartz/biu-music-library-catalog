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
$sql = "select DISTINCT $field from records where $field LIKE BINARY '%$q%'";
$rsd = mysql_query($sql);
//								$num_of_res = 0;
// For each result
$idx_of_not_starting_with_qry = 0;
while($rs = mysql_fetch_array($rsd, MYSQL_ASSOC)) {
	
	$fname = $rs[$field];
	
	// Change the encoding of the received text so it
	// displays well in the options drop-down menu and prepare
	// it for display on that menu
	$fname_dropdown_fmt = iconv("windows-1255", "UTF-8", $fname);
	$fname_dropdown_fmt = stripslashes($fname_dropdown_fmt)."\n";
//											++$num_of_res;

	// If current result begins with the query (we want the results beginning
	// with the query to appear first on the drop-down list options)
	if (stripos($fname, $q) === 0) {

		// Add to drop-down menu
		echo $fname_dropdown_fmt;
	} else {
		
		// Save in array for later; Used to output after the results
		// beginning with the query
		$results_contain_not_at_start[$idx_of_not_starting_with_qry] =
			$fname_dropdown_fmt;
		++$idx_of_not_starting_with_qry;
	}
}
//				system("echo $num_of_res >> c:\\debug.txt");
// For each result containing the query without beginning with the
// query
foreach ($results_contain_not_at_start as $cur_result)
{
//									system("echo not beginning >> c:\\debug.txt");
	// Add result to drop-down menu
	echo $cur_result;
}
?>