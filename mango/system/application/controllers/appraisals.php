<?php
class Appraisals extends Controller {
	
	public function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		
	}
	
	function index() {
		
		redirect('appraisals/appraisal_list', 'refresh');

	}
	
	public function appraisal_list($me = 0, $offset = 0) {
		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$this->authorize->saveLastURL(); //saves the url
		
		$user_id = null;
		$data['search_name'] = 'View All Appraisals';
		$data['action'] = $me;
		if(!$me) {
			$data['search_name'] = 'Appraisals Assigned To Me: ' . $data['user_data']['user_name'];
			$user_id = $data['user_data']['user_id'];
		}
		
		
		$this->load->library('pagination');
		$this->load->model('sales/appraisal_model');
		$this->load->model('sales/invoice_reports_model');
		$this->load->model('utils/lookup_list_model');
		
		
		$data['invoice_types'] = $this->lookup_list_model->getInvoiceTypes();
		$data['invoice_status'] =  $this->lookup_list_model->getInvoiceStatus();
		
		$db_config['per_page'] = '50';
		$db_config['cur_page'] = $offset;
		
		$data['appraisals'] = $this->appraisal_model->listAllAppraisals($db_config['per_page'], $offset, $user_id);
		$db_config['base_url'] =  $this->config->item('base_url') . 'appraisals/appraisal_list/' . $me;
		$db_config['total_rows'] = $data['appraisals']['num_rows'];
		$this->pagination->initialize($db_config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links
		unset($data['appraisals']['num_rows']);
		
		//print_r($data['appraisals']);
		
		$this->load->view('sales/appraisal/sales_appraisal_list_view', $data);		
	} 
	
	function appraisal_view($appraisal_id) {
		$this->load->model('sales/appraisal_model');
		$this->load->model('sales/invoice_model');
		$this->load->model('customer/customer_model');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/material_model');
		$this->load->model('user/user_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['appraisal_data'] = $this->appraisal_model->getAppraisalData($appraisal_id);
		$data['customer_data'] = $this->customer_model->getCustomerData($data['appraisal_data']['customer_id']);
		$data['invoice_data'] = $this->invoice_model->getInvoiceData($data['appraisal_data']['invoice_id']);
		$data['item_data'] = $this->inventory_model->getInventoryData($data['appraisal_data']['item_id'], true);
		$data['invoice_item_data'] = $this->invoice_model->getInvoiceItemData($data['appraisal_data']['invoice_id'], $data['appraisal_data']['item_id']);
		$data['appraiser_data'] = $this->user_model->getUserData($data['appraisal_data']['user_id']);
		$data['appraisal_plot_data'] = $this->appraisal_model->getAppraisalPlotsData($data['appraisal_data']['appraisal_id']);
		$data['signature_data'] = $this->user_model->getCurrentSignature($data['appraisal_data']['user_id']);
		$data['material_data'] = $this->material_model->getAppraisalMaterial($data['appraisal_data']['item_id']);
		$data['item_diamonds'] = $this->appraisal_model->getAppriasalDiamonds($data['appraisal_data']['item_id']);
		$data['item_gemstones'] = $this->appraisal_model->getAppriasalGemstones($data['appraisal_data']['item_id']);
		
		
		//Replace crazy chars
		$pattern = '/[^a-zA-Z0-9.]/i';
		$data['file_name'] = preg_replace($pattern, "_", $data['customer_data']['first_name'] . ' ' . $data['customer_data']['last_name'] . ' ' . $data['item_data']['item_name']); 
		
		$this->load->view('sales/appraisal/sales_appraisal_preview_view', $data);
		
	}
	
}