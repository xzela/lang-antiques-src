<?php
/**
 * This class relates to the Customer Job section of Mango
 * 
 * Most Job related tasks and methods are stored here
 * 
 * 
 * @author zeph
 *
 */
class Customer_job_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	/**
	 * Updates a customer job record, 
	 * sets the status column to 0 (canceled).
	 * 
	 * @param [int] $id = job id
	 * @param [text] $reason = reason for canceling job
	 * 
	 * @return null
	 */
	public function cancelJob($id, $reason) {
		$data = array('status' => 0, 'close_date' => date('Y/m/d'), 'notes' => $reason);
		$this->db->where('job_id', $id);
		$this->db->update('customer_jobs', $data);
		
		return null; //null
	} //end cancelJob();
	
	/**
	 * Updates a customer job record, 
	 * sets the status column to 2 (completed).
	 * 
	 * @param [int] $id = job id
	 * 
	 * @return TRUE
	 */
	public function completeJob($id) {
		$data = array('status' => 2, 'close_date' => date('Y/m/d'));
		$this->db->where('job_id', $id);
		$this->db->update('customer_jobs', $data);
		
		return true; //true? why is this set to true?
	} //end completeJob();
	
	/**
	 * Deletes a Job from the database;
	 * 
	 * @param [int] $customer_id = customer id
	 * @param [int] $job_id = job id
	 * 
	 * @return null
	 */
	public function deleteJob($customer_id, $job_id) {
		$this->db->where('customer_id', $customer_id);
		$this->db->where('job_id', $job_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('customer_jobs');
		
		return null; //null
	} //end deleteJob()
	
	/**
	 * This returns all of the jobs for a specific customer
	 * 
	 * @param [int] $id = customer id 
	 * 
	 * @return [array] multi-dim array of customer job data
	 */
	public function getCustomerJobs($id) {
		$this->ci->load->helper('snappy_source');
		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus();
		
		$this->db->from('customer_jobs');
		$this->db->join('workshop_info', 'workshop_info.workshop_id = customer_jobs.workshop_id', 'left');
		$this->db->where('customer_id', $id);
		$this->db->order_by('open_date', 'DESC');
		
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']];
				//$row['workshop_name'] = $this->ci->workshop_model->getWorkshopName($row['workshop_id']);
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getCustomerJobs();

	/**
	 * Returns all customer jobs by employee 
	 * 
	 * @param [int] $id = user id, user who created job
	 * 
	 * @return [array] = multi-din  array of customer jobs
	 */
	public function getCustomerJobsByEmployee($id) {
		$this->ci->load->helper('snappy_source');
		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus();
		
		$this->db->from('customer_jobs');
		$this->db->join('workshop_info', 'workshop_info.workshop_id = customer_jobs.workshop_id', 'left');
		$this->db->where('customer_jobs.user_id', $id);
		
		//construct a WHERE NOT IN clause
		$nums = array(0,2);
		$this->db->where_not_in('customer_jobs.status', $nums);
		
		$data = array();
		$query = $this->db->get();
		//Again, should probably move this into the controller or view 
		$url_array = array(0 => 'customer/view_job/', 1 => 'customer/edit_job/', 2 => 'customer/view_job/');
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']];
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getCustomerJobsByEmployee();
	
	/**
	 * Gets specific job data
	 * 
	 * @param [int] $id = job id
	 * 
	 * @return [array] = array of job data
	 */
	public function getJobData($id) {
		$this->db->from('customer_jobs');
		$this->db->where('job_id', $id);
		
		$data = array();
		$query = $this->db->get();
		$status_array = array(0 => '<span class="warning">Canceled</span>', 1 => 'Inprogress', 2=> '<span class="success">Completed</span>');
		
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']];
				$data = $row;
			}
		}
		return $data; //array
	} //end getJobData();
	
	/**
	 * Returns all open customer jobs. 
	 * 
	 * @return [array] = multi-dimensional  array of customer jobs
	 */
	public function getOpenCustomerJobs($sorter = null) {
		$this->ci->load->helper('snappy_source');
		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus(); //returns an array of status
		
		$this->db->select('customer_jobs.*, workshop_info.*, customer_info.*, customer_jobs.notes AS job_notes');
		$this->db->from('customer_jobs');
		$this->db->join('workshop_info', 'workshop_info.workshop_id = customer_jobs.workshop_id', 'left');
		$this->db->join('customer_info', 'customer_info.customer_id = customer_jobs.customer_id', 'left');
		
		//construct a WHERE NOT IN clause 
		$nums = array(0,2);
		$this->db->where_not_in('customer_jobs.status', $nums);
		if(isset($sorter) && sizeof($sorter) > 0) {
			$this->db->order_by($sorter['field'], $sorter['direction']);
		}
		else {
			$this->db->order_by('workshop_info.name', 'ASC');
		}
		
		$data = array();
		$query = $this->db->get();
		//should probably not have this here...
		$url_array = array(0 => 'customer/view_job/', 1 => 'customer/edit_job/', 2 => 'customer/view_job/');
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']];
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getOpenCustomerJobs();

	
	/**
	 * Inserts the History record of a deleted 
	 * job entry and returns insert id
	 * @param [array] $fields = Array of fields 
	 * 
	 * @return [int] delete_id;
	 */
	public function insertCustomerJobDeleteHistory($fields) {
		$this->db->insert('customer_job_delete_history', $fields);
		return $this->db->insert_id(); //int
	} //end insertCustomerJobDeleteHistory();
	
	/**
	 * Inserts a job record into the customer_jobs table
	 * 
	 * @param [array] $fields = database column names and values
	 *  
	 * @return [int] = customer job id
	 */
	public function insertJob($fields) {
		$this->db->insert('customer_jobs', $fields);
		return $this->db->insert_id(); //int
	} //end insertJob();
	
	/**
	 * Update a customer job with new data
	 * 
	 * @param [int] $id = job id
	 * @param [array] $fields = array of fields and values
	 * 
	 * @return null
	 */
	public function updateCustomerJob($id, $fields) {
		$this->db->where('job_id', $id);
		$this->db->limit(1); //always limit one
		$this->db->update('customer_jobs', $fields);
		
		return null; //null;
	} //end updateCustomerJob();
	
	/**
	 * AJAX call to update selected field
	 * 
	 * @param [int] $id = job id
	 * @param [string] $field = column name
	 * @param [string] $value = value of column
	 * 
	 * @return [string] $value
	 */
	public function AJAX_updateCustomerJobField($id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('job_id', $id);
		$this->db->update('customer_jobs', $data);
		
		return $value; //string
				
	} //end AJAX_updateCustomerJobField();
	
} //end Customer_job_model();
?>