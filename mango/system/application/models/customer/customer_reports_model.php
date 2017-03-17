<?php
class Customer_reports_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();

	}
	/**
	 * Generates a CSV file of all customers, vendors, workshops,
	 * that are on the mailing list,
	 * for more details see: mailing_list view in @database.
	 *
	 * Note that the folder /var/www/langantiques.com/mango/files :files
	 * needs to be owned/grouped by www-data on the server, else apache
	 * will not be able to write to the folder
	 *
	 * @return null
	 */
	public function generateMailingList() {
		$this->load->dbutil(); //database util class
		$this->load->helper('file');

		$this->db->from('mailing_list');
		$query = $this->db->get();
		$data = '';
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$line = '';
				//start formating name column;
				if($row['spouse_first'] != '') {// if the spouses first name is not null, check the last name
					if($row['spouse_last'] != '') { //if spouse_last name is not null,
						if($row['spouse_last'] == $row['last_name']) { // check to see if it matches the primary last_name
							$row['name'] = $row['first_name'] . ' and ' . $row['spouse_first'] . ' ' . $row['last_name'];
						}
						else {// if it does not, append both names.
							$row['name'] = $row['first_name'] . ' ' . $row['last_name'] . ' and ' . $row['spouse_first'] . ' ' . $row['spouse_last'];
						}
					}
					else { //if the last name is null
						$row['name'] = $row['first_name'] . ' and ' . $row['spouse_first'] . ' ' . $row['last_name'];
					}
				}
				$line .= '"' . str_replace('"', '"'.'"', $row['type']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['name']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['address']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['city']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['state']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['zip']) . '"' . ',';
				$line .= "\r\n";
				$data .= $line;
			}
		}

		//echo $data;
		//$delimiter = ",";
		//$newline = "\r\n";
		//$data = $this->dbutil->csv_from_result($ro, $delimiter, $newline);

		$name = 'customer_mailing_list_' . date('Y.m.d') . '.csv';
		$path = './files/csv/' . $name;

		if(!write_file($path, $data)) {
			echo 'help!';
			//cound not write file for some reason
		}
		else {
     		$file = read_file($path);
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename="'. $name . '"');

			echo $file; //needed to return the file to the browser
		}

	} //end GenerateMailingList();

	/**
	 * This is used for listing through the customers
	 *
	 * @param [int] $per_page = number of results per page
	 * @param [int] $offset = offset used
	 * @param [string] $sort = field to sort by
	 * @param [string] $direction = direction of sort
	 *
	 * @return [array] multidimtonal of customer data
	 */
	public function getAvailableCustomers($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('customer_info');
		$this->db->where('active', 1);
		if($sort != FALSE) {
			$this->db->order_by($sort, $direction);
		}
		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		if($data['num_rows'] > 0) {
			//$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getAvailableCustomers();

	/**
	 * This returns all of the items that have been purchased by
	 * a specific customer
	 *
	 * @param [int] $id = customer_id
	 * @return [array] = multidimential array of items;
	 */
	public function getCustomerPurchasedItems($id) {
		$this->ci->load->model('image/image_model');
		$this->db->select('invoice.invoice_id, invoice.total_price, invoice.tax,
							invoice.invoice_status, invoice_items.invoice_item_id,
							invoice_items.item_number, invoice_items.item_id,
							invoice.sale_date,
							invoice_items.sale_price, invoice_items.sale_tax,
							invoice.total_price, inventory.item_name, invoice_items.item_status AS i_item_status');
		$this->db->from('invoice');
		$this->db->join('invoice_items', 'invoice_items.invoice_id = invoice.invoice_id', 'left');
		$this->db->join('inventory', 'inventory.item_id = invoice_items.item_id', 'left');
		$buyer_type = array(1, 3);
		$this->db->where_in('invoice.buyer_type', $buyer_type);
		$this->db->where('invoice.buyer_id', $id);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[] = $row;
			}
		}
		return $data; //array

	} //end getCustomerPurchjasedItems();

	/**
	 * This returns all of the item that a specific customer
	 * sold to us. The name kind of sucks.
	 * @TODO rename this method to 'getPrivateBuyItems'
	 * //we call items which a customer sells us as Private Buys.
	 *
	 * @param [int] $id = customer_id;
	 *
	 * @return [array] multidimential array of sold items;
	 */
	public function getCustomerSoldItems($id) {
		$this->ci->load->model('image/image_model');
		$this->ci->load->model('utils/lookup_list_model');
		$status = $this->ci->lookup_list_model->getItemStatus();

		$this->db->from('inventory');
		$this->db->where('seller_id', $id);
		$this->db->where('seller_type', 2);
		$this->db->order_by('purchase_date', 'ASC');

		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['item_status_text'] = $status[$row['item_status']]['name'];
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[] = $row;
			}
		}
		return $data; //array

	} //end getCustomerSoldItems();

	/**
	 * This returns all of the store credit values for a specific
	 * customer
	 *
	 * @param [int] $id = customer_id
	 * @return [array] multidimential array of credits
	 */
	public function getCustomerStoreCredit($id) {
		$this->db->from('customer_store_credit');
		$this->db->where('customer_id', $id);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getCustomerStoreCredit();


	public function getCustomerStoreCreditAmount($customer_id) {
		$temp_data = array();
		$amount = 0;
		$this->db->from('customer_store_credit');
		$this->db->where('customer_id', $customer_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				// addition
				if($row['action_type'] == 1 || $row['action_type'] == 3) {
					$amount += $row['credit_amount'];
				}
				// subtraction
				if($row['action_type'] == 0 || $row['action_type'] == 4) {
					$amount -= $row['credit_amount'];
				}
			}
		}
		return $amount;
	}

	/**
	 * Returns a customer store credit record
	 *
	 * @param [int] $customer_id = customer id
	 * @param [int] $credit_id = store credit id
	 *
	 * @return [array] array of customer store credit data
	 */
	public function getCustomerStoreCreditData($customer_id, $credit_id) {
		$this->db->from('customer_store_credit');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('store_credit_id', $credit_id);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array

	} //end getCustomerStoreCreditData();

} //end Customer_report_model();
?>