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

function get_php_string($str)
{
	//return htmlspecialchars($str, ENT_QUOTES);
	$tmp = str_replace ("\"", '&quot;', $str);
	$tmp = str_replace ("'", '&#039;', $tmp);
	return $tmp;
}

function my_add_slashes($str)
{
	return str_replace ("\"", "\\\"", $str);
}

function get_hacked_string($str)
{
	return str_replace ("\"", 'zQuotesz', $str);
}

function restore_hacked_string($str)
{
	return str_replace ('zQuotesz', "\"", $str);
}

// using first letter in the field to determine its langauge.
// the alignment should be set according to this.
function determineLang($field)
{
    // Reset non-standard character counter
	$non_standard_char_count = 0;
	
	// For each character in string
	$field_len = strlen($field);
	for ($cur_char_idx = 0; $cur_char_idx < $field_len; ++$cur_char_idx)
	{
		// If this is a non-standard ASCII code (meaning, extended)
		if (127 < ord($field[$cur_char_idx]))
		{
			// Increase non-standard char count
			$non_standard_char_count++;
		}
		
		// If found at-least one non-standard code
		if ($non_standard_char_count > 0)
		{
			// Assume Hebrew text
			return "heb";
		}
	}
	
	// Assume English text
	return "en";
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

function my_add_slashes (val)
{
	var newVal = "";
	var i = 0 ;
	for (i = 0; i < val.length ; i++ )
	{
		if (val.charAt(i) == "\"")
		{
			newVal = newVal.concat("\\\"");
		}
		else
		{
			newVal = newVal.concat(val.charAt(i));
		}
	}
	
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
