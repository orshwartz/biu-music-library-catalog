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
<!-- This file displays the screen to adding, deleting and updating
	 new media types. -->
<html>
<head>
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">
	<title>מערכת הזנת נתונים</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">


        <script language="JavaScript">
		// call updateMedia.php to update the media.
        function updateit(id)
        {
        	// opens media update page in a new window
        	var val = window.open("updateMedia.php?id="+id ,"updateMediaDialog","height=150,width=500,status=no,scroll=auto,help=no,center=yes,resizable=yes,top=250,left=270");
        }

        // shows confirmation dialog, before deleting media type
        // and if user confirms, proceed with deletion by redirecting
        // and sending appropriate parameters
        function deleteit(id)
        {
			if(confirm("המדיה עומדת להימחק. להמשיך?"))
        	{
        		document.location="adminAddMedia.php?id=" + id + "&showmsg=1&action=delmedia";
        	}
        }

        // checks the fields of media form for valid input
        function checkMedia()
        {
        	if( (document.mediaForm.eng_media.value!="") && (document.mediaForm.heb_media.value!=""))
        	{
				if ( ! isInputValid(document.mediaForm.eng_media.value, <?php echo $lang_regex['name_expr'][$ENGLISH];?>))
					alert ("שם המדיה בלועזית אינו חוקי") ;
				else
					if ( ! isInputValid ( document.mediaForm.heb_media.value, <?php echo $lang_regex['name_expr'][$HEBREW];?>))
						alert("שם המדיה בעברית אינו חוקי") ;
					else
					{    //disable button
                        document.mediaForm.Badd.disabled = true;
                        document.mediaForm.showmsg.value = 1;
						document.mediaForm.submit();
					}
            }
        	else
        		alert("נא למלא כל השדות");
        }

        </script>

</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(5);">

<?php
$msg = "";
$action = &$_GET['action'];
$showmsg = &$_GET['showmsg'];

if (($action == "addmedia") || ($action == "newmedia") || ($action == "delmedia")) {
    if ($action == "addmedia") {
        // Inserting new media type
        $eng_media = process_data($_GET['eng_media']);
        $heb_media = process_data($_GET['heb_media']);

		// check if there's already a media with this name
        $qry = "select eng_name,heb_name from media where eng_name = \"" . $eng_media . "\" OR heb_name = \"" . $heb_name . "\"" ;

		// we found a media with the same name, deny
        if (($resultSet = mysql_query($qry)) != false) {
            if (mysql_num_rows($resultSet) > 0)
                setSessionMsg("לא ניתן להוסיף את המדיה. קיימת מדיה בעלת שם זהה במערכת", 1) ;

            else
            {
	            if (isset($eng_media)) {
	            	// insert the media to database
	                $qry = "insert into media values (NULL,'" . $eng_media . "','" . $heb_media . "',0)";
	                $resultSet = mysql_query($qry);
	                setSessionMsg("המדיה נוספה בהצלחה", 0) ;
	            }
	        }
        } else
            setSessionMessageDatabaseError() ;
    }
    if ($action == "delmedia") {
        // Deleting the media type
        $id = $_GET['id'];

        if (isset($id)) {
    		// set the deleted flag. we can't delete it completely cause
    		// there might be items whose media type is this media.
            $qry = "update media set deleted =1 where id = " . $id;
            if (($resultSet = mysql_query($qry)) != false)
                setSessionMsg("המדיה נמחקה בהצלחה", 0) ;
            else
                setSessionMessageDatabaseError() ;
        }
    }
}

// Quering the database for all media types and
// creating the list of it
if ($showmsg)
    displaySessionMsg() ;	// success/error messge

// the deleted medias are still in the database, so we need to check the
// deleted flag is not on.
$qry = "select id,eng_name,heb_name from media where deleted <> 1 order by id";
if (($resultSet = mysql_query($qry)) != false) {
    $num_rows = mysql_num_rows($resultSet);
    print "<form action=adminAddMedia.php method=GET name=mediaForm><table class='recordTitle' align=center border=0>";
	// add all medias
    for($i = 0; $i < $num_rows ; $i++) {
        $eng_name = new_mysql_result($resultSet, $i, "eng_name");
        $heb_name = new_mysql_result($resultSet, $i, "heb_name");
        $id = new_mysql_result($resultSet, $i, "id");
        echo "<tr><td>" . ($i + 1) . "</td><td>$eng_name&nbsp;&nbsp;$heb_name</td><td><input type=button name='Bupdate' class='recordTitle' value=\"&nbsp;עדכן&nbsp;\" onclick=\"javascript: updateit('" . $id . "')\"></td><td><input type=button name='Bdelete' class='recordTitle' value=\"מחק\" onclick=\"javascript: deleteit('" . $id . "')\"></td></tr>";
    }
    print "<tr><td colspan=4 heidth=20>&nbsp;</td></tr>";
    print "<tr><td colspan=2 align=center>אנגלית</td><td colspan=2 align=center>עברית</td></tr>";
    print "<tr><td colspan=4 align=center>";
    print "<input type=text name=eng_media>&nbsp;&nbsp;<input type=text name=heb_media dir=rtl><br><br><input type=button name='Badd' class='recordTitle' value=\"הוסף\" onclick=\"javascript: checkMedia()\"'><input type=hidden name=action value=addmedia>";
    print "<input type=hidden name=showmsg>";
    print "</td></tr>";
    print "</table></form>";
} else
    setSessionMessageDatabaseError() ;
?>
<br>
<center>
<hr width=500>
</center>

</body>
</html>