<?php

set_time_limit(1000);
//Send Data to Serial Port
//This sends data through the serial port for the printer

//This prints out the labels and barcode
//The barcode uses code 128-C

include "send_tag_printer.php";


if(isset($_REQUEST['print_queue'])) {

	//Get all tags where they are active
	include "open_connection.php";
	$getQueue = "SELECT * FROM inventory_tag WHERE active = 1";
	$queueResults = mysql_query($getQueue);
	while($qrow = mysql_fetch_array($queueResults)) {

		$item_number = $qrow['item_number'];
		$line_1 = $qrow['line_1'];
		$line_2 = $qrow['line_2'];
		$line_3 = $qrow['line_3'];
		$line_4 = $qrow['line_4'];
		$line_5 = $qrow['line_5'];
		$item_id = $qrow['item_id'];

		//Get the item information by it's tag
		$getItem = "SELECT * FROM inventory WHERE item_id = '$qrow[item_id]'";
		$itemResults = mysql_query($getItem);

		$item_number = "";
		$mjr_class_id = "";
		$min_class_id = "";
		$item_price = "";
		$item_name = "";
		$item_description = "";

		
		while($irow = mysql_fetch_array($itemResults)) {
			$item_number = $irow['item_number'];
			$mjr_class_id = $irow['mjr_class_id'];
			$min_class_id = $irow['min_class_id'];
			$item_price = $irow['item_price'];
			$item_name = $irow['item_name'];
			$item_description = $irow['item_description'];
			
		}
	//Send the information to the Printer
	sendToPrinter($line_1, $line_2, $line_3, $line_4, $line_5, $item_number, $mjr_class_id, $min_class_id, $item_price, $item_id);
	$removeItem = "UPDATE inventory_tag SET active = 0 WHERE item_id = '$item_id'";
	mysql_query($removeItem);
	
	}
	
	include 'close_connection.php';
	header("Location: ../printing_done.php");
}

function padWithZeros($s, $n) {
  return sprintf("%0" . $n . "d", $s);
}

?>