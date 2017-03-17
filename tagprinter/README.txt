README document for Tag Printer

Things You need to run this application:

Software:
	Apache2 - This runs locally on the computer that will use the tag printer
	PHP5 - This also runs locally with Apache2
		GD2 - Image render for thumbnail views of the items
		MySQL - This connects to the remote database

Hardware:
	C.iTOH - Label Printer/Barcode. It's hella old dude.
	
Method:
	The label printer uses the Serial port of the local machine to print out labels. Clarity used a custom application
	that sent hexadecimal commands which the printer use to print these labels. There is no documentation for the printer
	or what type of commands are available. All of this was done by reverse engineering the commands that were being sent
	to the printer from clarity. 

Main Files:
	scripts/php_serial.class.php - 	Main class that sends data through the serial port. I did not create that class
	scripts/send_tag_printer.php - 	Sends the data to the Printer. This is used for printing single tags or queued tags.
					This also holds all of the commands which are required by the printer. The commands 
					Are sent via Hex and mean different things. Not sure how it really works but it does. 

Everything else should be self-explanatory.