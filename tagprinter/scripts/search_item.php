<?php
//Find an Item from the searching method


if(isset($_POST['submit_search'])) {

	$item_id = $_POST['search_txt'];
	include 'open_connection.php';
	
	$sql = "SELECT * FROM inventory WHERE item_number = '$item_id'";
	$results = mysql_query($sql);
	echo mysql_num_rows($results);
	if (mysql_num_rows($results) > 0) {
		$id = "";
		while ($row = mysql_fetch_array($results)) {
			$id = $row['item_id'];
			//print_r ($row);
		}
		header("Location: ../edit_tag.php?item_id=$id");
	}
	else {
		header("Location: ../search_for_item.php?nothing=1");
	}
	include 'close_connection.php';
}
?>