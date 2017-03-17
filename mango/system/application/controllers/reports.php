<?php
class Reports extends Controller {

	var $ci;

	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		$this->ci =& get_instance();

	}

	function index() {
		$this->authorize->saveLastURL(); //saves the url
		$data['user_data'] = $this->authorize->getSessionData();

		$this->load->view('reports/reports_view', $data);
	}

	function catalogue_report($report_id, $type, $action = null, $item_id = null) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('reports/report_model');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['report_data'] = $this->report_model->getCatalogueReportData($report_id, $type);
		$data['report_items'] = $this->report_model->getCatalogueReportItemsData($report_id);

		if($action == 'add') {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('item_number', 'Item Number', 'required|min_length[1]|max_length[66]|callback_CB_check_item_number');
			if($this->form_validation->run() == true) {
				$this->load->model('inventory/inventory_model');
				$item_id = $this->inventory_model->getIdByNumber($this->input->post('item_number'));
				if($item_id != false) {
					$this->report_model->addCatalogueReportItem($report_id, $item_id);
					redirect('reports/catalogue_report/' . $report_id . '/' . $type, 'refresh');
				}
				else {
					$this->load->view('reports/reports_catalogue_report_add_view', $data);
				}
			}
			else {
				$this->load->view('reports/reports_catalogue_report_add_view', $data);
			}
		}
		else if($action == 'remove') {
			if($item_id != null) {
				$this->report_model->removeCatalogueReportItem($report_id, $item_id);
				redirect('reports/catalogue_report/' . $report_id . '/' . $type, 'refresh');
			}
		}
		else {
			$this->load->view('reports/reports_catalogue_report_view', $data);
		}
	}

	function convert_catalogue_report($report_id, $new_type) {
		$this->load->model('reports/report_model');

		$this->report_model->convertCatalogueReport($report_id, $new_type);

		redirect('reports/catalogue_report/' . $report_id . '/' . $new_type, 'refresh');
	}

	function convert_inventory_report_to_image_report() {
		$this->load->helper('form');
		$this->load->helper('file');
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		$this->load->model('admin/minor_class_model');
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['report_data'] = $this->report_model->getMajorMinorClassReportData();
		$data['major_classes'] = $this->major_class_model->getMajorClasses();
		$data['minor_classes'] = $this->minor_class_model->getMinorClasses();
		$data['status'] = $this->lookup_list_model->getItemStatus();

		$this->form_validation->set_rules('major_class_id', 'Major Class', 'trim');
		$this->form_validation->set_rules('minor_class_id', 'Minor Class', 'trim');
		//print_r($data['status']);
		if($this->form_validation->run() == true) {
			//print_r($this->input->post());
			$fields['major_class_id'] = $this->input->post('major_class_id');
			$fields['minor_class_id'] = $this->input->post('minor_class_id');
			$fields['status'] = array();
			unset($_POST['major_class_id']);
			unset($_POST['minor_class_id']);

			if($fields['major_class_id'] == 'any' || $fields['major_class_id'] == '') {
				//show me every major class
				$fields['major_class_id'] = false;
			}
			if($fields['minor_class_id'] == 'any' || $fields['minor_class_id'] == '') {
				//show me every minor class
				$fields['minor_class_id'] = false;
			}

			if($this->input->post('everything') == 'on' || $this->input->post('everything') == 1) {
				//show everything
				$fields['everything'] = true;
			}
			else {
				foreach($data['status'] as $status) {
					if($this->input->post($status['field_name'])) {
						$fields['status'][] = (string)$status['id'];
						$data['status'][(string)$status['id']]['checked'] = true;
					}
				}
				$fields['quantity'] = $this->input->post('quantity');
			}

			$data['fields'] = $fields;
			$data['report_data'] = $this->report_model->getInventoryCheckReportData($fields);
			$catalogue = array();
				$catalogue['report_name'] = 'Inventory Check Conversion: ' . date('Y.m.d');
				$catalogue['report_type'] = 1;

			$report_id = $this->report_model->insertCatalogueReport($catalogue);

			foreach($data['report_data'] as $item) {
				$this->report_model->addCatalogueReportItem($report_id, $item['item_id']);
			}

			redirect('reports/catalogue_report/' . $report_id . '/1', 'refresh');
		}
	}
	function create_catalogue_report($type = 0) { //type: 0=inventory, 1=image
		$this->load->model('reports/report_model');

		$this->load->library('form_validation');
		$data['user_data'] = $this->authorize->getSessionData();

		$data['report_type_number'] = $type;
		$report_names =  array(0 => 'Inventory', 1 => 'Image');
		$data['report_type'] = $report_names[$type];
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('report_name', 'Report Name', 'trim|required|min_length[1]|max_length[66]');
		$this->form_validation->set_rules('report_type', 'Report Type', 'trim|required');

		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['report_name'] = $this->input->post('report_name');
				$fields['report_type'] = $this->input->post('report_type');

			$report_id = $this->report_model->insertCatalogueReport($fields);
			redirect('reports/catalogue_report/' . $report_id . '/' . $this->input->post('report_type'), 'refresh');
		}
		$this->load->view('reports/reports_catalogue_create_view', $data);

	}
	function daily_monies_report() {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();
		$this->form_validation->set_rules('start_date', 'Start Date', 'required|date');

		$data['start_date'] = date('Y-m-d'); //today
		$data['end_date'] = $data['start_date'];
		$data['total_payments'] = 0;
		$data['invoice_payments'] = $this->report_model->getDailyInvoicePayments($data['start_date'], $data['end_date']);
		$data['layaway_payments'] = $this->report_model->getDailyLayawayPayments($data['start_date'], $data['end_date']);
		if($this->form_validation->run() == true) {
			$data['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date')));
			$data['end_date'] = $data['start_date'];
			$data['invoice_payments'] = $this->report_model->getDailyInvoicePayments($data['start_date'], $data['end_date']);
			$data['layaway_payments'] = $this->report_model->getDailyLayawayPayments($data['start_date'], $data['end_date']);
		}
		$data['total_payments'] += $data['invoice_payments']['total_monies'] + $data['layaway_payments']['total_monies'];
		$this->load->view('reports/reports_daily_monies_view', $data);
	}

	function detailed_category_cost_retail_report() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		$this->load->model('admin/minor_class_model');
		$this->load->model('reports/report_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['report_data'] = $this->report_model->getMajorMinorClassReportData();
		$data['major_classes'] = $this->major_class_model->getMajorClasses();
		$data['minor_classes'] = $this->minor_class_model->getMinorClasses();

		$this->form_validation->set_rules('major_class_id', 'Major Class', 'trim|numeric');
		$this->form_validation->set_rules('minor_class_id', 'Minor Class', 'trim|numeric');

		if($this->form_validation->run() == true) {
			$data['report_data'] = $this->report_model->getMajorMinorClassReportData($this->input->post('major_class_id'), $this->input->post('minor_class_id'));
		}

		$this->load->view('reports/reports_major_class_view', $data);

	}


	function grand_total_report() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->model('reports/report_model');

		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['inventory'] = $this->report_model->getGrandTotalInventoryReport();
		$data['consignment'] = $this->report_model->getGrandTotalConsignmentReport();
		$data['fk_inventory'] = $this->report_model->getFKGrandTotalInventoryReport();
		$data['fk_consignment'] = $this->report_model->getFKGrandTotalConsignmentReport();

		$this->load->view('reports/reports_inventory_grand_total_view', $data);
	}


	function grouped_major_minor_class_report() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		$this->load->model('admin/minor_class_model');
		$this->load->model('reports/report_model');



		$data['user_data'] = $this->authorize->getSessionData();
		$data['report_data'] = $this->report_model->getMajorMinorClassReportData();
		$data['major_classes'] = $this->major_class_model->getMajorClasses();
		$data['minor_classes'] = $this->minor_class_model->getMinorClasses();

		$this->form_validation->set_rules('major_class_id', 'Major Class', 'trim|numeric');
		$this->form_validation->set_rules('minor_class_id', 'Minor Class', 'trim|numeric');

		if($this->form_validation->run() == true) {
			$data['report_data'] = $this->report_model->getMajorMinorClassReportData($this->input->post('major_class_id'), $this->input->post('minor_class_id'));
		}

		$this->load->view('reports/reports_grouped_major_minor_cost_retail_view', $data);

	}
	function inventory_check_report() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		$this->load->model('admin/minor_class_model');
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['report_data'] = $this->report_model->getMajorMinorClassReportData();
		$data['major_classes'] = $this->major_class_model->getMajorClasses();
		$data['minor_classes'] = $this->minor_class_model->getMinorClasses();
		$data['status'] = $this->lookup_list_model->getItemStatus();

		$this->form_validation->set_rules('major_class_id', 'Major Class', 'trim');
		$this->form_validation->set_rules('minor_class_id', 'Minor Class', 'trim');
		//print_r($data['status']);
		if($this->form_validation->run() == true) {
			//print_r($this->input->post());
			$fields['major_class_id'] = $this->input->post('major_class_id');
			$fields['minor_class_id'] = $this->input->post('minor_class_id');
			$fields['status'] = array();
			unset($_POST['major_class_id']);
			unset($_POST['minor_class_id']);

			if($fields['major_class_id'] == 'any' || $fields['major_class_id'] == '') {
				//show me every major class
				$fields['major_class_id'] = false;
			}
			if($fields['minor_class_id'] == 'any' || $fields['minor_class_id'] == '') {
				//show me every minor class
				$fields['minor_class_id'] = false;
			}

			if($this->input->post('everything') == 'on' || $this->input->post('everything') == 1) {
				//show everything
				$fields['everything'] = true;
			}
			else {

				foreach($data['status'] as $status) {
					if($this->input->post($status['field_name']) === '0') { //special case for sold (bad programming)
						$fields['status'][] = '0';
					}
					if($this->input->post($status['field_name'])) {
						$fields['status'][] = (string)$status['id'];
						$data['status'][(string)$status['id']]['checked'] = true;
					}
				}
				$fields['quantity'] = $this->input->post('quantity');
			}

			$data['fields'] = $fields;
			$data['report_data'] = $this->report_model->getInventoryCheckReportData($fields);
		}

		$this->load->view('reports/reports_inventory_check_view', $data);

	}
	function list_catalogue_reports() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->model('reports/report_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['reports'] = $this->report_model->getCatalogueReports();
		$data['report_type'] = array(0 => 'Inventory', 1 => 'Image');

		$this->load->view('reports/reports_catalogue_report_list_view', $data);
	}

	function layaways() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('reports/report_model');


		$this->form_validation->set_rules('start_date', 'Start Date', 'trim');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['start_date'] = '01/01/1997';
		$data['end_date'] = date('m/d/Y');


		if($this->form_validation->run() == true) {
			$data['start_date'] = date('Y/m/d', strtotime($this->input->post('start_date')));
			$data['end_date'] = date('Y/m/d', strtotime($this->input->post('end_date')));
			//$data['report_data'] = $this->report_model->getLayaways($data['start_date'], $data['end_date']);
			$data['report_data'] = $this->report_model->getLayawayPayments($data['start_date'], $data['end_date']);
		}
		else {
			//$data['report_data'] = $this->report_model->getLayaways(date('Y/m/d', strtotime($data['start_date'])), date('Y/m/d', strtotime($data['end_date'])));
			$data['report_data'] = $this->report_model->getLayawayPayments(date('Y/m/d', strtotime($data['start_date'])), date('Y/m/d', strtotime($data['end_date'])));
		}
		$this->load->view('reports/reports_layaways_view', $data);
	}

	function monthly_sales_report() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->model('utils/lookup_list_model');

		$this->load->model('reports/report_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['months'] = $this->lookup_list_model->getMonths();
		$data['years'] = $this->lookup_list_model->getYears();

		$this->load->view('reports/reports_sales_monthly_run_view', $data);
	}

	function open_memo($all = false) {
		$this->load->model('reports/report_model');

		$data['user_data'] = $this->authorize->getSessionData();
		if($all) {
			$data['report_data'] = $this->report_model->getOpenMemos(false);
		}
		else {
			$data['report_data'] = $this->report_model->getOpenMemos();
		}


		$this->load->view('reports/reports_open_memo_view', $data);
	}
	function remove_catalogue_report() {
		$this->load->model('reports/report_model');

		$report_id = $this->input->post('report_id');

		//first get all items applied to this report
		$items = $this->report_model->getCatalogueReportItemsData($report_id);
		//then remove each item in the list
		foreach($items as $item) {
			$this->report_model->removeCatalogueReportItem($report_id, $item['item_id']);
		}
		//then remove the report
		$this->report_model->deleteCatalogueReport($report_id);

		redirect('reports/list_catalogue_reports', 'refresh');
	}

	function run_monthly_consignment_report($month = null, $year = null) {
		$data['user_data'] = $this->authorize->getSessionData();

		$this->load->model('utils/utility_model');
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');

		if(isset($_POST['year'])) {
			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));

			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');

			$data['report_data'] = $this->report_model->getMonthlyConsignmentData($dates['start_date'], $dates['end_date']);
			//$this->output->enable_profiler(TRUE);
			$this->load->view('reports/reports_sales_monthly_consignment_view', $data);
		}
		else if($month != null && $year != null) {
			$dates = $this->utility_model->processDates($month, $year);

			$data['month'] = $month;
			$data['year'] = $year;

			$data['report_data'] = $this->report_model->getMonthlySalesPersonData($dates['start_date'], $dates['end_date']);
			//$this->output->enable_profiler(TRUE);
			$this->load->view('reports/reports_sales_monthly_consignment_view', $data);
		}
		else  {
			redirect('reports/monthly_sales_report','refresh');
		}
	}


	function run_monthly_fk_report($month = null, $year = null) { //fk = francesklien
		$data['user_data'] = $this->authorize->getSessionData();

		$this->load->model('utils/utility_model');
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');

		if(isset($_POST['year'])) {
			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));

			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');

			$data['report_data'] = $this->report_model->getMonthlyFKData($dates['start_date'], $dates['end_date']);
			//$this->output->enable_profiler(TRUE);
			$this->load->view('reports/reports_sales_monthly_fk_view', $data);
		}
		else  {
			redirect('reports/monthly_sales_report','refresh');
		}
	}



	function run_monthly_salesperson_report($month = null, $year = null) {

		$this->load->model('reports/report_model');
		$this->load->model('utils/utility_model');
		$this->load->model('utils/lookup_list_model');
		$data['user_data'] = $this->authorize->getSessionData();

		if(isset($_POST['year'])) {
			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));

			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');

			$data['report_data'] = $this->report_model->getMonthlySalesPersonData($dates['start_date'], $dates['end_date']);
			//$this->output->enable_profiler(TRUE);
			$this->load->view('reports/reports_sales_monthly_salesperson_view', $data);
		}
		else if($month != null && $year != null) {
			$dates = $this->utility_model->processDates($month, $year);

			$data['month'] = $month;
			$data['year'] = $year;

			$data['report_data'] = $this->report_model->getMonthlySalesPersonData($dates['start_date'], $dates['end_date']);
			$this->load->view('reports/reports_sales_monthly_salesperson_view', $data);
		}
		else  {
			redirect('reports/monthly_sales_report','refresh');
		}
	}

	function run_monthly_salesperson_detail_report($user_id, $month, $year) {
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->model('utils/utility_model');
		$this->load->model('user/user_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['salesperson'] = $this->user_model->getUserData($user_id);
		$dates = $this->utility_model->processDates($month, $year);

		$data['month'] = $month;
		$data['year'] = $year;

		$data['report_data'] = $this->report_model->getMonthlySalesPersonDetailData($dates['start_date'], $dates['end_date'], $user_id);
		$data['invoice_data'] = $this->report_model->getMonthlySalesNormalInvoice($dates['start_date'], $dates['end_date'], $user_id);
		$data['layaway_data'] = $this->report_model->getMonthlySalesLayawayPayments($dates['start_date'], $dates['end_date'], $user_id, true);
		$data['return_data'] = $this->report_model->getMonthlySalesReturns($dates['start_date'], $dates['end_date'], $user_id, true);
		//$this->output->enable_profiler(TRUE);
		$this->load->view('reports/reports_sales_monthly_salesperson_detail_view', $data);
	}

	function run_monthly_sum_total_report($month = null, $year = null) {
		$data = array();

		if(isset($_POST['year'])) {
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$data = $this->monthly_sum_total($month, $year);
			$data['user_data'] = $this->authorize->getSessionData();
			$this->load->view('reports/reports_sales_monthly_sum_total_view', $data);
		}
		else {
			redirect('reports', 'refresh');
		}
	}

	function compare_major($a, $b) {
		return strnatcmp($a['mjr_class_id'], $b['mjr_class_id']);
	}
	function compare_minor($a, $b) {
		return strnatcmp($a['min_class_id'], $b['min_class_id']);
	}
	function compare_suffix($a, $b) {
		return strnatcmp($a['suffix'], $b['suffix']);
	}


	function run_monthly_turnover_report($month = null, $year = null) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('admin/major_class_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->model('utils/utility_model');
		$this->load->model('sales/invoice_model');
		$this->load->model('reports/report_model');

		$data = array();

		$data['user_data'] = $this->authorize->getSessionData();
		$data['invoice_type'] = $this->lookup_list_model->getInvoiceTypes();
		$data['invoice_status'] = $this->lookup_list_model->getInvoiceStatus();
		$data['invoice_item_status'] = $this->lookup_list_model->getInvoiceItemStatus();
		//$data['major_classes'] = $this->major_class_model->getMajorClasses();

		if(isset($_POST['year'])) {
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$temp['report_data'] = $this->monthly_sum_total($month, $year);
			//print_r($temp);
			$item_temp = array();
			$return_data = array();

			foreach($temp['report_data']['date_span'] as $date) {
				if(isset($date['invoices']) && sizeof($date['invoices']) > 0) {
					foreach($date['invoices'] as $invoice) {
						foreach($invoice['items'] as $item) {
							$item['invoice_item_status'] = $item['item_status'];
							$item_data = $this->inventory_model->getInventoryData($item['item_id'], false);

							$item['mjr_class_id'] = $item_data['mjr_class_id'];
							$item['min_class_id'] = $item_data['min_class_id'];
							$item['suffix'] = $item_data['suffix'];
							$item['purchase_price'] = $item_data['purchase_price'];
							$item['item_name'] = $item_data['item_name'];
							$item['seller_type'] = $item_data['seller_type'];
							$item['seller_id'] = $item_data['seller_id'];
							$item['seller_data'] = $this->invoice_model->getSellerData($item_data['seller_id'], $item_data['seller_type']);
							$item['invoice_type'] = $invoice['invoice_type'];
							$item['sale_date'] = $invoice['sale_date'];
							$item['invoice_buyer_type'] = $invoice['buyer_type'];
							$item['invoice_buyer_id'] = $invoice['buyer_id'];
							$item['buyer_data'] = $this->invoice_model->getBuyerData($invoice['buyer_id'], $invoice['buyer_type']);
							if(!isset($item_temp[$item_data['mjr_class_id']]['major_data'])) {
								$item_temp[$item_data['mjr_class_id']]['major_data'] = $this->major_class_model->getMajorClassData($item_data['mjr_class_id']);
							}
							$item_temp[$item_data['mjr_class_id']]['items'][] = $item;
						}
					}
					if(isset($date['returns']) && sizeof($date['returns']) > 0) {
						foreach($date['returns'] as $return) {
								$return_data[] = $return;
						}
					}
				}
			}
			foreach($item_temp as &$mjr) { //pass by reference please
 				uasort($mjr['items'], array($this, 'compare_major'));
 				uasort($mjr['items'], array($this, 'compare_minor'));
 				uasort($mjr['items'], array($this, 'compare_suffix'));
			}
			ksort($item_temp);
			//print_r($item_temp);
			$data['group'] = $item_temp;
			$data['sums'] = $temp['report_data']['sums'];
			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));
			$data['layaway_data'] = $this->report_model->getMonthlyTurnoverLayawayData($dates['start_date'], $dates['end_date']);
			$data['return_data'] = $return_data;
			$this->load->view('reports/reports_monthly_turnover_view_copy', $data);
		}
		else {
			redirect('reports', 'refresh');
		}

		/**
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->model('utils/utility_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['invoice_type'] = $this->lookup_list_model->getInvoiceTypes();
		$data['invoice_status'] = $this->lookup_list_model->getInvoiceStatus();
		$data['invoice_item_status'] = $this->lookup_list_model->getInvoiceItemStatus();

		if(isset($_POST['year'])) {
			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));

			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');

			$data['invoice_data'] = $this->report_model->getMonthlyTurnoverData($dates['start_date'], $dates['end_date']);
			$data['layaway_data'] = $this->report_model->getMonthlyTurnoverLayawayData($dates['start_date'], $dates['end_date']);
			$data['special_data'] = $this->report_model->getMonthlyTurnoverSpecialData($dates['start_date'], $dates['end_date']);
			$data['credit_data'] = $this->report_model->getAllCustomerStoreCredit($dates['start_date'], $dates['end_date']);
			//$this->output->enable_profiler(TRUE);
			$this->load->view('reports/reports_monthly_turnover_view', $data);
			//print_r($data);
		}
		else if($month != null && $year != null) {
			$dates = $this->utility_model->processDates($month, $year);

			$data['month'] = $month;
			$data['year'] = $year;

			$data['invoice_data'] = $this->report_model->getMonthlyTurnoverData($dates['start_date'], $dates['end_date']);
			//$this->load->view('reports/reports_monthly_turnover_view', $data);
		}
		else  {
			redirect('reports/monthly_sales_report','refresh');
		}
		*
		**/
	}

	function run_yearly_turnover_report() {
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->model('utils/utility_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['invoice_type'] = $this->lookup_list_model->getInvoiceTypes();

		if(isset($_POST['year1'])) {
			$month1 = $this->input->post('month1');
			$year1 = $this->input->post('year1');
			$month2 = $this->input->post('month2');
			$year2 = $this->input->post('year2');
			$dates = $this->utility_model->processYears($month1, $year1, $month2, $year2);

			$report_data['invoice_data'] = $this->report_model->getYearlyTurnoverData($dates['start_date'], $dates['end_date']);
			$report_data['layaway_data'] = $this->report_model->getYearlyTurnoverLayawayData($dates['start_date'], $dates['end_date']);

			$data['major_group'] = array();

			foreach($report_data['invoice_data'] as $item) {
				$data['major_group'][$item['mjr_class_id']][] = $item;
			}
			foreach($report_data['layaway_data'] as $item) {
				$data['major_group'][$item['mjr_class_id']][] = $item;
			}

			$data['major_keys'] = array_keys($data['major_group']);
			//$data['layaway_data'] = $report_data['layaway_data'];
			//print_r($data['major_group']);



			if(isset($_POST['download_csv'])) {
				$this->report_model->generateYearlyTuroverReportCSV($report_data, $month1, $year1, $month2, $year2);
			}
			else {
				$this->load->view('reports/reports_yearly_turnover_view', $data);
			}
			//$this->output->enable_profiler(TRUE);
		}
	}
	function store_credit() {
		$this->load->model('reports/report_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim');
		$data['start_date'] = '01/01/1997';
		$data['end_date'] = date('m/d/Y');

		if($this->form_validation->run() == true) {
			$data['start_date'] = date('Y/m/d', strtotime($this->input->post('start_date')));
			$data['end_date'] = date('Y/m/d', strtotime($this->input->post('end_date')));

			$data['sum_credit'] = $this->report_model->getSumCustomerStoreCredit($data['end_date']);
			$data['report_data'] = $this->report_model->getAllCustomerStoreCredit($data['start_date'], $data['end_date']);
		}
		else {
			$data['sum_credit'] = $this->report_model->getSumCustomerStoreCredit(date('Y/m/d', strtotime($data['end_date'])));
			$data['report_data'] = $this->report_model->getAllCustomerStoreCredit();
		}
		$data['total_credit'] = $data['report_data']['total_credit'];
		$data['total_given'] = $data['report_data']['total_given'];
		$data['total_used'] = $data['report_data']['total_used'];
		unset($data['report_data']['total_credit']);
		unset($data['report_data']['total_given']);
		unset($data['report_data']['total_used']);

		//$this->output->enable_profiler(TRUE);
		$this->load->view('reports/reports_store_credit_view', $data);

	}





	function turnover() {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->model('utils/lookup_list_model');

		$this->load->model('reports/report_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['months'] = $this->lookup_list_model->getMonths();
		$data['years'] = $this->lookup_list_model->getYears();

		$this->load->view('reports/reports_monthly_turnover_run_view', $data);
	}





	function CB_check_item_number($str) {
		$this->load->model('inventory/inventory_model');
		$bool = false;
		$this->form_validation->set_message('CB_check_item_number', 'That\'s not a real item number.');
		if($this->inventory_model->getIdByNumber($str) != false) {
			$bool = true;
		}
		return $bool;

	}

	private function monthly_sum_total($month, $year) {
		$this->load->model('reports/report_model');
		$this->load->model('sales/invoice_model');
		$this->load->model('sales/layaway_model');
		$this->load->model('sales/return_model');
		$this->load->model('utils/utility_model');
		$this->load->model('utils/lookup_list_model');

		$data = array();

		$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));
		$data['month'] = $month;
		$data['year'] = $year;
		$sum = 0;
		$credit_used = 0;
		$return_amount = 0;

		//construct the date range array
		$date_range = array();
		foreach($this->utility_model->createDateRangeArray($dates['start_date'], $dates['end_date']) as $d) {
			$date_range[$d]['name'] = $d;
		}
		//select all invoices that match this criteria
		$invoices = $this->report_model->getActiveMonthlyInvoiceData($dates['start_date'], $dates['end_date']);
		//get all layaway payments
		$data['layaway_payments'] = $this->report_model->getMonthlyLayawayPaymentsData($dates['start_date'], $dates['end_date']);
		//get all store credit uses
		$store_credits = $this->report_model->getActiveStoreCreditWithinRange($dates['start_date'], $dates['end_date']);
		//get all returns
		$returns = $this->report_model->getNonStoreCreditReturnsData($dates['start_date'], $dates['end_date']);
		//print_r($returns);
		foreach($invoices as $invoice) {

			$invoices[$invoice['invoice_id']]['items'] = $this->report_model->getInvoiceItemsData($invoice['invoice_id']);
			$invoices[$invoice['invoice_id']]['special'] = $this->report_model->getActiveMonthlyInvoiceSpecialItemsData($invoice['invoice_id']);

			foreach($store_credits as $credit) {
				if($credit['invoice_id'] == $invoice['invoice_id']) {
					//if the credit amount is greater than the total price, added it
					if($credit['amount'] > $invoice['total_price']) {
						$credit_used += $invoice['total_price'];
					}
					else {// else use the credit amount
						$credit_used += $credit['amount'];
					}
					$invoices[$invoice['invoice_id']]['credit'][] = $credit;
				}
			}

			$date_range[$invoice['sale_date']]['invoices'][] = $invoices[$invoice['invoice_id']];
		}

		foreach($invoices as $group) {
			if(sizeof($group['items']) > 0) {
				foreach($group['items'] as $item) {
					$sum += $item['sale_price'];
				}
			}
			if(sizeof($group['special']) > 0) {
				foreach($group['special'] as $special) {
					$sum += $special['item_price'];
				}
			}
		}


		//returns
		foreach($returns as $return) {
			//append all the returns to the date range
			$date_range[$return['date']]['returns'][$return['return_id']] = $return;
			//fetch total_price from each return;
			$items = $this->report_model->getReturnedItemsData($return['return_id']);
			foreach($items as $item) {
				$item_data = $this->invoice_model->getInvoiceItemData($return['invoice_id'], $item['item_id']);
				$return_amount += $item_data['sale_price'];

				//append each retured ite to their parent
				$date_range[$return['date']]['returns'][$return['return_id']]['items'][] = $item_data;
			}

			$specials = $this->return_model->getReturnedSpecialItems($return['return_id']);
			foreach($specials as $special) {
				$return_amount += $special['item_price'];
				$date_range[$return['date']]['returns'][$return['return_id']]['specials'][] = $special;
			}

			//if credit was given, remove it from the return amount
			if($return['refund_type'] == 1) {
				$return_amount -= $return['refund'];

			}
		}
		$sum = $sum - $return_amount;

		foreach($data['layaway_payments'] as $payment) {
			$layaway_invoice = array();
			//get invoice total price;
			$layaway_invoice = $this->invoice_model->getInvoiceData($payment['invoice_id']);

			if(sizeof($layaway_invoice) > 0 ) {
				//echo $date_range[$payment['payment_date']]['name'] . ' layaway is greater <br />';
				$layaway_invoice['items'] = $this->report_model->getInvoiceItemsData($payment['invoice_id']);
				$layaway_invoice['special'] = $this->report_model->getActiveMonthlyInvoiceSpecialItemsData($payment['invoice_id']);

				//get layaway all payments
				$amount = $this->layaway_model->getSumTotalLayawayPaymentsWithinRange($payment['invoice_id'], $layaway_invoice['sale_date'], $dates['end_date']);
				//if the amount is greater than or equal to invoie sale_price then
				//the layaway has been paid off, use item prices

				if($amount >= $layaway_invoice['total_price']) {
					//echo 'include: ' . $invoice['invoice_id'] . ' ' . $invoice['total_price'] . '<br />';
					//@TODO dangerious! find better solution for calculating total total_price
					$sum += $layaway_invoice['total_price'];
					$date_range[$payment['payment_date']]['layaway_paid'][] = $layaway_invoice;
				}
			}
		}

		$sum = $sum - $credit_used;
		$data['sums']['returned'] = $return_amount;
		$data['sums']['credit'] = $credit_used;
		$data['sums']['sum'] = $sum;
		$data['date_span'] = $date_range;

		return $data;

	}
}
?>