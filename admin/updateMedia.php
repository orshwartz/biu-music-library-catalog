<?php
session_start();
// general functions
include_once('../func.php');
// database definitions
include_once('../db_common.php');
// CSS definitions
include_once('../styles.inc');
// alerts and session messages display
include_once('../common.php');

?>
<!-- This file is used when the "update media" button is clicked.
	It shows a pop-up window where one can change the media names. -->
<html>
<head>
	<link rel="icon" href="../images/DataInput.ico" type="image/x-icon">
	<link rel="shortcut icon" href="../images/DataInput.ico" type="image/x-icon">
	<title>עדכון מדיה</title>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1255">
</head>

<!-- places the focus on the first field in the form -->
<body onLoad="placeFocus(0);">
<br>

<?php

$flag = 0;
foreach($_GET as $key => $value) {
    $flag++;
} ;

if ($flag != 0) {
    $action = &$_GET['action'];
    $id = $_GET['id'];

    if (isset($action)) {	// if we clicked the "save changes" button
        // Perform update in the database, according to the input of the administrator
        $eng_name = process_data($_GET['eng_name']);
        $heb_name = process_data($_GET['heb_name']);
        $qry = "update media set eng_name='" . $eng_name . "', heb_name='" . $heb_name . "' where id=" . $id;
        $resultSet = mysql_query($qry);
        $num_rows = mysql_num_rows($resultSet);
        setSessionMsg("המדיה עודכנה בהצלחה", 0) ;

        ?>
        <script language=JavaScript>
          // link back to previous screen
          opener.location="adminAddMedia.php?showmsg=1&action=newmedia";
          self.close();
        </script>
<?php

    } else {	// we called this page from "Adminaddmedia.php"
        // Show current values, stored in the database
        $qry = "select eng_name,heb_name from media where id=" . $id;
        $resultSet = mysql_query($qry);
        $num_rows = mysql_num_rows($resultSet);
        print "<form action=updateMedia.php method=GET name=mediaForm>
         <table class='recordTitle' align=center border=0>";
        print "<tr><td align=center>אנגלית</td><td  align=center>עברית</td></tr>";
        for($i = 0;$i < $num_rows;$i++) {
            $eng_name = new_mysql_result($resultSet, $i, "eng_name");
            $heb_name = new_mysql_result($resultSet, $i, "heb_name");
            echo "<tr><td dir=ltr><input type=text name=eng_name value=\"" . $eng_name . "\">&nbsp;&nbsp;</td><td dir=rtl><input type=text name=heb_name value=\"" . $heb_name . "\"</td></tr>";
        }
        print "<tr><td colspan=2 align=center><input type=button value=\" סגור בלי לשמור \" onclick=\"javascript: self.close();\"><input type=button name='Bsave' value=\" שמור עדכונים \" onClick=\"javascript:Bsave.disabled=true;document.mediaForm.submit();\"></td></tr></table><input type=hidden name=id value=" . $id . "><input type=hidden name=action value=1></form>";
    }
}

?>

</body>
</html>

