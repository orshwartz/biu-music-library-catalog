<!-- This file defines legal regular expressions for each field
	and page/table titles in hebrew and english -->
<?php

$ENGLISH = 0 ;
$HEBREW = 1 ;
$LANG_NUM = $ALL_LANG = 2 ;

$lang_regex['year_expr'] = "/^[\d\-]*$/" ;
$lang_regex['item_no_expr'] = "/^[A-Za-z0-9\-]+$/" ;

$lang_regex['name_expr'][$ENGLISH] = "/^[A-Za-z\d-,?\'\"\(\)\.!;: ]*$/" ;
$lang_regex['name_expr'][$HEBREW] = "/^[�-�\d-,?\'\"\(\)\.!:; ]*$/" ;
$lang_regex['name_expr'][$ALL_LANG] = "/^[�-�A-Za-z\d-,?\'\"\(\)\.!:; ]*$/" ;

$lang_regex['free_text'][$ENGLISH] = "/^[^�-�]*$/" ;
$lang_regex['free_text'][$HEBREW] = "/^[^A-Za-z]*$/" ;

// the following are used by the results page, where the display can be
// in hebrew or in english.
$lang_directions[$ENGLISH] = 'ltr' ;
$lang_directions[$HEBREW] = 'rtl' ;

$lang_aligns[$ENGLISH] = 'left' ;
$lang_aligns[$HEBREW] = 'right' ;

$lang_terms['toPrevResults'][$ENGLISH] = 'To previous results' ;
$lang_terms['toPrevResults'][$HEBREW] = '������� �������' ;

$lang_terms['toNextResults'][$ENGLISH] = 'To next results' ;
$lang_terms['toNextResults'][$HEBREW] = '������� �����' ;

$lang_terms['title'][$ENGLISH] = 'Title (Composition, Song)' ;
$lang_terms['title'][$HEBREW] = '���� (�����, ���)' ;

$lang_terms['secondTitle'][$ENGLISH] = 'Added title' ;
$lang_terms['secondTitle'][$HEBREW] = '���� ����' ;

$lang_terms['search'][$ENGLISH] = 'Search' ;
$lang_terms['search'][$HEBREW] = '���' ;

$lang_terms['toNextPage'][$ENGLISH] = 'To next page' ;
$lang_terms['toNextPage'][$HEBREW] = '����� ���' ;

$lang_terms['toPrevPage'][$ENGLISH] = 'To previous page' ;
$lang_terms['toPrevPage'][$HEBREW] = '����� �����' ;

$lang_terms['searchResults'][$ENGLISH] = 'Search Results' ;
$lang_terms['searchResults'][$HEBREW] = '������ ������' ;

$lang_terms['fullDetails'][$ENGLISH] = 'Full Details' ;
$lang_terms['fullDetails'][$HEBREW] = '����� �����' ;

$lang_terms['fullDescrAction'][$ENGLISH] = 'For a full description click the title or item number' ;
$lang_terms['fullDescrAction'][$HEBREW] = '������ ��� �� ����� ��� �� ����� �� �� ���� �����' ;

$lang_terms['composer'][$ENGLISH] = 'Composer' ;
$lang_terms['composer'][$HEBREW] = '�����' ;

$lang_terms['searchGoogle'][$ENGLISH] = 'Search in Google' ;
$lang_terms['searchGoogle'][$HEBREW] = '��� �-Google' ;

$lang_terms['media'][$ENGLISH] = 'Media' ;
$lang_terms['media'][$HEBREW] = '����' ;

$lang_terms['resultsFound'][$ENGLISH] = '<num> result(s) were found' ;
$lang_terms['resultsFound'][$HEBREW] = '������ <num> ������' ;

$lang_terms['composerInHebrew'][$ENGLISH] = 'Composer name in Hebrew' ;
$lang_terms['composerInHebrew'][$HEBREW] = '�� ������ ������' ;

$lang_terms['composerInEnglish'][$ENGLISH] = 'Composer name in English' ;
$lang_terms['composerInEnglish'][$HEBREW] = '�� ������ �������' ;

$lang_terms['publisher'][$ENGLISH] = 'Publisher' ;
$lang_terms['publisher'][$HEBREW] = '����� ����' ;

$lang_terms['publishLocation'][$ENGLISH] = 'Publishing location' ;
$lang_terms['publishLocation'][$HEBREW] = '���� ����� ����' ;

$lang_terms['year'][$ENGLISH] = 'Year' ;
$lang_terms['year'][$HEBREW] = '���' ;

$lang_terms['coAuthor'][$ENGLISH] = 'Co-author' ;
$lang_terms['coAuthor'][$HEBREW] = '���� ����' ;

$lang_terms['compositionFormalName'][$ENGLISH] = 'Composition\'s formal name' ;
$lang_terms['compositionFormalName'][$HEBREW] = '�� ����� �� ������' ;

$lang_terms['solist'][$ENGLISH] = 'Solist' ;
$lang_terms['solist'][$HEBREW] = '����' ;

$lang_terms['performanceGroup'][$ENGLISH] = 'Performance group' ;
$lang_terms['performanceGroup'][$HEBREW] = '����� �����' ;

$lang_terms['orchestra'][$ENGLISH] = 'Orchestra' ;
$lang_terms['orchestra'][$HEBREW] = '������/�����' ;

$lang_terms['conductor'][$ENGLISH] = 'Conductor' ;
$lang_terms['conductor'][$HEBREW] = '����' ;

$lang_terms['notes'][$ENGLISH] = 'Notes' ;
$lang_terms['notes'][$HEBREW] = '�����' ;

$lang_terms['series'][$ENGLISH] = 'Series' ;
$lang_terms['series'][$HEBREW] = '����' ;

$lang_terms['subject'][$ENGLISH] = 'Subject' ;
$lang_terms['subject'][$HEBREW] = '����' ;

$lang_terms['collection'][$ENGLISH] = 'Donation' ;
$lang_terms['collection'][$HEBREW] = '�����' ;

$lang_terms['itemNo'][$ENGLISH] = 'Item number' ;
$lang_terms['itemNo'][$HEBREW] = '���� ����' ;

// the following are used by the confirmation messages in the
// index update section of the admin system
$lang_terms['confirmUpdateIndexToExisting'][$HEBREW] = "<index_type> ��� <existing_index> ��� ����. ��� ����� ��� ���?" ;
$lang_terms['confirmUpdateIndexToExisting'][$ENGLISH] = "<index_type> with the name '<existing_index>' already exists. Update anyway ?" ;

$lang_terms['alertSourceIndexNotExists'][$ENGLISH] = "<index_type> with the name '<source_index>' does not exist" ;

?>