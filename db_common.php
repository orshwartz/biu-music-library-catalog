<!-- This file defines the connection to the database.
	Must be included in all files which use the database. -->
<?php
$mysql_database = "library";
$mysql_username = "arhip";
$mysql_password = "12345";

$link = mysql_connect("localhost", $mysql_username, $mysql_password) or die ("Unable to connect to SQL server");
mysql_select_db($mysql_database, $link) or die ("Unable to select database");

// common functions
include_once('func.php');

?>
