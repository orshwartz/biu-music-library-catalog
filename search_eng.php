<!-- This file displays the simple search table (english) -->
<?php
// general functions
include_once('func.php');
// database definitions
include_once('db_common.php');
// CSS definitions
include_once('styles.inc');
// navigation bar to be displayed on top
include_once('searchNavBar.php');
?>
<html>
<head>
	<title>מערכת חיפוש נתונים</title>
	<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

        <script>
              function openModal(code){
                 // opens a generic dialog with a list of requested group
				 // (Composers, Soloist,Performance group, Orchestra, Conductor or Subject)
                 var val = window.open("indexWin.php?lang=en&let=A&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
              }
        </script>
</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">

<form action="results.php" method=GET name="searchForm">
<!-- determines the display language -->
<input name="display" type="hidden" value="eng">

<!-- creates the search table -->
<!-- table fields. When clicking on the index button, openModal() is called. -->
<table border=1 align=center width=500 class="dataTable">
	<tr>
	    <td colspan=2 class="bigTitle" align=center><b>Search</b></td>
	</tr>

    <tr>
		<td align=right>
		    <table border=0 bordercolor=black align=center width=100% class="dataTable">
			    <tr>
			        <td align=left>
			        	Composer
			        </td>
			        <td align=center width=20>
			            <a href="javascript: openModal(6);"><img src="images/indexE.gif" border=0></a>
			        </td>
			    </tr>
		    </table>
		</td>
		<td align=center>
			<input type=text name="composer">
		</td>
    </tr>

	<tr>
		<td align=left>
			Title (Composition, Song)
		</td>
		<td align=center>
			<input type=text name="composition_title">
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center>
			<input type=submit class="recordTitle" value="     send     ">
			<input type=hidden name=action value=1>
		</td>
	</tr>
</table>
</form>
<br>
<center>

<hr width=500>
<br><br>
</center>

</body>
</html>
