<?php
class Invoice_reports_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	function getOpenMemos($per_page, $offset, $sort, $direction) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('sales/invoice_model');
		$this->db->start_cache();
		$this->db->from('invoice');
		$this->db->where('invoice_type', 3);
		$this->db->where_not_in('invoice_status', array(2,3,4));

		if($sort != false) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count

		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		//var_dump($this->db->last_query());
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}
				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$payments = $this->ci->invoice_model->getInvoicePayments($row['invoice_id']);
				$row['payout_data'] = $this->ci->invoice_model->getActualPayoutCollected($row['invoice_id']);
				$row['actual_amount_paid'] = 0;
				foreach($payments as $payment) {
					$row['actual_amount_paid'] += $payment['amount'];
				}
				$data[$row['invoice_id']] = $row;
			}
		}

		return $data; //array
	} //end getOpenMemos();

	function getAllInvoices($per_page, $offset, $sort = null, $direction = null) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('sales/invoice_model');
		$this->db->start_cache();

		$this->db->from('invoice');
		$this->db->order_by('sale_date', 'DESC');
		$this->db->order_by('invoice_id', 'DESC');
		if($sort != false) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count
		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}
				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$payments = $this->ci->invoice_model->getInvoicePayments($row['invoice_id']);
				$row['payout_data'] = $this->ci->invoice_model->getActualPayoutCollected($row['invoice_id']);
				$row['actual_amount_paid'] = 0;
				foreach($payments as $payment) {
					$row['actual_amount_paid'] += $payment['amount'];
				}
				$data[$row['invoice_id']] = $row;
			}
		}

		return $data; //array
	} //end getAllInvoices();

	function getAllMemos($per_page, $offset, $sort, $direction) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->start_cache();
		$this->db->from('invoice');
		$this->db->where('invoice_type', 3);
		if($sort != false) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count

		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}
				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$payments = $this->ci->invoice_model->getInvoicePayments($row['invoice_id']);
				$row['payout_data'] = $this->ci->invoice_model->getActualPayoutCollected($row['invoice_id']);
				$row['actual_amount_paid'] = 0;
				foreach($payments as $payment) {
					$row['actual_amount_paid'] += $payment['amount'];
				}
				$data[$row['invoice_id']] = $row;
			}
		}

		return $data; //array
	} //end getAllMemos();

	function getAllReturns($per_page, $offset, $sort, $direction) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->db->start_cache();
		$this->db->from('returns');
		if($sort != false) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count

		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}
				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$data[$row['return_id']] = $row;
			}
		}

		return $data; //array
	} //end getAllReturns();

	function getAllInternetSales($per_page, $offset, $sort, $direction) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->start_cache();
		$this->db->from('invoice');
		if($sort != false) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->where('buyer_type', 3);
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count

		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}

				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$payments = $this->ci->invoice_model->getInvoicePayments($row['invoice_id']);
				$row['payout_data'] = $this->ci->invoice_model->getActualPayoutCollected($row['invoice_id']);
				$row['actual_amount_paid'] = 0;
				foreach($payments as $payment) {
					$row['actual_amount_paid'] += $payment['amount'];
				}
				$data[$row['invoice_id']] = $row;
			}
		}

		return $data; //array
	} //end getAllInternetSales();

	function getAllVendorInvoices($per_page, $offset, $sort, $direction) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->start_cache();

		$this->db->from('invoice');
		$this->db->where('buyer_type', 2);
		$this->db->where('invoice_type', 0);
		if($sort != false) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count
		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}
				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$payments = $this->ci->invoice_model->getInvoicePayments($row['invoice_id']);
				$row['payout_data'] = $this->ci->invoice_model->getActualPayoutCollected($row['invoice_id']);
				$row['actual_amount_paid'] = 0;
				foreach($payments as $payment) {
					$row['actual_amount_paid'] += $payment['amount'];
				}
				$data[$row['invoice_id']] = $row;
			}
		}

		return $data; //array
	} //end getAllInvoices();
} //end Invoice_reports_model();

?>