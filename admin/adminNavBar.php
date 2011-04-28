<!-- This file defines the navigation bar displayed on top of each of
	 the search system pages !-->
<?php
// general functions
include_once('../func.php');

?>

<table border=0 bordercolor=black align=center>
	<tr>
		<td height=10></td>
	</tr>
	<tr>
		<td>
			<!-- link to help documentation -->
			<td align=right>
				<form action="../help.pdf" method=GET>
				<input alt="עזרה" type="image" src="../images/help.gif">
				</form>
			</td>

			<td align=right>
				&nbsp; &nbsp;
			</td>

			<!-- link to update/delete files -->
			<td align=right>
				<form action="adminUpdate.php" method=GET>
				<input alt="עדכון/מחיקה" type="image" src="../images/update_del.gif">
				</form>
			</td>

			<!-- link to copy item -->
			<td align=right>
				<form action="SearchByItemNo.php" method=GET>
				<input name="action" type="hidden" value="copy">
				<input alt="העתקת פריט" type="image" src="../images/copy_item.gif">
				</form>
			</td>

			<!-- link to add item -->
			<td align=right>
				<form action="adminAddNewItem.php" method=GET>
				<input name="action" type="hidden" value="newitem">
				<input alt="הוספת פריט" type="image" src="../images/add_item.gif">
				</form>
			</td>

			<!-- link to add new media -->
			<td align=right>
				<form action="adminAddMedia.php" method=GET>
				<input name="action" type="hidden" value="newmedia">
				<input alt="הוספת מדיה" type="image" src="../images/add_media.gif">
				</form>
			</td>
		</td>
	</tr>
</table>

