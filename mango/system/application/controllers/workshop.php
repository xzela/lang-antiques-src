<?php
Class Workshop extends Controller {

	public $user_data = array();

	function __construct() {
		parent::Controller();
		//Check The user to see if they are logged in
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		$this->user_data = $this->authorize->getSessionData();
	}

	function index() {
		$this->list_workshops();
	}

	function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();

		$this->form_validation->set_rules('name', 'Compant Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('tax_id', 'Tax ID', 'trim|max_length[64]');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('phone', 'Phone Number', 'trim|max_length[12]');
		$this->form_validation->set_rules('fax', 'Fax Number', 'trim|max_length[12]');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[256]');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[256]');
		$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|max_length[5]');
		$this->form_validation->set_rules('country', 'Country', 'trim|max_length[256]');
		$this->form_validation->set_rules('notes', 'Notes', 'trim');


		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			$this->load->model('workshop/workshop_model');

			$fields = array();
			$keys = array_keys($_POST);

			foreach($keys as $key) {
				if(set_value($key) != "") { //must use 'set_value' to only select from 'set_rules'
					$fields[$key] = $this->input->post($key);
				}
				else if ($key == 'mailing_list') { //special case for checkboxes
					if($fields[$key] = 'on') {
						$fields[$key] = 1;
					}
				}
			}
			$workshop_id = $this->workshop_model->insertWorkshop($fields);
			redirect('workshop/edit/' . $workshop_id , 'refresh');
		}
		else {
			$this->load->view('workshop/workshop_add_view', $data);
		}
	}

	function add_item_job($id) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('workshop/workshop_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('inventory/inventory_model');
		$this->load->model('user/user_model');

		$this->form_validation->set_rules('workshop_id', 'Workshop', 'trim|required');
		$this->form_validation->set_rules('user_id', 'Requester', 'trim|required');
		$this->form_validation->set_rules('open_date', 'Open Date', 'trim|required');
		$this->form_validation->set_rules('est_return_date', 'Est. Return Date', 'trim');
		$this->form_validation->set_rules('est_price', 'Est. Price', 'trim|numeric');
		$this->form_validation->set_rules('instructions', 'Instructions', 'trim');
		$this->form_validation->set_rules('rush_order', 'Rush Order', 'trim');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['user_data'] = $this->authorize->getSessionData();
		$data['workshops'] = $this->workshop_model->getActiveWorkshops();
		$data['users'] = $this->user_model->getActiveUsers();

		if ($this->form_validation->run() != FALSE) {
			$fields = array();
				$fields['item_id'] = $id;
				$fields['workshop_id'] = $this->input->post('workshop_id');
				$fields['user_id'] = $this->input->post('user_id');
				$fields['open_date'] = date('Y/m/d', strtotime($this->input->post('open_date')));
				if($this->input->post('est_return_date') != '') {
					$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
				}
				$fields['instructions'] = $this->input->post('instructions');
				$fields['est_price'] = $this->input->post('est_price');
				$fields['status'] = 1;
				$fields['rush_order'] = ($this->input->post('rush_order') == 'on' ? 1 : 0);
				$fields['at_workshop'] = $this->input->post('at_workshop');

				$job_id = $this->workshop_model->insertInventoryJob($fields);

				$this->inventory_model->AJAX_updateField($id, 'item_status', 2);//update the item status to 2=workshop
				$this->inventory_model->AJAX_updateField($id, 'web_status', 0); //removes item from website
				redirect('workshop/edit_job/' . $job_id , 'refresh');

		}
		else {
			$this->load->view('workshop/workshop_add_job_view', $data);
		}
	}

	function cancel_job($job_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('inventory/inventory_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('workshop/workshop_model');
		$this->load->model('user/user_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['job_data'] = $this->workshop_model->getJobData($job_id);
		$data['item_data'] = $this->inventory_model->getItemData($data['job_data']['item_id']);
		$data['workshop'] = $this->workshop_model->getWorkshopData($data['job_data']['workshop_id']);
		$data['user_name'] = $this->user_model->getUserName($data['job_data']['user_id']);

		$this->form_validation->set_rules('reason', 'Reason', 'trim|min_length[5]|required');

		if ($this->form_validation->run() != FALSE) {
			$reason = 'Canceled: ' . $this->input->post('reason');
			$this->workshop_model->cancelInventoryJob($job_id, $reason);
			$this->inventory_model->AJAX_updateField($data['job_data']['item_id'], 'item_status', 1);

			redirect('workshop/item_jobs/' . $data['job_data']['item_id'] , 'refresh');
		}
		else {
			$this->load->view('workshop/workshop_cancel_job_view', $data);
		}
	}

	function complete_job() {
		$this->load->model('workshop/workshop_model');
		$this->load->model('inventory/inventory_model');

		$job_id = $this->input->post('job_id');
		$item_id = $this->input->post('item_id');

		$this->workshop_model->completeInventoryJob($job_id);

		$this->inventory_model->AJAX_updateField($item_id, 'item_status', 1);

		redirect('workshop/edit_job/' . $job_id , 'refresh');
	}

	function copy_billing_address($id) {
		$this->load->model('workshop/workshop_model');

		$data['billing'] = $this->workshop_model->getBillingAddress($id);
		$fields = array();
			$fields['ship_address'] = $data['billing']['address'];
			$fields['ship_address2'] = $data['billing']['address2'];
			$fields['ship_city'] = $data['billing']['city'];
			$fields['ship_state'] = $data['billing']['state'];
			$fields['ship_zip'] = $data['billing']['zip'];
			$fields['ship_country'] = $data['billing']['country'];

		$this->workshop_model->copyBillingAddress($id, $fields);

		redirect('workshop/edit/' . $id , 'refresh');
	}

	function customer_jobs($id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/workshop_reports_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['workshop'] = $this->workshop_model->getWorkshopData($id);
		$data['customer_jobs'] = $this->workshop_reports_model->getCustomerJobs($id);

		$this->load->view('workshop/workshop_customer_jobs_view', $data);
	}

	function edit($id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/workshop_reports_model');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['workshop'] = $this->workshop_model->getWorkshopData($id);
		$data['inventory_jobs'] = $this->workshop_reports_model->getInventoryJobs($id);
		$data['customer_jobs'] = $this->workshop_reports_model->getCustomerJobs($id);


		$this->load->view('workshop/workshop_edit_view', $data);
	}

	function edit_job($job_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->library('form_validation');
		$this->load->model('workshop/workshop_model');
		$this->load->model('inventory/inventory_model');
		$this->load->model('user/user_model');
		$this->load->helper('form');

		$data['users'] = $this->user_model->getActiveUsers();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['job_data'] = $this->workshop_model->getJobData($job_id);
		$data['workshops'] = $this->workshop_model->getActiveWorkshops();
		$data['user_list'] = $this->user_model->getActiveUsers();

		$this->form_validation->set_rules('workshop_id', 'Wrokshop', 'trim|required|numeric');
		$this->form_validation->set_rules('user_id', 'Requester', 'trim|required|numeric');
		$this->form_validation->set_rules('inspection_by_id', 'Inspector', 'trim|numeric');
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['workshop_id'] = $this->input->post('workshop_id');
				$fields['user_id'] = $this->input->post('user_id');
				$fields['inspection_by_id'] = $this->input->post('inspection_by_id');
				$fields['open_date'] = date('Y/m/d', strtotime($this->input->post('open_date')));
				$fields['est_price'] = (float)$this->input->post('est_price'); //cast a float
				$fields['act_price'] = (float)$this->input->post('act_price'); //cast a float
				$fields['job_cost'] = (float)$this->input->post('job_cost'); //cast a float
				$fields['instructions'] = $this->input->post('instructions');
				$fields['at_workshop'] = $this->input->post('at_workshop');

				$fields['notes'] = $this->input->post('notes');

			if($this->input->post('est_return_date') != '') {
				$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
			}
			if($this->input->post('act_return_date') != '') {
				$fields['act_return_date'] = date('Y/m/d', strtotime($this->input->post('act_return_date')));
			}

			$this->workshop_model->updateInventoryJob($job_id, $fields);

			if($this->input->post('update_complete_job')) {
				$item_id = $this->input->post('item_id');
				$this->workshop_model->completeInventoryJob($job_id);
				$this->inventory_model->AJAX_updateField($item_id, 'item_status', 1);

			}
			redirect('workshop/edit_job/' . $job_id, 'refresh');
		}
		else {
			$this->load->view('workshop/workshop_edit_inventory_job_view', $data);
		}

	}

	function edit_shipping($workshop_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('workshop/workshop_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['workshop'] = $this->workshop_model->getWorkshopData($workshop_id);

		$this->form_validation->set_rules('ship_phone', 'Ship Phone', 'trim|max_length[20]');
		$this->form_validation->set_rules('ship_other_phone', 'Ship Other Phone', 'trim|max_length[20]');
		$this->form_validation->set_rules('ship_address', 'Ship Address Line 1', 'trim|max_length[256]');
		$this->form_validation->set_rules('ship_address2', 'Ship Address Line 2', 'trim|max_length[256]');
		$this->form_validation->set_rules('ship_city', 'Ship City', 'trim|max_length[64]');
		$this->form_validation->set_rules('ship_state', 'Ship State', 'trim|max_length[2]');
		$this->form_validation->set_rules('ship_zip', 'Ship Zip', 'trim|max_length[11]');
		$this->form_validation->set_rules('ship_country', 'Ship Country', 'trim|max_length[256]');

		if($this->form_validation->run() == true) {

			$fields = array();
				$fields['ship_address'] = $this->input->post('ship_address');
				$fields['ship_address2'] = $this->input->post('ship_address2');
				$fields['ship_city'] = $this->input->post('ship_city');
				$fields['ship_state'] = $this->input->post('ship_state');
				$fields['ship_zip'] = $this->input->post('ship_zip');
				$fields['ship_country'] = $this->input->post('ship_country');

			$this->workshop_model->updateWorkshopShipping($workshop_id, $fields);
			redirect('workshop/edit/' . $workshop_id, 'refresh');

		}
		else {
			$this->load->view('workshop/workshop_edit_shipping_view', $data);
		}
	}

	function inventory_jobs($id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/workshop_reports_model');


		$data['user_data'] = $this->user_data;
		$data['workshop'] = $this->workshop_model->getWorkshopData($id);
		$data['inventory_jobs'] = $this->workshop_reports_model->getInventoryJobs($id);

		$this->load->view('workshop/workshop_inventory_jobs_view', $data);
		//$this->output->enable_profiler(TRUE); //Debug info?
	}


	function inventory_jobs_all() {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/workshop_reports_model');


		$data['user_data'] = $this->user_data;
		$data['workshops'] = $this->workshop_model->getActiveWorkshops();
		$data['inventory_jobs'] = $this->workshop_reports_model->getOpenJobs(null);

		$this->load->view('workshop/workshop_inventory_job_list_view', $data);

	}
	/**
	 * Shows all of the jobs for a specific Item
	 *
	 * @return unknown_type
	 */
	function item_jobs($item_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/pending_job_model');
		$this->load->model('inventory/inventory_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['jobs'] = $this->workshop_model->getInventoryJobs($item_id);
		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		$data['pending_job_id'] = null;
		if($data['item_data']['item_status'] == 8) { //in pending repair queue
			$data['pending_job_id'] = $this->pending_job_model->findPendingJobByItemId($item_id);
			if($data['pending_job_id'] == 0) {
				echo 'Something is wrong with this item. It has a "Pending Job" status, but no jobs. Please update the status back to Available and re-add the pending job.';
				echo anchor("inventory/change_status/" . $item_id, '<br/>Click here to do that now.');
			}
			$this->load->view('workshop/workshop_item_jobs_view', $data);
		}
		else {
			$this->load->view('workshop/workshop_item_jobs_view', $data);
		}
	}



	function list_workshops() {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/workshop_reports_model');
		$this->load->library('pagination');

		$data['user_data'] = $this->authorize->getSessionData();

		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'name'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}

		$db_config['per_page'] = '60'; //items per page

		$db_config['cur_page'] = $offset;

		$data['workshops'] = $this->workshop_reports_model->getAvailableWorkshops($db_config['per_page'], $offset, $sort, $direction);


		$db_config['base_url'] =  $this->config->item('base_url') . 'workshop/list_workshops/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['workshops']['num_rows'];

		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links

		$this->load->view('workshop/workshop_list_view', $data);
	}

	function pending_jobs() {
		$this->authorize->saveLastURL();
		$this->load->model('workshop/pending_job_model');

		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['pending_jobs'] = $this->pending_job_model->getOpenPendingJobs();
		$data['jobs'] = array();
		$this->load->view('workshop/workshop_pending_job_list_view', $data);
	}

	function pending_job_add($item_id) {
		$this->authorize->saveLastURL();
		$this->load->model('workshop/pending_job_model');
		$this->load->model('inventory/inventory_model');
		$this->load->library('form_validation');

		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);

		$this->form_validation->set_rules('item_id', 'Item ID', 'required|trim|numeric');
		$this->form_validation->set_rules('est_price', 'Est. Price', 'trim|numeric');

		if($this->form_validation->run() !== false) {
			$fields = array();
				$fields['item_id'] = $this->input->post('item_id');
				$fields['user_id'] = $data['user_data']['user_id'];
				$fields['open_date'] = date('Y/m/d', strtotime($this->input->post('open_date')));
				if($this->input->post('est_return_date') != '') {
					$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
				}
				$fields['est_price'] =  (float)$this->input->post('est_price');
				$fields['instructions'] = $this->input->post('instructions');
			$pending_job_id = $this->pending_job_model->insertPendingJob($fields);
			$this->inventory_model->updateItem($fields['item_id'], array('item_status' => 8)); //update item as 'Pending Repair'

			redirect('workshop/pending_job_edit/'. $pending_job_id, 'refresh');
		}
		else {
			$this->load->view('workshop/workshop_pending_job_add_view', $data);
		}
	}

	public function pending_job_assign($pending_job_id) {
		$this->load->model('workshop/pending_job_model');
		$this->load->model('workshop/workshop_model');
		$this->load->model('inventory/inventory_model');
		$this->load->model('user/user_model');
		$this->load->library('form_validation');

		$data = array();
		$data['user_data'] = $this->user_data;
		$data['workshops'] = $this->workshop_model->getActiveWorkshops();
		$data['users'] = $this->user_model->getActiveUsers();
		$data['job_data'] = $this->pending_job_model->getPendingJobData($pending_job_id);
		$data['item_data'] = $this->inventory_model->getItemData($data['job_data']['item_id']);

		$this->form_validation->set_rules('workshop_id', 'Workshop', 'trim|required');
		$this->form_validation->set_rules('user_id', 'Requester', 'trim|required');
		$this->form_validation->set_rules('open_date', 'Open Date', 'trim|required');
		$this->form_validation->set_rules('est_return_date', 'Est. Return Date', 'trim');
		$this->form_validation->set_rules('est_price', 'Est. Price', 'trim|numeric');
		$this->form_validation->set_rules('instructions', 'Instructions', 'trim');
		$this->form_validation->set_rules('notes', 'Notes', 'notes');
		$this->form_validation->set_rules('rush_order', 'Rush Order', 'trim');


		if($this->form_validation->run() === true) {
			$fields['item_id'] = $data['job_data']['item_id'];
			$fields['workshop_id'] = $this->input->post('workshop_id');
			$fields['user_id'] = $this->input->post('user_id');
			$fields['open_date'] = date('Y/m/d', strtotime($this->input->post('open_date')));
			if($this->input->post('est_return_date') != '') {
				$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
			}
			$fields['instructions'] = $this->input->post('instructions');
			$fields['notes'] = $this->input->post('notes');
			$fields['est_price'] = $this->input->post('est_price');
			$fields['status'] = 1;
			$fields['rush_order'] = ($this->input->post('rush_order') == 'on' ? 1 : 0);

			$job_id = $this->workshop_model->insertInventoryJob($fields);
			$this->pending_job_model->updatePendingJob($pending_job_id, array('job_status' => 'converted')); //change pending job status to converted
			$this->inventory_model->updateItem($data['job_data']['item_id'], array('item_status'=> 2, 'web_status'=> 0));//update the item status to 2=workshop
			redirect('workshop/edit_job/' . $job_id , 'refresh');
		}
		else {
			$this->load->view('workshop/workshop_pending_job_assign_view', $data);
		}
	}

	public function pending_job_edit($pending_job_id) {
		$this->authorize->saveLastURL();
		$this->load->model('workshop/pending_job_model');
		$this->load->model('inventory/inventory_model');
		$this->load->model('user/user_model');
		$this->load->library('form_validation');

		$data = array();
		$data['user_data'] = $this->user_data;
		$data['job_data'] = $this->pending_job_model->getPendingJobData($pending_job_id);
		$data['item_data'] = $this->inventory_model->getItemData($data['job_data']['item_id']);
		$data['users'] = $this->user_model->getAllUsers();

		$this->form_validation->set_rules('est_return_date', 'Est. Return Date', 'trim');
		$this->form_validation->set_rules('est_price', 'Est. Price', 'trim|numeric');

		if($this->form_validation->run() === true) {
			$fields = array();
				$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
				$fields['est_price'] = (float)$this->input->post('est_price');
				$fields['instructions'] = $this->input->post('instructions');
				$fields['notes'] = $this->input->post('notes');
			$this->pending_job_model->updatePendingJob($pending_job_id, $fields);
			redirect('workshop/pending_job_edit/'. $pending_job_id, 'refresh');
		}
		$this->load->view('workshop/workshop_pending_job_edit_view', $data);
	}

	function pending_job_delete() {
		$this->load->model('workshop/pending_job_model');
		$this->load->model('inventory/inventory_model');
		$pending_job_id = $this->input->post('pending_job_id');
		$item_id = $this->input->post('item_id');

		$fields = array();
			$fields['job_status'] = 'deleted';
		$this->pending_job_model->updatePendingJob($pending_job_id, $fields);
		$item_fields = array();
			$item_fields['item_status'] = 1; //available
		$this->inventory_model->updateItem($item_id, $item_fields);

		redirect('inventory/edit/' . $item_id, 'refresh');
	}

	function search_workshops() {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('workshop/workshop_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$this->load->view('workshop/workshop_search_view', $data);
	}

	function workshop_delete() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_rules('workshop_id', 'Workshop ID', 'required|min_length[1]|max_length[11]|callback_CB_check_vendor_id');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			redirect('admin/workshop_delete_confirm/' . $this->input->post('workshop_id'), 'refresh');
		}
		else {
			$this->load->view('admin/workshop/workshop_delete_view', $data);
		}
	}

	function workshop_delete_confirm($workshop_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('workshop/workshop_model');
		$this->load->model('workshop/workshop_reports_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['workshop_data'] = $this->workshop_model->getworkshopData($workshop_id);

		$data['customer_jobs'] = $this->workshop_reports_model->getCustomerJobs($workshop_id);
		$data['item_jobs'] = $this->workshop_reports_model->getInventoryJobs($workshop_id);

		$this->form_validation->set_rules('workshop_id', 'workshop id', 'require|trim|min_length[1]|numeric');

		if($this->form_validation->run() == true) {
			$this->workshop_model->deleteWorkshop($workshop_id);
			redirect('admin', 'refrehs');
		}
		else {
			$this->load->view('admin/workshop/workshop_confirm_delete_view', $data);
		}
	}

	/********************************************
	 * AJAX Calls
	 * These are AJAX calls
	 */
	function AJAX_get_workshop_names() {
		$this->load->model('workshop/workshop_model');
		$value = $_REQUEST['q']; //jQuery goofyness, can only use $_REQUESTS['q'] for query strings

		$data = $this->workshop_model->searchWorkshopNames($value);
		$junk = array();
		foreach($data as $row) {
			$json['workshop_id'] = $row['workshop_id'];
			$json['type'] = 3;
			$json['contact'] = $row['name'];
			$json['contact_name'] = $row['first_name'] . ' ' . $row['last_name'];
			$json['phone'] = $row['phone'];
			$json['address'] = $row['address'];
			$json['city'] = $row['city'];

			$junk['people'][] = $json;
		}
		echo json_encode($junk);
	}

	function AJAX_updateInventoryJobField($id, $field, $type = null) {
		$this->load->model('workshop/workshop_model');

		$value = $this->input->post('value');
		if($type == 'money') {
			$strip_chars = array(',', '$');
			$value = str_replace($strip_chars, '', $value);
			$return_value = '$' . $value;
		}
		else if($type == 'date') {
			$value = date('Y/m/d', strtotime($value));
			$return_value = date('m/d/Y', strtotime($value));;
		}
		else {
			$return_value = $value;
		}

		$this->workshop_model->updateInventoryJobField($id, $field, $value);

		echo $value; //This returns the value back to the field (find a fix)
	}

	function AJAX_updateMailingListStatus($id, $field, $value) {
		$this->load->model('workshop/workshop_model');
		$this->workshop_model->updateWorkshopField($id, $field, $value);
	}

	function AJAX_updateWorkshopField() {
		$this->load->model('workshop/workshop_model');

		$id = $this->input->post('workshop_id');
		$field = $this->input->post('id');
		$value = $this->input->post('value');
		$this->workshop_model->updateWorkshopField($id, $field, $value);

		echo $value; //This returns the value back to the field (find a fix)

	}
}
?>