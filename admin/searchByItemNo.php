<?php
session_start();
// general functions
include_once('../func.php');
// database definitions
include_once('../db_common.php');
// CSS definitions
include_once('../styles.inc');
// navigation bar to be displayed on top
include_once('adminNavBar.php');
?>
<!-- This file is used when we need to search for an item by number only.
 occures when: a) copying an item
			   b) updating/deleting.
-->
<html>
<head>
	<title>מערכת הזנת נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">

<?php
$msg = "";
$action = &$_GET['action'];

	// if action is set, it means we've already searched for an item number.
	// we'll display the results page and send to it the desired mode
	// (copy, delete or update) - recieved from the page who called this page.
    if ($action == "copy") {
        echo "<form action='adminResults.php' name='searchForm' method=GET class='recordTitle'>
				<input type=hidden name='mode' value='copy'>
				<input type=hidden name='action' value='copyitem'>
				<input name='display' type='hidden' value='heb'>";
    } else if ($action == "update") {
        echo "<form action='adminResults.php' name='searchForm' method=GET class='recordTitle'>
				  <input type=hidden name='mode' value='update'>
				  <input type=hidden name='action' value=1>
				  <input name='display' type='hidden' value='heb'>";
    } else if ($action == "delete") {
        echo "<form action='adminResults.php' name='searchForm' method=GET class='recordTitle'>
				  <input type=hidden name='mode' value='delete'>
				  <input type=hidden name='action' value=1>
				  <input name='display' type='hidden' value='heb'>";
    }

    ?>

	<!-- display the table where one can type in an item number to search for-->
	<table border=1 bordercolor=black align=center width=500 class='dataTable'>
		<tr>
			<td colspan=2 class="bigTitle" align=center><b><?php echo "חיפוש פריט"?></b></td>
		</tr>
		<tr>
			<td align=center>
				<input type=text name="item_no">
			</td>
			<td align=right>מס' פריט</td>
		</tr>
		<tr>
			<td colspan=2 align=center>
				<input type=submit class='recordTitle' value="     שלח     ">
			</td>
		</tr>
	</table>

	</form>

<br>
<center>
<hr width=500>
</center>

</body>
</html>