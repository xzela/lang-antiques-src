<?php
class Vendor_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();

	}

	/**
	 * Updates the Shipping Address with the Billing Address
	 * for a specific vendor
	 *
	 * @param [int] $id = vendor_id
	 * @param [array] $fields = array of fields
	 *
	 * @return null;
	 */
	public function copyBillingAddress($vendor_id, $fields) {
		$this->updateVendorShipping($vendor_id, $fields);

		return null;
	} //end copyBillingAddress();

	/**
	 * Deletes a Vendor from the database
	 *
	 * @param [int] $vendor_id
	 *
	 * @return null;
	 */
	public function deleteVendor($vendor_id) {
		$this->db->where('vendor_id', $vendor_id);
		$this->db->limit(1); //always limit one when deleting
		$this->db->delete('vendor_info');

		return null;
	} //ebd deleteVendor();

	/**
	 * Returns all of the Vendors;
	 *
	 * @return [array] = multi-dim array of vendor data;
	 */
	public function getAllVendors() {
		$this->db->from('vendor_info');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['vendor_id']] = $row;
			}
		}
		return $data; //array;
	} //end getAllVendors();

	/**
	 * Gets the billing address of a specific vendor
	 *
	 * @param [int] $vendor_id = vendor_id
	 *
	 * @return [array] = array of address data
	 */
	public function getBillingAddress($vendor_id) {
		$this->db->select('phone, alt_phone, name, address, address2, city, state, zip, country');
		$this->db->from('vendor_info');
		$this->db->where('vendor_id', $vendor_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}

		return $data; //array
	} //end GetBillingAddress();

	public function getVendorReturns($vendor_id) {
		$this->db->from('returns');
		$this->db->where('buyer_type', 2); // 1=customer, 2=vendor
		$this->db->where('buyer_id', $vendor_id);

		$data = array();
		$results = $this->db->get();
		if($results->num_rows() > 0) {
			foreach($results->result_array() as $row) {
				$data[]  = $row;
			}
		}

		return $data;
	}
	/**
	 * Gets the shipping address of a specific vendor
	 *
	 * @param [int] $vendor_id = vendor_id
	 *
	 * @return [array] array of address data
	 */
	public function getShippingAddress($vendor_id) {
		$this->db->select('ship_contact, ship_phone, ship_other_phone, ship_address, ship_address, ship_city, ship_state, ship_zip, ship_country');
		$this->db->from('vendor_info');
		$this->db->where('vendor_id', $vendor_id);
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
	 * Returns all of the vebdor data from the table 'vendor_info'
	 * uses a specifid ID
	 *
	 * @param [int] $vendor_id = vendor_id
	 *
	 * @return array = vendor data
	 */
	public function getVendorData($vendor_id) {
		$this->db->from('vendor_info');
		$this->db->where('vendor_id', $vendor_id);

		$query = $this->db->get();
		$data = array();

		if($query->num_rows() == 1) {
			foreach($query->result_array() as $row) {
				$row['phone'] = $row['phone']; //added for cont, what?
				$row['other_phone'] = $row['alt_phone']; //add for cont
				if($row['first_name'] == '' ) { //if no first name give,
					$row['first_name'] = $row['name'];
				}
				$row['link'] = base_url() . 'vendor/edit/' . $vendor_id;
				$data = $row;
			}
		}
		else {
			$this->ci->load->model('utils/lookup_list_model');
			$data = $this->ci->lookup_list_model->unknownVendor();
			$data['vendor_id'] = $vendor_id;
		}

		return $data; //array
	} //end getVendorData();

	/**
	 * Inserts a new vendor into the vendor_info table
	 *
	 * @param [array] $fields = all of the columns need to insert a record
	 * @return [int] vendor id;
	 */
	public function insertVendor($fields) {
		$this->db->insert('vendor_info', $fields);

		return $this->db->insert_id(); //int

	} //end insertVendor();

	/**
	 * Updates the Shipping Address of a Vendor,
	 * This could probably be removed.
	 *
	 * @param [int] $vendor_id = vendor id
	 * @param [array] $fields = array of fields and values
	 *
	 * @return null;
	 */
	public function updateVendorShipping($vendor_id, $fields) {
		$this->db->where('vendor_id', $vendor_id);
		$this->db->limit(1); //always limit one;
		$this->db->update('vendor_info', $fields);

		return null;
	} //end updateVendorShipping();

	/**
	 * This updates the data for a specific field (item_name, item_description, etc...)
	 *
	 * @param [int] $vendor_id =
	 * @param [string] $field = database name of field to update {first_name, home_phone, etc...}
	 * @param [string] $value = value of the field
	 *
	 * @return [string] = returns the value (used for display purposes only)
	 */
	public function updateVendorField($vendor_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->update('vendor_info', $data);

		return $value; //string

	} //end updateVendorField();

	/**
	 * Ajax query: n'ugh said.
	 *
	 * @param [string] $string
	 *
	 * @return [array] = multi-dim array of vendor data
	 */
	public function searchVendorNames($string, $n = null) {

		$sql = 'SELECT * FROM vendor_info WHERE ';

		/**
		 * Sending an array to explode()causes a Array to
		 * String conversion error. I have suppressed the Error using @
		 *
		 * Although his works, You need to fix this:
		 * @TODO Fix the String to Array conversion error.
		 */
		// $chars = array(' ', '+');
		$array = @explode(' ', $string);
		//var_dump($array);
		if(count($array) > 1) {
			foreach($array as $str) {
				if($str == end($array)) {
					$sql .= " (first_name LIKE '$str%' OR last_name LIKE '$str%' OR name LIKE '$string%') ";
				}
				else {
					$sql .= " (first_name LIKE '$str%' OR last_name LIKE '$str%' OR name LIKE '$string%') AND ";
				}
			}
		}
		else {
			$sql .= " (first_name LIKE '$string%' OR last_name LIKE '$string%' OR name LIKE '$string%') ";
		}
		if($n == '') {
			//do nothing, nothing in the n
		}
		else {
			$no_load = @explode(',', $n); //suppress error messages
			if(count($no_load) >= 1) {
				foreach($no_load as $id) {
					$sql .= ' AND vendor_id != ' . $id;
				}
			}
		}

		$sql .= ' ORDER BY name ASC ';
		$query = $this->db->query($sql);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end searchVendorNames();

	/**
	 * Checks and verifies that the vendor id exists,
	 *
	 * @param [string] $string = hopefully a vendor id
	 *
	 * @return [boolean];
	 */
	public function CB_checkVendorID($string) {
		$b = false;
		$this->db->from('vendor_info');
		$this->db->where('vendor_id', $string);
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			$b = true;
		}
		return $b; //bool
	} //end CB_checkVendorId();

} //end Vendor_model();
?>