<!-- This file displays the advanced search table -->
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
	<link rel="icon" href="images/DataSearch.ico" type="image/x-icon">
	<link rel="shortcut icon" href="images/DataSearch.ico" type="image/x-icon">
	<title>מערכת חיפוש נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">
        <script>

        function openModal(code){
        	// opens a generic dialog with a list of requested group
		 	// (Composers, Soloist,Performance group, Orchestra, Conductor or Subject)
        	var val = window.open("indexWin.php?lang=heb&let=א&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");

        }

        </script>

</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">

<form action="results.php" method=GET name="searchForm">

<!-- determines the display language -->
<input name="display" type="hidden" value="heb">

<!-- creates the search table -->
<!-- table title -->
<table border=0 align=center width=700>
	<tr>
		<td align=center height=5></td>
	</tr>
	<tr>
	        <td align=center class="menuTitle"><b>חיפוש מתקדם בעברית - יצירות של מלחינים ישראלים, שירים עבריים ומוסיקה יהודית</b></td>
	</tr>
	<tr>
		<td align=center height=10></td>
	</tr>
</table>

<!-- table fields. When clicking on the index button, openModal() is called. -->
<table border=1 align=center width=500 class="dataTable">
	<tr>
		<td colspan=2 class="bigTitle" align=center><b>חיפוש מתקדם</b></td>
	</tr>
	<tr>
		<td align=center dir=rtl>
			<select name="media_id">
			<option value=""> הכל
			<?php
			// Query for creating list (selectbox) of media items

			$qry = "select id,heb_name from media where deleted <> 1";
			$resultSet = mysql_query($qry);
			$num_rows = mysql_num_rows($resultSet);

			for($i = 0; $i < $num_rows; $i++)
			{
			    $heb_name = new_mysql_result($resultSet, $i, "heb_name");
			    $id = new_mysql_result($resultSet, $i, "id");
			    echo "<option value=$id> $heb_name";
			}
			?>
			</select>
		</td>
		<td align=right>מדיה</td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="composer" dir=rtl>
		</td>
		<td align=right>
		    <table border=0 bordercolor=black align=center width=100% class="dataTable">
			    <tr>
					<td align=center width=20>
						<a href="javascript: openModal(6);"><img src="images/indexH.gif" border=0></a>
					</td>
					<td align=right>
						מלחין
					</td>
				</tr>
		     </table>
		</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="composition_title" dir=rtl>
		</td>
		<td align=right>(כותר (יצירה, שיר</td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="publisher" dir=rtl>
		</td>
		<td align=right>
            <table border=0 bordercolor=black align=center width=100% class="dataTable">
	        	<tr>
					<td align=center width=20>
						<a href="javascript: openModal(7);"><img src="images/indexH.gif" border=0></a>
					</td>
					<td align=right>
						מוציא לאור
					</td>
	            </tr>
             </table>
        </td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="year" dir=rtl>
		</td>
		<td align=right>שנה </td>
	</tr>
	<tr>
		<td colspan=2>
		    <table border=0 bordercolor=black align=center width=500 class="dataTable">
				<tr>
					<td align=right colspan=2><b>ביצוע</b></td>
				</tr>
				<tr>
					<td align=center colspan=2>
						<table border=0 bordercolor=black align=center class="dataTable">
							<tr>
								<td align=center>
									<input type=text name="solist" dir=rtl>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(1);"><img src="images/indexH.gif" border=0></a>
											</td>
											<td align=right>סולן</td>
										</tr>
									</table>
								</td>
							</tr>
                            <tr>
								<td align=center>
									<input type=text name="performance_group" dir=rtl>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(2);"><img src="images/indexH.gif" border=0></a>
											</td>
											<td align=right>קבוצה מבצעת  </td>
										</tr>
									</table>
								</td>
							</tr>
				            <tr>
								<td align=center>
									<input type=text name="orchestra" dir=rtl>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(3);"><img src="images/indexH.gif" border=0></a>
											</td>
											<td align=right>תזמורת / מקהלה</td>
										</tr>
									</table>
								</td>
							</tr>

				            <tr>
								<td align=center>
									<input type=text name="conductor" dir=rtl>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(4);"><img src="images/indexH.gif" border=0></a>
											</td>
											<td align=right> מנצח </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
    	</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="series" dir=rtl>
		</td>
		<td align=right>
	        <table border=0 bordercolor=black align=center width=100% class="dataTable">
	        	<tr>
					<td align=center width=20>
						<a href="javascript: openModal(10);"><img src="images/indexH.gif" border=0></a>
					</td>
			         <td align=right>
			         	סדרה
			         </td>
	         	</tr>
	        </table>
        </td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="subject" dir=rtl>
		</td>
		<td align=right>
	        <table border=0 bordercolor=black align=center width=100% class="dataTable">
	        	<tr>
					<td align=center width=20>
						<a href="javascript: openModal(5);"><img src="images/indexH.gif" border=0></a>
					</td>
			         <td align=right>
			         	נושא
			         </td>
	         	</tr>
	        </table>
        </td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="item_second_title" dir=rtl>
		</td>
		<td align=right>כותר פריט</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="collection" dir=rtl>
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						<a href="javascript: openModal(8);"><img src="images/indexH.gif" border=0></a>
					</td>
					<td align=right>
						תרומה
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan=2 align=center>
			<input type=submit class="recordTitle" value="     שלח     ">
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
