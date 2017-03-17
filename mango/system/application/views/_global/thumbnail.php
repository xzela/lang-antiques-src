<?php
/**
* Creates a thumbnail image pulled from the database
***********************************************************
* I had some problems with including the database connection scripts
* so i just included the actual contents of those files in here. For some
* strange reason it was breaking the image out. I blame windows. 
* 
* @param [string] image_location = image location;
* 
* @return prints the image in raw form!
*/
if(isset($_REQUEST['image_location'])) {
	//Set all of the header content here
	$image_location = $_REQUEST['image_location'];
	$image_type = $_REQUEST['image_type'];
	$image_size = $_REQUEST['image_size'];
	if(isset($_REQUEST['thumb_size'])) {
		$imgw = $_REQUEST['thumb_size'];
	}
	else {
		// Set thumbnail-width to 75 pixel
		$imgw = 75;
		
	}
	// get originalsize of image
	$im = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"] . $image_location);
	//$im = imagecreatefromjpeg($doc_root . $image_location);
	//$im = imagecreatefromjpeg($image_location);
	$width = imagesx($im);
	$height = imagesy($im);
		
	// calculate thumbnail-height from given width to maintain aspect ratio
	$imgh = $height / $width * $imgw;
	// create new image using thumbnail-size
	$thumb= imagecreatetruecolor($imgw,$imgh);
	
	// copy original image to thumbnail
	ImageCopyResized($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im));
	
	$out = ImagejpeG($thumb); // show thumbnail on screen
	
	//Set Header Stuff
	//header("Content-type: $image_type");
	//header("Content-length: $image_size");
			
	print($out); //print of the photo!
	
	// clean memory
	imagedestroy ($im);
	imagedestroy ($thumb);
	
}


?>