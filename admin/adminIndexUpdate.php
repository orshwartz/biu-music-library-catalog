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

function checkValueExists ($db_column_name, $value)
{
	$exists = false;

	if ( isset($db_column_name) && ($db_column_name != "")
		& isset($value) & ($value != ""))
	{
		$qry = "select " . $db_column_name . " from records where " . $db_column_name . "='" . $value . "'" ;
		$resultSet = mysql_query($qry) ;
		if ($resultSet != FALSE)
			$exists = (mysql_num_rows ($resultSet) > 0) ? true : false;
	}

	return $exists ;
}

function updateValue ($db_column_name, $old_value, $new_value)
{
	$qry = "update records set " . $db_column_name . "='" . $new_value . "' where " . $db_column_name . "='" . $old_value . "'";
	$resultSet = mysql_query($qry);
}

function alertValueNotExists ($index_type_name, $value)
{
	global $ENGLISH, $lang_terms ;

	$confirm_msg = str_replace("<index_type>", $lang_terms[$index_type_name][$ENGLISH], $lang_terms['alertSourceIndexNotExists'][$ENGLISH]) ;
	$confirm_msg = str_replace("<source_index>", $value , $confirm_msg) ;
	echo "<script> alert (\"" . $confirm_msg . "\") </script>";
}

function confirmOverwrite($index_type_name, $value)
{
	global $ENGLISH, $lang_terms ;

	$confirmed = false;
	$confirm_msg = str_replace("<index_type>", $lang_terms[$index_type_name][$ENGLISH], $lang_terms['confirmUpdateIndexToExisting'][$ENGLISH]) ;
	$confirm_msg = str_replace("<existing_index>", $value, $confirm_msg) ;
	?>

	<script>
		if ( confirm (  <?php echo "\"" . $confirm_msg . "\"";?> ))
			<?php $confirmed = true ;?>
	</script>

	<?
	echo $confirmed;
	return $confirmed;
}

function updateIndexGroup($indexTypeName, $columnNames, $oldValue, $newValue)
{
	//$grp = { "solist", "solist2", "solist3" } ;
	$exists = false;
	$overwriteStatus = 0 ;
	$numUpdated = 0;

	if (isset($oldValue) && ($oldValue != ""))
	{
		for ( $i = 0 ; $i < count($columnNames) ; $i ++ )
		{
			$update = true;
			$tmpExists = false;
			//echo "old = " . $oldValue . "<br>";
			if ( $tmpExists = checkValueExists ($columnNames[$i], $oldValue))
			{
				$exists = $exists || $tmpExists ;
				if ( $overwriteStatus != 1)
					if (checkValueExists($columnNames[$i], $newValue))
					{
						if ( $overwriteStatus == 0)
						{
							if (confirmOverwrite($indexTypeName, $newValue))
								$overwriteStatus = 1;
							else
							{
								$update = false;
								$overwriteStatus = -1;
							}
						}
						else
							$update = false;
					}
			}
			else
				$update = false;


			if ($update)
			{
				$numUpdated ++ ;
				updateValue($columnNames[$i], $oldValue, $newValue);
			}
		}

		if ( ! $exists)
			alertValueNotExists ($indexTypeName, $oldValue);
	}

	return $numUpdated ;
}

function updateIndex($indexTypeName, $columnName, $oldValue, $newValue)
{
	return updateIndexGroup($indexTypeName, array($columnName), $oldValue, $newValue);
}

/*
// updates the index in database
function updateIndex ($db_column_name, $index_type_name, $old_value, $new_value, $checkForSourceExisting, $overwriteMode)
{
    global $ENGLISH, $lang_terms ;

    $toUpdate = 1;
    if (isset($new_value) && ($new_value != ""))
	{
        // search the database for all old value occurences
		$qry = "select " . $db_column_name . " from records where " . $db_column_name . "='" . $old_value . "'" ;
        $resultSet = mysql_query($qry) ;
        $confirm_msg = str_replace("<index_type>", $lang_terms[$index_type_name][$ENGLISH], $lang_terms['alertSourceIndexNotExists'][$ENGLISH]) ;
        $confirm_msg = str_replace("<source_index>", $old_value , $confirm_msg) ;

		if (($resultSet != false) && (mysql_num_rows ($resultSet) == 0))
		{
			$toUpdate = -1 ;
			// if there's no such old value, alert
	        if ($checkForSourceExisting)
	            echo "<script> alert (\"" . $confirm_msg . "\") </script>";
        }
		else if ($overwriteMode != 2)
		{
        	// check if the new value exists in database
            $qry = "select " . $db_column_name . " from records where " . $db_column_name . "='" . $new_value . "'" ;
            $resultSet = mysql_query($qry) ;
            if (($resultSet != false) && (mysql_num_rows ($resultSet) > 0)) {
				// if we try to replace the old value with an existing one, confirm
				// with user.

				if ($overwriteMode == -2)
					return -2;
				$toUpdate = 2 ;
                $confirm_msg = str_replace("<index_type>", $lang_terms[$index_type_name][$ENGLISH], $lang_terms['confirmUpdateIndexToExisting'][$ENGLISH]) ;
                $confirm_msg = str_replace("<existing_index>", $new_value, $confirm_msg) ;
                ?>
				<!--<script>
					if (! confirm (  <?php echo "\"" . $confirm_msg . "\"";?> ))
						<?php $toUpdate = -2 ;?>
				</script>
				-->
				<?/*php
            }
        }
    }
	else
        $toUpdate = 0 ;

    if ($toUpdate > 0) {	// all is fine, or user confirmed the change,
    					// update the index with the new value.
        $qry = "update records set " . $db_column_name . "='" . $new_value . "' where " . $db_column_name . "='" . $old_value . "'";
        $resultSet = mysql_query($qry);
    }

    return $toUpdate ;
}*/

function addUpdated ($currUpdateStatus, $updateRes)
{
	//return ($updateRes == -1) ? -1 : $currUpdateStatus ;
	if ($updateRes == 0)
		return $currUpdateStatus;

	if (($currUpdateStatus * $updateRes < 0) || ($currUpdateStatus == 3))
		return 3;

	return $updateRes ;
}

function getOverwriteExistingFromStatus($status)
{
	return (abs($status) == 2) ? $status : 0 ;
}


?>
<!-- This file displays the update index screen and allows us to update
	an index (which means, it would be replaced in all places in database at once).-->

<html>
<head>

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

if ($showmsg)
{
	displaySessionMsg() ;
}

// second time we call the page - after we clicked the "update index" button.
if (isset($action) && ($action == "update"))
{
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

	//echo "heb = " .$hebrew_composer . "<br>";

	//$updateStatus = 0 ;

	// get all field values and update each one.
	// you can update more than one index at one button click.
    //$updateStatus = addUpdated($updateStatus, updateIndex("composer", "composerInEnglish", $composer, $new_composer, true, true));
    //$updateStatus = addUpdated($updateStatus, updateIndex("hebrew_composer", "composerInHebrew", $hebrew_composer, $new_hebrew_composer, true, true)) ;
    //$updateStatus = addUpdated($updateStatus, updateIndex("series", "series", $series, $new_series, true, true)) ;
    //$updateStatus = addUpdated($updateStatus, updateIndex("composition_formal_name", "compositionFormalName", $composition_formal_name, $new_composition_formal_name, true, true)) ;
    //$updateStatus = addUpdated($updateStatus, updateIndex("publisher", "publisher", $publisher, $new_publisher, true, true)) ;

	//$grp = { "solist", "solist2", "solist3" } ;

/*
	$indexTypeNames = array ("composer" => "composerInEnglish",
							"hebrew_composer" => "composerInHebrew",
							"series" => "series",
							"composition_formal_name" => "compositionFormalName",
							"publisher" => "publisher",
							"solist" => array("solist", "solist2", "solist3"),
							"performance_group" => array("performanceGroup", "performanceGroup2", "performanceGroup3"),
							"orchestra" => array("orchestra", "orchestra2", "orchestra3"),
							"conductor" => array("conductor", "conductor2", "conductor3"),
							"collection" => "collection",
							"subject" => array("subject", "subject2", "subject3"));

	$oldNewValues = array ($composer => $new_composer,
							$hebrew_composer => $new_hebrew_composer,
							$series => $new_series,
							$composition_formal_name => $new_composition_formal_name,
							$publisher => $new_publisher,
							$solist => $new_solist,
							$performance_group => $new_performance_group,
							$orchestra => $new_orchestra,
							$conductor => $new_conductor,
							$collection => $new_collection,
							$subject => $new_subject) ;
*/
	$indexTypeNames = array ("composer",
							"hebrew_composer",
							"series",
							"composition_formal_name",
							"publisher",
							"solist",
							"performance_group",
							"orchestra",
							"conductor",
							"collection",
							"subject");

	$dbColumnNames = array ("composer",
							"hebrew_composer",
							"series",
							"composition_formal_name",
							"publisher",
							array("solist", "solist2", "solist3"),
							array("performance_group", "performance_group2", "performance_group3"),
							array("orchestra", "orchestra2", "orchestra3"),
							array("conductor", "conductor2", "conductor3"),
							"collection",
							array("subject", "subject2", "subject3"));

	$oldValues = array ($composer,
						$hebrew_composer,
						$series,
						$composition_formal_name,
						$publisher,
						$solist,
						$performance_group,
						$orchestra,
						$conductor,
						$collection,
						$subject);

	$newValues = array ($new_composer,
						$new_hebrew_composer,
						$new_series,
						$new_composition_formal_name,
						$new_publisher,
						$new_solist,
						$new_performance_group,
						$new_orchestra,
						$new_conductor,
						$new_collection,
						$new_subject);

	//echo "heb2 = " .$oldNewValues[$hebrew_composer];


	$updatableNum = 0 ;
	$updatedNum = 0 ;

	for ($i = 0 ; $i < count($indexTypeNames) ; $i ++ )
	{
		$indexTypeName = $indexTypeNames[$i];
		$dbColumnName = $dbColumnNames[$i];
		$oldValue = $oldValues[$i];
		$newValue = $newValues[$i];

		if (isset ($oldValue) && ($oldValue != ""))
		{
			//echo "value in: " . $indexTypeName . ": old = " . $oldValue . " , new =  " . $newValue . "<br>";
			if (is_array($dbColumnName))
				$updatedNum += (updateIndexGroup($indexTypeName, $dbColumnName, $oldValue, $newValue) > 0) ? 1 : 0 ;
			else
				$updatedNum += (updateIndex($indexTypeName, $dbColumnName, $oldValue, $newValue) > 0) ? 1 : 0 ;
			$updatableNum ++ ;
		}
		else
		{
			//echo "no value in: ". $indexTypeName . "<br>" ;
		}
		//sleep(2);
	}
/*
	$updateNum += updateIndex("composer", "composerInEnglish", $composer, $new_composer);
	$updateNum += updateIndex("hebrew_composer", "composerInHebrew", $hebrew_composer, $new_hebrew_composer) ;
	$updateNum += updateIndex("series", "series", $series, $new_series) ;
	$updateNum += updateIndex("composition_formal_name", "compositionFormalName", $composition_formal_name, $new_composition_formal_name) ;
	$updateNum += updateIndex("publisher", "publisher", $publisher, $new_publisher);

	$updateNum += updateIndexGroup("solist", array("solist", "solist2", "solist3"), $solist, $new_solist);
	$updateNum += updateIndexGroup("performance_group", array("performanceGroup", "performanceGroup2", "performanceGroup3") , $performance_group, $new_performance_group) ;
	$updateNum += updateIndexGroup("orchestra", array("orchestra", "orchestra2", "orchestra3") , $orchestra, $new_orchestra) ;
	$updateNum += updateIndexGroup("conductor", array("conductor", "conductor2", "conductor3") , $conductor, $new_conductor) ;

	$updateNum += updateIndex("collection", "collection", $collection, $new_collection);

	$updateNum += updateIndexGroup("subject", array("subject", "subject2", "subject3") , $subject, $new_subject) ;
*/
/*
	if (isset($solist) && ($solist != ""))
	{
		// on fields who have 3 colums (solist 1, 2, 3, etd)
		//don't forget to search the other columns for this value as well
		$res1 = updateIndex("solist", "solist", $solist, $new_solist, true, true);
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res2 = updateIndex("solist2", "solist", $solist, $new_solist, $res1 != -1, getOverwriteExistingFromStatus($res1));
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res = updateIndex("solist3", "solist", $solist, $new_solist, ($res1 != -1) && ($res2 != -1), getOverriteExistingFromStatus($res2));
		$updateStatus = addUpdated($updateStatus, $res) ;
	}

	if (isset($performance_group) && ($performance_group != ""))
	{
		$res1 = updateIndex("performance_group", "performanceGroup", $performance_group, $new_performance_group, true, true);
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res2 = updateIndex("performance_group2", "performanceGroup", $performance_group, $new_performance_group, $res1 != -1, getOverwriteExistingFromStatus($res1)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res = updateIndex("performance_group3", "performanceGroup", $performance_group, $new_performance_group, ($res1 != -1) && ($res2 != -1), getOverwriteExistingFromStatus($res2)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
	}

	if (isset($orchestra) && ($orchestra != ""))
	{
		$res1 = updateIndex("orchestra", "orchestra", $orchestra, $new_orchestra, true, true) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res2 = updateIndex("orchestra2", "orchestra", $orchestra, $new_orchestra, $res1 != -1, getOverwriteExistingFromStatus($res1)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res = updateIndex("orchestra3", "orchestra", $orchestra, $new_orchestra, ($res1 != -1) && ($res2 != -1), getOverwriteExistingFromStatus($res2)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
	}

	if (isset($conductor) && ($conductor != ""))
	{
		$res1 = updateIndex("conductor", "conductor", $conductor, $new_conductor, true, true);
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res2 = updateIndex("conductor2", "conductor", $conductor, $new_conductor, $res1 != -1, getOverwriteExistingFromStatus($res1)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res = updateIndex("conductor3", "conductor", $conductor, $new_conductor, ($res1 != -1) && ($res2 != -1), getOverwriteExistingFromStatus($res2)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
	}

    $updateStatus = addUpdated($updateStatus, updateIndex("collection", "collection", $collection, $new_collection, true, true));

	if (isset($subject) && ($subject != ""))
	{
		$res1 = updateIndex("subject", "subject", $subject, $new_subject, true, true);
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res2 = updateIndex("subject2", "subject", $subject, $new_subject, $res1 != -1, getOverwriteExistingFromStatus($res1)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
		$res = updateIndex("subject3", "subject", $subject, $new_subject, ($res1 != -1) && ($res2 != -1), getOverwriteExistingFromStatus($res2)) ;
		$updateStatus = addUpdated($updateStatus, $res) ;
	}
*/
	if ($updatedNum > 0)
	{
		if ($updatedNum == $updatableNum)
			setSessionMsg("האינדקסים עודכנו בהצלחה", 0) ;
		else
			setSessionMsg("חלק מהאינדקסים עודכנו בהצלחה", 0);
	}

    echo "<script>document.location='adminIndexUpdate.php?" . htmlspecialchars(SID) . "&showmsg=1'</script>";
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
<br><br>
<!-- back and forward navigation arrows -->
<a href="javascript:history.forward();"><img src="../images/forward_arrow_ani.gif" alt="לעמוד הבא" border=0></a><img src="../images/blank.gif"><a href="javascript:history.back();"><img src="../images/back_arrow_ani.gif" alt="לעמוד הקודם" border=0></a>
</center>

</body>
</html>