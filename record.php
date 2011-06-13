
<!-- This file prints all non empty details of a selected item.
 	If some property of the item has no value - it's not shown.
	Searches the database by $id, passed to this file as parameter
	from results.php . -->

<?php
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
// Creating the query, to search the database.
$id = $_GET['id'];
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

$direction = $lang_directions[$lang] ;
$align = $lang_aligns[$lang] ;
$query = "select * from records where id=" . $id;
$result = mysql_query($query) ;

// Echo the data aligned according to current language and in direction
// according to the field text's language.
function echoFieldData($field_val) {
	
	global $lang_directions, $ENGLISH, $HEBREW, $align;
	
	if (determineLang($field_val) == "en") {
		$field_dir = $lang_directions[$ENGLISH];
	}
	else {
		$field_dir = $lang_directions[$HEBREW];
	}
    echo "<td dir=" . $field_dir . " align=" . $align . ">";
    echo $field_val;
}

// table title
echo "<br><center>";
echo "<table>
        <tr>
     		<td class='bigTitle' align=$align><b>{$lang_terms['fullDetails'][$lang]}</b>
        </tr>
      </table>
      <br></center>";

?>

<!-- create the table.
	 use the direction determined above so that the alignment will be correct.
	 use titles as defined in lang.php, using hebrew/english.
	 fetch the data from the result we got from database earlier
	 at results.php .
-->
<table border=1 bordercolor=black align=center class='dataTable' dir=<?php echo $direction ;?>>
	<tr dir=<?php echo $direction ;?>>
		<td align= <?php echo $align ;?>><b><?php echo $lang_terms['media'][$lang] ;?></b></td>
			<?php
			echo "<td dir=" . $direction . ">";
			// Determine the apropriate media title for this item
			$media_id = new_mysql_result($result, 0, "media_id");
			$MediaQuery = "select eng_name,heb_name from media where id=" . $media_id;
			$MediaResult = mysql_query($MediaQuery) ;
			$eng_name = new_mysql_result($MediaResult, 0, "eng_name");
			$heb_name = new_mysql_result($MediaResult, 0, "heb_name");
			echo($display == 'eng' ? $eng_name : $heb_name) ;

			?>
		</td>
	</tr>

    <?php if (($item_no = new_mysql_result($result, 0, "item_no")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?>><b><?php echo $lang_terms['itemNo'][$lang] ;?></b></td>
			 <?php
			// Item's number is created as link.
			// Clicking this link, runs the search for all items that have exactly the same item number.
			echo "<td dir=" . $direction . ">";
			echo "<a href=\"results.php?display=$display&action=1&item_no=" . $item_no . "\">" . $item_no . "</a>";

			?>
		</td>
	</tr>
	<?php } ?>

	<?php if (($composer = new_mysql_result($result, 0, "composer")) != "") {?>
	<tr>
	     <td align=<?php echo $align;?>>
            <b><?php echo $lang_terms['composerInEnglish'][$lang];?>&nbsp;</b>
			<?php
				echoFieldData($composer);
			?>
		</td>
	</tr>
	<?php } ?>

	<?php if (($hebrew_composer = new_mysql_result($result, 0, "hebrew_composer")) != "") {?>
	<tr>
		<td align=<?php echo $align;?>><b><?php echo $lang_terms['composerInHebrew'][$lang] ;?></b></td>
			<?php
				echoFieldData($hebrew_composer);
			?>
		</td>
	</tr>
	<?php } ?>

	<?php if (($composition_formal_name = new_mysql_result($result, 0, "composition_formal_name")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> > <b><?php echo $lang_terms['compositionFormalName'][$lang] ;?></b></td>
			<?php
				echoFieldData($composition_formal_name);
    		?>&nbsp;
		</td>
	</tr>
	<?php } ?>

	<?php if (($composition_title = new_mysql_result($result, 0, "composition_title")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> ><b><?php echo $lang_terms['title'][$lang] ;?></b></td>
			<?php
				echoFieldData($composition_title);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($publisher = new_mysql_result($result, 0, "publisher")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> ><b><?php echo $lang_terms['publisher'][$lang] ;?></b></td>
			<?php
				echoFieldData($publisher);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($publisher_place = new_mysql_result($result, 0, "publisher_place")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> ><b><?php echo $lang_terms['publishLocation'][$lang] ;?></b></td>
			<?php
				echoFieldData($publisher_place);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($year = new_mysql_result($result, 0, "year")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> ><b><?php echo $lang_terms['year'][$lang] ;?></b></td>
			<?php
				echoFieldData($year);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($solist = new_mysql_result($result, 0, "solist")) != "") {?>
	<tr dir=<?php echo $direction ;?>>
		<td align=<?php echo $align ;?> ><b><?php echo $lang_terms['solist'][$lang] ;?></b></td>
			<?php
				echoFieldData($solist);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($solist2 = new_mysql_result($result, 0, "solist2")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> dir=<?php echo $lang_directions[$lang];?>><b><?php echo $lang_terms['solist'][$lang] . " 2" ;?></b></td>
			<?php
				echoFieldData($solist2);
	    	?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($solist3 = new_mysql_result($result, 0, "solist3")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> dir=<?php echo $lang_directions[$lang];?>><b><?php echo $lang_terms['solist'][$lang] . " 3" ;?></b></td>
			<?php
				echoFieldData($solist3);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($performance_group = new_mysql_result($result, 0, "performance_group")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> ><b><?php echo $lang_terms['performanceGroup'][$lang] ;?></b></td>
			<?php
 				echoFieldData($performance_group);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($perfomance_group2 = new_mysql_result($result, 0, "performance_group2")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> dir=<?php echo $direction;?>><b><?php echo $lang_terms['performanceGroup'][$lang] . " 2" ;?></b></td>
			<?php
				echoFieldData($perfomance_group2);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($perfomance_group3 = new_mysql_result($result, 0, "performance_group3")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> dir=<?php echo $lang_directions[$lang];?>><b><?php echo $lang_terms['performanceGroup'][$lang] . " 3" ;?></b></td>
			<?php
				echoFieldData($perfomance_group3);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($orchestra = new_mysql_result($result, 0, "orchestra")) != "") {?>
	<tr>
		<td align=<?php echo $align;?>><b><?php echo $lang_terms['orchestra'][$lang] ;?></b></td>
			<?php
				echoFieldData($orchestra);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($orchestra2 = new_mysql_result($result, 0, "orchestra2")) != "") {?>
	<tr>
		<td align=<?php echo $align;?> dir=<?php echo $direction;?>><b><?php echo $lang_terms['orchestra'][$lang] . " 2" ;?></b></td>
			<?php
				echoFieldData($orchestra2);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($orchestra3 = new_mysql_result($result, 0, "orchestra3")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> dir=<?php echo $direction ;?>><b><?php echo $lang_terms['orchestra'][$lang] . " 3" ;?></b></td>
			<?php
				echoFieldData($orchestra3);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

 	<?php if (($conductor = new_mysql_result($result, 0, "conductor")) != "") {?>
	<tr>
		<td align=<?php echo $align;?>> <b><?php echo $lang_terms['conductor'][$lang] ;?></b></td>
			<?php
				echoFieldData($conductor);
	    	?>&nbsp;
		</td>
	</tr>
	<?php }?>

  	<?php if (($conductor2 = new_mysql_result($result, 0, "conductor2")) != "") {?>
	<tr>
		<td align=<?php echo $align;?> dir=<?php echo $direction;?>><b><?php echo $lang_terms['conductor'][$lang] . " 2" ;?></b></td>
			<?php
				echoFieldData($conductor2);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($conductor3 = new_mysql_result($result, 0, "conductor3")) != "") {?>
	<tr>
		<td align=<?php echo $align;?> dir=<?php echo $direction;?>><b><?php echo $lang_terms['conductor'][$lang] . " 3" ;?></b></td>
			<?php
				echoFieldData($conductor3);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($notes = new_mysql_result($result, 0, "notes")) != "") {
    $notes = restoreEOL($notes)?>
    <!-- use resotreEOL to display newlines correctly-->
	<tr>
		<td align=<?php echo $align ;?>><b><?php echo $lang_terms['notes'][$lang] ;?></b></td>
			<?php
    		echo "<td dir=" . $direction . ">";
    		?>

				<?php
						echo "<textarea scrolling=no rows='6' cols='40' wrap='soft' dir=";  
						echo (determineLang($notes)=="en")?$lang_directions[$ENGLISH]:$lang_directions[$HEBREW];
						echo " style='width: 100%;overflow:auto' readonly=\"readonly\">";
						echo $notes;
						echo "</textarea>";
				?>
		</td>
	</tr>
	<?php }?>

	<?php if (($series = new_mysql_result($result, 0, "series")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?>><b><?php echo $lang_terms['series'][$lang] ;?></b></td>
			<?php
				echoFieldData($series);
	    	?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($subject = new_mysql_result($result, 0, "subject")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?>><b><?php echo $lang_terms['subject'][$lang] ;?></b></td>
			<?php
				echoFieldData($subject);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($subject2 = new_mysql_result($result, 0, "subject2")) != "") {?>
	<tr>
		<td align=<?php echo $align;?> dir= <?php echo $direction ;?>><b><?php echo $lang_terms['subject'][$lang] . " 2" ;?></b></td>
			<?php
				echoFieldData($subject2);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($subject3 = new_mysql_result($result, 0, "subject3")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?> dir=<?php echo $direction ;?>><b><?php echo $lang_terms['subject'][$lang] . " 3" ;?></b></td>
			<?php
				echoFieldData($subject3);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($item_second_title = new_mysql_result($result, 0, "item_second_title")) != "") {?>
	<tr>
		<td align= <?php echo $align ;?> ><b><?php echo $lang_terms['secondTitle'][$lang] ;?></b></td>
			<?php
				echoFieldData($item_second_title);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($secondAuthor = new_mysql_result($result, 0, "second_author")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?>><b><?php echo $lang_terms['coAuthor'][$lang] ;?></b></td>
			<?php
				echoFieldData($secondAuthor);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>

	<?php if (($collection = new_mysql_result($result, 0, "collection")) != "") {?>
	<tr>
		<td align=<?php echo $align ;?>><b><?php echo $lang_terms['collection'][$lang] ;?></b></td>
			<?php
				echoFieldData($collection);
    		?>&nbsp;
		</td>
	</tr>
	<?php }?>
</table>

<center>
<br><br>
</center>

</body>
</html>
