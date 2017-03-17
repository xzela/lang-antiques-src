<?php
include 'database.class.php';

function getTagQueues() {
	$conn = new Database();
	$sql = 'SELECT * FROM inventory_tag_queue';
	$conn->open();
	$query = $conn->query($sql);
	
	$data = array();
	if(mysql_num_rows($query) > 0) {
		foreach(mysql_result_array() as $row) {
			
			$data[$row['queue_id']] = $row;
		}		
	}
	return $data;
}

?>