<?php
class Printer extends Controller {

	function __construct() {
		parent::Controller();
	}

	function index() {
		echo 'uh?';
	}

	function catalogue_report($report_id, $report_type) {
		$this->load->model('reports/report_model');
		$data['report_data'] = $this->report_model->getCatalogueReportData($report_id, $report_type);
		$data['report_items'] = $this->report_model->getCatalogueReportItemsData($report_id, true);

		if($report_type == 0) {
			$this->load->view('print/print_catalogue_inventory', $data);
		}
		else { // report_type = 1
			$this->load->view('print/print_catalogue_image', $data);
		}

	}

	function credit_card($invoice_id, $buyer_id) {
		$this->load->model('customer/customer_model');
		$this->load->model('sales/invoice_model');
		$data['buyer_data'] = $this->customer_model->getCustomerData($buyer_id);
		$data['card_data'] = $this->customer_model->getCustomerCreditCardInfo($invoice_id, $buyer_id);
		$data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);

		$this->load->view('print/print_credit_card', $data);
	}

	function layaways() {
		$this->load->model('reports/report_model');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');

		$data['report_data'] = $this->report_model->getLayawayPayments(date('Y/m/d', strtotime($data['start_date'])), date('Y/m/d', strtotime($data['end_date'])));


		$this->load->view('print/print_layaways_report', $data);
	}

	function monthly_sum_total_report() {

		$this->load->model('reports/report_model');
		$this->load->model('sales/invoice_model');
		$this->load->model('sales/layaway_model');
		$this->load->model('sales/return_model');
		$this->load->model('utils/utility_model');
		$this->load->model('utils/lookup_list_model');

		if(isset($_POST['year'])) {

			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));
			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');
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
			$this->load->view('print/print_monthly_sum_total_report', $data);
		}
	}

	function invoice($invoice_id, $sc = true) {
		$this->load->model('sales/invoice_model');
		$this->load->model('sales/layaway_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->model('utils/company_model');
		$this->load->model('customer/customer_reports_model');

		$data['company_data'] = $this->company_model->getCompanyInformation();
		$data['company_logo'] = $this->company_model->getCompanyLogo();

		$data['invoice_data'] = $this->invoice_model->getInvoiceData($invoice_id);
		$data['invoice_items'] = $this->invoice_model->getInvoiceItemsData($invoice_id, true); //true = return gemstone information
		$data['special_items'] = $this->invoice_model->getInvoiceSpecialItemsData($invoice_id);
		$data['payments'] = $this->invoice_model->getInvoicePayments($invoice_id);
		$data['layaway_payments'] = $this->layaway_model->getLayawayPayments($invoice_id);
		$data['payment_methods'] = $this->lookup_list_model->getPaymentMethods();
		$data['customer_total_store_credit'] = 0;
		$data['show_store_credit'] = $sc;

		if($data['invoice_data']['buyer_type'] == 1 || $data['invoice_data']['buyer_type'] == 3) {
			$data['customer_total_store_credit'] = $this->customer_reports_model->getCustomerStoreCreditAmount($data['invoice_data']['buyer_id']);
		}
		$data['total_invoice_price'] = $data['invoice_data']['total_price'] + $data['invoice_data']['tax'] + $data['invoice_data']['ship_cost'];
		$data['buyer_data'] = $this->invoice_model->getBuyerData($data['invoice_data']['buyer_id'], $data['invoice_data']['buyer_type']);


		$this->load->view('print/print_invoice', $data);
	}




	function returns($return_id) {
		$this->load->model('sales/return_model');
		$this->load->model('sales/invoice_model');
		$this->load->model('utils/company_model');

		$data['company_data'] = $this->company_model->getCompanyInformation();
		$data['company_logo'] = $this->company_model->getCompanyLogo();

		$data['return_data'] = $this->return_model->getReturnData($return_id);
		$data['return_items'] = $this->return_model->getReturnedInvoiceItems($return_id, true);
		$data['invoice_data'] = $this->invoice_model->getInvoiceData($data['return_data']['invoice_id']);
		$data['special_items'] = $this->return_model->getReturnedSpecialItems($return_id);
		$data['buyer_data'] = $this->invoice_model->getBuyerData($data['return_data']['buyer_id'], $data['return_data']['buyer_type']);
		//$this->output->enable_profiler(TRUE);
		$this->load->view('print/print_return', $data);
	}

	function report_grand_total() {
		$this->load->model('reports/report_model');

		$data = array();
		$data['inventory'] = $this->report_model->getGrandTotalInventoryReport();
		$data['consignment'] = $this->report_model->getGrandTotalConsignmentReport();
		$data['fk_inventory'] = $this->report_model->getFKGrandTotalInventoryReport();
		$data['fk_consignment'] = $this->report_model->getFKGrandTotalConsignmentReport();

		$this->load->view('print/print_report_inventory_grand_total', $data);

	}

	function report_inventory_check() {
			$this->load->model('utils/lookup_list_model');
			$this->load->model('reports/report_model');

			$data['status'] = $this->lookup_list_model->getItemStatus();
			$fields = $this->input->post();
			unset($fields['print_report']);


			$fields['major_class_id'] = $this->input->post('major_class_id');
			$fields['minor_class_id'] = $this->input->post('minor_class_id');
			$fields['status'] = array();

			if(!isset($fields['major_class_id']) || $fields['major_class_id'] == '') {
				//show me every major class
				$fields['major_class_id'] = false;
			}
			if(!isset($fields['minor_class_id']) || $fields['minor_class_id'] == '') {
				//show me every minor class
				$fields['minor_class_id'] = false;
			}

			if(isset($fields['everything'])) {
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
			if(isset($fields['images'])) {
				$data['images'] = true;
			}
			else {
				$data['images'] = false;
			}
			//$data['report_data'] = $this->report_model->getMajorMinorClassReportData($this->input->post('major_class_id'), $this->input->post('minor_class_id'));
			$data['report_data'] = $this->report_model->getInventoryCheckReportData($fields);

			$this->load->view('print/print_inventory_check_report', $data);
	}

	function report_monthly_turnover() {
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->model('utils/utility_model');
		$data['invoice_type'] = $this->lookup_list_model->getInvoiceTypes();
		$data['invoice_status'] = $this->lookup_list_model->getInvoiceStatus();

		if(isset($_POST['year'])) {
			$dates = $this->utility_model->processDates($this->input->post('month'), $this->input->post('year'));

			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');

			$data['invoice_data'] = $this->report_model->getMonthlyTurnoverData($dates['start_date'], $dates['end_date']);
			$data['layaway_data'] = $this->report_model->getMonthlyTurnoverLayawayData($dates['start_date'], $dates['end_date']);
			$this->load->view('print/print_report_monthly_turnover', $data);
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
	}




	function store_credit() {
		$this->load->model('reports/report_model');

		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');

		$data['report_data'] = $this->report_model->getAllCustomerStoreCredit($data['start_date'], $data['end_date']);
		$data['sum_credit'] = $this->report_model->getSumCustomerStoreCredit($data['start_date']);
		$data['total_credit'] = $data['report_data']['total_credit'];
		$data['total_given'] = $data['report_data']['total_given'];
		$data['total_used'] = $data['report_data']['total_used'];
		unset($data['report_data']['total_credit']);
		unset($data['report_data']['total_given']);
		unset($data['report_data']['total_used']);

		//$this->output->enable_profiler(TRUE);
		$this->load->view('print/print_store_credit', $data);
	}

	function tag_printer($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/tag_model');

		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		$data['tag_data'] = $this->tag_model->getTagData($item_id);

		$this->load->view('print/print_tag_output', $data);
	}
}
?>
