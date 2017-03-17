<?php
class Inventory_list extends Controller {
		
	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();	
		
	}
	
	function list_all_items($sort = 'entry_date', $direcrion = 'DESC') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->library('pagination');		
		
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'entry_date'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'desc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}		
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
				
		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;
		
		$data['search_name'] = 'List All Items';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->inventory_reports_model->getAllItems($db_config['per_page'], $offset, $sort, $direction);
		
		$db_config['base_url'] =  $this->config->item('base_url') . 'inventory_list/list_all_items/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links
		
		$this->load->view('inventory/inventory_list_view', $data); //load view
	}
	
	function list_available_items($sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->library('pagination');

		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
				
		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;
		
		$data['search_name'] = 'List Available Items';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->inventory_reports_model->getAvailableItems($db_config['per_page'], $offset, $sort, $direction);
		
		$db_config['base_url'] =  $this->config->item('base_url') . 'inventory_list/list_available_items/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];
		
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		
		$this->load->view('inventory/inventory_list_view', $data); //load view		
	}
	
	function list_online_items($sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->library('pagination');
		
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
				
		$config['per_page'] = '20'; //items per page
		
		$data['search_name'] = 'List Only Items Only';
		$data['user_data'] = $this->authorize->getSessionData();
		$data['items'] = $this->inventory_reports_model->getOnlineItems($config['per_page'], $offset, $sort, $direction);
		$config['cur_page'] = $offset;
		
		$config['base_url'] =  $this->config->item('base_url') .  'inventory_list/list_online_items/' . $sort . '/' . $direction . '/';
		$config['total_rows'] = $data['items']['num_rows'];
		 
		$this->pagination->initialize($config);
		
		
		$data['pagination'] = $this->pagination->create_links();
		
		
		$this->load->view('inventory/inventory_list_view', $data);		
	}
	
	function list_non_sold_items_with_images($sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->helper('url');
		$this->load->library('pagination');
		
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
		
		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;
		
		$data['search_name'] = 'List Non-Sold Items With Web Images Not Online';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->inventory_reports_model->getNonSoldWithImages($db_config['per_page'], $offset, $sort, $direction);
		
		$db_config['base_url'] =  $this->config->item('base_url') . 'inventory_list/list_non_sold_items_with_images/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];
		
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		$this->load->view('inventory/inventory_list_view', $data); //load view
		//$this->output->enable_profiler(TRUE);
	}
	
	function list_non_sold_items_without_images($sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->helper('url');
		$this->load->library('pagination');
		
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
		
		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;
		
		$data['search_name'] = 'List Non-Sold Items Without Web Images';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->inventory_reports_model->getNonSoldWithOutImages($db_config['per_page'], $offset, $sort, $direction);
		
		$db_config['base_url'] =  $this->config->item('base_url') . 'inventory_list/list_non_sold_items_without_images/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];
		
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		$this->load->view('inventory/inventory_list_view', $data); //load view
		//$this->output->enable_profiler(TRUE);
	}	
	
	function list_sold_items($sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->helper('url');
		$this->load->library('pagination');
		
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
		
		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;
		
		$data['search_name'] = 'List Sold Items Only';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->inventory_reports_model->getSoldItems($db_config['per_page'], $offset, $sort, $direction);
		
		$db_config['base_url'] =  $this->config->item('base_url') . 'inventory_list/list_sold_items/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];
		
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		$this->load->view('inventory/inventory_list_view', $data); //load view
	}
	
	public function whats_new($sort = 'item_number', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/inventory_reports_model');
		$this->load->helper('url');
		$this->load->library('pagination');
		
		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'item_number'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}
		$data['direction_url'] = 'inventory_list/' . $this->uri->segment(2) . '/'; 
		
		$db_config['per_page'] = '20'; //items per page
		$db_config['cur_page'] = $offset;
		
		$data['search_name'] = 'Whats New';
		$data['user_data'] = $this->authorize->getSessionData(); //load the user data
		$data['items'] = $this->inventory_reports_model->getWhatsNew($db_config['per_page'], $offset, $sort, $direction);
		
		$db_config['base_url'] =  $this->config->item('base_url') . 'inventory_list/whats_new/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['items']['num_rows'];
		
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		$this->load->view('inventory/inventory_list_view', $data); //load view
	}
}
?>