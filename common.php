<?php
// used in order to use session messages
session_start();

// displays a message - either in an alert box or at the top of the page.
function displayMsg ($msg, $isAlertMsg)
{
    if ($isAlertMsg == 1)
        echo "<script> alert (\"" . $msg . "\") ;</script>" ;
    else
        echo "<center><b>" . $msg . "</b></center></style><br><br>" ;
}

// sets a session message
function setSessionMsg ($msg, $isError)
{
    $_SESSION['msg'] = $msg ;
    $_SESSION['is_err_msg'] = $isError ;
}

// displays a session message
function displaySessionMsg()
{
    displayMsg ($_SESSION['msg'] , $_SESSION['is_err_msg']) ;
}

// sets the session message to database error
function setSessionMessageDatabaseError()
{
    setSessionMsg("שגיאה במסד הנתונים", 1) ;
}

?>
<!-- This file is used whenever messages are displayed (errors,
	alerts, etc). Plus, validates the regular expressions. -->
<!-- checks if the data typed in a table field is legal.
	validation is determined by regular expressions defined in lang.php. -->
<script language="JavaScript">
	function isInputValid ( inputStr,  inputType)
	{
		exp = new RegExp ( inputType) ;
		return exp.test(inputStr) ;
	}
</script>