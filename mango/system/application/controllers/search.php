<?php
class Search extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		$this->load->helper('form');

	}

	function index() {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('utils/lookup_list_model');
		$this->load->model('inventory/modifier_model');
		$this->load->model('inventory/material_model');
		$this->load->model('vendor/vendor_model');
		$this->session->unset_userdata('search_data');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_classes'] = $this->lookup_list_model->getMajorClasses('mjr_class_id');
		$data['minor_classes'] = $this->lookup_list_model->getMinorClasses();
		$data['gemstones'] = $this->lookup_list_model->getGemstoneNames();
		$data['modifiers'] = $this->modifier_model->getModifiers();
		$data['materials'] = $this->material_model->getMaterials();
		$data['gemstone_cuts'] = $this->lookup_list_model->getGemstoneCuts();
		$data['diamond_colors'] = $this->lookup_list_model->getDiamondColors();
		$data['diamond_clarities'] = $this->lookup_list_model->getDiamondClarities();
		$data['vendors'] = $this->vendor_model->getAllVendors();

		$this->load->view('search/advance_search_view', $data);
	}

	function advance_search($sort = 'entry_date', $direcrion = 'DESC', $offset = 0) {
		$this->load->model('search/search_model');
		$data['user_data'] = $this->authorize->getSessionData();

		if(!empty($_POST)) {
			$this->session->set_userdata('dirt', $_POST);
			$dirty_post = $_POST;
		}
		else {
			$dirty_post = $this->session->userdata('dirt');
		}

		//$dirty_post = $_POST;
		//print_r($_POST);
		$post = array();
		$session = array();
		if(sizeof($dirty_post) > 0) {
			$post = $this->filterFields($dirty_post);
			$keys = array_keys($post);
			$text_array = '';
			foreach($keys as $key) {
				if(end($keys) == $key) {
					$text_array .= $key . '=' . $post[$key];
				}
				else {
					$text_array .= $key . '=' . $post[$key] . ',';
				}
			}
			$this->search_model->setSessionSearch($data['user_data']['user_id'], $text_array);
		}
		else {
			$session_text = $this->search_model->getSessionSearch($data['user_data']['user_id']);
			$session_text = explode(',', $session_text);
			$t_array = array();
			//print_r($session_text);
			foreach($session_text as $text_array) {
				$text = explode('=', $text_array);
				$t_array[$text[0]] = $text[1];
			}
			$post = $t_array;
		}


		//print_r($post);
		$this->load->library('pagination');
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'entry_date'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'desc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'search/advance_search/';

		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;

		$data['search_name'] = 'Advance Search';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->search_model->advanceSearch($post, $db_config['per_page'], $offset, $sort, $direction);

		$db_config['base_url'] =  $this->config->item('base_url') . 'search/advance_search/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];

		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links


		//$this->output->enable_profiler(TRUE);

		$this->load->view('inventory/inventory_list_view', $data); //load view

	}

	function quick_search($string = null, $sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->library('pagination');
		$this->load->model('search/search_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$string = $this->input->post('search_string');
		//Check for ID numbers
		/**
		 * May need to rewrite this function, may bee a
		 * better way to do it
		 *
		 * @TODO check out ID Search
		 */
		if (preg_match('/^(\d+)-(\d+)-(\d+)/', $string) ) {
			if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
			if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
			if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
			$data['direction_url'] = $this->uri->segment(2) . '/' . $this->uri->segment(3); //used for the direction field

			$db_config['per_page'] = '20'; //items per page
			$db_config['cur_page'] = $offset;

			$data['search_name'] = 'Quick Search: ' . $string;
			$data['items'] = $this->search_model->searchForNumber($string);

			$db_config['base_url'] =  $this->config->item('base_url') . 'search/quick_search/' . $sort . '/' . $direction . '/';
			$db_config['total_rows'] = $data['items']['num_rows'];

			$this->pagination->initialize($db_config);
			$data['pagination'] = $this->pagination->create_links(); //load pagination links

			$this->load->view('inventory/inventory_list_view', $data);
		}
		else {
			if($this->uri->segment(3)) {$string = $this->uri->segment(3);}
			if ($this->uri->segment(4)) { $sort = $this->uri->segment(4);} else { $sort = 'item_number'; }
			if ($this->uri->segment(5)) { $direction = $this->uri->segment(5); } else { $direction = 'asc';}
			if ($this->uri->total_segments() <= 3) { $offset = 0; } else { $offset = $this->uri->segment(6, 0);}
			$data['direction_url'] = 'search/' . $this->uri->segment(2) . '/' . $string . '/'; //used for the direction field

			$db_config['per_page'] = '20'; //items per page
			$db_config['cur_page'] = $offset;
			$data['search_name'] = 'Quick Search: ' . $string;
			$data['items'] = $this->search_model->searchForString($string, $db_config['per_page'], $offset, $sort, $direction);

			$db_config['base_url'] =  $this->config->item('base_url') . 'search/quick_search/' . $string . '/' . $sort . '/' . $direction . '/';
			$db_config['total_rows'] = $data['items']['num_rows'];

			$this->pagination->initialize($db_config);
			$data['pagination'] = $this->pagination->create_links(); //load pagination links

			$this->load->view('inventory/inventory_list_view', $data);
		}

	}

	private function filterFields($post) {
		$clean = array();
		$keys = array_keys($post);
		//clean post data for blank and 'any' values
		foreach($keys as $key) {
			if($post[$key] != '' && $post[$key] != 'any') {
				$clean[$key] = $post[$key];
			}
		}
		unset($clean['submit_search']); //remove the submit button
		return $clean;
	}
}
?>