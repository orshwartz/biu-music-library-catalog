<?php
session_start();
// general functions
include_once('func.php');
// database definitions
include_once('db_common.php');
// CSS definitions
include_once('styles.inc');
// navigation bar to be displayed on top
include_once('searchNavBar.php');
// language definitions, for the display can be both in hebrew and in english
include_once('lang.php') ;
?>


<html>
<head>
	<title>מערכת חיפוש נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

</head>

<body>


<?php
// using first letter in the field to determine its langauge.
// the alignment should be set according to this.
function determineLang($field)
{
    if ((ord(substr($field, 0, 1)) >= 0) && (ord(substr($field, 0, 1)) <= 127)) {
        return "en";
    } else {
        return "heb";
    }
}

if (!isset($_GET['mode']))
    $mode = "";
else
    $mode = $_GET['mode'];

$action = $_GET['action'];
$display = $_GET['display'];

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
    // clear session variable if this it a new search
    if ($action == 1) unset($_SESSION['sessQuery']);
    // Read all variables that was sent to this page.
    // use process_data to add slashes in case the search includes quotes
    // (may cause problems with sql queries).

    if (!isset($_GET['media_id']))
        $media_id = "";
    else
        $media_id = trim($_GET['media_id']);

    if (!isset($_GET['composer']))
        $composer = "";
    else
        $composer = process_data($_GET['composer']);

    if (!isset($_GET['composition_title']))
        $composition_title = "";
    else
        $composition_title = process_data($_GET['composition_title']);

    if (!isset($_GET['publisher']))
        $publisher = "";
    else
        $publisher = process_data($_GET['publisher']);

    if (!isset($_GET['year']))
        $year = "";
    else
        $year = process_data($_GET['year']);

    if (!isset($_GET['solist']))
        $solist = "";
    else
        $solist = process_data($_GET['solist']);

    if (!isset($_GET['performance_group']))
        $performance_group = "";
    else
        $performance_group = process_data($_GET['performance_group']);

    if (!isset($_GET['orchestra']))
        $orchestra = "";
    else
        $orchestra = process_data($_GET['orchestra']);

    if (!isset($_GET['conductor']))
        $conductor = "";
    else
        $conductor = process_data($_GET['conductor']);

    if (!isset($_GET['collection']))
        $collection = "";
    else
        $collection = process_data($_GET['collection']);

    if (!isset($_GET['notes']))
        $notes = "";
    else
        $notes = process_data($_GET['notes']);

    if (!isset($_GET['subject']))
        $subject = "";
    else
        $subject = process_data($_GET['subject']);

    if (!isset($_GET['item_title']))
        $item_title = "";
    else
        $item_title = process_data($_GET['item_title']);

    if (!isset($_GET['item_no']))
        $item_no = "";
    else
        $item_no = process_data($_GET['item_no']);

    // this string should be passed with the url in case there's more than
    // one page of results.
    $searchParameters = "media_id=$media_id&".
    					"composer=$composer&".
    					"composition_title=$composition_title&".
    					"publisher=$publisher&".
    					"year=$year&".
					    "solist=$solist&".
					    "performance_group=$performance_group&".
					    "orchestra=$orchestra&".
					    "conductor=$conductor&".
					    "collection=$collection&".
					    "notes=$notes&".
					    "subject=$subject&".
					    "item_title=$item_title&".
					    "item_no=$item_no";

    // ////////////////////// end of definition of variables used in paging of results //////////////////////////////////

    $pageSize = 25; // show no more than 25 rows of results on each page

    $curPage = &$_GET['curPage'];
    $startRow = &$_GET['startRow'];

    if (!isset($curPage)) {
        $curPage = 1; //if curPage variable not defined, current page will be first one
    }
    if (!isset($startRow)) {
        $startRow = 1; //if startRow variable not defined, search results are shown from the first row
    } else {
        $startRow = ($curPage * $pageSize) - ($pageSize -1); //calculate the page number, to start with
    }

    // //////////////////////////////////////////////////////////////////////////
    // if session variable sessQuery not defined, create the query by criteria
    // passed to this file as parameters

	// determine language of what we try to search for.
	// if hebrew, we should use "like binary" instead of "like" in queries.
    if (!isset($_SESSION['sessQuery'])) {
        $searchQry = "select * from records where 1=1 "; // we want an empty search to succeed too
        if ($media_id != "") {
            $searchQry = $searchQry . " and media_id = " . $media_id;
        }
        if ($composer != "") {
            if (determineLang($composer) == "en")
                $searchQry = $searchQry . " and (composer like '%" . $composer . "%' or second_author like '%" . $composer . "%')";
            else if (determineLang($composer) == "heb")
                $searchQry = $searchQry . " and (hebrew_composer like binary '%" . $composer . "%' or second_author like binary '%" . $composer . "%')";
        }
        if ($composition_title != "") {
            if (determineLang($composition_title) == "en") {
                $searchQry = $searchQry . " and (composition_title like '%" . $composition_title . "%'";
                $searchQry = $searchQry . " or composition_formal_name like '%" . $composition_title . "%' ";
                $searchQry = $searchQry . " or item_second_title like '%" . $composition_title . "%') ";
            } else if (determineLang($composition_title) == "heb") {
                $searchQry = $searchQry . " and (composition_title like binary '%" . $composition_title . "%'";
                $searchQry = $searchQry . " or composition_formal_name like binary '%" . $composition_title . "%' ";
                $searchQry = $searchQry . " or item_second_title like binary '%" . $composition_title . "%') ";
            }
        }

        if ($publisher != "") {
            if (determineLang($publisher) == "en")
                $searchQry = $searchQry . " and publisher like '%" . $publisher . "%'";
            else if (determineLang($publisher) == "heb")
                $searchQry = $searchQry . " and publisher like binary '%" . $publisher . "%'";
        }

        if ($year != "")
            $searchQry = $searchQry . " and year = " . $year ;

        if ($solist != "") {
            if (determineLang($solist) == "en") {
                $searchQry = $searchQry . " and (solist like '%" . $solist . "%' ";
                $searchQry = $searchQry . " or solist2 like '%" . $solist . "%'";
                $searchQry = $searchQry . " or solist3 like '%" . $solist . "%'";
                $searchQry = $searchQry . " or second_author like '%" . $solist . "%') ";
            } else if (determineLang($solist) == "heb") {
                $searchQry = $searchQry . " and (solist like binary '%" . $solist . "%' ";
                $searchQry = $searchQry . " or solist2 like binary '%" . $solist . "%'";
                $searchQry = $searchQry . " or solist3 like binary '%" . $solist . "%'";
                $searchQry = $searchQry . " or second_author like binary '%" . $solist . "%') ";
            }
        }
        if ($performance_group != "") {
            if (determineLang($performance_group) == "en") {
                $searchQry = $searchQry . " and (performance_group like '%" . $performance_group . "%' ";
                $searchQry = $searchQry . " or performance_group2 like '%" . $performance_group . "%' ";
                $searchQry = $searchQry . " or performance_group3 like '%" . $performance_group . "%') ";
            } else if (determineLang($performance_group) == "heb") {
                $searchQry = $searchQry . " and (performance_group like binary '%" . $performance_group . "%' ";
                $searchQry = $searchQry . " or performance_group2 like binary '%" . $performance_group . "%' ";
                $searchQry = $searchQry . " or performance_group3 like binary '%" . $performance_group . "%') ";
            }
        }
        if ($orchestra != "") {
            if (determineLang($orchestra) == "en") {
                $searchQry = $searchQry . " and (orchestra like '%" . $orchestra . "%' ";
                $searchQry = $searchQry . " or orchestra2 like '%" . $orchestra . "%' ";
                $searchQry = $searchQry . " or orchestra3 like '%" . $orchestra . "%') ";
            } else if (determineLang($orchestra) == "heb") {
                $searchQry = $searchQry . " and (orchestra like binary '%" . $orchestra . "%' ";
                $searchQry = $searchQry . " or orchestra2 like binary '%" . $orchestra . "%' ";
                $searchQry = $searchQry . " or orchestra3 like binary '%" . $orchestra . "%') ";
            }
        }

        if ($conductor != "") {
            if (determineLang($conductor) == "en") {
                $searchQry = $searchQry . " and (conductor like '%" . $conductor . "%' ";
                $searchQry = $searchQry . " or conductor2 like '%" . $conductor . "%' ";
                $searchQry = $searchQry . " or conductor3 like '%" . $conductor . "%') ";
            } else if (determineLang($conductor) == "heb") {
                $searchQry = $searchQry . " and (conductor like binary '%" . $conductor . "%' ";
                $searchQry = $searchQry . " or conductor2 like binary '%" . $conductor . "%' ";
                $searchQry = $searchQry . " or conductor3 like binary '%" . $conductor . "%') ";
            }
        }
        if ($collection != "") {
            if (determineLang($collection) == "en")
                $searchQry = $searchQry . " and collection like '%" . $collection . "%'";
            else if (determineLang($collection) == "heb")
                $searchQry = $searchQry . " and collection like binary '%" . $collection . "%'";
        }
        if ($notes != "") {
            if (determineLang($notes) == "en")
                $searchQry = $searchQry . " and notes like '%" . $notes . "%'";
            else if (determineLang($notes) == "heb")
                $searchQry = $searchQry . " and notes like binary '%" . $notes . "%'";
        }

        if ($item_title != "") {
            if (determineLang($item_title) == "en")
                $searchQry = $searchQry . " and item_title like '%" . $item_title . "%'";
            else if (determineLang($item_title) == "heb")
                $searchQry = $searchQry . " and item_title like binary '%" . $item_title . "%'";
        }
        if ($item_no != "")
            $searchQry = $searchQry . " and item_no = '" . $item_no . "'";

        if ($subject != "") {
            if (determineLang($subject) == "en") {
                $searchQry = $searchQry . " and (subject like '%" . $subject . "%' ";
                $searchQry = $searchQry . " or subject2 like '%" . $subject . "%' ";
                $searchQry = $searchQry . " or subject3 like '%" . $subject . "%' ";
                $searchQry = $searchQry . " or item_second_title like '%" . $subject . "%') ";
            } else if (determineLang($subject) == "heb") {
                $searchQry = $searchQry . " and (subject like binary '%" . $subject . "%' ";
                $searchQry = $searchQry . " or subject2 like binary '%" . $subject . "%' ";
                $searchQry = $searchQry . " or subject3 like binary '%" . $subject . "%' ";
                $searchQry = $searchQry . " or item_second_title like binary '%" . $subject . "%') ";
            }
        }

        $searchQry = $searchQry . " order by composer, composition_title, media_id";
        // Store created sql query in the session variable, so it can be used
        // again when the user asks to show more search results (next page)
        $_SESSION['sessQuery'] = $searchQry;
        $runQuery = $searchQry;
    } else {
        // Use alredy created query
        $runQuery = $_SESSION['sessQuery'];
    }
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
            $link = "results.php?$searchParameters&display=$display&curPage=" . $nextPage . "&startRow=" . $startRow . "&action=2&mode=" . $mode;
            $previous_page = "<a href=\"" . $link . "\" class=\"link3\">" . $lang_terms['toPrevResults'][$lang] . "</a>" ;
        } else {
            $previous_page = "" ;
        }

        $newLine = "";

        if (($curPage != $pageNumber) && ($pageNumber != 0)) {
            $nextPage = $curPage + 1;
            $link = "results.php?$searchParameters&display=$display&curPage=" . $nextPage . "&startRow=" . $startRow . "&action=2&mode=" . $mode;
            $next_page = "<a href=\"" . $link . "\" class=\"link3\">" . $lang_terms['toNextResults'][$lang] . "</a>";
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
    // Show results table

	// use definitions in lang.php to determine titles and alignments
    echo "<center>";
    echo "<table>
        <tr>
           <td align=center class='menuTitle'><b>" . $lang_terms['searchResults'][$lang] . "</b><BR><BR></td>
        </tr>
        <tr>
           <td align=center class='normalTitle'><b><font color=#2778C3>" . str_replace("<num>", $num_rows, $lang_terms['resultsFound'][$lang]) . "</font></b></td>
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
        echo "<tr><td>&nbsp;</td><td align=center dir=$lang_directions[$lang]><b>" . $lang_terms['media'][$lang] . "</b></td><td align=center dir=$lang_directions[$lang]><b>" . $lang_terms['composer'][$lang] . "</b></td><td align=center dir=$lang_directions[$lang]><b>" . $lang_terms['title'][$lang] . "</b></td></tr>";

        for($i = $loop_startRow; ($i < ($loop_pageSize + $loop_startRow)) && ($i < $num_rows); $i++) {

			// use stripslashes to get rid of the slashes we added when using
			// process_data.
            $id = stripslashes($resultArr[$i]->id);
            $composer = stripslashes($resultArr[$i]->composer);
            $composition_title = stripslashes($resultArr[$i]->composition_title);
            $media_id = stripslashes($resultArr[$i]->media_id);

            $direction = $lang_directions[$lang] ;
            $align = $lang_aligns[$lang] ;
            // check if the first char of the composition title is between 0 and 127,
            // i.e. ascii. if so, align to the left. else (probably hebrew) align to the right.
            // if ((ord(substr($composition_title, 0, 1)) >= 0) && (ord(substr($composition_title, 0, 1)) <= 127))
            if (determineLang($composition_title) == "en") {
                $align_title = "left";
                $align_dir = "ltr";
            } else if (determineLang($composition_title) == "heb")
                { // else
                    $align_title = "right";
                $align_dir = "rtl";
            }

            echo "<tr>";
            echo "<td align=left>&nbsp;<a href=\"record.php?id=" . $id . "&display=" . $display . "\">" . ($i + 1) . "</a>&nbsp;</td><td align=left>&nbsp;" . mediaNamebyID($media_id) . "&nbsp;</td><td align=left>&nbsp;" . $composer . "&nbsp;</td><td align=$align_title dir=$align_dir>&nbsp;<a href=\"record.php?id=" . $id . "&display=" . $display . "\">" . $composition_title . "</a>&nbsp;</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    // //////////////////////////////////////////////
    // Paging area
    // Here we show page numbers as links and also links to the "Next page" and "Previous page",
    // if it's nessecary (the query returned more than $pageSize results).
    ?>
 <table cellpadding="8" cellspacing="0" border="0" align=center class="recordTitle">
 <tr>
  <td colspan=3 height=20>&nbsp;</td>
 </tr>
 <tr class="link3">
 <?php
  	// Print previous page link
 	echo "<td>" . $previous_page . "</td>";
 ?>
 <td align="center"> <!-- valign="middle" -->
 <?php 	
 	$maxPageLinks = 20;
    for ($count = max(0,$curPage-$maxPageLinks/2-1);
    	 $count < min($pageNumber,$curPage+$maxPageLinks/2-1);
    	 $count++) {
        $string = "";

        $linkPage = $count + 1;
        $link = "results.php?$searchParameters&display=$display&curPage=" . $linkPage . "&startRow=" . $startRow . "&action=2&mode=" . $mode;
        if ($linkPage != $curPage) {
            $string = $string . " <a href=\"" . $link . "\" class=\"link3\"><u>";
        } else {
            $string = $string . " <b>";
        }
        $string = $string . $linkPage ;
        if ($linkPage != $curPage) {
            $string = $string . "</u></a> " ;
        } else {
            $string = $string . "</b> ";
        }

        echo $string;

    }
?>
     </td>
<?php
  	// Print next page link
 	echo "<td>" . $next_page . "</td>";
?>
    </tr>
	<tr><td colspan=3>&nbsp;</td></tr>

    </table>
<?php
    // //////////////////////////////////////////////
}

?>
<!-- This file prints the search results
 	Performing the search in the database, depending on parameters (search criteria) passed.
	The title is linked to the details of the item page (record.php) . -->
<center>
<br><br>
<hr width=500>
<br><br>
<!-- back and forward navigation arrows -->
<a href="javascript:history.forward();"><img src="images/forward_arrow_ani.gif" alt="<?php echo $lang_terms['toNextPage'][$lang] ;
?>" border=0></a><img src="images/blank.gif"><a href="javascript:history.back();"><img src="images/back_arrow_ani.gif" alt="<?php echo $lang_terms['toPrevPage'][$lang] ;
?>" border=0></a>
</center>
</body>
</html>
