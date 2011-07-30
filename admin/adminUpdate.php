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
// alerts and session messages display
include_once('../common.php');

?>
<!-- This file, gives the administrator ability
	to update or delete the record item that have been inserted into the databse.
	Also, there is a possibility to create a new record, based on the data
 	in the record that currently opened in the "edit" mode.
	When updating, first we need to select an item number (Searchbyitemno.php)
	And so we get the results with an "update" or "delete" links. When clicking
	those links, this page is called to display the update tables or for deleting.
	 -->
<html>
<head>
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">
	<title>מערכת הזנת נתונים</title>
   	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

	<script language="JavaScript">

        function openModal(code)
        {
        	// opens a generic dialog with a list of requested group
			// (Composers, Soloist,Performance group, Orchestra, Conductor or Subject)
          	if (code==14)	// english composer, default display is english
        		var val = window.open("../indexWin.php?lang=en&let=A&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
         	else			// others, default is hebrew
                var val = window.open("../indexWin.php?lang=heb&let=א&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
        }

        // update item: validates fields and if all is well resends it to page to update.
        function updateItem()
        {
        	// change /r/ns to <br>s in notes so they'll display correctly.
			var notes	 =  saveEOL (document.searchForm.notes.value) ;

			//validates the values of record form
			if(document.searchForm.media_id.value != "")
            {
            	if(document.searchForm.media_id.value != "-1")
                {
                	if( ((!isNaN(document.searchForm.year.value)) && ( parseInt(document.searchForm.year.value) > 1900) && ( parseInt(document.searchForm.year.value) < 2155)) || (document.searchForm.year.value == "") )
                    {
                    	// everything is okay, send it to page with confirm=true
                    	document.location.href="adminUpdate.php"+document.location.search+"&confirm=true"+
                              "&media_id="+document.searchForm.media_id.value+
                              "&old_item_no="+document.searchForm.old_item_no.value+
                              "&item_no="+document.searchForm.item_no.value+
                              "&publisher="+document.searchForm.publisher.value+
                              "&publisher_place="+document.searchForm.publisher_place.value+
                              "&year="+document.searchForm.year.value+
                              "&notes="+notes+
                              "&series="+document.searchForm.series.value+
                              "&collection="+document.searchForm.collection.value+
                              "&item_second_title="+document.searchForm.item_second_title.value;
                    }
                    else	// year isn't legal
      			    	alert(".הערך של שנה לא חוקי\n. ערך חוקי הוא מספר בין 1901 ל-2155") ;
                }
                else	// media isn't legal
                	alert("המדיה לא חוקית, נא לבחור מדיה אחרת");
			}
            else	// no media chosen
				alert("נא לבחור את המדיה");
        }

		// update piece: validates and if all is well resends is to page
		// with confirm=true to update.
		function update()
        {
      	  	  if( (document.searchForm.composition_title.value !="") && (document.searchForm.composer.value !="") && (document.searchForm.composer2.value !="") )
              {
              		document.location.href="adminUpdate.php"+document.location.search+"&confirm=true"+
                      "&composer="+document.searchForm.composer.value+
                      "&hebrew_composer="+document.searchForm.composer2.value+
                      "&composition_formal_name="+document.searchForm.composition_formal_name.value+
                      "&second_author="+document.searchForm.second_author.value+
                      "&composition_title="+document.searchForm.composition_title.value+
                      "&solist="+document.searchForm.solist.value+
                      "&solist2="+document.searchForm.solist2.value+
                      "&solist3="+document.searchForm.solist3.value+
                      "&performance_group="+document.searchForm.performance_group.value+
                      "&performance_group2="+document.searchForm.performance_group2.value+
                      "&performance_group3="+document.searchForm.performance_group3.value+
                      "&orchestra="+document.searchForm.orchestra.value+
                      "&orchestra2="+document.searchForm.orchestra2.value+
                      "&orchestra3="+document.searchForm.orchestra3.value+
                      "&conductor="+document.searchForm.conductor.value+
                      "&conductor2="+document.searchForm.conductor2.value+
                      "&conductor3="+document.searchForm.conductor3.value+
                      "&subject="+document.searchForm.subject.value+
                      "&subject2="+document.searchForm.subject2.value+
                      "&subject3="+document.searchForm.subject3.value;
      		  	}
	          	else	// some values weren't filled
      		    	alert("נא למלא את הכותר, שם המלחין בלועזית ובעברית");
		}

		// delete an item or a piece. confirm with user first.
        function deleteit()
        {
        	if (confirm("? הפריט עומד להימחק מבסיס הנתונים. להמשיך"))
            	document.location.href="adminUpdate.php"+document.location.search+"&confirm=true";
            else
                document.location.href="adminUpdate.php"+document.location.search+"&confirm=false";
        }

	</script>

</head>
<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">

<?php
$action = &$_GET['action'];
$display = &$_GET['display'];
$confirm = &$_GET['confirm'];
$mode = &$_GET['mode'];
$id = &$_GET['id'];
$item_no = &$_GET['item_no'];
$showmsg = &$_GET['showmsg'];

if ($showmsg)
    displaySessionMsg() ;

if (!isset($mode)) {
    ?>

	<!-- if we reached this page from the button in navbar, prompt
		user to choose action. -->
	<center><br><br>
	<table border=0 bordercolor=black align=center width="100%"">
	   	<tr>
			<td class="menuTitle" align=center height=10>
	   			<b>בחר בפעולה שברצונך לבצע<br><Br></b>
	   		</td>
	   	</tr>
	</table>
	</center>

  <center>
  <!-- update button -->
  <a href="SearchByItemNo.php?action=update"><img src="../images/update_item.gif" border=0></a>
  <BR><BR>
  <!-- delete button -->
  <a href="SearchByItemNo.php?action=delete"><img src="../images/del_item.gif" border=0></a>
  <BR><BR>
  <!-- index update button -->
  <a href="adminIndexUpdate.php"><img src="../images/index_update.gif" border=0></a>
  </center>
<?php
// if delete and confirm is false
} else if (isset($mode) && ($mode == "delete") && (isset($confirm)) && ($confirm == "false")) {
    // if this is a piece
	if (isset($id)) { // find the associated item_no
            // find the item number associated with this track
        $qry = "select item_no from records where id ='$id'";
        $resultSet = mysql_query($qry);
        $num_rows = mysql_num_rows($resultSet);
        if ($num_rows != 0)
            $item_no = new_mysql_result($resultSet, 0, "item_no");
    }
    // don't delete piece/item no, but go back to the results page
	// and display the item.
    echo "<script>document.location='adminResults.php?item_no=$item_no&action=$action&mode=$mode&display=$display'</script>";
} else if (isset($mode) && ($mode == "delete") && (!isset($confirm))) {
	// if delete and user still hasn't confirmed
    // requests administrator confirmation about deleting the content
    echo "<script>deleteit();</script>";
} else if (isset($mode) && ($mode == "delete") && (isset($confirm)) && ($confirm == "true")) {
	// if delete and user confirmed
    if (isset($id)) { // we clicked a link, delete one piece
        // find the item number associated with this piece
        $qry = "select item_no from records where id ='$id'";
        $resultSet = mysql_query($qry);
        $num_rows = mysql_num_rows($resultSet);
        if ($num_rows != 0)
            $item_no = new_mysql_result($resultSet, 0, "item_no");

        $qry = "delete from records where id =" . $id;
    } else if (isset($item_no)) // we clicked the "delete item" button
								// delete whole item
        $qry = "delete from records where item_no ='$item_no'";

    $resultSet = mysql_query($qry);
    $confirm = false;

    if ($resultSet != false)
        setSessionMsg("הפריט נמחק בהצלחה", 0) ;
    else
        setSessionMessageDatabaseError() ;

    if (isset($id)) { // one piece
            echo "<script>document.location='adminResults.php?item_no=$item_no&action=$action&mode=$mode&display=$display&showmsg=1'</script>";
    }
    else if (isset($item_no)) // whole item
        echo "<script>document.location='adminResults.php?item_no=$item_no&action=$action&mode=$mode&display=$display&showmsg=1'</script>";
} else if (isset($mode) && ($mode == "updateItem") && (isset($confirm)) && ($confirm == "true")) {
	// update item (clicked "update item" button in results screen)
	// after user confirmation
    // Save the changed values of the record in the database
    $media_id = $_GET['media_id'];
    $item_no = $_GET['item_no'];
    $series = process_data($_GET['series']);
    $publisher = process_data($_GET['publisher']);
    $publisher_place = process_data($_GET['publisher_place']);
    $year = process_data($_GET['year']);
    $collection = process_data($_GET['collection']);
    $notes = process_data($_GET['notes']);
    $item_second_title = process_data($_GET['item_second_title']);

    $old_item_no = $_GET['old_item_no'];
    // to remove year from db, if empty field was sent
    if (!isset($year) || ($year == ""))
        $year = "null";

    $qry = "update records set media_id='" . $media_id . "',item_no='" . $item_no . "',series='" . $series . "',publisher='" . $publisher . "',publisher_place='" . $publisher_place . "',year=$year,collection='" . $collection . "',notes='" . $notes . "',item_second_title='" . $item_second_title . "' where item_no='" . $old_item_no . "'";
    $resultSet = mysql_query($qry);

    if ($resultSet != false)
        setSessionMsg("הפריט עודכן בהצלחה", 0) ;
    else
        setSessionMessageDatabaseError() ;
    echo "<script>document.location='adminResults.php?item_no=$item_no&action=$action&mode=$mode&display=$display&showmsg=1'</script>";
} else if (isset($mode) && ($mode == "update") && (isset($confirm)) && ($confirm == "true")) {
	// update one piece (clicked link in results screen)
	// after user confirmation
    // Save the changed values of the record in the database
    $composer = process_data($_GET['composer']);
    $hebrew_composer = process_data($_GET['hebrew_composer']);
    $second_author = process_data($_GET['second_author']);
    $composition_formal_name = process_data($_GET['composition_formal_name']);
    $composition_title = process_data($_GET['composition_title']);
    $solist = process_data($_GET['solist']);
    $solist2 = process_data($_GET['solist2']);
    $solist3 = process_data($_GET['solist3']);
    $performance_group = process_data($_GET['performance_group']);
    $performance_group2 = process_data($_GET['performance_group2']);
    $performance_group3 = process_data($_GET['performance_group3']);
    $orchestra = process_data($_GET['orchestra']);
    $orchestra2 = process_data($_GET['orchestra2']);
    $orchestra3 = process_data($_GET['orchestra3']);
    $conductor = process_data($_GET['conductor']);
    $conductor2 = process_data($_GET['conductor2']);
    $conductor3 = process_data($_GET['conductor3']);
    $subject = process_data($_GET['subject']);
    $subject2 = process_data($_GET['subject2']);
    $subject3 = process_data($_GET['subject3']);
    $id = $_GET['id'];

    $qry = "update records set second_author='" . $second_author . "',composer='" . $composer . "',composition_title='" . $composition_title . "',solist='" . $solist . "',solist2='" . $solist2 . "',solist3='" . $solist3 . "',performance_group='" . $performance_group . "',performance_group2='" . $performance_group2 . "',performance_group3='" . $performance_group3 . "',orchestra='" . $orchestra . "',orchestra2='" . $orchestra2 . "',orchestra3='" . $orchestra3 . "',conductor='" . $conductor . "',conductor2='" . $conductor2 . "',conductor3='" . $conductor3 . "',subject='" . $subject . "',subject2='" . $subject2 . "',subject3='" . $subject3 . "',hebrew_composer='" . $hebrew_composer . "', composition_formal_name='" . $composition_formal_name . "' where id=" . $id;
    $resultSet = mysql_query($qry);
    if ($resultSet != false)
        setSessionMsg("הפריט עודכן בהצלחה", 0) ;
    else
        setSessionMessageDatabaseError() ;

	$qry = "select item_no from records where id ='$id'";
    $resultSet = mysql_query($qry);
    $num_rows = mysql_num_rows($resultSet);
    if ($num_rows != 0)
        $item_no = new_mysql_result($resultSet, 0, "item_no");

	// and return to results page with showing the item records.
    echo "<script>document.location='adminResults.php?item_no=$item_no&action=$action&mode=$mode&display=$display&showmsg=1'</script>";
} else if (isset($mode) && (($mode == "update") || ($mode == "updateItem"))) {
	// update piece or item, before user confirmation - means we should
	// display the values in table and let him change and confirm.
    if ($mode == "update") { //one piece
        $id = $_GET['id'];
        $searchQry = "select * from records where id = " . $id;
    } else { // if ($mode == "updateItem"), whole item
            $item_no = &$_GET['item_no'];
        $searchQry = "select * from records where item_no = '" . $item_no . "'";
    }

    $result = mysql_query($searchQry) ;
    if ($result != false) {
        $item_no = new_mysql_result($result, 0, "item_no");
        $media_id = new_mysql_result($result, 0, "media_id");
        $composer = new_mysql_result($result, 0, "composer");
        $second_author = new_mysql_result($result, 0, "second_author");
        $composer2 = new_mysql_result($result, 0, "hebrew_composer");
        $composition_formal_name = new_mysql_result($result, 0, "composition_formal_name");
        $composition_title = new_mysql_result($result, 0, "composition_title");
        $publisher = new_mysql_result($result, 0, "publisher");
        $publisher_place = new_mysql_result($result, 0, "publisher_place");
        $year = new_mysql_result($result, 0, "year");
        $solist = new_mysql_result($result, 0, "solist");
        $solist2 = new_mysql_result($result, 0, "solist2");
        $solist3 = new_mysql_result($result, 0, "solist3");
        $performance_group = new_mysql_result($result, 0, "performance_group");
        $performance_group2 = new_mysql_result($result, 0, "performance_group2");
        $performance_group3 = new_mysql_result($result, 0, "performance_group3");
        $orchestra = new_mysql_result($result, 0, "orchestra");
        $orchestra2 = new_mysql_result($result, 0, "orchestra2");
        $orchestra3 = new_mysql_result($result, 0, "orchestra3");
        $conductor = new_mysql_result($result, 0, "conductor");
        $conductor2 = new_mysql_result($result, 0, "conductor2");
        $conductor3 = new_mysql_result($result, 0, "conductor3");
        $notes = new_mysql_result($result, 0, "notes");
        $series = new_mysql_result($result, 0, "series");
        $subject = new_mysql_result($result, 0, "subject");
        $subject2 = new_mysql_result($result, 0, "subject2");
        $subject3 = new_mysql_result($result, 0, "subject3");

        $item_second_title = new_mysql_result($result, 0, "item_second_title");
        $collection = new_mysql_result($result, 0, "collection");

        $notes = restoreEOL($notes) ;
    } else
        setSessionMessageDatabaseError() ;

    ?>

<!-- creates the update item/update piece table -->
<!-- table fields. When clicking on the index button, openModal() is called. -->
<form action='adminUpdate.php' name='searchForm' method=GET>
<table border=1 class='dataTable' bordercolor=black align=center width=500>
	<tr>
		<td colspan=2 class="bigTitle" align=center><b>עדכון פריט</b></td>
	</tr>
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>
    <?php
    if ($mode == "update") {	// one piece

        ?>
		<tr>
			<td align=center>
				<input type=text name="composer" value="<?php echo get_php_string($composer);?>" dir=ltr>
			</td>
			<td align=right>
				<table border=0 bordercolor=black align=center width=100% class="dataTable">
					<tr>
						<td align=center width=20>
							<a href="javascript: openModal(14);"><img src="../images/indexH.gif" border=0></a>
						</td>
						<td align=right>
							שם המלחין בלועזית
						</td>
					</tr>
				</table>
			</td>
 		</tr>
		<tr>
			<td align=center width="50%">
				<input type=text name="composer2" value="<?php echo get_php_string($composer2);?>" dir=rtl>
			</td>
			<td align=right>
				<table border=0 bordercolor=black align=center width=100% class="dataTable">
					<tr>
				 		<td align=center width=20>
				 			<a href="javascript: openModal(13);"><img src="../images/indexH.gif" border=0></a>
				 		</td>
				 		<td align=right>
							שם המלחין בעברית
				 		</td>
			 		</tr>
                </table>
			</td>
		</tr>
		<tr>
			<td align=center>
				<input type=text name="composition_formal_name" value="<?php echo get_php_string($composition_formal_name);?>" dir=ltr>
			</td>
			<td align=right>
				<table border=0 bordercolor=black align=center width=100% class="dataTable">
			 		<tr>
			 			<td align=center width=20>
			 				<a href="javascript: openModal(9);"><img src="../images/indexH.gif" border=0></a>
			 			</td>
			 			<td align=right>
				 			שם תקני של היצירה
			 			</td>
			 		</tr>
			 	</table>
			</td>
        </tr>
		<tr>
	    	<td align=center>
				<input type=text name="composition_title" value="<?php echo get_php_string($composition_title);?>" dir=ltr>
			</td>
			<td align=right>(כותר (יצירה, שיר</td>
		</tr>
		<tr>
			<td colspan=2>
            	<table border=0 bordercolor=black class='dataTable' align=center width=500>
					<tr>
						<td align=right colspan=2><b>ביצוע</b></td>
					</tr>
					<tr>
						<td align=center colspan=2>
							<table border=0 bordercolor=black align=center class='dataTable'>
								<tr>
									<td align=center>
										<input type=text name="solist" dir=ltr value="<?php echo get_php_string($solist);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(1);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>סולן</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="solist2" dir=ltr value="<?php echo get_php_string($solist2);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(15);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>סולן 2</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="solist3" dir=ltr value="<?php echo get_php_string($solist3);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(16);"><img src="../images/indexH.gif" border=0></a>
												</td>
										  		<td align=right>סולן 3</td>
											</tr>
										</table>
									</td>
								</tr>
                             	<tr>
									<td align=center>
										<input type=text name="performance_group" dir=ltr value="<?php echo get_php_string($performance_group);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(2);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>קבוצה מבצעת  </td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="performance_group2" dir=ltr value="<?php echo get_php_string($performance_group2);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(17);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>קבוצה מבצעת 2</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="performance_group3" dir=ltr value="<?php echo get_php_string($performance_group3);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(18);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>קבוצה מבצעת 3</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="orchestra" dir=ltr value="<?php echo get_php_string($orchestra);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(3);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>תזמורת / מקהלה</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="orchestra2" dir=ltr value="<?php echo get_php_string($orchestra2);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(19);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>תזמורת / מקהלה 2</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="orchestra3" dir=ltr value="<?php echo get_php_string($orchestra3);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(20);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>תזמורת / מקהלה 3</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="conductor" dir=ltr value="<?php echo get_php_string($conductor);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(4);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right> מנצח </td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="conductor2" dir=ltr value="<?php echo get_php_string($conductor2);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(21);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>מנצח 2</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td align=center>
										<input type=text name="conductor3" dir=ltr value="<?php echo get_php_string($conductor3);?>">
									</td>
									<td align=right>
										<table border=0 bordercolor=black align=center width=100% class="dataTable">
											<tr>
												<td align=center width=20>
													<a href="javascript: openModal(22);"><img src="../images/indexH.gif" border=0></a>
												</td>
												<td align=right>מנצח 3</td>
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
			<input type=text name="subject" dir=ltr value="<?php echo get_php_string($subject);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						<a href="javascript: openModal(5);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>נושא</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="subject2" dir=ltr value="<?php echo get_php_string($subject2);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						<a href="javascript: openModal(11);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>נושא 2</td>
				</tr>
			 </table>
		</td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="subject3" dir=ltr value="<?php echo get_php_string($subject3);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						 <a href="javascript: openModal(12);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>נושא 3</td>
				 </tr>
			</table>
		</td>
	 </tr>
	<tr>
		<td align=center>
			<input type=text name="second_author" dir=ltr value="<?php echo get_php_string($second_author);?>">
		</td>
		<td align=right>מחבר שותף</td>
	</tr>
    <tr>
		<td colspan=2 align=center>
        	<input type=button value="  עדכן  " onclick="javascript: update();">
		</td>
    </tr>

<?php } else if ($mode == "updateItem") { // whole item (diffrent table to display)
        ?>

      <!-- save the old item number-->
      <input type=hidden name="old_item_no" value="<?php echo get_php_string($item_no);?>">

        <tr>
			<td align=center>
				<input type=text name="item_no" dir=ltr value="<?php echo get_php_string($item_no);?>">
			</td>
			<td align=right>מס' פריט</td>
		</tr>

       <tr>
            <td align=center dir=rtl>
                <select name="media_id">
				<option value=""> -------
				<?php
		        // Create media types list
		        $qry = "select id,eng_name,heb_name from media where deleted <> 1";
		        $resultSet = mysql_query($qry);
		        if ($resultSet != false) {
		            $num_rows = mysql_num_rows($resultSet);
		            for($i = 0;$i < $num_rows;$i++) {
		                $eng_name = new_mysql_result($resultSet, $i, "eng_name");
		                $mediaid = new_mysql_result($resultSet, $i, "id");
		                echo "<option value=$mediaid";
		                if ($media_id == $mediaid)
		                    echo " SELECTED ";
		                echo "> $eng_name";
		            }
		        } else
		            setSessionMessageDatabaseError() ;

		        ?>
				</select>
	    	</td>
	    	<td align=right>מדיה</td>
		</tr>
		<tr>
	    	<td align=center>
				<input type=text name="publisher" dir=ltr value="<?php echo get_php_string($publisher);?>">
	    	</td>
	    	<td align=right>
		  		<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
						<td align=center width=20>
			    			<a href="javascript: openModal(7);"><img src="../images/indexH.gif" border=0></a>
						</td>
						<td align=right>מוציא לאור</td>
                  	</tr>
                </table>
        	</td>
        </tr>
		<tr>
			<td align=center>
				<input type=text name="publisher_place" dir=ltr value="<?php echo get_php_string($publisher_place);?>">
			</td>
			<td align=right>מקום הוצאה לאור</td>
		</tr>
		<tr>
			<td align=center>
				<input type=text name="year" dir=ltr value="<?php echo get_php_string($year);?>">
			</td>
			<td align=right>שנה </td>
		</tr>
        <tr>
			<td align=center>
				<textarea name="notes" dir=ltr scrolling=no rows='6' cols='40' wrap='soft' style='width: 100%;overflow:auto'><?php echo $notes ;/*new_mysql_result($result,0, "notes");*/?></textarea>
			</td>
			<td align=right>הערות</td>
		</tr>
		<tr>
			<td align=center>
				<input type=text name="series" dir=ltr value="<?php echo get_php_string($series);?>">
			</td>
			<td align=right>
				<table border=0 bordercolor=black align=center width=100% class="dataTable">
					<tr>
						<td align=center width=20>
							<a href="javascript: openModal(10);"><img src="../images/indexH.gif" border=0></a>
					 	</td>
					 	<td align=right>סדרה</td>
				 	</tr>
			 	</table>
			</td>
		</tr>
        <tr>
			<td align=center>
            	<input type=text name="item_second_title" dir=ltr value="<?php echo get_php_string($item_second_title);?>">
            </td>
		    <td align=right>כותר פריט</td>
        </tr>
		<tr>
			<td align=center>
				<input type=text name="collection" dir=ltr value = "<?php echo get_php_string($collection);?>" >
			</td>
			<td align=right>
				<table border=0 bordercolor=black align=center width=100% class="dataTable">
					<tr>
						<td align=center width=20>
							<a href="javascript: openModal(8);"><img src="../images/indexH.gif" border=0></a>
					 	</td>
					 	<td align=right>תרומה</td>
					</tr>
				</table>
			</td>
		</tr>
     	<tr>
			<td colspan=2 align=center>
        		<input type=button value="  עדכן  " onclick="javascript: updateItem();">
			</td>
    	</tr>

<?php }
    ?>
</form>
</table>


<?php
}

?>
<center>
<br><br>
<hr width=500>
</center>

</body>
</html>
