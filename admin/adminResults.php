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

?>
<!-- This file prints the search results (items administrator wants to update or delete)
	 Performing the search in the database, depending on parameters (search criteria) passed.
	 The title is linked to the details of the item page.
	 There is a link to update page for each item that found.
	 Paging of search results is similar to the user's search results (results.php)-->


<html>
<head>
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">
	<title>מערכת הזנת נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">
</head>

<body>
<!-- this search page use hebrew only (unlike the search system
	where the display language can be english or hebrew).
	Plus, we've reached this page by searching for an item no
	(searchItemNo.php). -->
<?php
$mode = &$_GET['mode'];
$action = &$_GET['action'];
$display = &$_GET['display'];
$showmsg = &$_GET['showmsg'];

switch ($display) {
    case 'heb':
        $lang = $HEBREW ;
        break ;
    case 'eng':
    default:
        $lang = $ENGLISH ;
        break ;
}

// action = 1 - new search
// action = 2 - continue to show results of the query (next page of the results)
if (isset($action)) {
    // clear session variable if this it new search
    if ($action == 1) unset($_SESSION['sessQuery']);

    // Read all variables that was sent to this page.
    if (!isset($_GET['media_id']))
        $media_id = "";
    else
        $media_id = trim($_GET['media_id']);

    if (!isset($_GET['composer']))
        $composer = "";
    else
        $composer = trim($_GET['composer']);

    if (!isset($_GET['composition_title']))
        $composition_title = "";
    else
        $composition_title = trim($_GET['composition_title']);

    if (!isset($_GET['publisher']))
        $publisher = "";
    else
        $publisher = trim($_GET['publisher']);

    if (!isset($_GET['year']))
        $year = "";
    else
        $year = trim($_GET['year']);

    if (!isset($_GET['solist']))
        $solist = "";
    else
        $solist = trim($_GET['solist']);

    if (!isset($_GET['performance_group']))
        $performance_group = "";
    else
        $performance_group = trim($_GET['performance_group']);

    if (!isset($_GET['orchestra']))
        $orchestra = "";
    else
        $orchestra = trim($_GET['orchestra']);

    if (!isset($_GET['conductor']))
        $conductor = "";
    else
        $conductor = trim($_GET['conductor']);

    if (!isset($_GET['collection']))
        $collection = "";
    else
        $collection = trim($_GET['collection']);

    if (!isset($_GET['notes']))
        $notes = "";
    else
        $notes = trim($_GET['notes']);

    if (!isset($_GET['subject']))
        $subject = "";
    else
        $subject = trim($_GET['subject']);

    if (!isset($_GET['item_title']))
        $item_title = "";
    else
        $item_title = trim($_GET['item_title']);

    if (!isset($_GET['item_no']))
        $item_no = "";
    else
        $item_no = trim($_GET['item_no']);

    // ////////////////////// Paging variables //////////////////////////////////
    $pageSize = 15; // show no more than 15 rows of results on each page

    $curPage = &$_GET['curPage'];
    $startRow = &$_GET['startRow'];
    if (!isset($curPage)) {
        $curPage = 1;//if curPage variable not defined, current page will be first one
    }
    if (!isset($startRow)) {
        $startRow = 1;//if startRow variable not defined, search results are shown from the first row
    } else {
        $startRow = ($curPage * $pageSize) - ($pageSize -1); //calculate the page number, to start wit
    }
    // //////////////////////////////////////////////////////////////////////////

    // //////////////////////////////////////////////////////////////////////////
    // if session variable sessQuery not defined, create the query by criteria
    // passed to this file as parameters

    $searchQry = "select * from records where 1=1 "; // we want an empty search to succeed too

    if ($item_no != "")
        $searchQry = $searchQry . " and item_no like '" . $item_no . "'";
    // exact match of item number
    $searchQry = $searchQry . " order by composer, composition_title, media_id";

    // Store created sql query in the session variable, so it can be used
    // again when the user asks to show more search results (next page)
    $_SESSION['sessQuery'] = $searchQry;
    $runQuery = $searchQry;

     // put the results in an array (cause SQL has some weird problems when sorting)
    $result = mysql_query($runQuery) ;
    $num_rows = mysql_num_rows($result);
    
    class res {
        var $composer; //First variable: Used for sorting
        var $composition_title;
        var $media_id;
        var $id;
    }
    
    $resultArr = array();
    $j = 0;
    while ($row = mysql_fetch_array($result)) {
        $resultArr[$j] = new res();
        $resultArr[$j]->id = $row["id"];
        $resultArr[$j]->composer = $row["composer"];
        $resultArr[$j]->composition_title = $row["composition_title"];
        $resultArr[$j]->media_id = $row["media_id"];
        
    	// Get formal name of item (for displaying on page)
		$item_second_title = $row["item_second_title"];
        
        $j++;
    }
    sort($resultArr);
    
    // ////////////////////////// Paging Links generation /////////////////////////////////////////////////////////
    // Calculations based on currnet page and page size, to create links
    // for paging (Next page/ Previous page)
    $pageNumber = $num_rows / $pageSize;
    $pageNumber = floor($pageNumber);

    if (($num_rows % $pageSize) != 0)
        $pageNumber += 1;

    $next_page = "";
    $previous_page = "";

    if ($num_rows != "0") {
        $string = "<table border=\"0\" bgcolor=\"#9CCEFF\" width=\"320\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">";
        $string = $string . "<tr><td valign=\"top\" width=\"100\">";
        if ($curPage != 1) {
            $nextPage = $curPage - 1;
            $link = "adminResults.php?item_no=$item_no&display=$display&curPage=" . $nextPage . "&startRow=" . $startRow . "&action=2&mode=" . $mode;
            $previous_page = "<center><a href=\"" . $link . "\" class=\"link3\">" . $lang_terms['toPrevResults'][$lang] . "</a></center>" ;
        } else {
            $previous_page = "" ;
        }

        $newLine = "";

        if (($curPage != $pageNumber) && ($pageNumber != 0)) {
            $nextPage = $curPage + 1;
            $link = "adminResults.php?item_no=$item_no&display=$display&curPage=" . $nextPage . "&startRow=" . $startRow . "&action=2&mode=" . $mode;
            $next_page = "<center><a href=\"" . $link . "\" class=\"link3\">" . $lang_terms['toNextResults'][$lang] . "</a></center>";
        } else {
            $next_page = "";
        }

        $loop_startRow = $startRow - 1;
        $loop_pageSize = $pageSize ;
    }
    else {
        $loop_startRow = 0;
        $loop_pageSize = 0;
    }

    // //////////////////////////////////End of Paging Links//////////////////////////////////////////////////
    // Show results

	// use definitions in lang.php to determine titles and alignments
	// (hebrew only).

    if ($showmsg)
        displaySessionMsg() ;

    echo "<center>";
    echo "<table>
        <tr>
           <td align=center class='menuTitle'><b>" . $lang_terms['searchResults'][$lang] . "</b><BR><BR></td>
        </tr>
        <tr>
           <td dir=rtl align=center class='normalTitle'><b><font color=#2778C3>" . str_replace("<num>", $num_rows, $lang_terms['resultsFound'][$lang]) . " עבור <I>" . (($item_second_title=="")?"": $item_second_title . " </I>שמספרו <I>") . $item_no . "</I></font></b></td>
        </tr>";

    if ($num_rows > 0) {
        echo "<tr>
                     <td align=center class='smallTitle'>" . $lang_terms['fullDescrAction'][$lang] . "<br><BR></td>
                 </tr>";
    }

    echo "</table>";
    echo "<br></center>";

    if ($num_rows > 0) {
        echo "<table border=1 bordercolor=black align=center class='dataTable'>";
        echo "<tr class='normalTitle'><td>&nbsp;</td><td align=center dir=$lang_directions[$lang]><b>" . $lang_terms['media'][$lang] . "</b></td><td align=center dir=$lang_directions[$lang]><b>" . $lang_terms['composer'][$lang] . "</b></td><td align=center dir=$lang_directions[$lang]><b>" . $lang_terms['title'][$lang] . "</b></td>";
        if (isset($mode) && (($mode == "update") || ($mode == "delete") || ($mode == "copy")))
            echo "<td align=center dir=$lang_directions[$lang]><b>פעולה</b></td>";
        echo "</tr>";

        for($i = $loop_startRow; ($i < ($loop_pageSize + $loop_startRow)) && ($i < $num_rows); $i++) {
            $id = stripslashes($resultArr[$i]->id);
            $composer = stripslashes($resultArr[$i]->composer);
            $composition_title = stripslashes($resultArr[$i]->composition_title);
            $media_id = stripslashes($resultArr[$i]->media_id);

            $direction = $lang_directions[$lang] ;
            $align = $lang_aligns[$lang] ;
            // If assuming English, align to the left.
            // Else (probably hebrew) align to the right.
            if (determineLang($composition_title) == "en") {
                $align_title = "left";
                $text_dir = "ltr";
            }
            else {
                $align_title = "right";
                $text_dir = "rtl";
            }
            echo "<tr>";
            $record_details_link = "..\\record.php?display=heb&id=" . $id; 
            echo "<td align=left>&nbsp;<a href=\"$record_details_link\">" . ($i + 1) . "</a>&nbsp;</td><td align=left>" . mediaNamebyID($media_id) . "</td><td align=left>" . $composer . "</td><td align=$align_title dir=$text_dir><a href=\"$record_details_link\">" . $composition_title . "</a></td>";
            // we got the mode varianle from searchByItem no.
            // if update, display an update link near the result.
            if (isset($mode) && $mode == "update")
                echo "<td align=right>&nbsp; <a href=\"adminUpdate.php?id=" . $id . "&action=$action&display=$display&mode=$mode\">עדכן</a>&nbsp;</td>";
            // if delete, display a delete link near the result.
			else if (isset($mode) && $mode == "delete")
                echo "<td align=right>&nbsp; <a href=\"adminUpdate.php?id=" . $id . "&action=$action&display=$display&mode=$mode\">מחק</a>&nbsp;</td>";
            // if copy, display a copy link near the result.
			else if (isset($mode) && $mode == "copy")
                echo "<td align=right>&nbsp; <a href=\"adminAddNewItem.php?fromID=" . $id . "&display=$display&item_no=$item_no&confirm=false&action=$action\">העתק</a>&nbsp;</td>";

            echo "</tr>";
        }

        echo "</table>";
        // only if we selected an item (and didn't run an empty search to get all results)
        // this button should appear.

		// "delete item" button (deletes whole item)
        if (isset($mode) && ($mode == "delete") && isset($item_no) && ($item_no != "")) {
            echo "<br><br><center><tr><td>
       <input type=button class='recordTitle' value='  מחיקת פריט  ' onClick='
       	document.location.href=\"adminUpdate.php?mode=delete&display=$display&item_no=$item_no&action=$action\" '></td></tr></center>";
        } else if (isset($mode) && ($mode == "update") && isset($item_no) && ($item_no != "")) {
        	// "update item" (gives a different table to update item related values).
		    echo "<br><br><center><tr><td>
        <input type=button class='recordTitle' value='  עדכון פריט  ' onClick='
       	document.location.href=\"adminUpdate.php?mode=updateItem&display=$display&item_no=$item_no&action=$action\" '></td></tr></center>";
        }
    }
    // //////////////////////////////////////////////
    // Paging area
    // Here we show page numbers as links and also links to the "Next page" and "Previous page",
    // if it's nessecary (the query returned more than $pageSize results).
    ?>
 <table cellpadding="0" cellspacing="0" border="0" width="60%" align=center class="recordTitle">
 <tr>
  <td colspan=3 height=20>&nbsp;</td>
 </tr>
 <tr>

 <td class="link3" colspan=2 align="center" >
 <?php
    for ($count = 0; $count < $pageNumber ; $count++) {
        $string = "";

        $linkPage = $count + 1;
        $link = "adminResults.php?item_no=$item_no&display=$display&curPage=" . $linkPage . "&startRow=" . $startRow . "&action=2&mode=" . $mode;
        if ($linkPage != $curPage) {
            $string = $string . " <a href=\"" . $link . "\" class=\"link3\"><u>";
        } else {
            $string = $string . " ";
        }
        $string = $string . $linkPage ;
        if ($linkPage != $curPage) {
            $string = $string . "</u></a> " ;
        } else {
            $string = $string . " ";
        }
        echo $string;
    }

    ?>
     </td>
    </tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td class="link3" align="center"><b><?php echo $next_page;?></b></td>
	<td class="link3" align="center"><b><?php echo $previous_page ?></b></td>
	</tr>

    </table>
<?php
    // //////////////////////////////////////////////
}

?>
<center>
<br><br>
<hr width=500>
<br><br>
</center>

</body>
</html>
