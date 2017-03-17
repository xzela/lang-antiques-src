<?php
/**
 * This is the Static pages controller
 * This loads all of the static pages which are
 * not related to the products
 *
 * @author zeph
 *
 */
class Pages extends Controller {

	function __construct() {
		parent::Controller();
		$this->session->unset_userdata('parent_id');
		$this->load->helper('ssl');
		remove_ssl();

	}
	/**
	 * Redirect users back to root directory
	 *
	 */
	public function index() {
		redirect('/', 'refresh');
	}

	/**
	 * Load the 'sell-your-jewelry' page
	 *
	 */
	public function selling_your_jewelry() {
		$data = array();
		$this->load->model('pages/page_content');
		$data['content'] = $this->page_content->get_content('selling_jewelry');

		$this->load->view('pages/selling_jewelry_view', $data);
	}

	/**
	 * Load 'contact-us' page
	 *
	 */
	public function contact_us() {
		$data = array();

		$this->load->view('pages/contact_view', $data);
	}
	/**
	 * Loads the 'decorative-periods' page,
	 *
	 * @param [string] $period = specific period;
	 */
	public function decorative_periods($period = null) {
		$data = array();
		switch($period) {
			case 'georgain-period':
				$this->load->view('pages/periods/georgain_period_view', $data);
				break;
			case 'victorian-period':
				$this->load->view('pages/periods/victorian_period_view', $data);
				break;
			case 'art-nouveau-period':
				$this->load->view('pages/periods/art_nouveau_period_view', $data);
				break;
			case 'edwardian-period':
				$this->load->view('pages/periods/edwardian_period_view', $data);
				break;
			case 'art-deco-period':
				$this->load->view('pages/periods/art_deco_period_view', $data);
				break;
			case 'retro-period':
				$this->load->view('pages/periods/retro_period_view', $data);
				break;
			case 'fifties-period':
				$this->load->view('pages/periods/fifties_period_view', $data);
				break;
			default :
				$this->load->view('pages/periods/decorative_periods_view', $data);
				break;
		}
	}

	/**
	 * Loads esitamte form
	 *
	 * @return [type] [description]
	 */
	public function estimate_form() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		echo 'estimate-form';


	}

	/**
	 *  Loads the 'introduction-to-diamonds' page.
	 */
	function intro_diamonds() {
		$data = array();
		$this->load->view('pages/diamonds/diamonds_intro_view', $data);
	}

	/**
	 * Loads the 'introduction-to-diamonds' page.
	 */
	function intro_gemstones() {
		$this->load->model('gemstones/gemstone_model');
		$data = array();
		//Get all of the Active Gemstones (to show in a list)
		$data['gemstones'] = $this->gemstone_model->getWebActiveGemstones();

		$this->load->view('pages/gemstones/gemstones_intro_view', $data);
	}

	/**
	 * Loads a Specific Gemstone content
	 *
	 * @param [string] $gemstone = url string of gemstone;
	 */
	public function gemstone_information($gemstone) {
		$this->load->model('gemstones/gemstone_model');
		//Get the content of the specific Gemstone, via it's URL string
		$data['gemstone'] = $this->gemstone_model->getGemstoneDataByName($gemstone);
		$this->load->view('pages/gemstones/gemstones_information_view', $data);
	}
	/**
	 * Loads the 'we-recycle-gemstones' page
	 *
	 */
	public function gemstone_recycle() {
		$data = array();
		$this->load->view('pages/gemstones/gemstones_recycle_view', $data);
	}

	/**
	 * Load the 'greeting-cards' page
	 */
	public function greeting_cards() {
		$data = array();
		$this->load->view('pages/greeting_cards_view', $data);
	}

	/**
	 *  Load the 'jewelry-care' page
	 */
	public function jewelry_care() {
		$data = array();
		$this->load->view('pages/jewelry_care_view', $data);
	}

	/**
	 * Load the 'our-frendily-safe' page
	 */
	public function our_friendly_staff() {
		$data = array();
		$this->load->model('pages/page_content');
		$data['content'] = $this->page_content->get_content('our_staff');

		$this->load->view('pages/our_staff_view', $data);
	}

	/*
	 * Load the 'our-store' page
	 */
	public function our_store() {
		$data = array();
		$this->load->model('pages/page_content');

		$data['content'] = $this->page_content->get_content('our_store');

		$this->load->view('pages/our_store_view', $data);
	}

	/**
	 * Load the page not found
	 */
	public function page_not_found() {
		$data = array();
		//$this->router->show_404(); //does not work,
		header("HTTP/1.1 404 Not Found");
		$this->load->view('pages/404_view', $data);
	}

	/**
	 * Load the 'shipping-policies' page
	 */
	public function shipping_policies() {
		$data = array();
		$this->load->view('pages/shipping_policies_view', $data);
	}
	/**
	 * Load the 'testimonials' page
	 */
	public function testimonials() {
		$data = array();
		$this->load->view('pages/testimonials_view', $data);
	}
}
?>