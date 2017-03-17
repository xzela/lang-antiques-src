<?php
class Staff_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Returns the Staff Memebers Name;
	 * 
	 * @param [int] $staff_id = user id
	 * 
	 * @return [string] = staff member name
	 */
	public function getStaffName($staff_id) {
		$this->db->from('users');
		$this->db->select('first_name, last_name');
		$this->db->where('user_id', $staff_id);
		$this->db->limit(1);
		
		$data = '';
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['name'] = $row['first_name'] . ' ' . $row['last_name'];
				$data = $row['name'];
			}
		}
		return $data;
	} //end getStaffName();
}
?>