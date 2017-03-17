<?php
class Database {
	
	var $conn;

	function open() {
		$this->conn = mysql_connect('localhost', 'phpuser', 'wsbhexz') or die('Error connecting to database: ' . mysql_error());
		mysql_select_db('langdb01');
	}
	
	
	function close() {
		mysql_close($this->conn);
	}
	
	function query($sql) {
		$query = mysql_query($sql) or die('Error: ' . mysql_error());
		
		if($query) {
			return $query;
		}
		else {
			return 'Error running query: ' . $sql . '<br />' . mysql_error(); 
		}
	}
}

?>