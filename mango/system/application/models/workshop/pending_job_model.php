<?php

class Pending_job_model extends Model {

	public $ci;

	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	public function findPendingJobByItemId($item_id) {
		$data = 0;
		$this->db->from('inventory_pending_jobs');
		$this->db->where('item_id', $item_id);
		$this->db->where('job_status', 'open');
		$this->db->order_by('insert_date', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row['pending_job_id'];
			}
		}
		return $data;
	}
	public function getPendingJobData($pending_id) {
		$data = array();
		$this->db->from('inventory_pending_jobs');
		$this->db->where('pending_job_id', $pending_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				return $data = $row;
			}
		}

		return $data;
	}
	public function getOpenPendingJobs() {
		$data = array();
		$this->db->from('inventory_pending_jobs');
		$this->db->join('inventory', 'inventory_pending_jobs.item_id = inventory.item_id');
		$this->db->where('job_status', 'open');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	public function insertPendingJob($fields) {
		$this->db->insert('inventory_pending_jobs', $fields);

		return $this->db->insert_id();
	}

	/**
	 *
	 * @param unknown_type $pending_job_id
	 * @param unknown_type $fields
	 *
	 * @return null
	 */
	public function updatePendingJob($pending_job_id, $fields) {
		$this->db->where('pending_job_id', $pending_job_id);
		$this->db->limit(1);
		$this->db->update('inventory_pending_jobs', $fields);

		return null;
	} //end updatePendingJob();
}