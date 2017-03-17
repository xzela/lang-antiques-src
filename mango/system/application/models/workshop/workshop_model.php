<?php
/**
 * Workshop Model, used to modify workshop related data
 *
 * @author user
 *
 */
Class Workshop_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Cancels an Inventory Job
	 *
	 * @param [int] $job_id = job id
	 * @param [string] $reason = reason
	 *
	 * @return null;
	 */
	public function cancelInventoryJob($job_id, $reason) {
		$data = array('status' => 0, 'end_date' => date('Y/m/d'), 'notes' => $reason);
		$this->db->where('job_id', $job_id);
		$this->db->update('inventory_jobs', $data);

		return null; //null
	} //end cancelInventoryJob();

	/**
	 * Deletes a Workshop from the database
	 *
	 * @param [int] $workshop_id
	 *
	 * @return null;
	 */
	public function deleteWorkshop($workshop_id) {
		$this->db->where('workshop_id', $workshop_id);
		$this->db->limit(1); //always limit one when deleting
		$this->db->delete('workshop_info');

		return null;
	} //end deleteWorkshop();
	/**
	 * Updates an Inventory Job to 2 (completed)
	 *
	 * @param [int] $job_id = job id
	 *
	 * @return null;
	 */
	public function completeInventoryJob($job_id) {
		$data = array('status' => 2, 'end_date' => date('Y/m/d'));
		$this->db->where('job_id', $job_id);
		$this->db->update('inventory_jobs', $data);

		return null; //null
	} //end completeInventoryJob();

	/**
	 * Updates the Shipping Address with the Billing Address
	 * for a specific vendor
	 *
	 * @param [int] $id = workshop_id
	 * @param [array] $fields = array of fields
	 *
	 * @return null;
	 */
	public function copyBillingAddress($workshop_id, $fields) {
		$this->db->where('workshop_id', $workshop_id);
		$this->db->update('workshop_info', $fields);

		return null;
	} //end copyBillingAddress();

	/**
	 * Attempts to find any actcost field that is greater than 0
	 *
	 * @return [array] = multi-dim array of inventory job data
	 */
	public function findNotNullActCost() {
		$this->db->from('inventory_jobs');
		$this->db->where('act_price >', '0');
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end findNotNullActCost();

	/**
	 * Gets all of the Active Workshops
	 *
	 * @return [array] = multi-dim array of workshop data
	 */
	public function getActiveWorkshops() {
		$this->db->from('workshop_info');
		$this->db->order_by('name', 'ASC');
		$this->db->where('active', 1); //1=active, 0=inactive

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getActiveWorkshops()

	/**
	 * Gets the billing address of a specific vendor
	 *
	 * @param [int] $id = workshop_id
	 *
	 * @return [array] array of address data
	 */
	public function getBillingAddress($workshop_id) {
		$this->db->select('address, address2, city, state, zip, country');
		$this->db->from('workshop_info');
		$this->db->where('workshop_id', $workshop_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() == 1) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;
	} //end getBillingAddress();

	/**
	 * Gets whichever jobs the item is applied to and set to active.
	 *
	 * @param unknown_type $item_id
	 *
	 * @return [array] = multi-dim array of job data
	 */
	public function getCurrentActiveJob($item_id) {
		$this->db->from('inventory_jobs');
		$this->db->join('workshop_info', 'inventory_jobs.workshop_id = workshop_info.workshop_id');
		$this->db->where('inventory_jobs.item_id', $item_id);
		$this->db->where('inventory_jobs.status',1); //1=active

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getCurrentActiveJob();

	/**
	 * Gets all of the jobs applied to a specific item
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = multi-dim array of job data
	 */
	public function getInventoryJobs($item_id) {
		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus();

		$this->db->from('inventory_jobs');
			$this->db->join('workshop_info', 'workshop_info.workshop_id = inventory_jobs.workshop_id');
		$this->db->where('inventory_jobs.item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']];
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getInventoryJobs()

	/**
	 * Returns all of the job information by job_id
	 *
	 * @param [int] $job_id = Job id
	 * @return [array] = all job details
	 */
	public function getJobData($job_id) {
		$this->load->model('user/user_model');
		$select = array('inventory_jobs.job_id', 'inventory_jobs.workshop_id', 'inventory_jobs.user_id', 'inventory_jobs.open_date', 'inventory_jobs.rush_order', 'inventory_jobs.at_workshop', 'inventory_jobs.sent_date',
			'inventory_jobs.est_return_date', 'inventory_jobs.act_return_date', 'inventory_jobs.instructions', 'inventory_jobs.inspection_by_id',
			'inventory_jobs.est_price', 'inventory_jobs.act_price', 'inventory_jobs.job_cost', 'inventory_jobs.status', 'inventory_jobs.end_date',
			'inventory_jobs.notes', 'workshop_info.name', 'workshop_info.address', 'workshop_info.phone', 'inventory.item_number',
			'inventory.item_name', 'inventory.item_id', 'inventory.item_description');

		$this->db->select($select);
		$this->db->from('inventory_jobs');
		$this->db->join('workshop_info', 'workshop_info.workshop_id = inventory_jobs.workshop_id');
		$this->db->join('inventory', 'inventory.item_id = inventory_jobs.item_id');
		$this->db->where('inventory_jobs.job_id', $job_id);
		$status_array = array(0 => '<span class="warning">Canceled</span>', 1 => 'Inprogress', 2=> '<span class="success">Completed</span>');
		$query = $this->db->get();

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']];
				$row['requester'] = $this->ci->user_model->getUserName($row['user_id']);
				//test the inspector field
				if ($row['inspection_by_id'] != '') {
					$row['inspector'] = $this->ci->user_model->getUserName($row['inspection_by_id']);;
				}
				else {
					$row['inspector'] = '';
				}

				$data = $row;
			}
		}
		return $data; //array
	} //end getJobData();



	/**
	 * returns a image based on the status of the job
	 *
	 * @param [int] $status = status of the job (0,1,2)
	 * @return [string] = HTML image
	 */
	public function getStatusImage($status) {
		$this->load->helper('snappy_source');
		switch($status) {
			case 0:
				return snappy_image('icons/cross.png', '');
				break;
			case 1:
				return snappy_image('icons/bullet_yellow.png', '');
				break;
			case 2:
				return snappy_image('icons/tick.png', ''); //string
				break;
		}
	} //end getStatusImage()

	/**
	 * Retruns all data known for a specific workshop
	 *
	 * @param [int] $id = workshop_id
	 * @return [array] = workshop data
	 */
	public function getWorkshopData($workshop_id) {
		$this->db->from('workshop_info');
		$this->db->where('workshop_id', $workshop_id);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getWorkShopData()

	/**
	 * Inserts a Inventory Job
	 *
	 * @param [array] $fields = array of columns and values
	 *
	 * @return [int] = inventory job id
	 */
	public function insertInventoryJob($fields) {
		$this->db->insert('inventory_jobs', $fields);

		return $this->db->insert_id(); //int

	} //end insertInventoryJob();

	/**
	 * Inserts a new Workshop into the database;
	 *
	 * @param [array] $fields
	 *
	 * @return [int] workshop id
	 */
	public function insertWorkshop($fields) {
		$this->db->insert('workshop_info', $fields);

		return $this->db->insert_id(); //int
	} //end insertWorkshop();

	public function isItemJobAtWorkshop($item_id) {
		$bool = false;
		$jobs = $this->getInventoryJobs($item_id);
		foreach($jobs as $job) {
			if($job['status'] == 1 && $job['at_workshop'] == 'yes') {
				$bool = true;
				break;
			}
		}
		return $bool;
	}

	/**
	 * Update a specific Job
	 *
	 * @param [int] $job_id = job id
	 * @param [array] $fields = array of keys and values
	 *
	 * @return null
	 */
	public function updateInventoryJob($job_id, $fields) {
		$this->db->where('job_id', $job_id);
		$this->db->limit(1); //why limit one?
		$this->db->update('inventory_jobs', $fields);

		return null;
	} //end updateInventoryJob();

	/**
	 * Updates a specific inventory Job Field,
	 *
	 * @param [int] $job_id = job id
	 * @param [string] $field = column
	 * @param [string] $value = value to update
	 *
	 * @return [string] = value that has changed.
	 */
	public function updateInventoryJobField($job_id, $field, $value) {
		$data = array($field => $value);

		$this->db->where('job_id', $job_id);
		$this->db->update('inventory_jobs', $data);

		return $value; //string
	} //end updateInventoryJobField();

	/**
	 * This updates the data for a specific field (item_name, item_description, etc...)	 *
	 * @param [int] $id
	 * @param [string] $field = database name of field to update {first_name, home_phone, etc...}
	 * @param [string] $value = value of the field
	 *
	 * @return [string] = returns the value (used for display purposes only)
	 */
	public function updateWorkshopField($id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('workshop_id', $id);
		$this->db->update('workshop_info', $data);

		return $value; //string
	} //end updateWorkshopField();

	/**
	 * Updates a specific workshop based on workshop_id
	 *
	 * @param [int] $workshop_id = workshop id
	 * @param [array] $fields = array of fields and values
	 */
	public function updateWorkshopShipping($workshop_id, $fields) {
		$this->db->where('workshop_id', $workshop_id);
		$this->db->limit(1); //why am i limiting one?
		$this->db->update('workshop_info', $fields);
	} //end updateWorkshopShipping()

	/**
	 * AJAX call: Gets the workshop info based on strings
	 * @param [string] $string = search string
	 *
	 * @return [array] = multi-dim array of workshop data;
	 */
	public function searchWorkshopNames($string) {
		//this sql attempts to select specific workshopes
		//based on their first_name, last_name, or company name
		$sql = 'SELECT * FROM workshop_info WHERE ';
		//explodes string by ' '(space)
		$array = explode(' ', $string);
		//if the array is larger than 1 (meaning more than one name was supplied)
		if(count($array) > 1) {
			//loop thru each string
			foreach($array as $str) {
				if($str == end($array)) {
					$sql .= " (first_name LIKE '$str%' OR last_name LIKE '$str%' OR name LIKE '$str%')";
				}
				else {
					$sql .= " (first_name LIKE '$str%' OR last_name LIKE '$str%' OR name LIKE '$str%') AND ";
				}
			}
		}
		else {
			$sql .= " (first_name LIKE '$string%' OR last_name LIKE '$string%' OR name LIKE '$string%') ";
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
	} //end searchWorkshopNames()

} //end Workshop_model()
?>