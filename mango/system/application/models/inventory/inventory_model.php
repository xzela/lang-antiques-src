<?php
class Inventory_model extends Model {

	var $ci;
	var $selected_database;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
		$this->ci->load->library('session');
		//Auditing purposes: Sets the global user_id in SQL to the users_id (is there a better way?)
		$this->ci->db->query("SET @user_id = " . $this->ci->session->userdata('user_id'));
	}

	/**
	 * Applys a seller to an item
	 *
	 * @param [int] $item_id = item id
	 * @param [int] $type = seller type [1=venodr, 2=customer]?
	 * @param [int] $seller_id = id of seller (vendor, customer)
	 *
	 * @return [null]
	 */
	public function applySeller($item_id, $type, $seller_id) {
		$data = array('seller_type' => $type, 'seller_id' => $seller_id);
		$this->db->where('item_id', $item_id);
		$this->db->update('inventory', $data);

		return null;
	} //end applySeller();

	/**
	 * Creates a new item number
	 * These are human numbers which are created by which min and mjr classes
	 *
	 * @param [int] $major_class_id = major class id
	 * @param [int] $minor_class_id = minor class id
	 *
	 * @return [string] = returns the human item name string
	 */
	public function createItemNumber($major_class_id, $minor_class_id) {

		$temp_data = array();
		$i = 0;

		//Get all of the items where they meet the criteria
		$this->db->select('item_number');
		$this->db->from('inventory');
		$this->db->where('mjr_class_id', $major_class_id);
		if($this->config->item('project') == 'lang') { //only show for Lang Antiques/Mango.
			$this->db->where('min_class_id !=', 91); //i think this is breaking the rules
		}
		$query = $this->db->get();
		foreach($query->result_array() as $row) {
			//Explode the item number into an array
			$num = explode("-", $row['item_number']);
			//Get the third [0,1,{2}] element of the each item number and insert it into the temp_data
			$temp_data[$i] = $num[2];
			$i++;
		}
		//Reoder the array by largest number last
		sort($temp_data);
		//Locate the last element of the array and add one
		$last_num = end($temp_data)+1 ;

		//construct and return the new inventory number
		return $major_class_id . "-" . $minor_class_id . "-" . $last_num;
	} //end createItemNumber();

	public function deleteStaffComment($fields) {
		$this->db->where('comment_id', $fields['comment_id']);
		$this->db->where('item_id', $fields['item_id']);
		$this->db->limit(1);

		$this->db->delete('inventory_staff_comments');
	}

	/**
	 * Deletes a specific comment
	 *
	 * @param [int] $id = comment_id
	 * @return null
	 */
	public function deleteUserComment($id) {
		$this->db->where('comment_id', $id);
		$this->db->LIMIT(1);
		$this->db->delete('inventory_user_comments');

		return null;
	} //end deleteUserComment();


	/**
	 * Returns the Invoice Id that the item
	 * is currently sold to. There may be mistakes
	 * with this
	 *
	 * @param [int] $id = item_id
	 * @return [int] invoice_id
	 */
	public function getCurrentMemo($item_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_items.item_id', $item_id);
		$this->db->where('invoice_items.item_status', 0); //sold
		$query = $this->db->get();
		$invoice_id = null;
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$invoice_id = $row['invoice_id'];
			}
		}
		return $invoice_id;
	} //end getCurrentMemo();

	/**
	 * Returns the Invoice Id that the item
	 * is currently sold to. There may be mistakes
	 * with this
	 *
	 * @param [int] $id = item_id
	 * @return [int] invoice_id
	 */
	public function getCurrentInvoice($item_id, $pending = false) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_items.item_id', $item_id);
		$this->db->where('invoice_items.item_status', 0);
		$query = $this->db->get();
		$invoice_id = null;
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$invoice_id = $row['invoice_id'];
			}
		}
		return $invoice_id;
	} //end getCurrentInvoice();

	/**
	 * Returns all Edit history on an item
	 *
	 * @param [int] $id
	 * @return [array] = multi-dim array of hsitory data
	 */
	public function getEditHistory($item_id) {
		$this->db->from('inventory_audit');
		$this->db->where('item_id', $item_id);
		$this->db->order_by('date_changed', 'DESC');

		$query = $this->db->get();
		$history = array();
		if($query->num_rows() > 0) {
			$history['num_rows'] = $query->num_rows();
			foreach ($query->result_array() as $row) {
				$history['history'][] = $row;
			}
		}
		return $history; //array
	} //end getEditHistory();

	/**
	 * Returns the user who entered the item
	 * @TODO this method is lame. I should actually move this
	 * to the user_model or something
	 *
	 * @param [int] $id = user id
	 * @return [string] = first last
	 */
	public function getEnteredBy($id) {
		if($id == 0 || $id == '') {
			$data = array(
				'user_id' => 0,
				'login_name' => 'Unknown',
				'first_name' => 'Unknown',
				'last_name' => '',
				'email' => 'null@langantiques.com'
			);
		}
		else {
			$this->db->select('first_name, last_name, email');
			$this->db->from('users');
			$this->db->where('user_id', $id);
			$query = $this->db->get();
			$data = array();
			if($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$data = $row;
				}
			}
		}
		return $data;
	} //end getEnteredBy();

	/**
	 * Returns the id of an item by it's number
	 * Returns either the item_id or a false;
	 *
	 * @param [string] $string
	 *
	 * @return [bool]|[int]
	 */
	public function getIdByNumber($string) {
		$this->db->select('item_id');
		$this->db->from('inventory');
		$this->db->where('item_number', $string);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			return $row->item_id;
		}
		return false;
	} //end getIdByNumber();

	/**
	 * Proper function name, alias
	 *
	 * @param [int] $id = item id to lookup
	 * @param [bool] $extra = Include extra data such as images, names, etc.
	 * @return [array] = item data
	 */
	public function getInventoryData($id, $extra = true) {
		return $this->getItemData($id, $extra); //array
	} //end getInventoryData();

	/**
	 * Attepmts to find an item id based on a string
	 * which is hopefully an item number
	 *
	 * @param [string] $number = hopefully an item number;
	 *
	 * @return [int] = item id
	 */
	public function getInventoryId($number) {
		$this->db->select('item_id'); //only select item id
		$this->db->from('inventory');
		$this->db->where('item_number', $number);
		$this->db->limit(1); //always limit one
		$id = null;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			$id = $row->item_id;
		}

		return $id; //int;
	} //end getInventoryId();

	/**
	 * Returns a given item number for a specific
	 * inventory item based on it's id.
	 *
	 * @param [int] $item_id = item id
	 * @return [string] = item number
	 */
	public function getInventoryNumber($item_id) {
		$this->db->select('item_number');
		$this->db->from('inventory');
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$query =  $this->db->get();
		$number = null;
		if($query->num_rows() > 0) {
			$row = $query->row();
			$number = $row->item_number;
		}
		return $number; //string
	} //end getInventoryNumber();

	/**
	 * Returns all of the invoice history for a specific item
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = multi-dim array of invoice history data
	 */
	public function getInvoiceHistory($item_id) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('utils/lookup_list_model');

		$this->db->from('invoice_items');
		$this->db->join('invoice', 'invoice.invoice_id = invoice_items.invoice_id');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();
		$invoice_type = $this->ci->lookup_list_model->getInvoiceTypes();
		$item_status = $this->ci->lookup_list_model->getInvoiceItemStatus();
		$data = array();
		$data['num_rows'] = $query->num_rows();
		if($data['num_rows'] > 0) {
			foreach($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //1=customer, 3=internet_customer
					$buyer = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					$row['buyer_name'] = anchor('customer/edit/' . $row['buyer_id'], $buyer['first_name'] . ' ' . $buyer['last_name']);
				}
				else if($row['buyer_type'] == 2) {//2=vendor
					$buyer = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					$row['buyer_name'] = anchor('vendor/edit/' . $row['buyer_id'], $buyer['name']);
				}
				$row['invoice_text'] = $invoice_type[$row['invoice_type']]['name'];
				$row['item_status_text'] = $item_status[$row['item_status']]['name'];
				$data['invoice'][] = $row;
			}
		}

		return $data; //array
	} //end getInvoiceHistory();

	/**
	 * Returns all staff comments on a specific item
	 *
	 * @param [int] $id item_id
	 * @return array
	 */
	public function getItemStaffComments($item_id) {
		$this->ci->load->model('user/user_model');

		$this->db->from('inventory_staff_comments');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['staff'] = $this->ci->user_model->getUserData($row['staff_id']);
				$data[] = $row;
			}

		}

		return $data;
	} //end getItemStaffComments()
	/**
	 * Returns all user comments on a specific item
	 *
	 * @param [int] $id item_id
	 * @return array
	 */
	public function getItemUserComments($item_id) {
		$this->db->from('inventory_user_comments');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}

		}

		return $data;
	} //end getItemUserComments()

	/**
	 * Returns all of the data for a given inventory item
	 *
	 * @param [int] $id = item id to lookup
	 * @param [bool] $extra = Include extra data such as images, class names, etc.
	 *
	 * @return [array] = multi-dim array of item data
	 */
	public function getItemData($item_id, $extra = true) {
		$this->ci->load->model('admin/minor_class_model');
		$this->ci->load->model('admin/major_class_model');
		$this->ci->load->model('image/image_model');
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->model('inventory/material_model');
		$this->ci->load->model('inventory/modifier_model');
		$this->ci->load->model('inventory/assemble_model');
		$this->db->from('inventory');
		$this->db->where('item_id', $item_id);
		$this->db->limit(1);

		$query = $this->db->get();

		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				if($extra) {
					$row['icon_status'] = $this->itemStatus($row['item_status'], true, $row['item_id']);
					if($row['item_status'] == 2) { //at workshop
						$this->ci->load->model('workshop/workshop_model');
						if($this->ci->workshop_model->isItemJobAtWorkshop($row['item_id'])) {
							$this->load->helper('snappy_source');
							$row['icon_status'] = snappy_image('icons/star.png') . $row['icon_status'];
						}

					}
					$row['icon_web_status'] = $this->itemWebStatus($row['web_status'], true);
					$row['push_data'] = $this->getPushData($row['item_id']);
					$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
					//$row['image_array'] = $this->ci->image_model->getImageLocation($this->ci->image_model->getLangImageId($row['item_id']), $this->ci->image_model->getBaseImageId($row['item_id']));
					$row['mjr_class_name'] = $this->ci->major_class_model->getMajorClassName($row['mjr_class_id']);
					$row['min_class_name'] = $this->ci->minor_class_model->getMinorClassName($row['min_class_id']);
					$row['material_count'] = $this->ci->material_model->countMaterial($row['item_id']);
					$row['modifier_count'] = $this->ci->modifier_model->countModifiers($row['item_id']);
					$row['item_job_cost'] = $this->getItemJobCost($row['item_id']);
					//$row['base_array'] = $this->ci->image_model->getAllBaseImages($row['item_id']);
					//$row['lang_array'] = $this->ci->image_model->getAllLangImages($row['item_id']);

					if($row['is_assembled'] == 1) {
						if($row['assemble_type'] == 1) { //if parent, find children
							$assemble = $this->ci->assemble_model->getAssembleIdFromParent($row['item_id']);
							if(sizeof($assemble) > 0) {
								$row['assembly_data']['childen'] = $this->ci->assemble_model->getChildrenAssemblyData($assemble['assemble_id']);
							}
						}
						else { //if child, find parent
							$assemble = $this->ci->assemble_model->getAssembleIdFromChild($row['item_id']);
							if(sizeof($assemble) > 0) {
								$row['assembly_data']['parent'] = $this->ci->assemble_model->getParentAssemblyData($assemble['assemble_id']);
								$row['assembly_data']['parent']['item_number'] = $this->ci->inventory_model->getInventoryNumber($row['assembly_data']['parent']['parent_item_id']);
							}
						}
					}
					if($row['seller_id'] != '') {
						$this->load->model('sales/invoice_model');
						//seller_type: 1=vendor, 2=customer
						$row['seller_data'] = $this->ci->invoice_model->getSellerData($row['seller_id'], $row['seller_type']);
					}
				}
				$data = $row;
			}
		}
		return $data; //array
	} //end getItemData();

	/**
	 * Returns the total job cost of an item
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [float] = job cost
	 */
	public function getItemJobCost($item_id) {
		$cost = 0;
		$this->db->select('job_cost');
		$this->db->from('inventory_jobs');
		$this->db->where('item_id', $item_id);
		$this->db->where('status', 2); //completed jobs
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$cost += $row['job_cost'];
			}
		}
		return $cost; //float
	} //end getItemJobCost();

	/**
	 * Attempts to the get the next suffix within a
	 * Major class grouping.
	 *
	 * Ugh! if you look at the database, you'll see hella
	 * gaps between the suffixes
	 *
	 * this may be unrecoverable.
	 *
	 * @param [int] $major_id = major class id
	 *
	 * @return [int] = the next suffix in line;
	 */
	public function getNextSuffixByMajorClass($major_id) {
		$this->db->select('suffix'); //select only suffix column
		$this->db->from('inventory');
		$this->db->where('mjr_class_id', $major_id);
		$this->db->order_by('suffix', 'desc'); //order desc
		$this->db->limit(1); //limit on to get last.

		$query = $this->db->get();
		$suffix = 0; //assume no suffixie exist
		if($query->num_rows() > 0) {
			$row = $query->row();
			$suffix = $row->suffix; //get suffix
		}
		//add one to current suffix,
		$suffix = $suffix + 1;

		return $suffix; //int
	} //end getNextSuffixByMajorClass();

	/**
	 * Returns all of the items who do not have a
	 * suffix, probably created using:
	 * www.langantiques.com/admin
	 *
	 * @return [array] = multi-dim array of items
	 */
	public function getNoSuffixItems() {
		$data = array();
		$this->db->from('inventory');
		$this->db->where('suffix is null', null, true);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getNoSuffixItems();

	/**
	 * Returns the PushData
	 *
	 * The push data things are complacted and
	 * doesnt make any sense because it deals with two
	 * databases...
	 *
	 *
	 * @param [int] $item_id = item id
	 */
	public function getPushData($item_id) {
		$this->ci->load->model('utils/push_model');
		$this->ci->load->model('inventory/gemstone_model');
		//test to see if the item was pushed
		//$ignore_list = array('item_id', 'lang_id', 'fran_id', 'item_status');
		$ignore_list = array(); //$this->ci->push_model->getIgnoredFields();
		$b = false;
		$this->db->from('inventory');
		$this->db->where('item_id', $item_id);
		$this->db->limit(1);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$b = (bool)$row['push_state'];
				//because i'm not smart enough to store a bool in the database
				if($b) {//push data has been found
					//get the lang item where the lang_id=fran_id
					$inventory_data = $this->ci->push_model->getLangInventoryData($row['item_id']);
					$data['inventory_sync_data'] = $this->ci->push_model->compareDataSets($row, $inventory_data, $ignore_list);
				}
			}
		}
		return $data; //array;
	} //end getPushData();



	/**
	 * gets the return history of an item
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = multi-dim array of history data
	 */
	public function getReturnHistory($item_id) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');
		$this->ci->load->helper('url');

		$this->db->from('return_items');
		$this->db->join('returns', 'returns.return_id = return_items.return_id');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();

		$data = array();
		$data['num_rows'] = $query->num_rows();
		if($data['num_rows'] > 0) {
			foreach($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //1=customer, 3=internet_customer
					$buyer = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					$row['buyer_name'] = anchor('customer/customer/edit/' . $row['buyer_id'], $buyer['first_name'] . ' ' . $buyer['last_name']);
				}
				else if($row['buyer_type'] == 2) {//2=vendor
					$buyer = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					$row['buyer_name'] = anchor('vendor/vendor/edit/' . $row['buyer_id'], $buyer['name']);
				}
				//Get the Invoice Type Text
				switch ($row['return_type']) {
					case 1:
						$row['return_text'] = 'Normal Return';
						break;
					case 2:
						$row['return_text'] = 'Memo Return';
						break;
				}
				$data['return'][] = $row;
			}
		}

		return $data; //array
	} //end getReturnHistory()

	/**
	 * Inserts the new item into the database
	 *
	 * @param [array] $fields = array of fields
	 *
	 * @return [int] = item id
	 */
	public function insertItem($fields) {
		$fields['item_number'] = $this->createItemNumber($fields['mjr_class_id'], $fields['min_class_id']);
		$n = explode('-', $fields['item_number']);
		$fields['suffix'] = $n[2];
		$this->db->insert('inventory', $fields);

		return $this->db->insert_id(); //int
	} //end insertItem();

	/**
	 * Inserts a new Staff Comment
	 *
	 * @param [array] $fields = array of fields
	 *
	 * @return [int] = staff comment id
	 */
	public function insertStaffComment($fields) {
		$this->db->insert('inventory_staff_comments', $fields);
		return $this->db->insert_id();
	} //end insertStaffComments;

	/**
	 * Returns a string with <img ..> html
	 *
	 * @param [int] $status = Item status.
	 * @param [bool] $text = return status text.
	 * @param [int] $item_id = item_id: Used to find Invoices and stuff
	 *
	 * @return [string]
	 */
	public function itemStatus($status, $text = false, $item_id = null) {
		$this->load->helper('snappy_source');
		$this->load->helper('url');

		$invoice_text = ''; //used to show invoice_id
		switch($status) {
			case 0:
				$image_text = 'Sold';
				if($item_id != null) {
					$invoice_id = $this->getCurrentInvoice($item_id);
					if($invoice_id != null) {
						$invoice_text .= ' [' . anchor('sales/invoice/' . $invoice_id, 'View Invoice: #' . $invoice_id) . ']';
					}
				}
				$image = snappy_image('icons/money_dollar.png', $image_text, null, "title='$image_text'");
				break;
			case 1:
				$image_text = 'Available';
				$image = snappy_image('icons/tick.png', $image_text, null, "title='$image_text'");
				break;
			case 2:
				$image_text = 'At Workshop';
				$image = snappy_image('icons/flag_red.png', $image_text, null, "title='$image_text'");
				break;
			case 3:
				$image_text = 'Pending Sale';
				if($item_id != null) {
					$invoice_id = $this->getCurrentInvoice($item_id, true);
					if($invoice_id != null) {
						$invoice_text .= ' [' . anchor('sales/invoice/' . $invoice_id, 'View Invoice: #' . $invoice_id) . ']';
					}
				}

				$image = snappy_image('icons/flag_yellow.png', $image_text);
				break;
			case 4:
				$image_text = 'On Memo';
				$image = snappy_image('icons/flag_blue.png', $image_text, null, "title='$image_text'");
				break;
			case 5:
				$image_text = 'Stolen';
				$image = snappy_image('icons/bomb.png', $image_text, null, "title='$image_text'");
				break;
			case 6:
				$image_text = 'Assembled';
				$image = snappy_image('icons/cog.png', $image_text, null, "title='$image_text'");
				break;
			case 7:
				$image_text = 'Returned to Consignment';
				$image = snappy_image('icons/package_go.png', $image_text, null, "title='$image_text'");
				break;
			case 8:
				$image_text = 'Pending Repair';
				$image = snappy_image('icons/flag_red.png', $image_text, null, "title='$image_text'");
				break;
			case 91:
				$image_text = 'Frances Klein Import';
				$image = snappy_image('icons/table_relationship.png', $image_text, null, "title='$image_text'");
				break;
			case 98:
				$image_text = "Never Going Online";
				$image = snappy_image('icons/flag_red', $image_text, null, "title='$image_text'");
				break;
			case 99:
				$image_text = 'Unavailable';
				$image = snappy_image('icons/cross.png', $image_text, null, "title='$image_text'");
				break;
			default:
				$image = false;
		}
		if ($text && $image != false) {
			return $image . ' ' . $image_text . $invoice_text . '<br />';
		}
		else {
			return $image;
		}
	} //end itemStatus();

	/**
	 * Returns the webstats of an item
	 *
	 * @param [int] $status = status of item
	 * @param [boolean] $text = show text or not
	 *
	 * @return [mixed] = can either be a boolean or string;
	 */
	public function itemWebStatus($status, $text = false) {
		switch($status) {
			case 0:
				$status_text = 'Not Online';
				$image = snappy_image('icons/exclamation.png', $status_text, null, "title='$status_text'");
				break;
			case 1:
				$status_text = 'Online';
				$image = snappy_image('icons/tick.png', $status_text, null, "title='$status_text'");
				break;
			default:
				$image = false;
		}
		if($text && $image != false) {
			return $image . ' ' . $status_text;
		}
		else {
			return $image;
		}

	} //end itemWebStatus()

	/**
	 * Removes a seller from an item but presevrs the sell date,
	 * and purchase price
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return null
	 */
	public function removeSeller($item_id) {
		$data = array('seller_type' => null, 'seller_id' => null);
		$this->db->where('item_id', $item_id);
		$this->db->update('inventory', $data);

		return null; //null
	} //end removeSeller();

	/**
	 * Marks an Item as Pushed (from Frandango)
	 *
	 * @param [int] $item_id = item id
	 * @param [array] $fields = array of key value pairs
	 *
	 * @return null;
	 */
	public function markAsPushed($item_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory', $fields);

		return null; //null
	} //end markAsPushed();

	/**
	 * Updates an item number with new data
	 *
	 * @param [int] $item_id = item id
	 * @param [array] $fields = array of key valued pairs
	 *
	 * @return [int] = item id;
	 */
	public function updateItem($item_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory', $fields);

		return $item_id; //int
	} //end updateItem();

	/**
	 * Updates an inventory Item Number
	 *
	 * @param [int] $item_id = item id
	 * @param [int] $major_class_id = major class id
	 * @param [int] $minor_class_id = minor class id
	 * @param [string] $item_number = fully formatted item number string (xxx-xxx-xxxx)
	 *
	 * @return [string] = item number formatted string
	 */
	public function updateItemNumber($item_id, $major_class_id, $minor_class_id, $item_number) {
		$fields = array('item_number' => $item_number, 'mjr_class_id' => $major_class_id, 'min_class_id' => $minor_class_id);
		$this->db->where('item_id', $item_id);
		$this->db->update('inventory', $fields);

		return $item_number; //string
	} //end updateItemNumber();

	/**
	 * Updates the publish_date for a specific item
	 *
	 * @param $item_id = item_id
	 * @param $data = array('publish_date' => '2009/01/01')
	 *
	 * @return null
	 */
	public function updatePublishDate($item_id, $data) {
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory', $data);

		return null;
	} //end updatePublishData();

	/**
	 * This updates the data for a specific field (item_name, item_description, etc...)
	 *
	 * @param [int] $id
	 * @param [string] $field = database name of field to update {item_name, item_description, etc...}
	 * @param [string] $value = value of the field
	 *
	 * @return [string] = returns the value (used for display purposes only)
	 */
	public function AJAX_updateField($item_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory', $data);

		return $value; //string
	} //end AJAX_updateField();

	/**
	 * ****************************
	 * ***THIS MAY NOT BE USED*****
	 * ****************************
	 * ***YOU CAN PROBABLY DELETE**
	 * ****************************
	 */
	public function AJAX_searchAvailableInventoryNumbers($string) {
		$sql = 'SELECT * FROM inventory WHERE ';
		$sql .= " item_number LIKE '$string%' ";
		$sql .= ' AND item_status <> 0 AND item_status <> 4 AND item_status <> 5 AND item_status <> 7 AND item_status <> 99';
		$sql .= ' ORDER BY item_name DESC ';
		$sql .= ' LIMIT 20 ';
		$query = $this->db->query($sql);
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end AJAX_searchAvailableInventoryNumbers();

} //end Inventory_model();
?>