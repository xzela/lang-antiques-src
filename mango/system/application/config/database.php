<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/


$active_record = TRUE;
$active_group = "default"; //set the active group to default

if($_SERVER['HTTP_HOST'] == 'lang.localhost' || $_SERVER['HTTP_HOST'] == '50.57.31.192' || $_SERVER['HTTP_HOST'] == 'langantiques.com') {
	$active_group = "langdb01";
}
else if($_SERVER['HTTP_HOST'] == 'fran.localhost' || $_SERVER['HTTP_HOST'] == '50.57.31.192' || $_SERVER['HTTP_HOST'] == 'francesklein.com') {
	$active_group = "frandb01";
}

// Added "default" array container.
$db['default']['hostname'] = "localhost";
$db['default']['username'] = "phpuser";
$db['default']['password'] = "wsbhe3xz";
$db['default']['database'] = "langdb01";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

$db['langdb01']['hostname'] = "localhost";
$db['langdb01']['username'] = "phpuser";
$db['langdb01']['password'] = "wsbhe3xz";
$db['langdb01']['database'] = "langdb01";
$db['langdb01']['dbdriver'] = "mysql";
$db['langdb01']['dbprefix'] = "";
$db['langdb01']['pconnect'] = FALSE;
$db['langdb01']['db_debug'] = TRUE;
$db['langdb01']['cache_on'] = FALSE;
$db['langdb01']['cachedir'] = "";
$db['langdb01']['char_set'] = "utf8";
$db['langdb01']['dbcollat'] = "utf8_general_ci";

$db['frandb01']['hostname'] = "localhost";
$db['frandb01']['username'] = "phpuser";
$db['frandb01']['password'] = "wsbhe3xz";
$db['frandb01']['database'] = "frandb01";
$db['frandb01']['dbdriver'] = "mysql";
$db['frandb01']['dbprefix'] = "";
$db['frandb01']['pconnect'] = FALSE;
$db['frandb01']['db_debug'] = TRUE;
$db['frandb01']['cache_on'] = FALSE;
$db['frandb01']['cachedir'] = "";
$db['frandb01']['char_set'] = "utf8";
$db['frandb01']['dbcollat'] = "utf8_general_ci";

/* End of file database.php */
/* Location: ./system/application/config/database.php */
