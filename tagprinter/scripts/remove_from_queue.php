<?php
//Removes (disables) a tag from the queue
if(isset($_REQUEST['remove'])) {
	$tag_id = $_REQUEST['tag_id'];
	
	include "open_connection.php";
		//Set the active tag to 0, disable
		$sql = "UPDATE inventory_tag SET active = 0 WHERE tag_id = '$tag_id'";
		mysql_query($sql);
	include "close_connection.php";
	
	header("Location: ../view_queue.php");
}

?>