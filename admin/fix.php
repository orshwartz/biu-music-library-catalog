<?php
// database definitions
include_once('../db_common.php');

$qry = "update records set orchestra=NULL where orchestra='תזמורת סמפונית של מינכן'";
//$qry = "select * from records";
$resultSet = mysql_query($qry);
if ($resultSet != false)
	echo ("OK");
else
	echo ("Failed");

$qry = "update records set orchestra2=NULL where orchestra2='תזמורת סמפונית של מינכן'";
//$qry = "select * from records";
$resultSet = mysql_query($qry);
if ($resultSet != false)
	echo ("OK");
else
	echo ("Failed");

$qry = "update records set orchestra3=NULL where orchestra3='תזמורת סמפונית של מינכן'";
//$qry = "select * from records";
$resultSet = mysql_query($qry);
if ($resultSet != false)
	echo ("OK");
else
	echo ("Failed");
?>

