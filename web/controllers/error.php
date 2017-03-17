<?php
//http://maestric.com/doc/php/codeigniter_404

/**
 * Not sure if this is still used.
 * Anyway, directs the user to error pages
 * 
 * @author zeph
 *
 */
class Error extends Controller {
 	
 	function __construct() {
 		parent::Controller();
 	}

 	function error_404() {
		$ci =& get_instance();
		$ci->output->set_status_header('404');
		$ci->load->view('pages/404_view');
	}
	
	public function page_not_found() {
		$data = array();
		$this->load->view('pages/404_view', $data);
	}
}

?>