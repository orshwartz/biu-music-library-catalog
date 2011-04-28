<!-- This file includes general functions and scripts -->
<?php
error_reporting(E_ERROR);

// when adding data to database, add slashes as escape characters,
// avoids problems with quotes.
// NOTE: turn the magic quotes off on the php.ini configuration file!
function process_data ($data)
{
    return (addslashes(trim($data)));
}

// when fetching data from database, strip the slashes we added
function new_mysql_result ($result, $row, $field)
{
    return (stripslashes(mysql_result($result, $row, $field)));
}

// Translates id of the media to the media name, as it was entered
// by the administrator. Returns a string with title of the media.
function mediaNamebyID($id)
{
    $qry = "select heb_name,eng_name from media where id=$id";
    $resultSet = mysql_query($qry);
    $i = 0;
    return mysql_result($resultSet, $i, "eng_name");
}

// Replaces \r\n (newlines) with <br>s.
function saveEOL ($val)
{
    return str_replace ("\r\n", "<br>", $val) ;
}

// Replaces <br>s with \r\n (newlines).
function restoreEOL ($val)
{
    return str_replace("<br>", "\r\n", $val) ;
}

?>

<script>
<!-- Replaces \r\n (newlines) with <br>s. -->
function saveEOL (val)
{
	var newVal	= val ;
	while ( newVal.search ("\r\n") != -1 )
		newVal		= newVal.replace ("\r\n", "<br>") ;
	return newVal ;
}

<!-- Replaces <br>s with \r\n (newlines). -->
function restoreEOL (val)
{
	var newVal	= val ;
	while ( newVal.search ("<br>") != -1 )
		newVal		= newVal.replace ("<br>", "\r\n") ;
	return newVal ;
}

<!-- Places mouse focus on the first text field in each page.-->
function placeFocus(formInst)
{
	if (document.forms.length > 0)
	{
		var field = document.forms[formInst];
                if (field != null)
                {
    		   for (i = 0; i < field.length; i++)
    		   {
    			if ((field.elements[i].type == "text") || (field.elements[i].type == "textarea") || (field.elements[i].type.toString().charAt(0) == "s"))
    			{
       		           document.forms[formInst].elements[i].focus();
    			   break;
                     	}
	           }
                }
         }

}
</script>
