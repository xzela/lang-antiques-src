<?php
/**
 * Renders Thumbnails of images
 * 
 * 
 * @author zeph
 *
 */
class Images extends Controller {
	
	var $ci;
	
	function __construct() {
		parent::Controller();
	}
	
	/**
	 *  Redirects the user to the homepage if
	 *  they try to access the virtual image directory
	 */
	public function index() {
		redirect('/', 'refresh');
	}
	
	/**
	 * Returns a thumbnail version of the image.
	 * 
	 * @param [int|150] $size = size (in pixels) of the image to render
	 * @param [int] $image_id = id of image (see langdb01.image_base)
	 *  
	 */
	public function thumbnails($size = 150, $image_id) {
		header('Content-type: image/jpeg');
		
		$this->load->model('images/image_model');
		$image_id = substr($image_id, 0, -4);
		$data = $this->image_model->getImageThumbnail($image_id, $size);
		
		return $data['thumbnail'];
	}
}