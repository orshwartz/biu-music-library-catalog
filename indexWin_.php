<!-- This file brings up the pop-up window when clicking on the
	index button. the function openModal() calls this page, along
	with a code that defines wbich category we need to display.
	Builds the list of avialable values of requested type
    (Soloist,Performance group, Orchestra, Conductor, Subject or Composer)
 	depends on parameter passed.
	Used to simplify search process to the user, by giving to him/her
	ll possible values for selected criteria.
	Click on any item in this list closes the window with the list
	and writes selected value to the appropriate field in the search form.
 -->
  <html>
  <head>
	<link rel="icon" href="images/DataSearch.ico" type="image/x-icon">
	<link rel="shortcut icon" href="images/DataSearch.ico" type="image/x-icon">
  	<title>אינדקס</title>
  	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">

<?php
// general functions
include_once('func.php') ;
// CSS definitions
include_once('styles.inc');
// database definitions
include_once('db_common.php');

    // Possible $code parameter values :
	// 1, 15, 16:           $column = "solist";
    // 2, 17, 18:           $column = "performance_group";
	// 3, 19, 20:			$column = "orchestra";
	// 4, 21, 22:			$column = "conductor";
	// 5, 11, 12:			$column = "subject";
    // 6, 13, 14:			$column = "composer";
    // 7		:			$column = "publisher";
	// 8		:			$column = "collection";
	// 9		:			$column = "composition_formal_name";
	//10		:			$column = "series";
?>
    <script>
            function chooseVal(col,code,id,lang,id2)
            {
                 self.close();
                //Dynamically defines the value of search form field
                // and closes the window.
                if(col != "")
                {
                       // determine the direction of the text according to language.
                       // (ltr for english, rtl for hebrew)
                       if (lang == "en")
                       {
                       		// for values who has 3 feilds like
                       		// solist, solist2, solist3
                       		// we need to attach the right digit after the name
                       		// so we wont have ambiguity on fields values

                            if ((code == "11") || (code == "15")|| (code == "17") || (code == "19") || (code == "21")) //subject2, soloist2...
                               eval("opener.searchForm." + col + "2.dir=\"" + "ltr" + "\";");
                            else if ((code == "12") || (code == "16")|| (code == "18") || (code == "20") || (code == "22")) //subject3, soloist3...
                               eval("opener.searchForm." + col + "3.dir=\"" + "ltr" + "\";");
                            else
                               eval("opener.searchForm." + col + ".dir=\"" + "ltr" + "\";");

                       }
                       else if (lang == "heb")
                       {
                            if ((code == "11") || (code == "15")|| (code == "17") || (code == "19") || (code == "21")) //subject2, soloist2...
                               eval("opener.searchForm." + col + "2.dir=\"" + "rtl" + "\";");
                            else if ((code == "12") || (code == "16")|| (code == "18") || (code == "20") || (code == "22")) //subject2, soloist2...
                               eval("opener.searchForm." + col + "3.dir=\"" + "rtl" + "\";");
                            else
                               eval("opener.searchForm." + col + ".dir=\"" + "rtl" + "\";");
                       }
                       if ((code == "13") || (code == "14"))// if there are composer & composer2 and one of them was chosen a value.
                       {
                         // fill both hebrew and english fields when one of them is chosen. (admin).
                         // case is different when there is only one field. (search)
                           eval("opener.searchForm." + col + ".value=\"" + id + "\";");
                           eval("opener.searchForm." + col + ".dir=\"" + "ltr" + "\";");
                           eval("opener.searchForm." + col + "2.value=\"" + id2 + "\";");
                           eval("opener.searchForm." + col + "2.dir=\"" + "rtl" + "\";");
                       }

                       if ((code == "11") || (code == "15")|| (code == "17") || (code == "19") || (code == "21")) //subject2, soloist2...
                           eval("opener.searchForm." + col + "2.value=\"" + id + "\";");
                       else if ((code == "12") || (code == "16")|| (code == "18") || (code == "20") || (code == "22")) //subject3, soloist3...
                           eval("opener.searchForm." + col + "3.value=\"" + id + "\";");
                       else
                       {
                           eval("opener.searchForm." + col + ".value=\"" + id + "\";");
                       }
                }
                self.close();
            }
    </script>

	<script language="JavaScript">
		// creates the letters at the top, each one with its link
		function loadLetter(let,lang, code)
		{
		  document.location.href="indexWin.php?let=" + let + "&lang=" + lang + "&code=" + code;
		}
	</script>

  </head>


<!-- override the stylesheet's defaults -->
<body style="background-color: white;">
<center>
<a href="javascript: self.close();" class='recordTitle'>סגור חלון</a>
<form name=listForm><table align=center class='recordTitle'>
<tr><td class=bigTitle> נא לבחור מהרשימה </td></tr>
<tr><td height=15> </td></tr></table>
  <P>

  <?php

    if (!isset($_GET['code']))
        $code = "";
    else
        $code = $_GET['code'];

    if (!isset($_GET['lang']))
        $lang = "";
    else
        $lang = $_GET['lang'];

	// creates the letter navigation bar at top
    for ($i = ord('A') ; $i <= ord('Z') ; $i ++) {
        echo "<a class='recordTitle' href=\"javascript: loadLetter('" . chr($i) . "','en', '" . $code . "');\"><b>" . chr($i) . "</b></a>";
        echo (chr($i) == 'S') ? "<br>" : "&nbsp" ;
    }

    echo "<P><P>";

    for ($i = ord('א') ; $i <= ord('ת') ; $i ++) {
        if (! strstr ("ךםןףץ", chr($i)))
            echo "<a class='recordTitle' href=\"javascript: loadLetter('" . chr($i) . "','heb', '" . $code . "');\"><b>" . chr($i) . "</b></a>&nbsp";
    }

    echo "</center> <BR><BR>";

	// get the chosen letter (after the user chose one from the bar)
    if (!isset($_GET['let'])) {
        $let = "";
    } else {
        $let = $_GET['let'];
    }

	// check the langauge the chosen letter is at.
    if ($lang == 'en')
        print "<font size=5><b>" . $let . "</b></font>";
    else if ($lang == 'heb') // align letter to the right.
        print "<div dir=rtl><font size=5><b>" . $let . "</b></font></div>";

    // determine which column to display using its code
    switch ($code) {
        case "1":	// solist1
        case "15":	// solist2
        case "16":	// solist3
            $column = "solist";
            break;
        case "2":
        case "17":
        case "18":
            $column = "performance_group";
            break;
        case "3":
        case "19":
        case "20":
            $column = "orchestra";
            break;
        case "4":
        case "21":
        case "22":
            $column = "conductor";
            break;
        case "5":
        case "11":
        case "12":
            $column = "subject";
            break;
        case "6":	// composer (one language, as used in the search system)
        case "13":	// composer both in english and in hebrew, as
        case "14":	// used in the admin system
            $column = "composer";
            break;
        case "7":
            $column = "publisher";
            break;
        case "8":
            $column = "collection";
            break;
        case "9":
            $column = "composition_formal_name";
            break;
        case "10":
            $column = "series";
            break;

        default:
            $column = "";
    }

    // Searching the database for all possible values of requested column.
    // Takes care of non-unique results, to show each title only once.

    // for every search in hebrew "like binary" should be used in query
    // instead of "like".
    if (isset($column))
	{
		// we'll put the results in an array cause sql is no good
		// in sorting hebrew. better do it on our own.
        $matches = array();

        if (($column == "composer") || ($column == "publisher") || ($column == "collection") || ($column == "composition_formal_name") || ($column == "series"))
		{
            // Performing database query which finds all composers, whos name starts with selected letter
            $searchQry = "";
            if ($column == "composer")
			{
                if ($lang == "en")
                    $searchQry = "select composer, hebrew_composer from records where " . $column . " like '" . $let . "%' group by " . $column . "";

                if ($lang == "heb")
                    $searchQry = "select composer, hebrew_composer from records where hebrew_composer like binary '" . $let . "%' group by hebrew_composer";
                {
                    $result = mysql_query($searchQry) ;
                    $num_rows = mysql_num_rows($result);

                    if ($lang == "en") {
                        class res {
                            var $composer; //First variable: Used for sorting
                            var $hebrew_composer;
                        }
                    } else if ($lang == "heb") {
                        class res {
                            var $hebrew_composer; //First variable: Used for sorting
                            var $composer;
                        }
                    }

					// we'll put the results in an array cause sql is no good
					// in sorting hebrew. better do it on our own.

                    $resultArr = array();
                    $j = 0;
                    while ($row = mysql_fetch_array($result)) {
                        $resultArr[$j] = new res();
                        $resultArr[$j]->composer = $row["composer"];
                        $resultArr[$j]->hebrew_composer = $row["hebrew_composer"];
                        $j++;
                    }
                    sort($resultArr);	// here we do the sort,
                    					// good for both hebrew and english
                }
            } else {	// publisher, composition_formal_name, or series
                if ($lang == "en")
                    $searchQry = "select $column from records where " . $column . " like '" . $let . "%' group by " . $column . "";
                if ($lang == "heb")
                    $searchQry = "select $column from records where " . $column . " like binary '" . $let . "%' group by " . $column . "";
                {
                    $result = mysql_query($searchQry) ;
                    // sql doesn't handle hebrew well and may give incorrect results
                    // so we'll check it's output to ensure that the first letter is really $let.
                    while ($row = mysql_fetch_array($result)) {
                        if (($row[$column] {0} == strtolower($let))
								|| ($row[$column] {0} == strtoupper($let)))
                        $matches[] = $row[$column];
                    }

                    $num_rows = sizeof($matches);
                }
            }
        } else {
			// solist, solist2, solist3 and every category which has three fields
            // union all info from all columns (solist, solist2, solist3 and all other fields
            // which has more than one column)
            if ($lang == "en") {
                $strQuery = "select " . $column . " as " . $column . " from records where " . $column . " like '" . $let . "%'
                            union
                            select " . $column . "2 as " . $column . " from records where " . $column . "2 like '" . $let . "%'
                            union
                            select " . $column . "3 as " . $column . " from records where " . $column . "3 like '" . $let . "%'
                            order by " . $column . "";
            } else if ($lang == "heb") {
                $strQuery = "select " . $column . " as " . $column . " from records where " . $column . " like binary '" . $let . "%'
                            union
                            select " . $column . "2 as " . $column . " from records where " . $column . "2 like binary '" . $let . "%'
                            union
                            select " . $column . "3 as " . $column . " from records where " . $column . "3 like binary '" . $let . "%'
                            order by " . $column . "";
            }

            $result = mysql_query($strQuery);
            // sql doesn't handle hebrew well and may give incorrect results
            // so we'll check it's output to ensure that the first letter is really $let.
            while ($row = mysql_fetch_array($result)) {
                if (($row[$column] {0} == strtolower($let))
						|| ($row[$column] {0} == strtoupper($let)))
                $matches[] = $row[$column];
                if (($row[$column . "2"] {0} == strtolower($let))
					 	|| ($row[$column . "2"] {0} == strtoupper($let)))
                $matches[] = $row[$column . "2"];
                if (($row[$column . "3"] {0} == strtolower($let))
						|| ($row[$column . "3"] {0} == strtoupper($let)))
                $matches[] = $row[$column . "3"];
            }

            $num_rows = sizeof($matches);
        }

        if (!isset($num_rows))
            $num_rows = 0;

		// determine table's align by language
        switch ($lang) {
            case "en":
                echo "<table border=0 align=left class='recordTitle'>";
                break;
            case "heb":
                echo "<table border=0 align=right class='recordTitle'>";
                break;
        }

        $result_match = 0;
        // sorts the array
        sort($matches);

		// print the table with all values
        for($i = 0;$i < $num_rows;$i++) {
            echo "<tr>";

			// if column = composer we need to display the values
			// both in hebrew and in english
            if ($column == "composer") {
                $printValue = $resultArr[$i]->composer;
                $printValue2 = $resultArr[$i]->hebrew_composer;
                echo "<tr>";
                if ($code == "6") {	// used in the search system -
                					// both names are linked to same field
                    if ($lang == "en") {
                        echo "<td dir=ltr><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue) . "', '" . $lang . "')\">" . stripslashes($printValue) . "</a></td>";
                        echo "<td width=10% align=center> - </td>";
                        echo "<td dir=rtl><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue2) . "', '" . $lang . "')\">" . stripslashes($printValue2) . "</a></td>";
                    } else if ($lang == "heb") {
                        echo "<td dir=ltr><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue) . "', '" . $lang . "')\">" . stripslashes($printValue) . "</a></td>";
                        echo "<td width=10% align=center> - </td>";
                        echo "<td dir=rtl><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue2) . "', '" . $lang . "')\">" . stripslashes($printValue2) . "</a></td>";
                    }
                } else if (($code == "13") || ($code == "14")) {
                	// used in the admin system - should fill 2 fields together -
                	// composer and hebrew composer
                    echo "<td dir=ltr><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue) . "', '" . $lang . "','" . addslashes($printValue2) . "')\">" . stripslashes($printValue) . "</a></td>";
                    echo "<td width=10% align=center> - </td>";
                    echo "<td dir=rtl><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue) . "', '" . $lang . "','" . addslashes($printValue2) . "')\">" . stripslashes($printValue2) . "</a></td>";
                }
                echo "</tr>";
            } else { 	// not composers, list in one language
                $printValue = $matches[$result_match];
                $result_match++;

                if ($lang == "heb") {
                    echo "<tr>";
                    echo "<td dir=rtl><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue) . "', '" . $lang . "')\">" . stripslashes($printValue) . "</a></td>";
                    echo "</tr>";
                } else if ($lang == "en") {
                    echo "<tr>";
                    echo "<td dir=ltr><a href=\"javascript: chooseVal('" . $column . "','" . $code . "','" . addslashes($printValue) . "', '" . $lang . "')\">" . stripslashes($printValue) . "</a></td>";
                    echo "</tr>";
                }
            }

            echo "</tr>";
        }
        echo "<tr><td height=30>&nbsp;</td></tr>";
        echo "</table></form>";
    }

    ?>


  </body>
  </html>
