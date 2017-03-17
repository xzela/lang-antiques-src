<?php
/**
 * Major Class Model
 * 
 * Returns Major Class data.
 * 
 * This does not Update, Delete, or Insert new data.
 * See Mango for more information
 * 
 * @author user
 *
 */
class Major_class_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		
		$this->ci =& get_instance(); 
	}
	
	/**
	 * Returns data of a specific Major Classes
	 * 
	 * @param [int] $major_class_id = major class id
	 * 
	 * @return [array] = array of Major Class data
	 */
	public function getMajorClassData($major_class_id) {
		$this->db->from('major_class');
		$this->db->where('mjr_class_id', $major_class_id);
		$this->db->limit(1);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['name'] = $row['mjr_class_name'];
				$data = $row;
			}
		}
		return $data; //array
	} //end getMahjorClassData();
	
	/**
	 * Get all Major Classes
	 * 
	 * @return [array] = multi-dem array of major classes
	 */
	public function getMajorClasses() {
		$this->db->from('major_class');
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		} 
		return $data; //array
	} //end getMajorClasses();
	
} //end Major_class_model();
?>