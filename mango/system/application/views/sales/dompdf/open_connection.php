<?php
/*
* Open Connection Script
**************************************
* This opens a database connection
*/

$dbhost = 'localhost';
$dbuser = 'phpuser';
$dbpass =  'wsbhe3xz';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql: ' . mysql_error());
$dbname = 'langdb01';

mysql_select_db($dbname);

//Set the user_id here:
if (isset($_SESSION['user_id'])) {
	$setUSER_ID = "SET @user_id = $_SESSION[user_id];";
	mysql_query($setUSER_ID) or die("error setting user_id $setUSER_ID <br />" . mysql_error());
}
else {
	$setUSER_ID = "SET @user_id = 0;";
	mysql_query($setUSER_ID) or die("error setting user_id $setUSER_ID <br />" . mysql_error());
}


//For closing the connection see: close_connection.php
?>
