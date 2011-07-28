<?php
session_start();
// general functions
include_once('../func.php');
// database definitions
include_once('../db_common.php');
// CSS definitions
include_once('../styles.inc');
?>
<!-- This file is called after we got into the admin system.
	Prompts the user to choose the operation he'd like to perform. -->
<html>
<head>
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">
	<title>מערכת הזנת נתונים</title>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">
</head>

<body>

	<center><br><br>
	<table border=0 align=center width="100%"">
	   <tr>
	   <td class="menuTitle" align=center height=10>
	   <b>בחר בפעולה שברצונך לבצע<br><Br></b>
	   </td>
	   </tr>
	</table>
	</center>

	<!--  Creates navigation links, when no action was choosen yet-->

	<table border=0 align=center>
		<!-- add media-->
    	<tr>
			<td align=center>
	            <form action="adminAddMedia.php" method=GET>
	            <input name="action" type="hidden" value="newmedia">
	            <input alt="הוספת מדיה" type="image" src="../images/add_media.gif">
	            </form>
            </td>
		</tr>

		<!-- add new item -->
        <tr>
			<td align=center>
            	<form action="adminAddNewItem.php" method=GET>
	            <input type="hidden" name="action" value="newitem">
	            <input alt="הוספת פריט" type="image" src="../images/add_item.gif">
	            </form>
            </td>
		</tr>

		<!-- copy item -->
        <tr>
			<td align=center>
	            <form action="SearchByItemNo.php" method=GET>
	            <input name="action" type="hidden" value="copy">
	            <input alt="העתקת פריט" type="image" src="../images/copy_item.gif">
	            </form>
            </td>
		</tr>

		<!-- update/delete item -->
        <tr>
			<td align=center>
	            <form action="adminUpdate.php" method=GET>
	            <input alt="עדכון/מחיקה" type="image" src="../images/update_del.gif">
	            </form>
            </td>
		</tr>
	</table>

<center>
<br><br>
<hr width=500>
</center>

</body>
</html>