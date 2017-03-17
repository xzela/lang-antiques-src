<?php
/**
 * Lookup Lists...
 * SOme of these are hardcoded lists of values
 * Some are not. Depends on how dynamic each list is
 *
 * @author user
 *
 */
class Lookup_list_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Returns all of the diamond colors
	 *
	 * @return array
	 */
	public function getDiamondColors() {
		$this->db->from('diamond_color');
		$this->db->order_by('seq', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['color_id']] = $row;
			}
		}
		return $data;
	}
	/**
	 * Returns all of the diamond clarities
	 *
	 * @return [array]
	 */
	public function getDiamondClarities() {
		$this->db->from('diamond_clarity');
		$this->db->order_by('seq', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['clarity_id']] = $row;
			}
		}
		return $data; //array
	} //end getDiamondClarities

	/**
	 * Returns all of the Gemstone Names
	 *
	 * @return [array]
	 */
	public function getGemstoneNames() {
		//@TODO should update outside methods to referrence
		$this->db->from('stone_type');
		$this->db->order_by('stone_name');
		$query = $this->db->get();

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//Set the stone_id field as the index for array
				//example: array[11] = array(stone_id=>11, stone_name=>coral, etc...)
				$data[$row['stone_id']] = $row;
			}
		}

		return $data; //array
	} //end getGemstoneNames();

	/**
	 * get the scale of a gemstone,
	 *
	 * @return [array]
	 */
	public function getGemstoneScale() {
		$this->db->from('stone_scale');
		$this->db->order_by('scale_id');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['scale_id']] = $row;
			}
		}
		return $data;
	} //end getGemstoneScale()

	/**
	 * Returns all of the gemstone cuts
	 * @todo rename the database table to gemstone_cuts
	 *
	 * @return array
	 */
	public function getGemstoneCuts() {
		$this->db->from('diamond_cut');
		$this->db->order_by('cut_name', 'ASC');
		$query = $this->db->get();
		$data = array();
		$data[0] = array('cut_name' => '');
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//Set the cut_id field as the index for array
				//example: array[53] = array(cut_id=>53, cut_name=>bead, etc...)
				$data[$row['cut_id']] = $row;
			}
		}

		return $data; //array
	} //end getGemstoneCuts();

	/**
	 * Returns the Invoice Item Status
	 *
	 */
	public function getInvoiceItemStatus() {
		$status = array();
			$status[0] = array('id' => 0, 'name' => 'Normal Sale');
			$status[1] = array('id' => 1, 'name' => 'Returned');
			$status[2] = array('id' => 2, 'name' => 'Pending Return');
			$status[3] = array('id' => 3, 'name' => 'Closed Memo');
		return $status; //array
	} //end getInvoiceItemStatus();

	/**
	 * Returns the invoice Status
	 */
	public function getInvoiceStatus() {
		$status = array();
			$status[0] = array('id' => 0, 'name' => 'Completed'); //done, can not be edited, but not retured
			$status[1] = array('id' => 1, 'name' => 'Unfinished'); //still open, can be edited
			$status[2] = array('id' => 2, 'name' => 'Returned'); //done, can not be edited, retured
			$status[3] = array('id' => 3, 'name' => 'Closed'); //memo only, done, closed out. memo close date updated
			$status[4] = array('id' => 4, 'name' => 'Converted'); //memo only, converted
			$status[5] = array('id' => 5, 'name' => 'Cancelled'); //layaways only, cancelled
		return $status;
	} //end getInvoiceStatus();

	/**
	 * Returns the invoice items
	 */
	public function getInvoiceTypes() {
		$type = array();
			$type[0] = array('id' => 0, 'name' => 'Invoice');
			$type[1] = array('id' => 1, 'name' => 'Layaway');
			$type[3] = array('id' => 3, 'name' => 'Memo');
			$type[4] = array('id' => 4, 'name' => 'Converted Memo');

		return $type; //array
	} //end getInvoiceTypes();

	/**
	 * Returns different item status types
	 *
	 * @return [array]
	 */
	public function getItemStatus() {
		$status = array();
			$status[0] = array('id' => 0,
							'name' => 'Sold',
							'checked' => false,
							'field_name' => 'sold'
						);
			$status[1] = array('id' => 1,
							'name' => 'Available',
							'checked' => false,
							'field_name' => 'available'
						);
			$status[2] = array('id' => 2,
							'name' => 'Out on Job',
							'checked' => false,
							'field_name' => 'out_on_job'
						);
			$status[3] = array('id' => 3,
							'name' => 'Pending Sale',
							'checked' => false,
							'field_name' => 'pending_sale'
						);
			$status[4] = array('id' => 4,
							'name' => 'Out on Memo',
							'checked' => false,
							'field_name' => 'out_on_memo'
						);
			$status[5] = array('id' => 5,
							'name' => 'Burgled',
							'checked' => false,
							'field_name' => 'burgled'
						);
			$status[6] = array('id' => 6,
							'name' => 'Assembled',
							'checked' => false,
							'field_name' => 'assembled'
						);
			$status[7] = array('id' => 7,
							'name' => 'Returned To Consignee',
							'checked' => false,
							'field_name' => 'return_to_consignee'
						);
			$status[8] = array(
							'id' => 8,
							'name' => 'Pending Repair Queue',
							'checked' => false,
							'field_name' => 'pending_repair'
						);
			$status[91] = array('id' => 91,
							'name' => 'Francesklein Import',
							'checked' => false,
							'field_name' => 'francesklein_import'
						);
			$status[98] = array('id' => 98,
							'name' => 'Never Going Online',
							'checked' => false,
							'field_name' => 'never_going_online'
						);
			$status[99] = array('id' => 99,
							'name' => 'Unavailable',
							'checked' => false,
							'field_name' => 'unavailable'
					);
		return $status;
	} //getItemStatus

	/**
	 * Returns all of the different Job Status
	 *
	 * @return [array]
	 */
	public function getJobStatus() {
		$status = array();
			$status[0] = snappy_image('icons/cross.png') . 'Canceled';
			$status[1] = snappy_image('icons/bullet_yellow.png'). 'Inprogress';
			$status[2] = snappy_image('icons/tick.png'). 'Completed';
		return $status; //array
	} //end getJobStatus()

	/**
	 * Queries database to get all of the active Major Class
	 *
	 * @TODO remove lookup_list_model.php:getMajorClasses(); method, use: major_class_model.php:getMajorClasses();
	 *
	 * @return [array]
	 */
	public function getMajorClasses($sort = null) {
		$this->db->from('major_class');
		$this->db->where('active', 1);
		if($sort != null) {
			$this->db->order_by($sort);
		}
		else {
			$this->db->order_by('web_seq');
		}
		$query = $this->db->get();

		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$row['major_class_id'] = $row['mjr_class_id'];
				$row['major_class_name'] = $row['mjr_class_name'];
				$data[$row['major_class_id']] = $row;
			}
		}
		return $data; //array

	} //end getMajorClasses()

	/**
	 * Queries database to get all active Minor Classes
	 *
	 * @TODO remove lookup_list_model.php:getMinorClasses(); method, use: minor_class_model.php:getMinorClasses();
	 *
	 * @return [array]
	 */
	public function getMinorClasses() {
		$this->db->from('minor_class');
		$this->db->where('active', 1);
		$this->db->order_by('min_class_id');

		$query = $this->db->get();

		$minor_class = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$minor_class[] = $row;
			}
		}
		return $minor_class; //array
	} //end getMinorClasses()

	/**
	 * Returns the months
	 *
	 * @return [array]
	 */
	public function getMonths() {
		$month = array();

		$month[1] = array('id' => 1, 'name' => 'January');
		$month[2] = array('id' => 2, 'name' => 'February');
		$month[3] = array('id' => 3, 'name' => 'March');
		$month[4] = array('id' => 4, 'name' => 'April');
		$month[5] = array('id' => 5, 'name' => 'May');
		$month[6] = array('id' => 6, 'name' => 'June');
		$month[7] = array('id' => 7, 'name' => 'July');
		$month[8] = array('id' => 8, 'name' => 'August');
		$month[9] = array('id' => 9, 'name' => 'September');
		$month[10] = array('id' => 10, 'name' => 'October');
		$month[11] = array('id' => 11, 'name' => 'November');
		$month[12] = array('id' => 12, 'name' => 'December');

		return $month; //array
	} //end getMonths()

	/**
	 * Returns the Payment Methods a customer can make
	 *
	 * @return [array]
	 */
	public function getPaymentMethods() {
		//@TODO: Insert PaymentMethods into the datebase, create table
		$payment_methods = array();
			$payment_methods[0] = array('id' => 0, 'name' => 'AMEX');
			$payment_methods[1] = array('id' => 1, 'name' => 'Discover');
			$payment_methods[2] = array('id' => 2, 'name' => 'Mastercard');
			$payment_methods[3] = array('id' => 3, 'name' => 'Visa');
			$payment_methods[4] = array('id' => 4, 'name' => 'Store Credit');
			$payment_methods[5] = array('id' => 5, 'name' => 'Check');
			$payment_methods[6] = array('id' => 6, 'name' => 'Wire Transfer');
			$payment_methods[7] = array('id' => 7, 'name' => 'Trade');
			$payment_methods[9] = array('id' => 9, 'name' => 'Cash');
		return $payment_methods; //array
	} //end getPaymentMethods
	/**
	 * Returns the types of Credit a customer can get
	 *
	 * @return [array]
	 */
	public function getReturnCreditType() {
		$type = array();
			$type[1] = array('id' => 1, 'name' => 'Store Credit');
			$type[2] = array('id' => 2, 'name' => 'Cash');
		return $type; //array
	} //end getReturnCreditType();

	/**
	 * Returns the type of templates a stone might use
	 *
	 * @return [array] = array of template types
	 */
	public function getStoneTemplateTypes() {
		//@TODO: Insert Stone Template Types into the datebase, create table
		$temp = array();
			$temp[1] = array('id' => 1, 'name' => 'Gemstone');
			$temp[2] = array('id' => 2, 'name' => 'Pearl');
			$temp[3] = array('id' => 3, 'name' => 'Diamond');
			$temp[4] = array('id' => 4, 'name' => 'Jadeite');
			$temp[5] = array('id' => 5, 'name' => 'Opal');
		return $temp;

	} //end getStoneTemplateTypes();

	/**
	 * Returns the states of a sub element,
	 * Not sure if I use this
	 *
	 * @return [array]
	 */
	public function getSubElementStates() {
		$data = array();
			$data[0] = 'Disabled';
			$data[1] = 'Enabled';
		return $data; //array
	} //end getSubElementStates();

	/**
	 * Gets the Special item types
	 * See database table for more info
	 *
	 * @return [array] = array of types
	 */
	public function getSpecialItemType() {
		$this->db->from('invoice_special_item_type');
		$this->db->order_by('type_id');

		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['type_id']] = $row;
			}
		}
		return $data; //array
	} //end getSpecialItemType();

	/**
	 * Returns a list of Years
	 *
	 * @return [array] = array of years
	 */
	public function getYears() {
		$year = array();
		$current_year = strftime("%Y");
		for($i = 2000; $i <= $current_year+1 && $y = strftime("%Y", mktime(0,0,0,0,0,$i)); ++$i) {
			$year[$i] = array('id' => $i, 'name' => $y+1);
		}
		return $year; //array
	} //end getYears();

	/**
	 * Returns an unknown Customer
	 *
	 * @return [array] = array of unknown customer data
	 */
	public function unknownCustomer() {
		$data = array();
			$data['name'] = 'Unknown';
			$data['link'] = '';
			$data['first_name'] = 'Unknown';
			$data['customer_type'] = 0;
			$data['last_name'] = 'Unknown';
			$data['spouse_first'] = 'Unknown';
			$data['spouse_last'] = 'Unknown';
			$data['phone'] = 'Unknown';
			$data['home_phone'] = 'Unknown';
			$data['work_phone'] = 'Unknown';
			$data['address'] = 'Unknown';
			$data['address2'] = 'Unknown';
			$data['city'] = 'Unknown';
			$data['state'] = 'Unknown';
			$data['zip'] = 'Unknown';
			$data['country'] = 'Unknown';
			$data['has_ship'] = 0;
			$data['ship_address'] = 'Unknown';
			$data['ship_address2'] = 'Unknown';
			$data['ship_city'] = 'Unknown';
			$data['ship_state'] = 'Unknown';
			$data['ship_zip'] = 'Unknown';
			$data['ship_country'] = 'Unknown';
			$data['email'] = 'Unknown';
			$data['notes'] = 'Unknown';
			$data['ship_contact'] = 'Unknown';
			$data['ship_phone'] = 'Unknown';
			$data['ship_other_phone'] = 'Unknown';
			$data['mailing_list'] = 0;
			$data['password'] = 'Unknown';

		return $data; //array
	} //end unknownCustomer

	/**
	 * Returns static data for an Unknown Vendor
	 *
	 * @return [array] = array of unknown vendor data;
	 */
	public function unknownVendor() {
		$data = array();
			$data['name'] = 'Unknown';
			$data['link'] = '';
			$data['tax_id'] = 'Unknown';
			$data['address'] = 'Unknown';
			$data['address2'] = 'Unknown';
			$data['city'] = 'Unknown';
			$data['state'] = 'Unknown';
			$data['zip'] = 'Unknown';
			$data['country'] = 'Unknown';
			$data['has_ship'] = 0;
			$data['ship_contact'] = 'Unknown';
			$data['ship_phone'] = 'Unknown';
			$data['ship_address'] = 'Unknown';
			$data['ship_address2'] = 'Unknown';
			$data['ship_city'] = 'Unknown';
			$data['ship_state'] = 'Unknown';
			$data['ship_zip'] = 'Unknown';
			$data['ship_country'] = 'Unknown';
			$data['first_name'] = 'Unknown';
			$data['last_name'] = 'Unknown';
			$data['phone'] = 'Unknown';
			$data['alt_phone'] = 'Unknown';
			$data['fax'] = 'Unknown';
			$data['email'] = 'Unknown';
			$data['notes'] = 'Unknown';
			$data['active'] = 0;
			$data['legacy_vendor_code'] = 'Unknown';
			$data['legacy_vendor_id'] = 0;
			$data['mailing_list'] = 0;

		return $data; //array
	} //end unknownVendor()

} //end Lookup_list_model();
?>