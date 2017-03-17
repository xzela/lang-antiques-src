<?php
class Workshop_reports_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Returns all of the Workshops
	 *
	 * @param [int] $per_page = items per page (default:20)
	 * @param [int] $offset = number to offset
	 * @param [string] $sort = column to sort
	 * @param [string] $direction = direction of sort
	 *
	 * @return [array] = multi-dim array of workshops
	 */
	public function getAvailableWorkshops($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('workshop_info');
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
			foreach ($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getAvailbleWorkshops()'

	/**
	 * Gets all of the custimer jobs based on a workshop
	 *
	 * @param [int] $workshop_id = workshop id
	 *
	 * @return [array] = multi-dim array of customer jobs;
	 */
	public function getCustomerJobs($workshop_id) {
		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus();

		$this->db->from('customer_jobs');
		$this->db->where('workshop_id', $workshop_id);
		$this->db->order_by_field('status', '1,2,0');

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']]; //status text
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getCustomerJobs();

	/**
	 * Gets all of the Inventoru Jobs, based on Workshop Id
	 *
	 * @param [int] $workshop_id = workshop id
	 *
	 * @return [array] = multi-dim array of workshop data
	 */
	public function getInventoryJobs($workshop_id) {
		$this->load->model('image/image_model');
		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus();

		$this->db->from('inventory_jobs');
		$this->db->join('inventory', 'inventory.item_id = inventory_jobs.item_id');
		$this->db->where('workshop_id', $workshop_id);
		$this->db->order_by_field('inventory_jobs.status', '1,2,0');

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['status']]; //status text
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']); //get images
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getInventoryJobs();

	/**
	 * Returns all of the Open Jobs
	 *
	 * @return [array] = multi-dim array of open job data
	 */
	public function getOpenJobs($sorter = null) {
		$this->ci->load->helper('snappy_source');
		$this->ci->load->model('image/image_model');

		$this->ci->load->model('utils/lookup_list_model');
		$status_array = $this->ci->lookup_list_model->getJobStatus();

		$this->db->select('inventory_jobs.*, workshop_info.*, inventory.*, inventory_jobs.notes AS job_notes, inventory_jobs.status AS job_status');
		$this->db->from('inventory_jobs');
		$this->db->join('workshop_info', 'workshop_info.workshop_id = inventory_jobs.workshop_id');
		$this->db->join('inventory', 'inventory.item_id = inventory_jobs.item_id');
		$this->db->where('inventory_jobs.status', 1); //1=active
		$this->db->where('inventory_jobs.open_date <=', 'CURDATE()');
		$this->db->order_by('workshop_info.name','ASC');
		$this->db->order_by('inventory.mjr_class_id', 'ASC');
		$this->db->order_by('inventory.min_class_id', 'ASC');
		$this->db->order_by('inventory.suffix', 'ASC');

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['status_text'] = $status_array[$row['job_status']]; //get status name
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']); //get item images

				$data[] = $row;
			}
		}
		return $data; //array

	} //end getOpenJobs()

} //end Workshop_report_model()
?>