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
	<title>מערכת חיפוש נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

	<script>
		function openModal(code)
		{
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
		<td colspan=2 class="bigTitle" align=center><b>Advanced Search</b></td>
	</tr>
	<tr>
		<td align=left>Media</td>
		<td align=center dir=ltr>
			<select name="media_id">
			<option value=""> All
			<?php
			// Query for creating list (selectbox) of media items

			$qry = "select id,eng_name from media where deleted <> 1";
			$resultSet = mysql_query($qry);
			$num_rows = mysql_num_rows($resultSet);

			for($i = 0;$i < $num_rows;$i++) {
			    $eng_name = new_mysql_result($resultSet, $i, "eng_name");
			    $id = new_mysql_result($resultSet, $i, "id");
			    echo "<option value=$id> $eng_name";
			}
			?>
			</select>
		</td>
	</tr>
    <tr>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=left>Composer	</td>
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
		<td align=left>Title (Composition, Song) </td>
		<td align=center>
			<input type=text name="composition_title">
		</td>
	</tr>
	<tr>
	    <td align=right>
	        <table border=0 bordercolor=black align=center width=100% class="dataTable">
			    <tr>
					<td align=left>
					Publisher
					</td>
					<td align=center width=20>
					  <a href="javascript: openModal(7);"><img src="images/indexE.gif" border=0></a>
					</td>
				</tr>
	         </table>

           	 <td align=center>
                  <input type=text name="publisher">
             </td>
    	</td>
	</tr>
	<tr>
		<td align=left>Year </td>
		<td align=center>
			<input type=text name="year">
		</td>
	</tr>
	<tr>
	  <td colspan=2>
		<table border=0 bordercolor=black align=center width=500 class="dataTable">
			<tr>
			<td align=left colspan=2><b>Performance</b></td>
			</tr>
			<tr>
				<td align=center colspan=2>
					<table border=0 bordercolor=black align=center class="dataTable">
					<tr>
						<td>
							<table border=0 bordercolor=black width=100% class="dataTable">
								<tr>
									<td align=left>Soloist</td>
									<td align=center width=20>
										<a href="javascript: openModal(1);"><img src="images/indexE.gif" border=0></a>
									</td>
								</tr>
							</table>
						</td>

						<td align=center>
							<input type=text name="solist">
						</td>
					</tr>
					<tr>
						<td>
							<table border=0 bordercolor=black width=100% class="dataTable">
								<tr>
									<td align=left>Performance group </td>
									<td align=center width=20>
										<a href="javascript: openModal(2);"><img src="images/indexE.gif" border=0></a>
									</td>
								</tr>
							</table>
						</td>
						<td align=center>
							<input type=text name="performance_group">
						</td>
					</tr>

					<tr>
						<td>
							<table border=0 bordercolor=black width=100% class="dataTable">
								<tr>
									<td align=left>Orchestra</td>
									<td align=center width=20>
										<a href="javascript: openModal(3);"><img src="images/indexE.gif" border=0></a>
									</td>
								</tr>
							</table>
						</td>
						<td align=center>
							<input type=text name="orchestra">
						</td>
					</tr>

					<tr>
						<td>
							<table border=0 bordercolor=black width="100%" class="dataTable">
								<tr>
									<td align=left> Conductor </td>
									<td align=left width=20>
										<a href="javascript: openModal(4);"><img src="images/indexE.gif" border=0></a>
									</td>
								</tr>
							</table>
						</td>
						<td align=center>
							<input type=text name="conductor">
						</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align=right>
		        <table border=0 bordercolor=black align=center width=100% class="dataTable">
		         <tr>
		              <td align=left>
		              Subject
		              </td>
		              <td align=center width=20>
		                  <a href="javascript: openModal(5);"><img src="images/indexE.gif" border=0></a>
		              </td>
		         </tr>
		         </table>
		</td>
		<td align=center>
		    <input type=text name="subject">
		</td>
	</tr>

	<tr>
	    <td align=right>
        	<table border=0 bordercolor=black align=center width=100% class="dataTable">
            <tr>
                  <td align=left>
                  Donation
                  </td>
                  <td align=center width=20>
                      <a href="javascript: openModal(8);"><img src="images/indexE.gif" border=0></a>
                  </td>
             </tr>
             </table>

		</td>
        <td align=center>
          <input type=text name="collection">
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
<!--P-->
<br>
<center>
<hr width=500>
<br><br>
</center>

</body>
</html>
