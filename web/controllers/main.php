<?php
/**
 * Main (default) controller for web
 *
 * @author user
 *
 */
class Main extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->helper('ssl');
		remove_ssl();
	}
	/**
	 * Loads the main_view view.
	 */
	function index() {
		$this->load->view('main_view.php');
	}
}


?>