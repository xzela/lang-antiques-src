<?php
class Report_model extends Model {

	var $ci;
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	public function addCatalogueReportItem($report_id, $item_id) {
		$fields = array();
			$fields['report_id'] = $report_id;
			$fields['item_id'] = $item_id;
		$this->db->insert('reports_items', $fields);
		return $this->db->insert_id();
	}

	public function convertCatalogueReport($report_id, $new_type) {
		$fields = array();
			$fields['report_type'] = $new_type;

		$this->db->where('report_id', $report_id);
		$this->db->limit(1);

		$this->db->update('reports', $fields);
	}

	public function deleteCatalogueReport($report_id) {
		$this->db->where('report_id', $report_id);
		$this->db->limit(1);
		$this->db->delete('reports');
	}

	public function insertCatalogueReport($fields) {
		$this->db->insert('reports', $fields);
		return $this->db->insert_id();
	}

	public function generateYearlyTuroverReportCSV($bulk, $month1, $year1, $month2, $year2) {
		$this->ci->load->model('utils/lookup_list_model');

		//$this->load->dbutil(); //database util class
		$this->load->helper('file');
		$d = $this->ci->lookup_list_model->getInvoiceTypes();
		$content = '"Major Class",' . '"Minor Class",'
			. '"Item Number",' . '"Invoice ID",' . '"Invoice Type",'
			. '"Buyer Name",' . '"Seller Name",' . '"Item Name",'
			. '"Sale Date",' . '"Sale Price",' . '"Cost",'
			. '"Last Layaway Payment",' . "\r\n";
		//print_r($bulk);
		foreach($bulk as $junk) {
			foreach($junk as $row) {
				$line = '';
				//start formating name column;
				$line .= '"' . str_replace('"', '"'.'"', $row['mjr_class_id']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['min_class_id']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['item_number']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['invoice_id']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $d[$row['invoice_type']]['name']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['buyer_data']['name']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['seller_data']['name']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['item_name']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['sale_date']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"$', number_format($row['sale_price'], 2)) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"$', number_format($row['purchase_price'], 2)) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['last_layaway_payment']) . '"' . ',';
				$line .= "\r\n";
				$content .= $line;
			}
		}

		$name = 'yearly_turnover_report_' . $month1 . '.' . $year1 . '-' . $month2 . '.' . $year2 . '.csv';
		$path = './files/csv/' . $name;

		if(!write_file($path, $content)) {
			//cound not write file for some reason
		}
		else {
     		$file = read_file($path);
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename="'. $name . '"');

			echo $file; //needed to return the file to the browser
		}

	}

	public function getActiveMonthlyInvoiceData($start, $end) {
		//start with Invoice Sales
		$this->db->from('invoice');
		$this->db->where('sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice_type', 0); //only sold invoices: 1=normal, 2=internet
		$this->db->where('buyer_type !=', 2);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['invoice_id']] = $row;
			}
		}
		return $data;
	}

	public function getActiveMonthlyInvoiceSpecialItemsData($invoice_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_status', 0); //only include normal items
		$this->db->where('item_type', 1); //only special items types
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	public function getActiveStoreCreditWithinRange($start, $end) {
		$this->db->from('invoice_payments');
		$this->db->where('date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('method', 4); //select only store credits
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	public function getAllCustomerStoreCredit($start = null, $end = null) {
		$this->db->from('customer_store_credit');
		$this->db->join('customer_info', 'customer_store_credit.customer_id = customer_info.customer_id');
		if($start != null && $end != null) {
			$this->db->where('customer_store_credit.date BETWEEN "' . $start . '" AND "' . $end . '"' , null, false);
		}
		$this->db->order_by('customer_info.last_name', 'ASC');

		$query = $this->db->get();
		$data = array();

		if($query->num_rows() > 0) {
			$credit = 0;
			$given = 0;
			$used = 0;
			foreach($query->result_array() as $row) {
				if($row['action_type'] == 1 || $row['action_type'] == 3) { //addition
					$given += $row['credit_amount'];
					$credit += $row['credit_amount'];
				}
				else { //0,2 subtraction
					$used -= $row['credit_amount'];
					$credit -= $row['credit_amount'];
				}
				$data[$row['customer_id']][] = $row;
			}
			$data['total_credit'] = $credit;
			$data['total_given'] = $given;
			$data['total_used'] = $used;

		}

		return $data;
	}

	public function getCatalogueReportData($report_id, $report_type) {
		$this->db->from('reports');
		$this->db->where('report_id', $report_id);
		$this->db->where('report_type', $report_type);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;
	}

	/**
	 *
	 * @param $report_id
	 * @param $extra
	 * @return unknown_type
	 */
	public function getCatalogueReportItemsData($report_id, $extra = false) {
		$this->ci->load->model('image/image_model');
		$this->db->from('reports_items');
		$this->db->where('report_id', $report_id);
		$this->db->join('inventory', 'reports_items.item_id = inventory.item_id');
		$this->db->order_by('mjr_class_id, min_class_id, suffix','ASC');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				if($extra) {
					$this->ci->load->model('inventory/material_model');
					$this->ci->load->model('inventory/diamond_model');
					$this->ci->load->model('inventory/gemstone_model');
					$this->ci->load->model('inventory/pearl_model');
					$this->ci->load->model('inventory/opal_model');
					$this->ci->load->model('inventory/jadeite_model');
					$row['item_materials'] = $this->ci->material_model->getAppliedMaterials($row['item_id']);
					$row['item_diamonds'] = $this->ci->diamond_model->getItemDiamonds($row['item_id']);
					$row['item_gemstones'] = $this->ci->gemstone_model->getItemGemstones($row['item_id']);
					$row['item_pearls'] = $this->ci->pearl_model->getItemPearls($row['item_id']);
					$row['item_opals'] = $this->ci->opal_model->getItemOpals($row['item_id']);
					$row['item_jadeite'] = $this->ci->jadeite_model->getItemJade($row['item_id']);

				}

				$data[$row['report_item_id']] = $row;
			}
		}
		return $data;
	}

	public function getGrandTotalInventoryReport() {
		//@TODO convert Grand Total SQL into ActiveRecord
		$sql = "SELECT "
			. "inventory.mjr_class_id, "
			. "major_class.mjr_class_name, "
			. "COUNT(inventory.item_id) AS quantity,"
			. "SUM(inventory.purchase_price) AS cost, "
			. "(SUM(inventory.purchase_price) / t.cost) * 100 AS pert_cost, "
			. "SUM(inventory.item_price) AS price, "
			. "(SUM(inventory.purchase_price) / t.price) * 100 AS pert_price, "
			. " t.cost AS total_cost, "
			. "t.price AS total_price "
		. " FROM inventory "
		. " JOIN "
			. "(SELECT SUM(inventory.purchase_price) AS cost, SUM(inventory.item_price) AS price "
			. " FROM inventory "
			. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id <> 3 AND min_class_id <> 93 AND min_class_id <> 91 AND item_quantity > 0) AS t "
		. " JOIN major_class ON inventory.mjr_class_id = major_class.mjr_class_id "
		. " WHERE inventory.item_status IN (1,2,3,4)  AND min_class_id <> 3 AND min_class_id <> 93 AND min_class_id <> 91 AND item_quantity > 0"
		. " GROUP BY inventory.mjr_class_id ";

		$data = array();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}

		return $data;
	}
	public function getFKGrandTotalInventoryReport() {
		//@TODO convert Grand Total SQL into ActiveRecord
		$sql = "SELECT "
			. "inventory.mjr_class_id, "
			. "major_class.mjr_class_name, "
			. "COUNT(inventory.item_id) AS quantity,"
			. "SUM(inventory.purchase_price) AS cost, "
			. "(SUM(inventory.purchase_price) / t.cost) * 100 AS pert_cost, "
			. "SUM(inventory.item_price) AS price, "
			. "(SUM(inventory.purchase_price) / t.price) * 100 AS pert_price, "
			. " t.cost AS total_cost, "
			. "t.price AS total_price "
		. " FROM inventory "
		. " JOIN "
			. "(SELECT SUM(inventory.purchase_price) AS cost, SUM(inventory.item_price) AS price "
			. " FROM inventory "
			. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id = 91 AND item_quantity > 0) AS t "
		. " JOIN major_class ON inventory.mjr_class_id = major_class.mjr_class_id "
		. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id = 91 AND item_quantity > 0"
		. " GROUP BY inventory.mjr_class_id ";

		$data = array();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}

		return $data;
	}

	public function getGrandTotalConsignmentReport() {
			$sql = "SELECT inventory.mjr_class_id, major_class.mjr_class_name, COUNT(inventory.item_id) AS quantity, SUM(inventory.purchase_price) AS cost, "
		. " (SUM(inventory.purchase_price) / t.cost) * 100 AS pert_cost, SUM(inventory.item_price) AS price, (SUM(inventory.purchase_price) / t.price) * 100 AS pert_price, "
		. " t.cost AS total_cost, t.price AS total_price "
		. " FROM inventory "
		. " JOIN (SELECT SUM(inventory.purchase_price) AS cost, SUM(inventory.item_price) AS price "
			. " FROM inventory "
			. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id IN (3) AND item_quantity > 0) AS t "
		. " JOIN major_class ON inventory.mjr_class_id = major_class.mjr_class_id "
		. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id IN (3) AND item_quantity > 0 "
		. " GROUP BY inventory.mjr_class_id ";

		$data = array();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	public function getFKGrandTotalConsignmentReport() {
			$sql = "SELECT inventory.mjr_class_id, major_class.mjr_class_name, COUNT(inventory.item_id) AS quantity, SUM(inventory.purchase_price) AS cost, "
		. " (SUM(inventory.purchase_price) / t.cost) * 100 AS pert_cost, SUM(inventory.item_price) AS price, (SUM(inventory.purchase_price) / t.price) * 100 AS pert_price, "
		. " t.cost AS total_cost, t.price AS total_price "
		. " FROM inventory "
		. " JOIN (SELECT SUM(inventory.purchase_price) AS cost, SUM(inventory.item_price) AS price "
			. " FROM inventory "
			. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id IN (93) AND item_quantity > 0) AS t "
		. " JOIN major_class ON inventory.mjr_class_id = major_class.mjr_class_id "
		. " WHERE inventory.item_status IN (1,2,3,4) AND min_class_id IN (93) AND item_quantity > 0 "
		. " GROUP BY inventory.mjr_class_id ";

		$data = array();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	public function getDailyInvoicePayments($start, $end) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');

		$data = array();
		$data['total_monies'] = 0;
		$data['records'] = array();

		$this->db->from('invoice_payments');
		$this->db->where('date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($row['buyer_type'] == 1) { //customer
					$row['buyer'] = $this->ci->customer_model->getCustomerData($row['buyer_id']);
				}
				else { //probably a vendor
					$row['buyer'] = $this->ci->vendor_model->getVendorData($row['buyer_id']);
				}
				if($row['method'] == 4) {
					$data['total_monies'] -= $row['amount'];
				}
				else {
					$data['total_monies'] += $row['amount'];
				}
				$data['records'][] = $row;
			}
		}
		return $data;
	}

	public function getDailyLayawayPayments($start, $end) {
		$this->ci->load->model('customer/customer_model');

		$data = array();
		$data['total_monies'] = 0;
		$data['records'] = array();

		$this->db->from('invoice_layaway');
		$this->db->where('payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['buyer'] = $this->ci->customer_model->getCustomerData($row['customer_id']);
				if($row['method'] == 4) {
					$data['total_monies'] -= $row['amount'];
				}
				else {
					$data['total_monies'] += $row['amount'];
				}
				$data['records'][] = $row;
			}
		}
		return $data;
	}

	public function getCatalogueReports() {
		$this->db->from('reports');
		$this->db->order_by('report_id', 'DESC');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['report_id']] = $row;
			}
		}
		return $data;
	}

	public function getInventoryCheckReportData($fields) {
		$this->ci->load->model('image/image_model');
		$this->ci->load->model('sales/invoice_model');

		//$this->db->select('inventory.*, invoice.invoice_id, invoice.buyer_type AS i_buyer_type, invoice.buyer_id AS i_buyer_id');
		//$this->db->select('inventory');

		$this->db->from('inventory');
		//$this->db->join('invoice_items', 'inventory.item_id = invoice_items.item_id', 'left');
		//$this->db->join('invoice', 'invoice_items.invoice_id = invoice.invoice_id', 'left');

		if($fields['major_class_id'] != false) {
			$this->db->where('mjr_class_id', $fields['major_class_id']);
		}
		if($fields['minor_class_id'] != false) {
			$this->db->where('min_class_id', $fields['minor_class_id']);
		}
		if(isset($fields['everything']) && $fields['everything'] == true) {
			//show everything
		}
		else {
			//test of status
			if(sizeof($fields['status']) > 0) {
				$this->db->where_in('inventory.item_status', $fields['status']);
			}
			//test for quantity
			if($fields['quantity'] == 1) { //greater than one
				$this->db->where('item_quantity > ', 0);
			}
			else if ($fields['quantity'] == 2) {//quantity is zero
				$this->db->where('item_quantity', 0);
			}
			else {
				//ingore quantity
			}
		}

		$this->db->order_by('mjr_class_id');
		$this->db->order_by('min_class_id');
		$this->db->order_by('suffix');

		$data = array();

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$row['status_text'] = ''; //nothing
				if($row['item_status'] == 0) { //sold
					//find the sold invoice
					$sold = $this->PV_findSoldInvoiceByItem($row['item_id']);
					$row['buyer_data'] = null;
					if(sizeof($sold) > 0) {
						$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($sold['buyer_id'], $sold['buyer_type']);
						$row['invoice_id'] = $sold['invoice_id'];
					}
				}
				else if($row['item_status'] == 2) { //out on job
					$this->ci->load->model('workshop/workshop_model');
					$row['workshop_data'] = $this->ci->workshop_model->getCurrentActiveJob($row['item_id']);
				}
				else if($row['item_status'] == 3) { //pending sale
					//find the pending sales
					$pending = $this->PV_findPendingInvoiceByItem($row['item_id']);
					$row['buyer_data'] = null;
					if(sizeof($pending) > 0) {
						$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($pending['buyer_id'], $pending['buyer_type']);
						$row['invoice_id'] = $pending['invoice_id'];
					}
				}
				else if($row['item_status'] == 4) { //out on memo
					$this->ci->load->model('inventory/inventory_model');
					$memo_id = $this->ci->inventory_model->getCurrentMemo($row['item_id']);
					$row['memo_data'] = $this->ci->invoice_model->getInvoiceData($memo_id);
					if(sizeof($row['memo_data']) > 0) {
						$row['memo_data']['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['memo_data']['buyer_id'], $row['memo_data']['buyer_type']);
					}
					else {
						$row['memo_data'] = array();
					}
				}
				else if($row['item_status'] == 7) { //returned to consignee
					$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
				}
				$data[] = $row;
			}
		}

		return $data;
		//return $this->db->last_query();
	}

	public function getInvoiceItemsData($invoice_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		//$this->db->where('item_status', 0); //only invoice items that are marked as sold
		$data = array();

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	public function getLayawayPayments($start = null, $end = null) {
		$this->ci->load->model('sales/invoice_model');
		$this->db->from('invoice_layaway');
		if($start != null && $end != null) {
			$this->db->where('payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		}
		$this->db->order_by('payment_date', 'ASC');
		$data = array();
		$query = $this->db->get();

		//echo $this->db->last_query();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$i = $this->ci->invoice_model->getInvoiceData($row['invoice_id']);
				if(!empty($i)) {
					$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($i['buyer_id'], 1);
					$row['sales_slip_number'] = $i['sales_slip_number'];
					$data[$row['layaway_id']] = $row;
				}
			}
		}
		return $data;
	}

	public function getLayaways($start = null, $end = null) {
		$this->ci->load->model('sales/invoice_model');
		$this->ci->load->model('sales/layaway_model');
		$this->db->from('invoice');
		$this->db->where('invoice_type', 1);
		if($start != null && $end != null) {
			$this->db->where('sale_date BETWEEN "' . $start . '" AND "' . $end . '"' , null, false);
		}
		$this->db->order_by('sale_date', 'DESC');
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['payment_amount'] = 0;
				$payments = $this->ci->layaway_model->getLayawayPayments($row['invoice_id']);
				foreach($payments as $payment) {
					$row['payment_amount'] += $payment['amount'];
				}
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				$row['price'] = $row['total_price'] + $row['tax'] + $row['ship_cost'];
				$data[$row['invoice_id']] = $row;
			}
		}
		return $data;
	}

	public function getMajorMinorClassReportData($major = null, $minor = null) {
		$this->db->from('inventory');
		$this->db->select('mjr_class_id, min_class_id');
		$this->db->select_sum('item_price');
		$this->db->select_sum('purchase_price');
		if($major != null) {
			$this->db->where('mjr_class_id', $major);
		}
		if($minor != null) {
			$this->db->where('min_class_id', $minor);
		}

		$this->db->group_by(array('mjr_class_id', 'min_class_id'));
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}

		return $data;
	}

	public function getMonthlyConsignmentData($start, $end) {
		$this->ci->load->model('sales/invoice_model');
		$this->db->select('invoice_items.item_id, invoice.invoice_id, invoice.sale_date, invoice_items.sale_price, inventory.item_name, inventory.item_number, invoice.buyer_id, invoice.buyer_type, invoice.invoice_type, inventory.seller_id, inventory.seller_type');
		$this->db->from('invoice_items');
		$this->db->join('invoice', 'invoice_items.invoice_id = invoice.invoice_id');
		$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('invoice.sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('inventory.min_class_id', 3);
		$this->db->where('inventory.item_status', 0);
		$this->db->order_by('invoice.sale_date', 'ASC');
		$data = array();
		$query = $this->db->get();

		$data['basic']['amount'] = 0;
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
				$data['basic']['amount'] += $row['sale_price'];
				$data['extra'][$row['item_id']] = $row;
			}
		}

		return $data;
	}

	public function getMonthlyFKData($start, $end) {
		$this->ci->load->model('sales/invoice_model');
		$this->db->select('invoice_items.item_id, invoice.invoice_id, invoice.sale_date, invoice_items.sale_price, inventory.item_name, inventory.item_number, invoice.buyer_id, invoice.buyer_type, invoice.invoice_type, inventory.seller_id, inventory.seller_type');
		$this->db->from('invoice_items');
		$this->db->join('invoice', 'invoice_items.invoice_id = invoice.invoice_id');
		$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('invoice.sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where_in('inventory.min_class_id', array(91,93));
		$this->db->where('inventory.item_status', 0);
		$this->db->order_by('invoice.sale_date', 'ASC');
		$data = array();
		$query = $this->db->get();

		$data['basic']['amount'] = 0;
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
				$data['basic']['amount'] += $row['sale_price'];
				$data['extra'][$row['item_id']] = $row;
			}
		}

		return $data;
	}
	public function getMonthlyLayawayPaymentsData($start, $end) {
		$this->db->from('invoice_layaway');
		$this->db->where('payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['invoice_id']] = $row;//remove any dups
			}
		}
		return $data;
	}

	public function getMonthlySalesLayawayPayments($start, $end, $user_id, $extra = false) {
		$this->ci->load->model('sales/invoice_model');

		$this->db->select('invoice_layaway.invoice_id, invoice_layaway.amount, invoice_layaway.payment_date, invoice.user_id, invoice.buyer_id, invoice.buyer_type');
		$this->db->from('invoice_layaway');
		$this->db->join('invoice', 'invoice_layaway.invoice_id = invoice.invoice_id');
		$this->db->where('invoice_layaway.payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice.user_id', $user_id); //get user

		$data = array();
		$data['basic']['amount'] = 0;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data['basic']['amount'] += $row['amount'];
				if($extra) {
					$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
					$data['extra'][] = $row;
				}
			}
		}
		return $data;
	}

	public function getMonthlySalesNormalInvoice($start, $end, $user_id) {
		$this->db->select('invoice.invoice_id, invoice.user_id, invoice.sale_date, invoice.total_price');
		$this->db->from('invoice');
		$this->db->where('sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice.user_id', $user_id); //get user
		$this->db->where('invoice_type <> ', 3); //ignore memos
		$this->db->where('invoice_type <> ', 1); //ignore layaways

		$data = array();
		$data['basic']['sale_count'] = 0;
		$data['basic']['amount'] = 0;

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data['basic']['sale_count']++;
				$data['basic']['amount'] += $row['total_price'];
			}
		}
		return $data;
	}

	public function getMonthlySalesPersonData($start, $end) {
		$this->db->select('invoice.invoice_id, invoice.user_id, invoice.sale_date, users.first_name, users.last_name');
		$this->db->from('invoice');
		$this->db->join('users', 'invoice.user_id = users.user_id');
		$this->db->where('sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice_type <> ', 3); //ignore memos
		$this->db->group_by('invoice.user_id');

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['invoice_data'] = $this->getMonthlySalesNormalInvoice($start, $end, $row['user_id']);
				$row['layaway_data'] = $this->getMonthlySalesLayawayPayments($start, $end, $row['user_id']);
				$row['return_data'] = $this->getMonthlySalesReturns($start, $end, $row['user_id']);
				$data[] = $row;
			}
		}
		return $data;
	}

	public function getMonthlySalesPersonDetailData($start, $end, $user_id) {
		$this->ci->load->model('sales/invoice_model');
		$this->ci->load->model('inventory/inventory_model');
		$this->db->select('invoice.invoice_id, invoice.invoice_type, invoice.user_id, invoice.sale_date, invoice.total_price, invoice.tax, invoice.buyer_id, invoice.buyer_type');
		$this->db->from('invoice');
		$this->db->where('sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice_type <> ', 3); //ignore memos
		$this->db->where('user_id', $user_id); //ignore memos

		$data = array();

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				$row['item_data'] = $this->ci->invoice_model->getInvoiceItemsData($row['invoice_id']);
				$row['special_data'] = $this->ci->invoice_model->getInvoiceSpecialItemsData($row['invoice_id']);
				$data[$row['invoice_id']] = $row;
			}
		}
		return $data;
	}

	public function getMonthlySalesReturns($start, $end, $user_id, $extra = false) {
		$this->ci->load->model('sales/invoice_model');

		$this->db->select('returns.return_id, returns.date, returns.refund, invoice.invoice_id, invoice.buyer_id, invoice.buyer_type, invoice_items.sale_price, invoice_items.item_status, returns.date');
		$this->db->from('returns');
		$this->db->join('invoice', 'returns.invoice_id = invoice.invoice_id');
		$this->db->join('invoice_items', 'invoice.invoice_id = invoice_items.invoice_id');
		$this->db->where('returns.date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice.user_id', $user_id); //get user
		$this->db->where('invoice_items.item_status', 1); //1=returned

		$data = array();
		$data['basic']['amount'] = 0;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data['basic']['amount'] -= $row['sale_price'];
				if($extra) {
					$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
					$data['extra'][] = $row;
				}
			}
		}
		return $data;
	}

	public function getMonthlyTurnoverData($start, $end) {
		$this->ci->load->model('utils/lookup_list_model');
		$this->ci->load->model('admin/major_class_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->select('*, invoice_items.item_status AS invoice_item_status, invoice.buyer_id AS invoice_buyer_id, invoice.buyer_type AS invoice_buyer_type');
		$this->db->from('invoice_items');
		$this->db->join('invoice', 'invoice_items.invoice_id = invoice.invoice_id');
		$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('invoice.sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice.invoice_type !=', 3); //no memo
		$this->db->where('invoice.invoice_status !=', 1); //not editable
		$this->db->order_by('mjr_class_id, min_class_id, suffix','ASC');

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if(!isset($data[$row['mjr_class_id']]['mjr_class'])) {
					$data[$row['mjr_class_id']]['mjr_class'] = $this->ci->major_class_model->getMajorClassData($row['mjr_class_id']);
				}
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['invoice_buyer_id'], $row['invoice_buyer_type']);
				$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);

				if($row['invoice_item_status'] != 1) { //not returned
					$data[$row['mjr_class_id']]['items'][$row['item_id']] = $row;
				}
			}
		}
		return $data; //
	} //end getMonthlyTurnoverData();

	/**
	 *
	 * @param unknown_type $start
	 * @param unknown_type $end
	 */
	public function getMonthlyTurnoverLayawayData($start, $end) {
		$this->ci->load->model('utils/lookup_list_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->distinct(true);
		$this->db->select('invoice.*, invoice_items.sale_price, invoice_layaway.invoice_id, inventory.*, invoice.buyer_id AS invoice_buyer_id, invoice.buyer_type AS invoice_buyer_type, ', false);
		$this->db->from('invoice_layaway');
		$this->db->join('invoice', 'invoice_layaway.invoice_id = invoice.invoice_id');
		$this->db->join('invoice_items', 'invoice.invoice_id = invoice_items.invoice_id');
		$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('invoice.layaway_end_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		//$this->db->where('invoice_layaway.payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->order_by('mjr_class_id, min_class_id, suffix','ASC');

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($this->PV_layawayPaidInFull($row['invoice_id'], $row['total_price'] + $row['tax'])) {
					$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['invoice_buyer_id'], $row['invoice_buyer_type']);
					$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
					$data[] = $row;
				}
			}
		}

		return $data; //array
	} //end getMonthlyTurnoverLayawayData()

	/**
	 * Returns the Monthy Specials Data
	 *
	 * @param [date] $start = start date
	 * @param [date] $end = end date
	 *
	 * @return [array] = multi-dim array
	 */
	public function getMonthlyTurnoverSpecialData($start, $end) {
		$this->ci->load->model('sales/invoice_model');
		$data = array();
		$this->db->distinct(true); //only return unique records
		$this->db->from('invoice_special_items');
		$this->db->join('invoice', 'invoice_special_items.invoice_id = invoice.invoice_id');
		$this->db->where('invoice.sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice_type ', 0);
		$this->db->where('item_status', 0);
		$this->db->where('item_type', 1);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				$data[] = $row;
			}
		}

		return $data; //array
	} //end getMonthlyTurnoverSpecialData();

	/**
	 * Attempts to find all of the store credit within a date range
	 *
	 * @param [date] $start = start date
	 * @param [date] $end = end date
	 *
	 * @return [array] = multi-dim array of
	 */
	public function getNonStoreCreditReturnsData($start, $end) {
		$this->db->from('returns');
		$this->db->join('invoice', 'returns.invoice_id = invoice.invoice_id');
		$this->db->where('date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice_status !=', 5); //do not include cancelled layaways
		$this->db->where_in('returns.buyer_type', array(1, 3)); //only select customers/internet customers

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($row['refund_type'] == 2) { //credit given
					$row['credit_given'] = true;
				}
				else {
					$row['credit_given'] = false;
				}
				$data[] = $row;
			}
		}

		return $data; //array
	} //end getNonStoreCreditReturnsData();

	/**
	 * Attempts to return all of the open memos between
	 * a specific date range.
	 *
	 * @param [date] $start = start date
	 * @param [date] $end = end date
	 *
	 * @return [array] = multi-dim array of invoice data
	 */
	public function getOpenLayaways($start = null, $end = null) {
		$this->ci->load->model('sales/invoice_model');
		$this->ci->load->model('sales/layaway_model');
		$this->db->from('invoice');
		$this->db->where('invoice_type', 1);
		if($start != null && $end != null) {
			$this->db->where('sale_date BETWEEN "' . $start . '" AND "' . $end . '"' , null, false);
		}
		$this->db->order_by('sale_date', 'DESC');
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['payment_amount'] = 0;
				$payments = $this->ci->layaway_model->getLayawayPayments($row['invoice_id']);
				foreach($payments as $payment) {
					$row['payment_amount'] += $payment['amount'];
				}
				$row['total_price'] = $row['total_price'] + $row['tax'] + $row['ship_cost'];
				if((int)($row['total_price'] - $row['payment_amount']) > 0) {
					$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
					$row['num_items'] = $this->ci->invoice_model->getNumberInvoiceItems($row['invoice_id']);
					$data[$row['invoice_id']] = $row;

				}
			}
		}

		return $data; //array
	} //end getOpenLayaways();

	/**
	 * Attempts to return all of the memos which are still
	 * open, based on memo_close_date
	 *
	 * @param [boolean] $open = filter by memo_close_date
	 *
	 * @return [array] = multi-dim array of invoice data
	 */
	public function getOpenMemos($open = true) {
		$this->ci->load->model('sales/invoice_model');
		$this->db->from('invoice');
		$this->db->where('invoice_type', 3);
		$this->db->where('invoice_status !=', 3);
		if($open) {
			$this->db->where('memo_close_date IS NULL', null, false);
		}
		$this->db->order_by('sale_date', 'DESC');
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				$row['num_items'] = $this->ci->invoice_model->getNumberInvoiceItems($row['invoice_id']);
				$data[$row['invoice_id']] = $row;
			}
		}

		return $data; //array
	} //end getOpenMemos();

	/**
	 * Attempts to find all of the items applied to
	 * a specific return.
	 *
	 * @param [int] $return_id = return id
	 *
	 * @return [array] = multi-dim array of items applied
	 */
	public function getReturnedItemsData($return_id) {
		$this->db->from('return_items');
		$this->db->where('return_id', $return_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}

		return $data; //array
	} //end getReturnedItemsData();

	/**
	 * Attempts to get the total amount of Credit, Credit Given,
	 * and Credit Used for every customer in the database.
	 * Optinal: can specify a end date.
	 *
	 * @param [date] $end = sets an end date to where by
	 *
	 * @return [array] = array of store credit data;
	 */
	public function getSumCustomerStoreCredit($end = null) {
		$this->db->from('customer_store_credit');

		if($end != null) {
			$this->db->where('date < "' . $end . '"' , null, false);
		}
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			$credit = 0;
			$given = 0;
			$used = 0;
			foreach($query->result_array() as $row) {
				if($row['action_type'] == 1 || $row['action_type'] == 3) { //addition
					$given += $row['credit_amount'];
					$credit += $row['credit_amount'];
				}
				else {
					$used -= $row['credit_amount'];
					$credit -= $row['credit_amount'];
				}
			}
			$data['total_credit'] = $credit; //totall amount of credit
			$data['total_given'] = $given; //total amount of credit left
			$data['total_used'] = $used; //total amount of credit used
		}

		return $data; //array
	} //end getSumCustomerStoreCredit();

	/**
	 * Attempts to return invoice turnover report data for a
	 * specific data range.
	 *
	 * @param [date] $start = start data
	 * @param [date] $end = end date
	 *
	 * @return [array] = multi-dim array of report data;
	 */
	public function getYearlyTurnoverData($start, $end) {
		$this->ci->load->model('utils/lookup_list_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->select('*, invoice.buyer_id AS invoice_buyer_id, invoice.buyer_type AS invoice_buyer_type');
		$this->db->from('invoice_items');
		$this->db->join('invoice', 'invoice_items.invoice_id = invoice.invoice_id');
		$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('invoice.sale_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$this->db->where('invoice.invoice_type !=', 3); //exclude memos
		$this->db->where('invoice.invoice_type !=', 1); //exclude layaways
		$this->db->where('invoice.invoice_status !=', 1); //exclude non-submitted invoices

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['last_layaway_payment'] = null;
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['invoice_buyer_id'], $row['invoice_buyer_type']);
				$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
				$data[] = $row;
			}
		}

		return $data; //array
	} //end getYearlyTurnoverData();

	/**
	 * Attempts to gather layaway report data for a specific date range
	 *
	 * @param [date] $start = start date
	 * @param [date] $end = end date
	 *
	 * @return [array] = multi-dim array of report data;
	 */
	public function getYearlyTurnoverLayawayData($start, $end) {
		$this->ci->load->model('utils/lookup_list_model');
		$this->ci->load->model('sales/invoice_model');

		$this->db->distinct(true); //only return unique rows.
		$this->db->select('invoice.*, invoice_layaway.payment_date AS layaway_payment_date, invoice.buyer_id AS invoice_buyer_id, invoice.buyer_type AS invoice_buyer_type, invoice_items.sale_price, invoice_layaway.invoice_id, inventory.*', false);
		$this->db->from('invoice_layaway');
		$this->db->join('invoice', 'invoice_layaway.invoice_id = invoice.invoice_id');
		$this->db->join('invoice_items', 'invoice.invoice_id = invoice_items.invoice_id');
		$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('invoice_layaway.payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($this->PV_layawayPaidInFull($row['invoice_id'], $row['total_price'] + $row['tax'])) {
					$row['last_layaway_payment'] = $row['layaway_payment_date'];
					$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['invoice_buyer_id'], $row['invoice_buyer_type']);
					$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
					$data[$row['invoice_id']] = $row;
				}
			}
		}

		return $data; //array
	} //end getYearlyTurnoverLayawayData();

	/**
	 * Attempst to remove a report item (item applied to
	 * a report) from the database.
	 *
	 * @param [int] $report_id = id of report
	 * @param [int] $item_id = item id
	 *
	 * @return NULL
	 */
	public function removeCatalogueReportItem($report_id, $item_id) {
		$this->db->where('report_id', $report_id);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit 1
		$this->db->delete('reports_items');

		return null;
	} //end removeCatelogueReportItem();

	/*
	 * *************************
	 * Private Method
	 * *************************
	 *
	 * Attempts to find an Invoice
	 *
	 */
	private function PV_findPendingInvoiceByItem($item_id) {
		$this->db->from('invoice');
		$this->db->join('invoice_items','invoice.invoice_id = invoice_items.invoice_id','left');
		$this->db->where('invoice_items.item_id', $item_id);
		$this->db->where('invoice_items.item_status', 0); //pending
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}

		return $data; //array
	} //end PV_findPendingInvoiceByItem();

	/**
	 * *************************
	 * Private Method
	 * *************************
	 *
	 * Attempts to find invoices with sold items
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return = array of invoice data
	 */
	private function PV_findSoldInvoiceByItem($item_id) {
		$this->db->from('invoice');
		$this->db->join('invoice_items','invoice.invoice_id = invoice_items.invoice_id','left');
		$this->db->where('invoice_items.item_id', $item_id);
		$this->db->where('invoice_items.item_status', 0); //sold
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}

		return $data; //array
	} //ebd PV_findSoldInvoiceByItem()

	/**
	 * *************************
	 * Private Method
	 * *************************
	 *
	 * Tests to see if a layaway has been paid in full
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [float] $price = total price
	 *
	 * @return [boolean] = false=not in full, true=paid
	 */
	private function PV_layawayPaidInFull($invoice_id, $price) {
		$this->db->from('invoice_layaway');
		$this->db->where('invoice_id', $invoice_id);
		$amount = 0;
		$b = false;
		$query = $this->db->get();

		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$amount += $row['amount'];
			}
		}
		if($price == $amount) { //price and paid amount are the same
			$b = true;
		}

		return $b; //bool
	} //end PV_layawayPaidInFull();

} //end Report_model();
?>