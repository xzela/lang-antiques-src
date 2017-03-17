<?php
//Send the data to the printer
include "php_serial.class.php";

function sendToPrinter($l1, $l2, $l3, $l4, $l5, $num, $mjr, $min, $price, $id) {

	// Let's start the class
	$serial = new phpSerial;

	// First we must specify the device. This works on both linux and windows (if
	// your linux serial device is /dev/ttyS0 for COM1, etc)
	$serial->deviceSet("COM1");

	// Then we need to open it
	$serial->deviceOpen();

	//start string in hex
	$startString = "02";
	//end string in hex
	$endString = "0D";
	
	// To write into
	//These are special commands that I don't understand
	//You can modify them to see what they do, but right now i do not have the time for it	
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("e");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("n");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("SA");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("e");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("f295");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("O0218");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("LC0003");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("R0000");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("PA");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("pA");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("D11");
	$serial->sendMessage(chr(hexdec($endString)));

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("H20");
	$serial->sendMessage(chr(hexdec($endString)));	
	//End of Special Commands...
	
	
	//Lines have a 16 chara limit.

	//line 1
	$serial->sendMessage(chr(hexdec($startString)));
	//$serial->sendMessa("1911A0600890107" . ' ' . $l1); //original
	$serial->sendMessage("1911A0600840107" . ' ' . $l1);
	//$serial->sendMessage("1911A0600890107" . ' ' . $l1);
	$serial->sendMessage(chr(hexdec($endString)));
	//line 2
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1911A0600740107" . ' ' . $l2);
	$serial->sendMessage(chr(hexdec($endString)));
	//line 3
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1911A0600640107" . ' ' . $l3);
	$serial->sendMessage(chr(hexdec($endString)));
	//line 4 
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1911A0600540107" . ' ' . $l4);
	$serial->sendMessage(chr(hexdec($endString)));
	//line 5
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1911A0600440107" . ' ' . $l5);
	$serial->sendMessage(chr(hexdec($endString)));
	//End of lines
	
	
	//Barcode
	//example: 1e2101500160120C0300011839
	//barcode structure  = 1e2101500160120C	XXX-	XXX -XXXX
	//barcode command  = 1e2101500160120C
	//ID number structure:	XXX-	XXX 	-XXXX	
	//				Mjr-	Min	-ID
	//				0300011839
	//				030-001-1839
	//Barcode Type Code 128-C

	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1e2101500160120C" . padWithZeros($mjr, 3) . padWithZeros($min, 3) . padWithZeros($id, 4));
	$serial->sendMessage(chr(hexdec($endString)));	
	
	
	//Numeric item Number
	//1911A060034012530-1-1839
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1911A0600340125" . $num);
	$serial->sendMessage(chr(hexdec($endString)));

	//Price of Item
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage("1911A0600030125" . "$" . number_format($price, 2));
	$serial->sendMessage(chr(hexdec($endString)));

	//End Command for Barcode/Tag Printer
	$serial->sendMessage(chr(hexdec($startString)));
	$serial->sendMessage(".E");
	$serial->sendMessage(chr(hexdec($endString)));	

	//Closes Connection
	$serial->deviceClose();
	//echo "Done Printing tag for item: " . $num . "<br />";
	//test sleep
	//sleep(5);	

}

?>