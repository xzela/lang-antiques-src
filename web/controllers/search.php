<?php

class Search extends Controller {

	public $categories = array();
	public $types = array();
	public $controller;
	public $sorts;

	function __construct() {
		parent::Controller();
		$this->load->model('major_class/major_class_model');
		$this->load->model('modifier/modifier_model');
		$this->load->model('menu/menu_model');
		$this->load->model('products/inventory_model');
		$this->load->library('my_pagination'); //modified pagination lib

		$this->categories = $this->major_class_model->getMajorClasses();
		$this->types = $this->modifier_model->getModifiers();
		$this->controller = 'search';
		$this->sorts = $this->menu_model->getSorts();
	}

	/**
	 * Method for searching
	 *
	 * @param [string] $string = url string
	 * @param [string] $direction = direction of sort
	 * @param [int] $offset = offset
	 */
	public function index($string = null, $field = 'publish_date', $direction = 'desc', $offset = null) {
		$url = 'search/' . $string;
		$data = array();
		$data['sorts'] = $this->sorts;
		$element = $this->menu_model->getMenuElementByURLString($url);
		foreach($this->sorts as $sort) {
			if($this->input->post($sort['field'] . '-' . $sort['direction'])) {
				$field = $sort['field'];
				if(isset($sort['direction'])) {
					$direction = $sort['direction'];
				}
				else {
					$direction = 'desc';
				}
			}
		}

		$p_config = $this->my_pagination->create_pagination_config($string, $offset, null, $field, $direction, $this->controller);
		$page = array();
		//switch to test which query to use
		//based on url-string
		switch ($url) {
			case 'search/all-rings': //show all rings
				$query_data = $this->inventory_model->getAllRings($p_config['per_page'], $offset, $field, $direction);
				break;
			case 'search/everything': //show everything
				$query_data = $this->inventory_model->getEverything($p_config['per_page'], $offset, $field, $direction);
				break;
			case 'search/the-archive': //show the archive
				$query_data = $this->inventory_model->getTheArchive($p_config['per_page'], $offset, $field, $direction);
				break;
			case 'search/whats-new': //show what's new
				$query_data = $this->inventory_model->getWhatsNewInventory($p_config['per_page'], $offset, $field, $direction);
				break;
			case 'search/staff-picks': //shows staff picks
				$query_data = $this->inventory_model->getStaffPicks($p_config['per_page'], $offset, $field, $direction);
				break;
			default: //default, error, 404
				$this->session->unset_userdata('parent_id');
				redirect('error/page-not-found', 'refresh');
				break;
		}

		$p_config['total_rows'] = $query_data['pagination']['total_rows'];
		if(sizeof($element) > 0) {
			$page['field'] = $field;
			if($element['special_title'] != '') {
				$page['h2'] = $element['special_title'];
			}
			else {
				$page['h2'] = $element['special_name'];
			}
			$page['meta']['meta_description'] = $element['meta_description'];
			$page['page_title'] = $element['special_name'];
			if($url == 'search/whats-new') {
				$page['h2'] .= ' <span class="normal">as of ' . date('F d, Y', strtotime($query_data['inventory'][0]['publish_date'])) . '</span>';
			}
			$page['page_paragraph'] = $element['page_paragraph'];
			if($offset != null) {
				$page['meta']['meta_description'] = $element['meta_description'] . ' Items ' . $offset . ' of ' . $p_config['total_rows'];
				$page['page_title'] = $element['special_name'] . ' - Items ' . $offset . ' of ' . $p_config['total_rows'] . ' - Lang Antiques';
			}

			$page['title'] = $element['special_title'];
			$page['breadcrumb'] =  $element['special_name'];
			$page['total_rows'] = $p_config['total_rows'];
			$page['offset'] = $offset;
			$page['all_url'] = base_url() . 'search/' . $string . '/' . $field . '/'. $direction . '/all';
			//if(($page['direction']) == null) {
			if(!isset($page['direction'])) {
				$page['direction'] = $direction;
			}
			$data['inventory_data'] = $query_data['inventory'];
			$data['page_data'] = $page;

			$this->my_pagination->initialize($p_config);
			$data['page_data']['pagination'] = $this->my_pagination->create_links();
			if($offset == 'all') {
				$data['page_data']['pagination'] = null;
			}
			$this->load->view('products/products_list_view', $data);
		}
		else {
			//redirect('pages/page-could-not-be-found', 'refresh');
		}
	} //end index();


	/**
	 * Search for specific term. Search term is stored in the user
	 * session.
	 *
	 * @param [string] $direction = direction of sort
	 * @param [int] $offset = offset to use
	 */
	public function quick_search($field = 'publish_date', $direction = 'desc', $offset = null) {
		$this->load->model('search/search_model');
		$this->load->library('my_pagination');
		$data = array();
		$data['sorts'] = $this->sorts;
		//remove the parent_id form session
		//no need to specify sub menu on searching
		$this->session->unset_userdata('parent_id');
		$s_data = array();
		//test for existing quicksearch data
		if($this->session->userdata('quicksearch') != null) {
			//if non-found,
			if($this->input->post('string')) {
				$s_data['string'] = $this->input->post('string');
				if($this->input->post('category_id')) {
					$s_data['category_id'] = $this->input->post('category_id');
				}
				else if($this->input->post('special_type')) {
					$s_data['special_type'] = $this->input->post('special_type');
				}
				if($this->input->post('sub')) {
					$s_data['sub'] = $this->input->post('sub');
				}
				$query = array('quicksearch' => $s_data);
				$this->session->set_userdata($query);
			}
			$s_data['string'] = $this->session->userdata('quicksearch');
		}
		else {
			$query = array('quicksearch' => $this->input->post('string'));
			$this->session->set_userdata($query);
			$s_data['string'] = $this->session->userdata('quicksearch');
		}
		foreach($this->sorts as $sort) {
			if($this->input->post($sort['field'] . '-' . $sort['direction'])) {
				$field = $sort['field'];
				if(isset($sort['direction'])) {
					$direction = $sort['direction'];
				}
				else {
					$direction = 'desc';
				}
			}
		}
		//$p_config = paination config array;
			//$string = url-string,
			//$offset = query offset
			//method_name = current method (for URL)
			//$direction = sort direction
			//controller_name = name of controller (for URL)
		$p_config = $this->my_pagination->create_pagination_config(null, $offset, 'quick-search', $field, $direction, $this->controller);
		//test for item number
		if(preg_match('/^(\d+)-(\d+)-(\d+)/', $s_data['string'])) {
			$query_data = $this->search_model->getItemByNumber($s_data['string'], $p_config['per_page'], $offset, $direction);
		}
		else {
			$query_data = $this->search_model->getQuickSearchResults($s_data, $p_config['per_page'], $offset, $direction);
		}
		//var_dump($this->session->userdata);
		//var_dump($s_data);
		//die();
		$p_config['total_rows'] = $query_data['pagination']['total_rows'];


		$page = array();
			$page['h2'] = 'Search Results for: ' . $s_data['string'];
			$page['page_title'] = 'Search Results for: ' . $s_data['string'];
			$page['meta']['meta_description'] = 'Here are all of the items which match the search criteria of "' . $s_data['string'] . '"';
			$page['title'] = $s_data['string'];
			$page['breadcrumb'] =  'Quick Search For: ' . $s_data['string'];
			$page['total_rows'] = $p_config['total_rows'];
			$page['offset'] = $offset;
			$page['all_url'] = base_url() . $this->config->item('index_page') . 'search/' . 'quick-search' . '/' . $field . '/' . $direction . '/all';
			$page['direction'] = $direction;
			$page['page_paragraph'] = null;
			$page['field'] = $field;
		if(!isset($query_data['inventory'])) {
			$data['inventory_data'] = array();
			$page['meta']['meta_description'] = 'We couldn\'t find anything for: "' . $s_data['string'] . '"';
		}
		else {
			$data['inventory_data'] = $query_data['inventory'];
		}
		$data['page_data'] = $page;

		//initialize the pagination
		$this->my_pagination->initialize($p_config);
		$data['page_data']['pagination'] = $this->my_pagination->create_links();
		if($offset == 'all') {
			$data['page_data']['pagination'] = null;
		}
		//this loads the view
		$this->load->view('products/products_list_view', $data);
	} //end quick_search();

}//end class
?>