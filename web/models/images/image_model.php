<?php
/**
 * Image Model
 * 
 * This Model generates thumbnails and selects images
 * 
 * This does not upload or delete images. 
 * 
 * See mango
 * 
 * @author user
 *
 */
class Image_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Returns a thumbnail image of an item 
	 * 
	 * @param [int] $image_id = internal image id
	 * @param [int] $size = pixel size of thumbnail 
	 * 
	 * @return [object] image object (binary)
	 */
	public function getImageThumbnail($image_id, $size) {
		$this->db->from('image_base'); //@TODO rename this table 'images_external'
		$this->db->where('image_id', $image_id);
		$data = null;
		$query = $this->db->get();
		if($size < 500) {
			if($query->num_rows() > 0) {
				foreach($query->result_array() as $row) {
					$data = $this->_createThumbnail($row['image_location'], $size);
				}
			}
		}
		return $data; //object
	} //end getImageThumbnail();
	
	/**
	 * Finds the web images of a specific item
	 * and returns an array of image data
	 * 
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of image data
	 */
	public function getItemWebImages($item_id) {
		$this->db->from('image_base'); //@TODO rename this table 'images_external'
		$this->db->where('item_id', $item_id);
		$this->db->order_by('ISNULL(image_seq), image_seq ASC');
		$data = array();
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getItemWebImages();
	
	/**
	 * Private
	 * 
	 * @param [string] $path = path to image location
	 * @param [int] $size = size of image in pixels
	 * 
	 * @return [object] = image object (JEPG)
	 */
	private function _createThumbnail($path, $size) {
		$im = imagecreatefromjpeg($_SERVER["DOCUMENT_ROOT"] . $path);
		$width = imagesx($im);
		$height = imagesy($im);
		// Set thumbnail-width to 100 pixel
		$imgw = $size;
		// calculate thumbnail-height from given width to maintain aspect ratio
		$imgh = $height / $width * $imgw;
		// create new image using thumbnail-size
		//$thumb= ImageCreate($imgw,$imgh);
		$thumb= imagecreatetruecolor($imgw,$imgh);
	
		// copy original image to thumbnail
		//ImageCopyResized($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im));
		imagecopyresampled($thumb,$im,0,0,0,0,$imgw,$imgh,ImageSX($im),ImageSY($im));
	
		// show thumbnail on screen
		$out = ImagejpeG($thumb, null, 100);
		//header("Content-type: $type");
		//header("Content-length: $size");
		
		
		//print of the photo!	
			
		return $out;
		// clean memory
		//imagedestroy ($im);
		//imagedestroy ($thumb);
		
	} //end _createThumbnail();

} //end Image_model();
?>