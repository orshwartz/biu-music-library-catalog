<!-- This file defines the navigation bar displayed on top of each of
	 the search system pages !-->
<?php
// general functions
include_once('func.php');
?>

<table border=0 align=center>
<tr>
	<td height=10>
	</td>
</tr>
<tr>
	<!-- link to the help documentation -->
	<td align=right>
		<form action="help.pdf" method=GET>
		<input alt="עזרה" type="image" src="images/help.gif">
		</form>
	</td>

	<td align=right>
		&nbsp; &nbsp;
	</td>

	<!-- link to advanced search (english) -->
	<td align=right>
		<form action="advsearch_eng.php" method=GET>
		<input alt="חיפוש מתקדם באנגלית" type="image" src="images/advanced_search_eng.gif">
		</form>
	</td>

	<!-- link to simple search (english) -->
	<td align=right>
		<form action="search_eng.php" method=GET>
		<input alt="חיפוש באנגלית" type="image" src="images/search_eng.gif">
		</form>
	</td>

	<!-- link to advanced search (hebrew) -->
	<td align=right>
		<form action="advsearch.php" method=GET>
		<input alt="חיפוש מתקדם" type="image" src="images/advanced_search_heb.gif">
		</form>
	</td>

	<!-- link to simple search (hebrew) -->
	<td align=right>
		<form action="search.php" method=GET>
		<input alt="חיפוש" type="image" src="images/search_heb.gif">
		</form>
	</td>

</tr>
</table>

