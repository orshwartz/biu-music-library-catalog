
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
// language definitions, for the display can be both in hebrew and in english
include_once('../lang.php');

// updates the index in database
function updateIndex ($db_column_name, $index_type_name, $old_value, $new_value, $checkForExisting)
{
    global $ENGLISH, $lang_terms ;

    static $toUpdate ;
    $toUpdate = true ;
    if (isset($new_value) && ($new_value != "")) {
        // search the database for all old value occurences
		$qry = "select " . $db_column_name . " from records where " . $db_column_name . "='" . $old_value . "'" ;
        $resultSet = mysql_query($qry) ;
        $confirm_msg = str_replace("<index_type>", $lang_terms[$index_type_name][$ENGLISH], $lang_terms['alertSourceIndexNotExists'][$ENGLISH]) ;
        $confirm_msg = str_replace("<source_index>", $old_value , $confirm_msg) ;
		// if there's no such old value, alert
        if (($resultSet != false) && (mysql_num_rows ($resultSet) == 0)) {
            echo "<script> alert (\"" . $confirm_msg . "\") </script>";
            $toUpdate = false ;
        } else if ($checkForExisting) {
        	// check if the new value exists in database
            $qry = "select " . $db_column_name . " from records where " . $db_column_name . "='" . $new_value . "'" ;
            $resultSet = mysql_query($qry) ;
            if (($resultSet != false) && (mysql_num_rows ($resultSet) > 0)) {
				// if we try to replace the old value with an existing one, confirm
				// with user.

				$toUpdate = true ;
               /* $confirm_msg = str_replace("<index_type>", $lang_terms[$index_type_name][$ENGLISH], $lang_terms['confirmUpdateIndexToExisting'][$ENGLISH]) ;
                $confirm_msg = str_replace("<existing_index>", $new_value, $confirm_msg) ;
                ?>
				<script>
					if (! confirm (  <?php echo "\"" . $confirm_msg . "\"";?> ))
						<?php $toUpdate = false ;?>
				</script>
				<?php*/
            }
        }
    } else
        $toUpdate = false ;

    if ($toUpdate) {	// all is fine, or user confirmed the change,
    					// update the index with the new value.
        $qry = "update records set " . $db_column_name . "='" . $new_value . "' where " . $db_column_name . "='" . $old_value . "'";
        $resultSet = mysql_query($qry);
    }

    return $toUpdate ;
}
?>
<!-- This file displays the update index screen and allows us to update
	an index (which means, it would be replaced in all places in database at once).-->
<html>
<head>
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">

	<title>מערכת הזנת נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

    <script>
        function openModal(code){
        	// opens a generic dialog with a list of requested group
		 	// (Composers, Soloist,Performance group, Orchestra, Conductor or Subject)
          	if (code==14)	// english composer, default display is english
        		var val = window.open("../indexWin.php?lang=en&let=A&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
         	else			// others, default is hebrew
                var val = window.open("../indexWin.php?lang=heb&let=א&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
        }

		// checks if all values were filled correctly and sends alerts if not.
       	function update()
        {
        	// if we clicked the "update index" button without selecting anything
        	// to update
           if( (document.searchForm.composer.value =="") &&(document.searchForm.composer2.value =="") && (document.searchForm.composition_formal_name.value =="") && (document.searchForm.solist.value =="") && (document.searchForm.performance_group.value =="") && (document.searchForm.orchestra.value =="") && (document.searchForm.conductor.value =="") && (document.searchForm.subject.value =="") && (document.searchForm.series.value =="") && (document.searchForm.publisher.value =="") && (document.searchForm.collection.value =="") )
               alert("נא לבחור אינדקס לעדכון");
           else
           {
           	   // if we chose an index to update but we didn't select a new value
      	  	  if( (document.searchForm.new_composer.value =="") && (document.searchForm.new_composer2.value =="") && (document.searchForm.new_composition_formal_name.value =="") && (document.searchForm.new_solist.value =="") && (document.searchForm.new_performance_group.value =="") && (document.searchForm.new_orchestra.value =="") && (document.searchForm.new_conductor.value =="") && (document.searchForm.new_subject.value =="") && (document.searchForm.new_series.value =="") && (document.searchForm.new_publisher.value =="") && (document.searchForm.new_collection.value =="") )
      		      alert("נא לבחור ערך חדש לעדכון");
	          else
                  	{
	                  	// disable button so we can't update twice by mistake
	                     document.searchForm.Bupdate.disabled=true;
	                     document.searchForm.action.value = "update";
	                     document.searchForm.submit();
      		  		}
            }
		}

    </script>

</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">
<?php

$showmsg = &$_GET['showmsg'];
$action = &$_GET['action'];
$msg = "";

// second time we call the page - after we clicked the "update index" button.
if (isset($action) && ($action == "update")) {
    $composer = process_data(&$_GET['composer']);
    $hebrew_composer = process_data(&$_GET['composer2']); // hebrew composer
    $series = process_data(&$_GET['series']);
    $composition_formal_name = process_data(&$_GET['composition_formal_name']);
    $publisher = process_data(&$_GET['publisher']);
    $solist = process_data(&$_GET['solist']);
    $performance_group = process_data(&$_GET['performance_group']);
    $orchestra = process_data(&$_GET['orchestra']);
    $conductor = process_data(&$_GET['conductor']);
    $collection = process_data(&$_GET['collection']);
    $subject = process_data(&$_GET['subject']);

    $new_composer = process_data(&$_GET['new_composer']);
    $new_hebrew_composer = process_data(&$_GET['new_composer2']); // hebrew composer
    $new_series = process_data(&$_GET['new_series']);
    $new_composition_formal_name = process_data(&$_GET['new_composition_formal_name']);
    $new_publisher = process_data(&$_GET['new_publisher']);
    $new_solist = process_data(&$_GET['new_solist']);
    $new_performance_group = process_data(&$_GET['new_performance_group']);
    $new_orchestra = process_data(&$_GET['new_orchestra']);
    $new_conductor = process_data(&$_GET['new_conductor']);
    $new_collection = process_data(&$_GET['new_collection']);
    $new_subject = process_data(&$_GET['new_subject']);

	// get all field values and update each one.
	// you can update more than one index at one button click.
    updateIndex("composer", "composerInEnglish", $composer, $new_composer, true) ;
    updateIndex("hebrew_composer", "composerInHebrew", $hebrew_composer, $new_hebrew_composer, true) ;
    updateIndex("series", "series", $series, $new_series, true) ;
    updateIndex("composition_formal_name", "compositionFormalName", $composition_formal_name, $new_composition_formal_name, true) ;
    updateIndex("publisher", "publisher", $publisher, $new_publisher, true) ;

    // on fields who have 3 colums (solist 1, 2, 3, etd)
	//don't forget to search the other columns for this value as well
    if (updateIndex("solist", "solist", $solist, $new_solist, true)) {
        updateIndex("solist2", "solist", $solist, $new_solist, false);
        updateIndex("solist3", "solist", $solist, $new_solist, false);
    }

    if (updateIndex("performance_group", "performanceGroup", $performance_group, $new_performance_group, true)) {
        updateIndex("performance_group2", "performanceGroup", $performance_group, $new_performance_group, false) ;
        updateIndex("performance_group3", "performanceGroup", $performance_group, $new_performance_group, false) ;
    }

    if (updateIndex("orchestra", "orchestra", $orchestra, $new_orchestra, true)) {
        updateIndex("orchestra2", "orchestra", $orchestra, $new_orchestra, false) ;
        updateIndex("orchestra3", "orchestra", $orchestra, $new_orchestra, false) ;
    }

    if (updateIndex("conductor", "conductor", $conductor, $new_conductor, true)) {
        updateIndex("conductor2", "conductor", $conductor, $new_conductor, false) ;
        updateIndex("conductor3", "conductor", $conductor, $new_conductor, false) ;
    }

    updateIndex("collection", "collection", $collection, $new_collection, true) ;

    if (updateIndex("subject", "subject", $subject, $new_subject, true)) {
        updateIndex("subject2", "subject", $subject, $new_subject, false) ;
        updateIndex("subject3", "subject", $subject, $new_subject, false) ;
    }
    setSessionMsg("האינדקס עודכן בהצלחה", 0) ;

    echo "<script>document.location='adminIndexUpdate.php?showmsg=1'</script>";
}

	if ($showmsg)
	{
		displaySessionMsg() ;
	}
?>

<!-- creates the index update table -->
<!-- table fields. When clicking on the index button, openModal() is called. -->
<table border=1 bordercolor=black align=center width=600 class="dataTable">
<form action="adminIndexUpdate.php" name="searchForm" method=GET>
<input type=hidden name="action">
		<tr>
			<td colspan=3 class="bigTitle" align=center><b>טבלת אינדקסים</b></td>
		</tr>
		<tr>
            <td align=center class="normalTitle"><b>ערך חדש</b></td>
			<td align=center class="normalTitle"><B>ערך קודם</b></td>
			<td align=center class="normalTitle"><b>אינדקס</b></td>
		</tr>
		<tr>
            <td align=center>
				<input type=text name="new_composer" dir=ltr>
	    	</td>

		    <td align=center>
			<input type=text name="composer" dir=ltr>
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
            <td align=center>
				<input type=text name="new_composer2" dir=rtl>
	    	</td>

			<td align=center>
				<input type=text name="composer2" dir=rtl>
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
				<input type=text name="new_composition_formal_name" dir=ltr>
			</td>

			<td align=center>
				<input type=text name="composition_formal_name" dir=ltr>
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
				<input type=text name="new_publisher" dir=ltr>
	    	</td>
			<td align=center>
				<input type=text name="publisher" dir=ltr>
			</td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
                     	<td align=center width=20>
                     		<a href="javascript: openModal(7);"><img src="../images/indexH.gif" border=0></a>
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
				<input type=text name="new_solist" dir=ltr>
	    	</td>

			<td align=center>
        		<input type=text name="solist" dir=ltr>
			</td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                    <tr>
                     	<td align=center width=20>
                     		<a href="javascript: openModal(1);"><img src="../images/indexH.gif" border=0></a>
                     	</td>
                     	<td align=right>
                     		סולן
                     	</td>
                    </tr>
                </table>
            </td>
		</tr>
		<tr>
            <td align=center>
				<input type=text name="new_performance_group" dir=ltr>
	    	</td>
			<td align=center>
				<input type=text name="performance_group" dir=ltr>
			</td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
                     	<td align=center width=20>
                     		<a href="javascript: openModal(2);"><img src="../images/indexH.gif" border=0></a>
                     	</td>
                     	<td align=right>
                     		קבוצה מבצעת
                     	</td>
                    </tr>
                </table>
            </td>
		</tr>
		<tr>
            <td align=center>
				<input type=text name="new_orchestra" dir=ltr>
	    	</td>

	      	<td align=center>
				<input type=text name="orchestra" dir=ltr>
			</td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
                     	<td align=center width=20>
                     		<a href="javascript: openModal(3);"><img src="../images/indexH.gif" border=0></a>
                     	</td>
                     	<td align=right>
                     		תזמורת / מקהלה
                     	</td>
                    </tr>
                </table>
        	</td>
		</tr>
        <tr>
        	<td align=center>
				<input type=text name="new_conductor" dir=ltr>
	    	</td>
            <td align=center>
     			<input type=text name="conductor" dir=ltr>
            </td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
                     	<td align=center width=20>
                     		<a href="javascript: openModal(4);"><img src="../images/indexH.gif" border=0></a>
                     	</td>
                     	<td align=right>
                     		מנצח
                     	</td>
                     </tr>
                </table>
            </td>
		</tr>
		<tr>
            <td align=center>
				<input type=text name="new_subject" dir=ltr>
	    	</td>
			<td align=center>
				<input type=text name="subject" dir=ltr>
			</td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
                    	<td align=center width=20>
                     		<a href="javascript: openModal(5);"><img src="../images/indexH.gif" border=0></a>
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
				<input type=text name="new_series" dir=ltr>
	    	</td>
			<td align=center>
				<input type=text name="series" dir=ltr>
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
				<input type=text name="new_collection" dir=ltr>
	    	</td>
			<td align=center>
				<input type=text name="collection" dir=ltr>
			</td>
			<td align=right>
            	<table border=0 bordercolor=black align=center width=100% class="dataTable">
                	<tr>
                     	<td align=center width=20>
                     		<a href="javascript: openModal(8);"><img src="../images/indexH.gif" border=0></a>
                     	</td>
                     	<td align=right>
                     		תרומה
                     	</td>
                    </tr>
                </table>
        	</td>
		</tr>
		<tr>
			<td colspan=3 align=center>
				<input type=button name="Bupdate" class="recordTitle" value="     עדכן     " onClick="javascript:update();">
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

