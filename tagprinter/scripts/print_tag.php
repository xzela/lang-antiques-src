<?php
//Send Data to Serial Port
//This sends data through the serial port for the printer

//This prints out the labels and barcode
//The barcode uses code 128-C
include "send_tag_printer.php";


if(isset($_POST['print_tag'])) {
	$item_number = "";
	$mjr_class_id = "";
	$min_class_id = "";
	$item_price = "";
	$item_name = "";
	$item_description = "";
	$item_id = $_REQUEST['item_id'];

	$line_1 = $_POST['line_1'];
	$line_2 = $_POST['line_2'];
	$line_3 = $_POST['line_3'];
	$line_4 = $_POST['line_4'];
	$line_5 = $_POST['line_5'];
	
	include "open_connection.php";
	$sql = "SELECT * FROM inventory WHERE item_id = '$item_id'";
	$results = mysql_query($sql);

	while($row = mysql_fetch_array($results)) {
		$item_number = $row['item_number'];
		$mjr_class_id = $row['mjr_class_id'];
		$min_class_id = $row['min_class_id'];
		$item_price = $row['item_price'];
		$item_name = $row['item_name'];
		$item_description = $row['item_description'];
	}

	include 'close_connection.php';
	sendToPrinter($line_1, $line_2, $line_3, $line_4, $line_5, $item_number, $mjr_class_id, $min_class_id, $item_price, $item_id);
	header("Location: ../printing_done.php");
}

function padWithZeros($s, $n) {
  return sprintf("%0" . $n . "d", $s);
}

?>