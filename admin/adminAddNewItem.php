<?php
session_start();

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">";

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
?>
<!-- This file displays the add new item table.
	It adds new items to data base, and also handles the copy item method -
	Either used from the navigation bar, or automatically after we add a new item. -->
<html>
<head>
	<script type="text/javascript" src="../autoComplete/js/jquery-1.6.4.js"></script>
	<script type='text/javascript' src="../autoComplete/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../autoComplete/js/jquery.autocomplete.css" />
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">
	<title>����� ���� ������</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

    <script language="javascript" type="text/javascript">
    
    	function openModal(code){
       		// opens a generic dialog with a list of requested group
			// (Composers, Soloist,Performance group, Orchestra, Conductor or Subject)
          if (code==14) // english composer, default display is english
        	var val = window.open("../indexWin.php?lang=en&let=A&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
         else			// others, default is hebrew
            var val = window.open("../indexWin.php?lang=heb&let=�&code="+code ,"modalDialog","height=500,width=350,status=no,scrollbars=yes,help=no,center=yes");
        }

		 // validates the item we try to add. if all is fine, submits the form.
         function addnew()
		 {
			// check the new item form fields for valid input
			if(document.searchForm.media_id.value != "")
			{
				  if( (document.searchForm.composition_title.value !="") && (document.searchForm.composer.value !="") && (document.searchForm.composer2.value !="") )
				  {
					  if ( ! isInputValid ( document.searchForm.item_no.value, <?php echo $lang_regex['item_no_expr'] ;?> ))
						  alert("���� ����� ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.composer.value, <?php echo $lang_regex['name_expr'][$ENGLISH] ;?> ))
						  alert("�� ������ ������� ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.composer2.value, <?php echo $lang_regex['name_expr'][$HEBREW] ;?> ))
						  alert("�� ������ ������ ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.composition_formal_name.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("��� ����� �� ������ ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.publisher_place.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� ������ ���� ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.year.value, <?php echo $lang_regex['year_expr'] ;?> ))
						  alert("��� ���� ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.solist.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� 1 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.solist2.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� 2 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.solist3.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� 3 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.performance_group.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ����� ����� 1 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.performance_group2.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ����� ����� 2 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.performance_group3.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ����� ����� 3 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.orchestra.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ������/����� 1 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.orchestra2.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ������/����� 2 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.orchestra3.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ������/����� 3 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.conductor.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� 1 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.conductor2.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� 2 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.conductor3.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ���� 3 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.series.value, <?php echo$lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ������ ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.subject.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("���� 1 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.subject2.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("���� 2 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.subject3.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("���� 3 ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.second_author.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ����� ����� ���� ����") ;
					  else if ( ! isInputValid ( document.searchForm.collection.value, <?php echo $lang_regex['name_expr'][$ALL_LANG] ;?> ))
						  alert("�� ����� ���� ����") ;

					  else
					  {
						  // don't let user click the button more than once (and add the item twice by mistake)
						  document.searchForm.BaddItem.disabled = true;
						  // all is fine, add item.
						  document.searchForm.submit();
					  }
				 }
				else	// mandatory fields left empty
				  alert("�� ���� �� �����, �� ������ ������� �������");
			}
			else	// media not chosen
			{
				alert("�� ����� �� �����");
			}
		}

         $().ready(function() {

				var AUTOCOMP_MIN_CHARS = 2;

				// Deal with general regular fields for autocompletion
				var autoComp_regular_fields =
					["composition_title",
					 "publisher",
					 "publisher_place",
					 "series",
					 "collection"];
				for (var cur_field_idx in autoComp_regular_fields) {
					var cur_field = autoComp_regular_fields[cur_field_idx];
					$("#"+cur_field).autocomplete("../autoComplete/populate_autoComplete.php?field="+cur_field, {
						width: 260,
						matchContains: true,
						minChars: AUTOCOMP_MIN_CHARS,
						scroll: true,
						selectFirst: false
					});
				}

				// Deal with general fields for which autocompletion data should be
				// extracted from 3 enumerated fields (e.g., "solist";"solist2";"solist3")
				var autoComp_3enum_fields =
					["solist",
					 "performance_group",
					 "orchestra",
					 "subject",
					 "conductor"];
				for (var cur_field_idx in autoComp_regular_fields) {
					var cur_field = autoComp_3enum_fields[cur_field_idx];
					$("#"+cur_field).autocomplete("../autoComplete/populate_3_enum_field_autoComplete.php?field="+cur_field, {
						width: 260,
						matchContains: true,
						minChars: AUTOCOMP_MIN_CHARS,
						scroll: true,
						selectFirst: false
					});
					$("#"+cur_field+"2").autocomplete("../autoComplete/populate_3_enum_field_autoComplete.php?field="+cur_field, {
						width: 260,
						matchContains: true,
						minChars: AUTOCOMP_MIN_CHARS,
						scroll: true,
						selectFirst: false
					});
					$("#"+cur_field+"3").autocomplete("../autoComplete/populate_3_enum_field_autoComplete.php?field="+cur_field, {
						width: 260,
						matchContains: true,
						minChars: AUTOCOMP_MIN_CHARS,
						scroll: true,
						selectFirst: false
					});
				}
				
				// Second author should get composer options
				$("#second_author").autocomplete("../autoComplete/populate_composer_autoComplete.php", {
					width: 260,
					matchContains: true,
					minChars: AUTOCOMP_MIN_CHARS,
					scroll: true,
					selectFirst: false
				});
		}); 

   </script>

</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">

<?php

$msg = "";
$action = &$_GET['action'];
$fromID = &$_GET['fromID'] ;

$item_no = "";

$displayUpdateLink = false;

// action = newitem when we reach an empty table (for example, using the
// navigation bar button.
// action = additem after the table is full.

// add new item, we reach here after we fill the fields in the table.
if (($action == "additem")) {
    $confirm = &$_GET['confirm'];
    // Add a new item to the records table
    // according to the data provided by administrator
    $title_txt = "����� ����";

	$media_id = &$_GET['media_id'];
	$composer = process_data(&$_GET['composer']);
	$composer2 = process_data(&$_GET['composer2']); // hebrew composer
	$item_no = process_data(&$_GET['item_no']);
	$second_author = process_data(&$_GET['second_author']);
	$series = process_data(&$_GET['series']);
	$composition_formal_name = process_data(&$_GET['composition_formal_name']);
	$composition_title = process_data(&$_GET['composition_title']);
	$publisher = process_data(&$_GET['publisher']);
	$publisher_place = process_data(&$_GET['publisher_place']);
	$year = process_data(&$_GET['year']);
	$solist = process_data(&$_GET['solist']);
	$solist2 = process_data(&$_GET['solist2']);
	$solist3 = process_data(&$_GET['solist3']);
	$performance_group = process_data(&$_GET['performance_group']);
	$performance_group2 = process_data(&$_GET['performance_group2']);
	$performance_group3 = process_data(&$_GET['performance_group3']);
	$orchestra = process_data(&$_GET['orchestra']);
	$orchestra2 = process_data(&$_GET['orchestra2']);
	$orchestra3 = process_data(&$_GET['orchestra3']);
	$conductor = process_data(&$_GET['conductor']);
	$conductor2 = process_data(&$_GET['conductor2']);
	$conductor3 = process_data(&$_GET['conductor3']);
	$collection = process_data(&$_GET['collection']);
	$notes = process_data(&$_GET['notes']);
	$item_second_title = process_data(&$_GET['item_second_title']);
	$subject = process_data(&$_GET['subject']);
	$subject2 = process_data(&$_GET['subject2']);
	$subject3 = process_data(&$_GET['subject3']);

	// check if this item exists in database
    $qry = "select item_no,composition_title from records where item_no=\"" . $item_no . "\"" ;

	// this item number already exists and user didn't confirm yet
	// confirm if he wants to add new piece under the existing item number.
    if (($result = mysql_query($qry)) != false) {
        if (mysql_num_rows ($result) > 0) {

        	$warn_msg_of_dup = "���� ���� ��� ���� ����� ���.\\n";
        	
        	// Check if composition title exists already with the above
        	// item number - if so, add warning
            while ($row = mysql_fetch_array($result)) {
		        if (strcmp($row["composition_title"],$composition_title) == 0) {
		        	
		        	$warn_msg_of_dup = "$warn_msg_of_dup\\n***����� ���� ���� �� ���� ��!***\\n";
		        	
		        	break;
		        } 
		    }
		    
		    $warn_msg_of_dup = "$warn_msg_of_dup\\n������?";
        	
        	
        	$confirm = &$_GET['confirm'];
            if (! isset($confirm))
                echo "<script>if (confirm(\"$warn_msg_of_dup\")) " . "document.location.href=\"adminAddNewItem.php\"+document.location.search+\"&confirm=true\" ;" . " else " . "document.location.href=\"adminAddNewItem.php\"+document.location.search+\"&confirm=false\" ;" . "</script>" ;
        }

		// insert item to database
        if ((mysql_num_rows ($result) <= 0) || ($confirm == "true")) {
            $qry = "insert into records (media_id,id,item_no,item_second_title,second_author,series,composer,composition_formal_name,composition_title,publisher,publisher_place,";
            // if administrator doesn't provided "year" value - leave it empty in
            // the database too
            if (isset($year) && ($year != ""))
                $qry = $qry . "year,";

            $qry = $qry . "solist,solist2,solist3,performance_group,performance_group2,performance_group3,orchestra,orchestra2,orchestra3,conductor,conductor2,conductor3,collection,notes,subject,subject2,subject3,hebrew_composer) values (" . $media_id . ",NULL,'" . $item_no . "','" . $item_second_title . "','" . $second_author . "','" . $series . "','" . $composer . "','" . $composition_formal_name . "','" . $composition_title . "','" . $publisher . "','" . $publisher_place . "'";

            if (isset($year) && ($year != ""))
                $qry = $qry . "," . $year;

            $qry = $qry . ",'" . $solist . "','" . $solist2 . "','" . $solist3 . "','" . $performance_group . "','" . $performance_group2 . "','" . $performance_group3 . "','" . $orchestra . "','" . $orchestra2 . "','" . $orchestra3 . "','" . $conductor . "','" . $conductor2 . "','" . $conductor3 . "','" . $collection . "','" . $notes . "','" . $subject . "','" . $subject2 . "','" . $subject3 . "','" . $composer2 . "')";

            $resultSet = mysql_query($qry);
            if ($resultSet != false)
                displayMsg ("����� ����� �����", 0) ;
            else
                setSessionMessageDatabaseError() ;
        }
    }

    $qry = "select max(id) mid from records where (composition_formal_name =\"" . $composition_formal_name . "\") and (composer = \"" . $composer . "\")";
    $resultSet = mysql_query($qry);

    if ($resultSet != false) {

    	// get the id of the item we have just inserted
        $fromID = new_mysql_result($resultSet, 0, "mid");

        // set the action for the upcoming item display (the display of the item we have just inserted)
        // (Or's comment: We're inside an "If" statement guarenteeing action=additem, so
        // the following statement seems pointless because it will always set $action to "newitem") 
        $action = (($action == "copyitem") ? "displayitem" : "newitem") ;
    } else
        setSessionMessageDatabaseError() ;
}

// This is how to create a javascript alert in this part of code, for debug
//echo '<script language="javascript">';
//echo "alert('Before if: item_no=\"$item_no\" and action=\"$action\"');";
//echo "</script>";

// If we're copying an item (item number already exists)
if ($action == "copyitem" ||
	$action == "2" ||
	$action == "additem" ||
	$action == "newitem") {

	// If there's a non-empty item number
	if ($item_no != "") {
		
		// Enable display of link for item update (in case we're
		// looking at the data from the copied item and see there's a
		// mistake which we want to fix on the item we copied from)
		$displayUpdateLink = true;
		
		// Define the link for updating the original item details
		$updateLink = "adminResults.php?mode=update&action=1&display=heb&item_no=$item_no";
	}
	
	// Else, for some reason there's no item number so attempt to get it again
	else {

		// Get item number for update link
		$item_no = process_data(&$_GET['item_no']);

		// If there's a non-empty item number
		if ($item_no != "") {

			// Enable display of link for item update (in case we're
			// looking at the data from the copied item and see there's a
			// mistake which we want to fix on the item we copied from)
			$displayUpdateLink = true;
			
			// Define the link for updating the original item details
			$updateLink = "adminResults.php?mode=update&action=1&display=heb&item_no=$item_no";
		}
	}
}

$showmsg = &$_GET['showmsg'];

// if we used the "new item" button from navigation bar.
// $fromID is used when using the "copy item" button from navigation bar.
// it means the piece id (see adminResult.php, when clicking the "copy" link
// near the piece).

if (!isset($fromID) || ($fromID == ""))
    $title_txt = "���� ���";

$showmsg = &$_GET['showmsg'];
$fromID = &$_GET['fromID'];
$confirm = &$_GET['confirm'];
{
    // If we are adding a new record for the first time, all fields will be empty
    // i.e. we used the "new item" button from the navigation bar.
    if ((!isset($confirm)) && ((!isset($fromID) || ($fromID == "")))) {
        $item_no = "";
        $media_id = "";
        $composer = "";
        $second_author = "";
        $composer2 = "";
        $composition_formal_name = "";
        $composition_title = "";
        $publisher = "";
        $publisher_place = "";
        $year = "";
        $solist = "";
        $solist2 = "";
        $solist3 = "";
        $performance_group = "";
        $performance_group2 = "";
        $performance_group3 = "";
        $orchestra = "";
        $orchestra2 = "";
        $orchestra3 = "";
        $conductor = "";
        $conductor2 = "";
        $conductor3 = "";
        $notes = "";
        $series = "";
        $subject = "";
        $subject2 = "";
        $subject3 = "";
        $item_second_title = "";
        $collection = "";
    }
	// if we tried to add an item whose item number already exists in database
	// but we didn't confirm.
	// in that case, we'll display the data in the fields, but not insert it
	// into the database.
	else if (($confirm == "false") && ((!isset($fromID) || ($fromID == "")))) {
        $title_txt = "����� ����";
        $media_id = stripslashes(&$_GET['media_id']);
        $composer = stripslashes(&$_GET['composer']);
        $composer2 = stripslashes(&$_GET['composer2']); // hebrew composer
        $item_no = stripslashes(&$_GET['item_no']);
        $second_author = stripslashes(&$_GET['second_author']);
        $series = stripslashes(&$_GET['series']);
        $composition_formal_name = stripslashes(&$_GET['composition_formal_name']);
        $composition_title = stripslashes(&$_GET['composition_title']);
        $publisher = stripslashes(&$_GET['publisher']);
        $publisher_place = stripslashes(&$_GET['publisher_place']);
        $year = stripslashes(&$_GET['year']);
        $solist = stripslashes(&$_GET['solist']);
        $solist2 = stripslashes(&$_GET['solist2']);
        $solist3 = stripslashes(&$_GET['solist3']);
        $performance_group = stripslashes(&$_GET['performance_group']);
        $performance_group2 = stripslashes(&$_GET['performance_group2']);
        $performance_group3 = stripslashes(&$_GET['performance_group3']);
        $orchestra = stripslashes(&$_GET['orchestra']);
        $orchestra2 = stripslashes(&$_GET['orchestra2']);
        $orchestra3 = stripslashes(&$_GET['orchestra3']);
        $conductor = stripslashes(&$_GET['conductor']);
        $conductor2 = stripslashes(&$_GET['conductor2']);
        $conductor3 = stripslashes(&$_GET['conductor3']);
        $collection = stripslashes(&$_GET['collection']);
        $notes = stripslashes(&$_GET['notes']);
        $item_second_title = stripslashes(&$_GET['item_second_title']);
        $subject = stripslashes(&$_GET['subject']);
        $subject2 = stripslashes(&$_GET['subject2']);
        $subject3 = stripslashes(&$_GET['subject3']);
    } else { // If we continue to add records, the new one will be
        	 // based on the data provided for the last inserted record.
        	 // this happens after we successfuly add a new item
        	 // or if we used the "copy item" button from navigation bar.
        $strQuery = "select * from records where id=" . $fromID ;

        $result = mysql_query($strQuery) ;

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
            $notes = restoreEOL($notes) ;
            $series = new_mysql_result($result, 0, "series");
            $subject = new_mysql_result($result, 0, "subject");
            $subject2 = new_mysql_result($result, 0, "subject2");
            $subject3 = new_mysql_result($result, 0, "subject3");

            $item_second_title = new_mysql_result($result, 0, "item_second_title");
            $collection = new_mysql_result($result, 0, "collection");
            $title_txt = "����� ����";
            $action = "additem";
        } else
            setSessionMessageDatabaseError() ;
    }
}
if ($showmsg)
    displaySessionMsg();

?>

<?php
// If an update link for the original copied item should be displayed
if ($displayUpdateLink) {
	
	echo "<div class='subtleText' align=center>";
	echo "<a href=$updateLink target=\"_blank\">���� ������ �����? ���/���� �������</a>";
	echo "</div>";
}
?>

<!-- creates the add new item/copy item table -->
<!-- table fields. When clicking on the index button, openModal() is called. -->
<form action='adminAddNewItem.php?showmsg=1&action=additem&fromID=" . $lastID . "' class='recordTitle' name="searchForm" method=GET>
<table border=1 bordercolor=black align=center width=500 class='dataTable'>
	<tr>
           <?php echo "<td colspan=2 class='bigTitle' align=center><b>$title_txt</b></td>";?>
    </tr>
    <tr>
	    <td align=center>
    	  	<input type=text name="item_no" value="<?php echo get_php_string($item_no);?>">
        </td>
        <td align=right>��' ����</td>
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
	    <td align=right>����</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text <?php if ($action == "displayitem") echo "readonly" ?> name="composer" id="composer" value="<?php echo get_php_string($composer);?>" dir=ltr>
		</td>
		<td align=right>
			<?php if ($action != "displayitem")
	    		echo "<table border=0 bordercolor=black align=center width=100% class=\"dataTable\">" . "<tr>" . "<td align=center width=20>" . "<a href=\"javascript: openModal(14);\"><img src=\"../images/indexH.gif\" border=0></a>" . "</td>" . "<td align=right>" ;?>
				�� ������ �������
			<?php if ($action != "displayitem")
	    		echo "</td>" . "</tr>" . "</table>" ;?>
		</td>
	</tr>
	<tr>
		<td align=center width="50%">
			<input type=text <?php if ($action == "displayitem") echo "readonly" ?> name="composer2" id="composer2" value="<?php echo get_php_string($composer2);?>" dir=rtl>
		</td>
		<td align=right>
			<?php if ($action != "displayitem")
    			echo "<table border=0 bordercolor=black align=center width=100% class=\"dataTable\">" . "<tr>" . "<td align=center width=20>" . "<a href=\"javascript: openModal(13);\"><img src=\"../images/indexH.gif\" border=0></a>" . "</td>" . "<td align=right>" ;
			?>
				�� ������ ������
			<?php if ($action != "displayitem")
    			echo "</td>" . "</tr>" . "</table>" ;
			?>
		</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text <?php if ($action == "displayitem") echo "readonly" ?> name="composition_formal_name" id="composition_formal_name" value="<?php echo get_php_string($composition_formal_name);?>" dir=<?php echo (determineLang($composition_formal_name)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
		</td>
		<td align=right>
			<?php if ($action != "displayitem")
    			echo "<table border=0 bordercolor=black align=center width=100% class=\"dataTable\">" . "<tr>" . "<td align=center width=20>" . "<a href=\"javascript: openModal(9);\"><img src=\"../images/indexH.gif\" border=0></a>" . "</td>" . "<td align=right>" ;
			?>
			�� ���� �� ������
			<?php if ($action != "displayitem")
    			echo "</td>" . "</tr>" . "</table>" ;
			?>
		</td>
    </tr>

	<tr>
	    <td align=center>
			<input type=text name="composition_title" id="composition_title" value="<?php echo get_php_string($composition_title);?>" dir=<?php echo (determineLang($composition_title)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
		</td>
		<td align=right>(���� (�����, ���</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="publisher" id="publisher" value="<?php echo get_php_string($publisher);?>" dir=<?php echo (determineLang($publisher)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
            	<tr>
					<td align=center width=20>
						<a href="javascript: openModal(7);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>����� ����</td>
                </tr>
            </table>
         </td>
    </tr>

	<tr>
		<td align=center>
			<input type=text name="publisher_place" id="publisher_place" value="<?php echo get_php_string($publisher_place) ;?>" dir=<?php echo (determineLang($publisher_place)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
		</td>
		<td align=right>���� ����� ����</td>
	</tr>

	<tr>
		<td align=center>
        		<input type=text name="year" id="year" value="<?php echo get_php_string($year);?>" dir=ltr>
		</td>
		<td align=right>��� </td>
	</tr>

	<tr>
		 <td colspan=2>
            <table border=0 bordercolor=black align=center class='dataTable' width=500>
				<tr>
					<td align=right colspan=2><b>�����</b></td>
				</tr>
				<tr>
					<td align=center colspan=2>
						<table border=0 bordercolor=black class='dataTable' align=center>
							<tr>
								<td align=center>
									<input type=text name="solist" id="solist" value="<?php echo get_php_string($solist);?>" dir=<?php echo (determineLang($solist)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(1);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>����</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="solist2" id="solist2" value="<?php echo get_php_string($solist2);?>" dir=<?php echo (determineLang($solist2)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(15);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>���� 2</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="solist3" id="solist3" value="<?php echo get_php_string($solist3);?>" dir=<?php echo (determineLang($solist3)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(16);"><img src="../images/indexH.gif" border=0></a>
											</td>
										  <td align=right>���� 3</td>
										</tr>
									</table>
								</td>
							</tr>

                             <tr>
								<td align=center>
									<input type=text name="performance_group" id="performance_group" value="<?php echo get_php_string($performance_group);?>" dir=<?php echo (determineLang($performance_group)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(2);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>����� �����  </td>
										</tr>
									</table>
								</td>
							</tr>

							<tr>
								<td align=center>
									<input type=text name="performance_group2" id="performance_group2" value="<?php echo get_php_string($performance_group2);?>" dir=<?php echo (determineLang($performance_group2)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(17);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>����� ����� 2</td>
										</tr>
									</table>
								</td>
							</tr>

							<tr>
								<td align=center>
									<input type=text name="performance_group3" id="performance_group3" value="<?php echo get_php_string($performance_group3);?>" dir=<?php echo (determineLang($performance_group3)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(18);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>����� ����� 3</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="orchestra" id="orchestra" value="<?php echo get_php_string($orchestra);?>" dir=<?php echo (determineLang($orchestra)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(3);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>������ / �����</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="orchestra2" id="orchestra2" value="<?php echo get_php_string($orchestra2);?>" dir=<?php echo (determineLang($orchestra2)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(19);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>������ / ����� 2</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="orchestra3" id="orchestra3" value="<?php echo get_php_string($orchestra3);?>" dir=<?php echo (determineLang($orchestra3)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(20);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>������ / ����� 3</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="conductor" id="conductor" value="<?php echo get_php_string($conductor);?>" dir=<?php echo (determineLang($conductor)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(4);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right> ���� </td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="conductor2" id="conductor2" value="<?php echo get_php_string($conductor2);?>" dir=<?php echo (determineLang($conductor2)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(21);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>���� 2</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align=center>
									<input type=text name="conductor3" id="conductor3" value="<?php echo get_php_string($conductor3);?>"  dir=<?php echo (determineLang($conductor3)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
								</td>
								<td align=right>
									<table border=0 bordercolor=black align=center width=100% class="dataTable">
										<tr>
											<td align=center width=20>
												<a href="javascript: openModal(22);"><img src="../images/indexH.gif" border=0></a>
											</td>
											<td align=right>���� 3</td>
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
			<textarea name="notes" scrolling=no rows='6' cols='40' wrap='soft' dir=<?php echo (determineLang($notes)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> style='width: 100%;overflow:auto'><?php echo get_php_string($notes);?></textarea>
		</td>
		<td align=right>�����</td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="series" id="series" dir=<?php echo (determineLang($series)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> value="<?php echo get_php_string($series) ;?>" >
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					 <td align=center width=20>
						<a href="javascript: openModal(10);"><img src="../images/indexH.gif" border=0></a>
					 </td>
					 <td align=right>����</td>
				 </tr>
			 </table>
		</td>
	</tr>
	<tr>
		<td align=center>
			<input type=text name="subject" id="subject" value="<?php echo get_php_string($subject);?>" dir=<?php echo (determineLang($subject)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?>>
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						<a href="javascript: openModal(5);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>����</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="subject2" id="subject2" dir=<?php echo (determineLang($subject2)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> value="<?php echo get_php_string($subject2);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						<a href="javascript: openModal(11);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>���� 2</td>
				</tr>
			 </table>
		</td>
	 </tr>

	<tr>
		<td align=center>
			<input type=text name="subject3" id="subject3" dir=<?php echo (determineLang($subject3)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> value="<?php echo get_php_string($subject3);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						 <a href="javascript: openModal(12);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>���� 3</td>
				 </tr>
			</table>
		</td>
	 </tr>
	 <tr>
		<td align=center>
			<input type=text name="item_second_title" id="item_second_title" dir=<?php echo (determineLang($item_second_title)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> value="<?php echo get_php_string($item_second_title);?>">
		 </td>
		<td align=right>���� ����</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="second_author" id="second_author" dir=<?php echo (determineLang($second_author)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> value="<?php echo get_php_string($second_author);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						 <a href="javascript: openModal(23);"><img src="../images/indexH.gif" border=0></a>
					</td>
					<td align=right>���� ����</td>
				 </tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align=center>
			<input type=text name="collection" id="collection" dir=<?php echo (determineLang($collection)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];?> value="<?php echo get_php_string($collection);?>">
		</td>
		<td align=right>
			<table border=0 bordercolor=black align=center width=100% class="dataTable">
				<tr>
					<td align=center width=20>
						<a href="javascript: openModal(8);"><img src="../images/indexH.gif" border=0></a>
					 </td>
					 <td align=right>�����</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center>
			<input type=button name="BaddItem" value="  ����  " onclick="javascript: addnew();">
			<input type=hidden name="action" value= <?php echo (($action == "copyitem") ? "copyitem" : "additem") ?>>
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

