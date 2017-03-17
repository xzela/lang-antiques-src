
<?php
/*
* Open Connection Script
**************************************
* This opens a database connection
*/

//$dbhost = '206.222.12.10';
$dbhost = 'localhost';
$dbuser = 'phpuser';
$dbpass =  'wsbhexz';

/*$dbhost = 'localhost';
$dbuser = 'langsit_root';
$dbpass =  '1oliver=3';*/

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');

$dbname = 'langdb01';

mysql_select_db($dbname);

//For closing the connection see: close_connection.php
?>
