<?php

Class Main extends Controller {
		
	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
	}
	/**
	 * 
	 * 
	 * @param [string] $list = list to sort (customers|workshop)
	 * @param [string] $field = Field to sort by (customer_name[last]|workshop_name)
	 * @param [string] $direction = direction of sort (ASC|DESC)
	 */
	function main_sort($list = null, $field = null, $direction = 'desc') {
		//$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('workshop/workshop_reports_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('customer/customer_special_orders_model');
		$data['user_data'] = $this->authorize->getSessionData();
		
		$customer = array();
		$workshop = array();
		
		switch($list) {
			case 'customer' :
				$customer['field'] = $field;
				$customer['direction'] = $direction;
				break;
			case 'workshop' :
				$workshop['field'] = $field;
				$workshop['direction'] = $direction;
				break; 
		}
		
		
		$data['inventory_jobs'] = $this->workshop_reports_model->getOpenJobs($workshop);
		$data['customer_jobs'] = $this->customer_job_model->getOpenCustomerJobs($customer);
		$data['special_orders'] = $this->customer_special_orders_model->getNonClosedSpecialOrders();
		
		$this->load->view('main_view', $data);
	}	
	

	function index() {
		//$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('workshop/workshop_reports_model');
		$this->load->model('customer/customer_job_model');
		$this->load->model('customer/customer_special_orders_model');
		$data['user_data'] = $this->authorize->getSessionData();
		
		$data['inventory_jobs'] = $this->workshop_reports_model->getOpenJobs(null);
		$data['customer_jobs'] = $this->customer_job_model->getOpenCustomerJobs(null);
		$data['special_orders'] = $this->customer_special_orders_model->getNonClosedSpecialOrders();
		
		$this->load->view('main_view', $data);
	}
	
}


?>