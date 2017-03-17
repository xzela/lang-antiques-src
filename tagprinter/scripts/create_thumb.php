<?php
/**
* Creates a thumbnail image pulled from the database
***********************************************************
* I had some problems with including the database connection scripts
* so i just included the actual contents of those files in here. For some
* strang reason it was breaking the image out. I blame windows. 
*/
//include '../scripts/open_connection.php';
//Set the database prams here:
$dbhost = 'dev01';
$dbuser = 'phpuser';
$dbpass =  'password';

//open connection to database
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
//Select database name
$dbname = 'langdb01';
mysql_select_db($dbname);

//id of image you want to get.
$id = $_REQUEST['image_id'];
if (isset($_REQUEST['type']) && $_REQUEST['type'] == 8) {
	$sql = "SELECT image_content, image_name, image_size, image_type FROM image_lang WHERE image_id = $id";
}
else {
	$sql = "SELECT image_content, image_title, image_size, image_type FROM image_base WHERE image_id = $id";
}

$result = mysql_query($sql);

//close the database connection
mysql_close($conn);
//include '../scripts/close_connection.php';


// read imagetype + -data from database
if(mysql_num_rows($result) == 1) {
	//Set all of the header content here
	$photo = mysql_result($result, 0, "image_content");
	$type = mysql_result($result, 0, "image_type");
	$size = mysql_result($result, 0, "image_size");

	// get originalsize of image
	$im = imagecreatefromstring($photo);
	$width = imagesx($im);
	$height = imagesy($im);
	// Set thumbnail-width to 100 pixel
	$imgw = 120;
	// calculate thumbnail-height from given width to maintain aspect ratio
	$imgh = $height / $width * $imgw;
	// create new image using thumbnail-size
	//$thumb= ImageCreate($imgw,$imgh);
	$thumb= imagecreatetruecolor($imgw,$imgh);

	// copy original image to thumbnail
	ImageCopyResized($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im));

	// show thumbnail on screen
	$out = ImagejpeG($thumb);
	header("Content-type: $type");
	header("Content-length: $size");
	//print of the photo!		
	print($out);
	// clean memory
	imagedestroy ($im);
	imagedestroy ($thumb);
}
?>

