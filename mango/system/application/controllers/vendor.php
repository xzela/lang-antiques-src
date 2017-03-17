<?php
class Vendor extends Controller {

	function __construct() {
		parent::Controller();
		/*
		 * Load libaries and other stuff
		 */
		$this->load->library('authorize');
		$this->authorize->isLoggedIn(); //Check The user to see if they are logged in

	}

	function index() {
		//$data['user_data'] = $this->authorize->getSessionData();
		//$this->load->view('vendor/vendor_view', $data);
		$this->list_vendors();
	}

	function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();

		$this->form_validation->set_rules('name', 'Compant Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('tax_id', 'Tax ID', 'trim|max_length[64]');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|max_length[64]');
		$this->form_validation->set_rules('phone', 'Phone Number', 'trim|max_length[20]');
		$this->form_validation->set_rules('fax', 'Fax Number', 'trim|max_length[20]');
		$this->form_validation->set_rules('alt_phone', 'Alt Phone Number', 'trim|max_length[20]');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|max_length[256]');
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[256]');
		$this->form_validation->set_rules('city', 'City', 'trim|max_length[256]');
		$this->form_validation->set_rules('state', 'State', 'trim|max_length[2]');
		$this->form_validation->set_rules('zip', 'Zip', 'trim|max_length[10]');
		$this->form_validation->set_rules('country', 'Country', 'trim|max_length[256]');
		$this->form_validation->set_rules('notes', 'Notes', 'trim');


		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			$this->load->model('vendor/vendor_model');

			$fields = array();

				$fields['name'] = $this->input->post('name');
				$fields['tax_id'] = $this->input->post('tax_id');
				$fields['first_name'] = $this->input->post('first_name');
				$fields['last_name'] = $this->input->post('last_name');
				$fields['middle_name'] = $this->input->post('middle_name');
				$fields['phone'] = $this->input->post('phone');
				$fields['fax'] = $this->input->post('fax');
				$fields['alt_phone'] = $this->input->post('alt_phone');
				$fields['email'] = $this->input->post('email');
				$fields['address'] = $this->input->post('address');
				$fields['city'] = $this->input->post('city');
				$fields['state'] = $this->input->post('state');
				$fields['zip'] = $this->input->post('zip');
				$fields['country'] = $this->input->post('country');
				$fields['notes'] = $this->input->post('notes');
				if($this->input->post('mailing_list') == 'on') {
					$fields['mailing_list'] = 1;
				}

			$vendor_id = $this->vendor_model->insertVendor($fields);
			redirect('vendor/edit/' . $vendor_id , 'refresh');
		}
		else {
			$this->load->view('vendor/vendor_add_view', $data);
		}
	}

	function copy_billing_address($id, $sales = false) {
		$this->load->model('vendor/vendor_model');

		$data['billing'] = $this->vendor_model->getBillingAddress($id);
		$fields = array();
			$fields['has_ship'] = 1;
			$fields['ship_address'] = $data['billing']['address'];
			$fields['ship_address2'] = $data['billing']['address2'];
			$fields['ship_city'] = $data['billing']['city'];
			$fields['ship_state'] = $data['billing']['state'];
			$fields['ship_zip'] = $data['billing']['zip'];
			$fields['ship_country'] = $data['billing']['country'];

		$this->vendor_model->copyBillingAddress($id, $fields);

		if($sales != false) {
			redirect('sales/add_shipping/' . $sales , 'refresh');
		}
		else {
			redirect('vendor/edit/' . $id , 'refresh');
		}
	}

	function edit($vendor_id) {
		$this->load->helper('form');

		$this->load->model('vendor/vendor_model');
		$this->load->model('inventory/partnership_model');
		$this->load->model('vendor/vendor_reports_model');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['partnerships'] = $this->partnership_model->getPartnerPartnerships($vendor_id);
		$data['vendor'] = $this->vendor_model->getVendorData($vendor_id);
		$data['purchased'] = $this->vendor_reports_model->getVendorPurchasedItems($vendor_id);
		$data['sold'] = $this->vendor_reports_model->getVendorSoldItems($vendor_id);
		$data['returns'] = $this->vendor_model->getVendorReturns($vendor_id);
		$data['refund_types'] = $this->lookup_list_model->getReturnCreditType();

		//$this->output->enable_profiler(TRUE); //Debug info?

		$this->load->view('vendor/vendor_edit_view', $data);
	}

	function edit_shipping($vendor_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vendor/vendor_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['vendor'] = $this->vendor_model->getVendorData($vendor_id);

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

			$this->vendor_model->updateVendorShipping($vendor_id, $fields);
			redirect('vendor/edit/' . $vendor_id, 'refresh');

		}
		else {
			$this->load->view('vendor/vendor_edit_shipping_view', $data);
		}
	}

	function list_vendors($sort = 'name', $direcrion = 'asc') {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('vendor/vendor_model');
		$this->load->model('vendor/vendor_reports_model');
		$this->load->library('pagination');

		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'name'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}

		$data['user_data'] = $this->authorize->getSessionData();
		$db_config['per_page'] = '20'; //items per page

		$db_config['cur_page'] = $offset;

		$data['vendors'] = $this->vendor_reports_model->getAvailableVendors($db_config['per_page'], $offset, $sort, $direction);


		$db_config['base_url'] =  $this->config->item('base_url') . 'vendor/list_vendors/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['vendors']['num_rows'];

		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links

		$this->load->view('vendor/vendor_list_view', $data);
	}

	function merge($id) {
		$this->load->model('vendor/vendor_model');
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['vendor'] = $this->vendor_model->getVendorData($id);

		$this->form_validation->set_rules('merge_vendors', 'Vendor Ids', 'trim|required|min_length[1]');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run() != FALSE) {
			$this->load->model('vendor/vendor_merge_model');

			$m_id = explode(',', $this->input->post('merge_vendors'));
			$new_id =  $data['vendor']['vendor_id'];
			foreach($m_id as $old_id) {
				$this->vendor_merge_model->mergeInventorySellerId($old_id, $new_id);
				$this->vendor_merge_model->mergeVendorStoreCredit($old_id, $new_id);
				$this->vendor_merge_model->mergeVendorInvoices($old_id, $new_id);
				$this->vendor_merge_model->mergeVendorInvoiceItems($old_id, $new_id);
				$this->vendor_merge_model->mergeVendorInvoicePayments($old_id, $new_id);
				$this->vendor_merge_model->mergeVendorReturns($old_id, $new_id);
				$this->vendor_merge_model->deleteVendor($old_id); //remove the old vendor
			}
			redirect('vendor/edit/' . $id, 'refresh');
			//$this->output->enable_profiler(TRUE);
		}
		else {
			$this->load->view('vendor/vendor_merge_view', $data);
		}
	}

	/*
	 * Admin Options Start here ->
	 */
	function vendor_delete() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_rules('vendor_id', 'Vendor ID', 'required|min_length[1]|max_length[11]|callback_CB_check_vendor_id');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			redirect('admin/vendor_delete_confirm/' . $this->input->post('vendor_id'), 'refresh');
		}
		else {
			$this->load->view('admin/vendor/vendor_delete_view', $data);
		}

	}




	function vendor_delete_confirm($vendor_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('vendor/vendor_model');
		$this->load->model('vendor/vendor_reports_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['vendor_data'] = $this->vendor_model->getVendorData($vendor_id);

		$data['purchesed_items'] = $this->vendor_reports_model->getVendorPurchasedItems($vendor_id);
		$data['sold_items'] = $this->vendor_reports_model->getVendorSoldItems($vendor_id);

		$this->form_validation->set_rules('vendor_id', 'vendor id', 'require|trim|min_length[1]|numeric');

		if($this->form_validation->run() == true) {
			$this->vendor_model->deleteVendor($vendor_id);
			redirect('admin', 'refrehs');
		}
		else {
			$this->load->view('admin/vendor/vendor_confirm_delete_view', $data);
		}
	}

	/*
	 * Admin Options End here <-
	 */







	function remove_shipping() {
		$this->load->model('vendor/vendor_model');

		$vendor_id = $this->input->post('vendor_id');

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
		$this->vendor_model->updateVendorShipping($vendor_id, $fields);

		redirect('vendor/edit/' . $vendor_id, 'refresh');
	}

	function search_vendors() {
		$this->load->model('vendor/vendor_model');
		$data['user_data'] = $this->authorize->getSessionData();


		$this->load->model('vendor/vendor_reports_model');
		$this->load->library('pagination');

		if ($this->uri->segment(3)) { $sort = $this->uri->segment(3);} else { $sort = 'name'; }
		if ($this->uri->segment(4)) { $direction = $this->uri->segment(4); } else { $direction = 'asc';}
		if ($this->uri->total_segments() <= 2) { $offset = 0; } else { $offset = $this->uri->segment(5, 0);}

		$data['user_data'] = $this->authorize->getSessionData();
		$db_config['per_page'] = '20'; //items per page

		$db_config['cur_page'] = $offset;

		$data['vendors'] = $this->vendor_reports_model->getAvailableVendors($db_config['per_page'], $offset, $sort, $direction);


		$db_config['base_url'] =  '/prototype/vendor/list_vendors/' . $sort . '/' . $direction . '/';
		$db_config['total_rows'] = $data['vendors']['num_rows'];

		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links



		$this->load->view('vendor/vendor_search_view', $data);
	}

	/********************************************
	 * AJAX Calls
	 * These are AJAX calls
	 */
	/**
	 * AJAX call to insert a vendor while on the seller information page
	 *
	 *
	 * @param [int] $id = item id
	 * @return unknown_type
	 */
	function AJAX_addVendor($id) {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Company Name', 'trim|required');
		$this->form_validation->set_rules('tax_id', 'Tax ID', 'trim');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim');
		$this->form_validation->set_rules('fax', 'Fax', 'trim');
		$this->form_validation->set_rules('alt_phone', 'Alt Phone', 'trim');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email');
		$this->form_validation->set_rules('address', 'Address', 'trim');
		$this->form_validation->set_rules('city', 'City', 'trim');
		$this->form_validation->set_rules('state', 'State', 'trim');
		$this->form_validation->set_rules('zip', 'Zip', 'trim');
		$this->form_validation->set_rules('country', 'Country', 'trim');
		$this->form_validation->set_rules('notes', 'Notes', 'trim');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() != FALSE) {
			$this->load->model('vendor/vendor_model');

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
			$vendor_id = $this->vendor_model->insertVendor($fields);
			$this->load->model('inventory/inventory_model');
			$this->inventory_model->applySeller($id, 1, $vendor_id);
			/**
			 * This is an AJAX call, it wont redirect or anything
			 */
			echo true;
		}
		else {
			echo validation_errors();
		}
	}

	function AJAX_get_vendor_names() {
		$this->load->model('vendor/vendor_model');
		$value = $_REQUEST['q']; //jQuery goofyness, can only use $_REQUESTS['q'] for query strings
		$n = null;
		if(isset($_REQUEST['n'])) {
			$n = $_REQUEST['n'];
		}
		$data = $this->vendor_model->searchVendorNames($value, $n);
		$junk = array();
		foreach($data as $row) {
			$json['vendor_id'] = $row['vendor_id'];
			$json['type'] = 2;
			$json['contact'] = $row['name'];
			$json['contact_name'] = $row['first_name'] . ' ' . $row['last_name'];
			$json['phone'] = $row['phone'];
			$json['address'] = $row['address'];
			$json['city'] = $row['city'];

			$junk['people'][] = $json;

		}
		echo json_encode($junk);
	}

	function AJAX_updateMailingListStatus($id, $field, $value) {
		$this->load->model('vendor/vendor_model');
		$this->vendor_model->updateVendorField($id, $field, $value);
	}

	function AJAX_updateVendorField() {
		$this->load->model('vendor/vendor_model');

		$id = $this->input->post('vendor_id');
		$field = $this->input->post('id');
		$value = $this->input->post('value');
		$this->vendor_model->updateVendorField($id, $field, $value);

		echo $value; //This returns the value back to the field (find a fix)

	}



	function CB_check_vendor_id($string) {
		$b = false;
		$this->load->model('vendor/vendor_model');
		$g = $this->vendor_model->CB_checkVendorId($string);
		$this->form_validation->set_message('CB_check_vendor_id', 'No Vendor by that ID. Please check that ID number and try again.');
		if($g) { //$g = flase, no names found
			$b = true;
		}
		return $b;
	}
}

?>