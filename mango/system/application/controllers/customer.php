<?php
class Customer extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn(); //Check The user to see if they are logged in
	}

	function index() {
		//redirect to search customers
		$this->list_customers();

	}

	function add() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();

		$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('middle_name', 'Middle Name', 'max_length[64]');
		$this->form_validation->set_rules('spouse_first', 'Spouse First Name', 'trim|max_length[64]');
		$this->form_validation->set_rules('spouse_last', 'Spouse Last Name', 'trim|max_length[64]');
		$this->form_validation->set_rules('spouse_middle', 'Spouse Middle', 'max_length[64]');
		$this->form_validation->set_rules('home_phone', 'Home Phone', 'trim|max_length[20]');
		$this->form_validation->set_rules('work_phone', 'Work Phone', 'trim|max_length[20]');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[256]');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[256]');
		$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|max_length[11]');
		$this->form_validation->set_rules('country', 'Country', 'trim|max_length[256]');
		$this->form_validation->set_rules('notes', 'Notes', 'trim');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			$this->load->model('customer/customer_model');

			$fields = array();
				$fields['first_name'] = $this->input->post('first_name');
				$fields['last_name'] = $this->input->post('last_name');
				$fields['middle_name'] = $this->input->post('middle_name');
				$fields['spouse_first'] = $this->input->post('spouse_first');
				$fields['spouse_last'] = $this->input->post('spouse_last');
				$fields['spouse_middle'] = $this->input->post('spouse_middle');
				$fields['home_phone'] = $this->input->post('home_phone');
				$fields['work_phone'] = $this->input->post('work_phone');
				$fields['email'] = $this->input->post('email');
				$fields['address'] = $this->input->post('address');
				$fields['address2'] = $this->input->post('address2');
				$fields['city'] = $this->input->post('city');
				$fields['state'] = $this->input->post('state');
				$fields['zip'] = $this->input->post('zip');
				$fields['country'] = $this->input->post('country');
				$fields['notes'] = $this->input->post('notes');

			$customer_id = $this->customer_model->insertCustomer($fields);
			redirect('customer/edit/' . $customer_id , 'refresh');
		}
		else {
			$this->load->view('customer/customer_add_view', $data);
		}
	}

	function add_job($id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('workshop/workshop_model');
		$this->load->model('user/user_model');

		$this->form_validation->set_rules('item_description', 'Item Description', 'trim|min_length[5]|required');
		$this->form_validation->set_rules('workshop_id', 'Workshop', 'trim|required');
		$this->form_validation->set_rules('user_id', 'Requester', 'trim|required');
		$this->form_validation->set_rules('open_date', 'Open Date', 'trim|required');
		$this->form_validation->set_rules('est_return_date', 'Est. Return Date', 'trim');
		$this->form_validation->set_rules('est_price', 'Est. Price', 'trim|numeric');
		$this->form_validation->set_rules('instructions', 'Instructions', 'trim');
		$this->form_validation->set_rules('rush_order', 'Rush Order', 'trim');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($id);
		$data['workshops'] = $this->workshop_model->getActiveWorkshops();
		$data['users'] = $this->user_model->getActiveUsers();

		if ($this->form_validation->run() != FALSE) {
			$fields = array();
				$fields['customer_id'] = $id;
				$fields['workshop_id'] = $this->input->post('workshop_id');
				$fields['user_id'] = $this->input->post('user_id');
				$fields['open_date'] = date('Y/m/d', strtotime($this->input->post('open_date')));
				$fields['item_description'] = $this->input->post('item_description');
				if($this->input->post('est_return_date') != '') {
					$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
				}
				$fields['instructions'] = $this->input->post('instructions');
				$fields['status'] = 1;
				$fields['rush_order'] = ($this->input->post('rush_order') == 'on' ? 1 : 0);

				$this->customer_job_model->insertJob($fields);
				$this->load->view('customer/customer_job_view', $data);
				redirect('customer/jobs/' . $id , 'refresh');
		}
		else {
			$this->load->view('customer/customer_add_job_view', $data);
		}
	}

	public function add_special_order ($customer_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_special_orders_model');
		$this->load->model('user/user_model');

		$this->form_validation->set_rules('order_description', 'Order Description', 'trim|min_length[5]|required');
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[5');
		$this->form_validation->set_rules('order_date', 'Order Date', 'trim|required');
		$this->form_validation->set_rules('invoice_id', 'Invoice ID', 'trim|numeric');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($customer_id);
		$data['users'] = $this->user_model->getActiveUsers();

		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['customer_id'] = $customer_id;
				$fields['order_description'] = $this->input->post('order_description');
				$fields['company_name'] = $this->input->post('company_name');
				$fields['order_date'] = date('Y/m/d', strtotime($this->input->post('order_date')));
				$fields['invoice_id'] = $this->input->post('invoice_id');
				if(!is_numeric($fields['invoice_id'])) {
					$fields['invoice_id'] = null;
				}

			//print_r($fields);
			$order_id = $this->customer_special_orders_model->addCustomerSpecialOrder($fields);
			redirect('customer/special_orders/' . $customer_id, 'refresh');

		}
		else {
			$this->load->view('customer/customer_add_special_order_view', $data);
		}
	}

	/**
	 * This loads the cancel job view
	 * This validates the form submittions
	 *
	 * @param [int] $j_id = job id
	 * @return null
	 */
	function cancel_job($customer_id, $job_id ) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('workshop/workshop_model');
		$this->load->model('user/user_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['job_data'] = $this->customer_job_model->getJobData($job_id);
		$data['customer'] = $this->customer_model->getCustomerData($data['job_data']['customer_id']);
		$data['workshop'] = $this->workshop_model->getWorkshopData($data['job_data']['workshop_id']);
		$data['user_name'] = $this->user_model->getUserName($data['job_data']['user_id']);
		$data['inspector_name'] = $this->user_model->getUserName($data['job_data']['inspection_id']);

		$this->form_validation->set_rules('reason', 'Reason', 'trim|min_length[5]|required');

		if ($this->form_validation->run() != FALSE) {
			$reason = $this->input->post('reason');
			$this->customer_job_model->cancelJob($job_id, $reason);
			redirect('customer/jobs/' . $data['job_data']['customer_id'] , 'refresh');
		}
		else {
			$this->load->view('customer/customer_cancel_job_view', $data);
		}
	}

	function complete_job() {
		$customer_id = $this->input->post('customer_id');
		$job_id = $this->input->post('job_id');
		$this->load->model('customer/customer_job_model');

		$this->customer_job_model->completeJob($job_id);

		redirect('customer/jobs/' . $customer_id , 'refresh');
	}

	function copy_billing_address($id, $sales = false) {
		$this->load->model('customer/customer_model');

		$data['billing'] = $this->customer_model->getBillingAddress($id);
		$fields = array();
			$fields['has_ship'] = 1;
			$fields['ship_contact'] = $data['billing']['first_name'] . ' ' . $data['billing']['last_name'];
			$fields['ship_address'] = $data['billing']['address'];
			$fields['ship_address2'] = $data['billing']['address2'];
			$fields['ship_city'] = $data['billing']['city'];
			$fields['ship_state'] = $data['billing']['state'];
			$fields['ship_zip'] = $data['billing']['zip'];
			$fields['ship_country'] = $data['billing']['country'];

		$this->customer_model->copyBillingAddress($id, $fields);
		if($sales != false) {
			redirect('sales/add_shipping/' . $sales , 'refresh');
		}
		else {
			redirect('customer/edit/' . $id , 'refresh');
		}

	}

	/*
	 * Admin functions start here....
	 */

	function customer_delete() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_rules('customer_id', 'Customer ID', 'required|min_length[1]|max_length[11]|callback_CB_check_customer_id');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			redirect('admin/customer_delete_confirm/' . $this->input->post('customer_id'), 'refresh');
		}
		else {
			$this->load->view('admin/customer/customer_delete_view', $data);
		}
	}

	function customer_delete_confirm($customer_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_reports_model');
		$this->load->model('customer/customer_job_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer_data'] = $this->customer_model->getCustomerData($customer_id);
		$data['store_credit'] = $this->customer_reports_model->getCustomerStoreCredit($customer_id);
		$data['purchesed_items'] = $this->customer_reports_model->getCustomerPurchasedItems($customer_id);
		$data['sold_items'] = $this->customer_reports_model->getCustomerSoldItems($customer_id);
		$data['jobs'] = $this->customer_job_model->getCustomerJobs($customer_id);

		$this->form_validation->set_rules('customer_id', 'customer id', 'require|trim|min_length[1]|numeric');

		if($this->form_validation->run() == true) {
			$this->customer_model->deleteCustomer($customer_id);
			redirect('admin', 'refrehs');
		}
		else {
			$this->load->view('admin/customer/customer_confirm_delete_view', $data);
		}
	}

	function customer_store_credit_delete_history() {
		$this->load->model('customer/customer_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['history'] = $this->customer_model->getStoreCreditDeleteHistory();

		$this->load->view('admin/customer/customer_store_credit_delete_history_view', $data);
	}

	function delete_job($customer_id, $job_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_job_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['job_data'] = $this->customer_job_model->getJobData($job_id);
		$data['customer'] = $this->customer_model->getCustomerData($data['job_data']['customer_id']);

		$this->load->view('customer/customer_job_delete_view', $data);
	}

	/*
	 * Admin functions end here....
	 */



	function edit($id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_reports_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('utils/lookup_list_model');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($id);
		$data['store_credit'] = $this->customer_reports_model->getCustomerStoreCredit($id);
		$data['purchased'] = $this->customer_reports_model->getCustomerPurchasedItems($id);
		$data['sold'] = $this->customer_reports_model->getCustomerSoldItems($id);
		$data['jobs'] = $this->customer_job_model->getCustomerJobs($id);
		$data['returns'] = $this->customer_model->getCustomerReturns($id);
		$data['refund_types'] = $this->lookup_list_model->getReturnCreditType();

		//$this->output->enable_profiler(TRUE); //Debug info?

		$this->load->view('customer/customer_edit_view', $data);
	}

	function edit_job($job_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('workshop/workshop_model');
		$this->load->model('user/user_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['job_data'] = $this->customer_job_model->getJobData($job_id);
		$data['customer'] = $this->customer_model->getCustomerData($data['job_data']['customer_id']);
		$data['workshops'] = $this->workshop_model->getActiveWorkshops();
		$data['users'] = $this->user_model->getActiveUsers();

		$this->form_validation->set_rules('item_description', 'Item Description', 'trim|required|min_length[5]|max_length[256]');
		$this->form_validation->set_rules('workshop_id', 'Workshop', 'trim|required|min_length[1]|max_length[11]|numeric');
		$this->form_validation->set_rules('user_id', 'Requester', 'trim|required|min_length[1]|max_length[11]|numeric');
		$this->form_validation->set_rules('inspection_id', 'Inspector', 'trim|min_length[1]|max_length[11]|numeric');
		$this->form_validation->set_rules('rush_order', 'Rush Order', 'trim|min_length[1]|max_length[11]|numeric');
		$this->form_validation->set_rules('open_date', 'Date Open', 'trim');
		$this->form_validation->set_rules('act_return_date', 'Actual Return Date', 'trim');
		$this->form_validation->set_rules('est_return_date', 'Est. Return Date', 'trim');
		$this->form_validation->set_rules('est_price', 'Est. Price', 'trim|money');
		//$this->form_validation->set_rules('act_price', 'Actual. Price', 'trim|money'); //removed because no one knows what it is
		$this->form_validation->set_rules('job_cost', 'Job Cost', 'trim|money');
		$this->form_validation->set_rules('instructions', 'Job Instructions', 'trim|max_length[256]');


		if($this->form_validation->run() == true) {

			$fields = array();
				$fields['item_description'] = $this->input->post('item_description');
				$fields['workshop_id'] = $this->input->post('workshop_id');
				$fields['user_id'] = $this->input->post('user_id');
				$fields['inspection_id'] = $this->input->post('inspection_id');
				$fields['rush_order'] = $this->input->post('rush_order');
				if(strtotime($this->input->post('open_date')) != false) {
					$fields['open_date'] = date('Y/m/d', strtotime($this->input->post('open_date')));
				}
				if(strtotime($this->input->post('act_return_date')) != false) {
					$fields['act_return_date'] = date('Y/m/d', strtotime($this->input->post('act_return_date')));
				}
				else {
					$fields['act_return_date'] = null;
				}
				if(strtotime($this->input->post('est_return_date')) != false) {
					$fields['est_return_date'] = date('Y/m/d', strtotime($this->input->post('est_return_date')));
				}
				else {
					$fields['est_return_date'] = null;
				}
				$fields['est_price'] = (float)preg_replace('/[\$,]/','',$this->input->post('est_price'));
				//$fields['act_price'] = number_format($this->input->post('act_price'),2,'.',''); //removed because no one knows what it is
				$fields['job_cost'] = (float)number_format($this->input->post('job_cost'),2,'.','');
				$fields['instructions'] = $this->input->post('instructions');
				$fields['notes'] = $this->input->post('notes');


				$this->customer_job_model->updateCustomerJob($job_id, $fields);
				if($this->input->post('update_complete_job')) {
					$this->customer_job_model->completeJob($job_id);
				}
				redirect('customer/edit_job/' . $data['job_data']['job_id'], 'refresh');

		}
		else {
			$this->load->view('customer/customer_edit_job_view', $data);
		}
	}

	public function edit_special_order($customer_id, $special_order_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_special_orders_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($customer_id);
		$data['special_order'] = $this->customer_special_orders_model->getCustomerSpecialOrder($customer_id, $special_order_id);
		$data['order_status'] = $this->customer_special_orders_model->getSpecialOrderStatus();

		$this->form_validation->set_rules('order_description', 'Order Description', 'trim|min_length[5]|required');
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|min_length[5');
		$this->form_validation->set_rules('order_date', 'Order Date', 'trim|required');
		$this->form_validation->set_rules('invoice_id', 'Invoice ID', 'trim|numeric');
		$this->form_validation->set_rules('order_status', 'Order Status', 'trim|required|numeric');

		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['order_description'] = $this->input->post('order_description');
				$fields['company_name'] = $this->input->post('company_name');
				$fields['order_date'] = date('Y/m/d', strtotime($this->input->post('order_date')));
				$fields['invoice_id'] = $this->input->post('invoice_id');
				$fields['order_status'] = $this->input->post('order_status');
				if(!is_numeric($fields['invoice_id'])) {
					$fields['invoice_id'] = null;
				}

			$this->customer_special_orders_model->updateCustomerSpecialOrder($special_order_id, $fields);

			redirect('customer/special_orders/' . $customer_id, 'refresh');
		}
		else {
			$this->load->view('customer/customer_edit_special_order_view', $data);
		}

	}

	function edit_shipping($customer_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('customer/customer_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($customer_id);

		$this->form_validation->set_rules('ship_contact', 'Ship Contact', 'trim|max_length[256]');
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
				$fields['has_ship'] = 1;
				$fields['ship_contact'] = $this->input->post('ship_contact');
				$fields['ship_phone'] = $this->input->post('ship_phone');
				$fields['ship_other_phone'] = $this->input->post('ship_other_phone');
				$fields['ship_address'] = $this->input->post('ship_address');
				$fields['ship_address2'] = $this->input->post('ship_address2');
				$fields['ship_city'] = $this->input->post('ship_city');
				$fields['ship_state'] = $this->input->post('ship_state');
				$fields['ship_zip'] = $this->input->post('ship_zip');
				$fields['ship_country'] = $this->input->post('ship_country');

			$this->customer_model->updateCustomerShipping($customer_id, $fields);
			redirect('customer/edit/' . $customer_id, 'refresh');

		}
		else {
			$this->load->view('customer/customer_edit_shipping_view', $data);
		}
	}

	function edit_store_credit($id, $action, $credit_id = null) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('customer/customer_model');

		$this->form_validation->set_rules('amount', 'Credit Amount', 'trim|numeric|required');
		$this->form_validation->set_rules('transaction_date', 'Transaction Date', 'trim|required');
		$this->form_validation->set_rules('reason', 'Reason', 'trim|required|min_length[5]');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($id);
		$data['action'] = $action;
		if ($this->form_validation->run() != FALSE) {
			$fields = array();
				$fields['customer_id'] = $id;
				$fields['invoice_id'] = 0;
				$fields['action_type'] = $action=='add'?3:4; //shorthand if statement
				$fields['credit_amount'] = $this->input->post('amount');
				$fields['is_special_item'] = $action=='add'?3:4; //shorthand if statement
				$fields['item_description'] = $this->input->post('reason');
				$fields['date'] = date('Y/m/d', strtotime($this->input->post('transaction_date')));
			$this->customer_model->insertStoreCredit($fields);
			redirect('customer/edit/' . $id, 'refresh');
		}
		else {
			if($action == 'add') {
				$this->load->view('customer/customer_store_credit_view', $data);
			}
			if($action == 'subtract') {
				$this->load->view('customer/customer_store_credit_view', $data);
			}
		}
	}

	function generate_mailing_list() {
		$this->load->model('customer/customer_reports_model');
		$this->customer_reports_model->generateMailingList();

		redirect('customer/', 'refresh');
	}

	function jobs($id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_job_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($id);
		$data['jobs'] = $this->customer_job_model->getCustomerJobs($id);
		$this->load->view('customer/customer_job_view', $data);

	}

	function list_customers($sort = 'last_name', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_reports_model');
		$this->load->library('pagination');

		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'last_name'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}

		$data['user_data'] = $this->authorize->getSessionData();
		$db_config['per_page'] = '20'; //items per page

		$db_config['cur_page'] = $offset;

		$data['customers'] = $this->customer_reports_model->getAvailableCustomers($db_config['per_page'], $offset, $sort, $direction);


		$db_config['base_url'] =  $this->config->item('base_url') . 'customer/list_customers/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['customers']['num_rows'];

		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links

		$this->load->view('customer/customer_list_view', $data);
	}

	function merge($id) {
		$this->load->model('customer/customer_model');
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($id);

		$this->form_validation->set_rules('merge_customers', 'Customer Ids', 'trim|required|min_length[1]');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run() != FALSE) {
			$this->load->model('customer/customer_merge_model');

			$m_id = explode(',', $this->input->post('merge_customers'));
			$new_id = $this->input->post('customer_id');
			foreach($m_id as $old_id) {
				$this->customer_merge_model->mergeInventorySellerId($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerStoreCredit($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerJobs($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerInvoices($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerInvoiceItems($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerLayawayPayments($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerInvoicePayments($old_id, $new_id);
				$this->customer_merge_model->mergeCustomerReturns($old_id, $new_id);
				$this->customer_merge_model->deleteCustomer($old_id);
			}
			redirect('customer/edit/' . $id, 'refresh');
			//$this->output->enable_profiler(TRUE);
		}
		else {
			$this->load->view('customer/customer_merge_view', $data);
		}
	}

	function remove_shipping() {
		$this->load->model('customer/customer_model');

		$customer_id = $this->input->post('customer_id');

		$fields = array();
			$fields['has_ship'] = 0;
			$fields['ship_contact'] = null;
			$fields['ship_phone'] = null;
			$fields['ship_other_phone'] = null;
			$fields['ship_address'] = null;
			$fields['ship_city'] = null;
			$fields['ship_state'] = null;
			$fields['ship_zip'] = null;
			$fields['ship_country'] = null;
		$this->customer_model->updateCustomerShipping($customer_id, $fields);

		redirect('customer/edit/' . $customer_id, 'refresh');
	}

	public function reset_password($customer_id) {
		$this->load->model('customer/customer_model');
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($customer_id);

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[1]|max_length[64]|matches[password2]');
		$this->form_validation->set_rules('password2', 'Verify Password', 'required|min_length[1]|max_length[64]|matches[password]');

		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['password'] = sha1($this->input->post('password'));
//			var_dump($fields);
			$this->customer_model->updateCustomerField($customer_id, 'password', $fields['password']);
			redirect('customer/edit/' . $customer_id, 'refresh');
		}
		else {
			$this->load->view('customer/customer_reset_password_view', $data);
		}

	}

	function search_customers() {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('customer/customer_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$this->load->view('customer/customer_search_view', $data);
	}

	function special_orders($customer_id) {
		$this->authorize->saveLastURL();

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_special_orders_model');
		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer'] = $this->customer_model->getCustomerData($customer_id);
		$data['special_orders'] = $this->customer_special_orders_model->getCustomerSpecialOrders($customer_id);

		$this->load->view('customer/customer_special_order_list_view', $data);
	}

	function store_credit_delete($customer_id, $credit_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_reports_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['customer_data'] = $this->customer_model->getCustomerData($customer_id);
		$data['credit'] = $this->customer_reports_model->getCustomerStoreCreditData($customer_id, $credit_id);

		$this->form_validation->set_rules('delete_reason', 'Delete Reason', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|required');
		$this->form_validation->set_rules('credit_id', 'Credit ID', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run() != FALSE) {
			$fields = array();
				$fields['user_id'] = $data['user_data']['user_id'];
				$fields['customer_id'] = $customer_id;
				$fields['credit_id'] = $credit_id;
				$fields['credit_amount'] = $data['credit']['credit_amount'];
				$fields['item_description'] = $data['credit']['item_description'];

			$history_id = $this->customer_model->insertCustomerStoreCreditDeleteHistory($fields);
			$this->customer_model->deleteCustomerStoreCredit($customer_id, $credit_id);
			redirect('customer/edit/' . $customer_id, 'refresh');
		}
		else {
			$this->load->view('customer/customer_store_credit_delete_view', $data);
		}
	}

	function view_job($j_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('customer/customer_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('workshop/workshop_model');
		$this->load->model('user/user_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['job_data'] = $this->customer_job_model->getJobData($j_id);
		$data['customer'] = $this->customer_model->getCustomerData($data['job_data']['customer_id']);
		$data['workshop'] = $this->workshop_model->getWorkshopData($data['job_data']['workshop_id']);
		$data['user_name'] = $this->user_model->getUserName($data['job_data']['user_id']);
		$data['inspector_name'] = $this->user_model->getUserName($data['job_data']['inspection_id']);

		$this->load->view('customer/customer_view_job_view', $data);
	}

	/**
	 * AJAX call to insert a customer while on the seller information page
	 *
	 *
	 * @param [int] $id = item id
	 * @return unknown_type
	 */
	function AJAX_addCustomer($id) {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('spouse_first', 'Spouse First Name', 'trim');
		$this->form_validation->set_rules('spouse_last', 'Spouse Last Name', 'trim');
		$this->form_validation->set_rules('home_phone', 'Home Phone', 'trim');
		$this->form_validation->set_rules('work_phone', 'Work Phone', 'trim');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email');
		$this->form_validation->set_rules('address', 'Address', 'trim');
		$this->form_validation->set_rules('city', 'City', 'trim');
		$this->form_validation->set_rules('state', 'State', 'trim');
		$this->form_validation->set_rules('zip', 'Zip', 'trim');
		$this->form_validation->set_rules('country', 'Country', 'trim');
		$this->form_validation->set_rules('notes', 'Notes', 'trim');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			$this->load->model('customer/customer_model');

			$fields = array();
			$keys = array_keys($_POST);

			foreach($keys as $key) {
				if(set_value($key) != "") { //uses set_value to only search through 'set_rules'
					$fields[$key] = $this->input->post($key);
				}
				else if ($key == 'mailing_list') { //special case for checkboxes
					if($fields[$key] = 'on') {
						$fields[$key] = 1;
					}
				}
			}
			$customer_id = $this->customer_model->insertCustomer($fields);
			$this->load->model('inventory/inventory_model');
			$this->inventory_model->applySeller($id, 2, $customer_id);

			echo true;
			/**
			 * This is an AJAX call, it wont redirect or anything
			 */
		}
		else {
			echo validation_errors();
		}
	}

	function AJAX_get_customer_names() { //$n = ids not to use;
		$this->load->model('customer/customer_model');
		$value = $_REQUEST['q']; //jQuery goofyness, can only use $_REQUESTS['q'] for query strings
		$n = null;
		if(isset($_REQUEST['n'])) {
			$n = $_REQUEST['n'];
		}
		$data = $this->customer_model->searchCustomerNames($value, $n);
		$junk = array();
		foreach($data as $row) {
			$json['customer_id'] = $row['customer_id'];
			$json['type'] = 1;
			$json['contact'] = $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['spouse_first'] . ' ' . $row['spouse_last'];
			$json['spouse'] = $row['spouse_first'] . ' ' . $row['spouse_last'];
			$json['phone'] = $row['home_phone'];
			$json['address'] = $row['address'];
			$json['city'] = $row['city'];

			$junk['people'][] = $json;
		}
		echo json_encode($junk);
	}

	function AJAX_get_customer_store_credit() {
		$this->load->model('customer/customer_reports_model');
		$customer_id = $this->input->post('customer_id');

		$credit_data = $this->customer_reports_model->getCustomerStoreCredit($customer_id);
		$credit = 0;
		foreach($credit_data as $credits) {
			if($credits['action_type'] == 1 || $credits['action_type'] == 3) {
				$credit += $credits['credit_amount'];
			}
			else {
				$credit -= $credits['credit_amount'];
			}
		}

		echo $credit;
	}

	function AJAX_updateCustomerField() {
		$this->load->model('customer/customer_model');

		$id = $this->input->post('customer_id');
		$field = $this->input->post('id');
		$value = $this->input->post('value');
		$this->customer_model->updateCustomerField($id, $field, $value);

		echo $value; //This returns the value back to the field (find a fix)
	}

	function AJAX_updateCustomerJobField($j_id, $field, $type = null) {
		$this->load->helper('form');
		$this->load->model('customer/customer_job_model');

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

		$this->customer_job_model->AJAX_updateCustomerJobField($j_id, $field, $value);

		echo $return_value; //This returns the value back to the field (find a fix)

	}

	function AJAX_updateMailingListStatus($id, $field, $value) {
		$this->load->model('customer/customer_model');
		$this->customer_model->updateCustomerField($id, $field, $value);
	}

	function CB_check_customer_id($string) {
		$b = false;
		$this->load->model('customer/customer_model');
		$g = $this->customer_model->CB_checkCustomerId($string);
		$this->form_validation->set_message('CB_check_customer_id', 'No Customer by that ID. Please check that ID number and try again.');
		if($g) { //$g = flase, no names found
			$b = true;
		}
		return $b;
	}
}
?>