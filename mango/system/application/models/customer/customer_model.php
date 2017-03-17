<?php
/**
 * Customer Model,
 *
 * used to modify customer data
 *
 * @author user
 *
 */
class Customer_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Copys the the billing address to the
	 * Shipping Address for a specific customer
	 *
	 * @param [int] $id = customer_id
	 * @param [array] $fields = array of fields
	 *
	 * @return null;
	 */
	public function copyBillingAddress($id, $fields) {
		$this->updateCustomerShipping($id, $fields);

		return null;
	} //end copyBillingAddress();

	/**
	 * Deletes a Customer for the database
	 *
	 * @param [int] $customer_id = customer id
	 */
	public function deleteCustomer($customer_id) {
		$this->db->where('customer_id', $customer_id);
		$this->db->limit(1); //always limit 1
		$this->db->delete('customer_info');

		return null;
	} //end deleteCustomer();

	/**
	 * Removes a store credit record form a specific customer
	 *
	 * @param [int] $customer_id = customer id
	 * @param [int] $credit_id = store credit id
	 *
	 * @return true ? why?
	 */
	public function deleteCustomerStoreCredit($customer_id, $credit_id) {
		$this->db->where('customer_id', $customer_id);
		$this->db->where('store_credit_id', $credit_id);
		$this->db->limit(1); //always limit 1;

		$this->db->delete('customer_store_credit');

		return true;
	} //end deleteCustomerStoreCredit();

	/**
	 * Gets the billing address of a specific customer
	 *
	 * @param [int] $id = customer_id
	 *
	 * @return [array] array of address data
	 */
	public function getBillingAddress($id) {
		$this->db->select('first_name, home_phone, work_phone, last_name,  address, address2, city, state, zip, country');
		$this->db->from('customer_info');
		$this->db->where('customer_id', $id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getBillingAddress();

	/**
	 * Returns the customer Credit Card Info
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [int] $customer_id = customer id
	 *
	 * @return [array] = array of credit card data
	 */
	public function getCustomerCreditCardInfo($invoice_id, $customer_id) {
		$this->db->from('internet_customer_creditcard');
		$this->db->select('internet_customer_creditcard.*');
		$this->db->where('int_customer_id', $customer_id);
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getCustomerCreditCardInfo();


	public function getCustomerReturns($customer_id) {
		$this->db->from('returns');
		$this->db->where('buyer_id', $customer_id);
		$this->db->where('buyer_type', 1); //1=  customer, 2= vendor

		$results = $this->db->get();
		$data = array();
		if($results->num_rows() > 0) {
			foreach($results->result_array() as $row) {
				$data[] = $row;
			}
		}

		return $data;
	}

	public function getDecryptedCreditCard($invoice_id, $customer_id) {
		$this->db->from('internet_customer_creditcard');

		// get decrypted creditcard number and cvv number;
		$this->db->select("AES_DECRYPT(`internet_customer_creditcard`.`encrypt_card_number`,'" . AES_KEY . "') AS `decrypt_card_number`", false);
		$this->db->select("AES_DECRYPT(`internet_customer_creditcard`.`encrypt_card_cvv`,'" . AES_KEY . "') AS `decrypt_card_cvv`", false);

		$this->db->where('int_customer_id', $customer_id);
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	}


	/**
	 * Returns all of the customer data from the table 'customer_info'
	 * uses a specifid ID
	 *
	 * @param [int] $id = customer_id
	 * @return [array] = customer data
	 */
	public function getCustomerData($id) {
		$this->db->from('customer_info');
		$this->db->where('customer_id', $id);

		$query = $this->db->get();
		$data = array();

		if($query->num_rows() > 0) { //customer found
			foreach($query->result_array() as $row) {
				$row['phone'] = $row['home_phone']; //added for cont
				$row['other_phone'] = $row['work_phone']; //add for cont
				$row['link'] = base_url() . 'customer/edit/' . $id;
				$row['name'] = $row['first_name'] . ' ' . $row['last_name'];
				$data = $row;
			}
		}
		else { //else, return unknown customer
			$this->ci->load->model('utils/lookup_list_model');
			$data = $this->ci->lookup_list_model->unknownCustomer();
			$data['customer_id'] = $id;
		}
		return $data; //array
	} //end getCustomerData();

	/**
	 * Returns a specific customers store credit by date;
	 *
	 * @param [int] $customer_id = customer id
	 * @param [int] $invoice_id = invoice id
	 * @param [date] $date = date
	 *
	 * @return [array] = store credit data
	 */
	public function getCustomerStoreCreditByDate($customer_id, $invoice_id, $date) {
		$this->db->from('customer_store_credit');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('date', $date);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getCustomerStoreCreditByDate();

	/**
	 * Gets the Shipping address of a specific customer
	 *
	 * @param [int] $id = customer_id
	 *
	 * @return [array] = array of customer address data
	 */
	public function getShippingAddress($id) {
		$this->db->select('ship_contact, ship_phone, ship_other_phone, ship_address, ship_address2, ship_city, ship_state, ship_zip, ship_country');
		$this->db->from('customer_info');
		$this->db->where('customer_id', $id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getShippingAddress();

	/**
	 * Returns all of the store credit delete histroy
	 *
	 * @return [array] = multi-din  array of delete history
	 */
	public function getStoreCreditDeleteHistory() {
		$this->ci->load->model('user/user_model');
		$this->db->from('customer_store_credit_delete_history');
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['user_name'] = $this->ci->user_model->getUserName($row['user_id']);
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getStoreCreditDeleteHistory();

	/**
	 * Inserts data into the 'customer_info' table
	 *
	 * @param [array] $fields = all of the data supplied by
	 * the user, only the fields that are enteried are inserted.
	 *
	 * @return [int] = new customer id
	 */
	public function insertCustomer($fields) {
		$this->db->insert('customer_info', $fields);
		return $this->db->insert_id(); //int
	} //end insertCustomer();

	/**
	 * Inserts a Store Credit Delete History record
	 * Stores the history of deleting store credit.
	 *
	 * @param [array] $fields = array of fields
	 * @return delete store credit id
	 */
	public function insertCustomerStoreCreditDeleteHistory($fields) {
		$this->db->insert('customer_store_credit_delete_history',$fields);
		return $this->db->insert_id(); //int
	} //end insertCustomerStoreCreditDeleteHistory();

	/**
	 * Insters a Store Credit Entry for a specific customer
	 *
	 * @param [array] $fields = fields array
	 * @return [int] = store credit id
	 */
	public function insertStoreCredit($fields) {
		$this->db->insert('customer_store_credit', $fields);
		return $this->db->insert_id(); //int
	} //end insertStoreCredit();

	/**
	 * Masks the credit card number and cvv
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [int] $customer_id = customer id
	 *
	 * @return null
	 */
	public function maskCreditCard($invoice_id, $customer_id) {

		$updates = array('encrypt_card_number' => null, 'encrypt_card_cvv' => null, 'masked' => '1');

		$this->db->where('int_customer_id', $customer_id);
		$this->db->where('invoice_id', $invoice_id);
		$this->db->limit(1); //always limit one
		$this->db->update('internet_customer_creditcard', $updates);

		return null;
	} //end maskCreditCard();

	/**
	 * Searches a customer_info table for specific string
	 *
	 * @param [string] $string =  search string
	 * @param [string] $n = ids not to use
	 *
	 * @return [array] = multi-din  array of customer names
	 */
	public function searchCustomerNames($string, $n) {
		$sql = 'SELECT * FROM customer_info WHERE (';
		$string = mysql_real_escape_string($string);
		$array = explode(' ', $string);
		//print_r($array);
		/**
		 * Clean this stuff up a bit
		 * @TODO clean this part of the search
		 */

		if(sizeof($array) > 1) {
			$sql .= " ((first_name LIKE '$string%' OR last_name LIKE '$string%') "
			. " OR (spouse_first LIKE '$string%' OR spouse_last LIKE '$string%') ) OR ";
			foreach($array as $str) {
				//$sql .= ' AND ';
				if($str == end($array)) {
					$sql .= " ((first_name LIKE '$str%' OR last_name LIKE '$str%') "
						. " OR (spouse_first LIKE '$str%' OR spouse_last LIKE '$str%')) ";
				}
				else {
					$sql .= " ((first_name LIKE '$str%' OR last_name LIKE '$str%') "
						. " OR (spouse_first LIKE '$str%' OR spouse_last LIKE '$str%') ) AND ";
				}
			}
		}
		else {
			$sql .= " (first_name LIKE '$string%' OR last_name LIKE '$string%') "
				. " OR (spouse_first LIKE '$string%' OR spouse_last LIKE '$string%') ";
		}

		$sql .= ')';
		if($n == '') {
			//do nothing, nothing in the n
		}
		else {
			$no_load = @explode(',', $n); //suppress error messages
			if(count($no_load) >= 1) {
				foreach($no_load as $id) {
					$sql .= ' AND customer_id != ' . $id;
				}
			}
		}
		$sql .= ' ORDER BY last_name, first_name ASC LIMIT 50 ';
		//echo $sql;
		$query = $this->db->query($sql);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end searchCustomerNames();

	/**
	 * This updates the data for a specific field (item_name, item_description, etc...)	 *
	 * @param [int] $id
	 * @param [string] $field = database name of field to update {first_name, home_phone, etc...}
	 * @param [string] $value = value of the field
	 *
	 * @return [string] = returns the value (used for display purposes only)
	 */
	public function updateCustomerField($id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('customer_id', $id);
		$this->db->limit(1); //always limit one
		$this->db->update('customer_info', $data);

		return $value; //string
	} //end updateCustomerField();

	/**
	 * Updates the Shipping Address with the Billing Address
	 * for a specific customer
	 *
	 * @param [int] $id = customer_id
	 * @param [array] $fields = array of fields
	 *
	 * @return null;
	 */
	public function updateCustomerShipping($id, $fields) {
		$this->db->where('customer_id', $id);
		$this->db->limit(1); //always limit one
		$this->db->update('customer_info', $fields);

		return null; //null
	} //end updateCustomerShipping();

	public function getCustomerStoreCreditByInvoiceId($invoice_id) {

	}

	/**
	 * Updates a store credit via a credit id,
	 *
	 * @param [int] $credit_id = store credit id
	 * @param [array] $fields = array of fields and data;
	 *
	 * @return null;
	 */
	public function updateCustomerStoreCreditByInvoiceId($credit_id, $fields) {
		$this->db->where('store_credit_id', $credit_id);
		$this->db->limit(1); //always limit one
		$this->db->update('customer_store_credit', $fields);

		return null;
	} //end updateCustomerStoreCreditByInvoiceId();

	/**
	 * Call back to cehck Customer ID
	 *
	 * @param [string] $string = customer id, should be an int, but you never know....
	 *
	 * @return [bool]
	 */
	public function CB_checkCustomerID($string) {
		$b = false;
		$this->db->from('customer_info');
		$this->db->where('customer_id', $string);
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			$b = true;
		}
		return $b; //bool
	} //end CB_checkCustomerID();

} //end Customer_model();
?>