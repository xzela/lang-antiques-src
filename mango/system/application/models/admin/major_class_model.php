<?php
class Major_class_model extends Model {
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Callback: tests the Major Class Id, 
	 * makes sure it is unique
	 * 
	 * @param [string] $string = string
	 * 
	 * false: $string is unique, ok to insert
	 * true: $string is not unique, do not insert 
	 * 
	 * @return [boolean];
	 */	
	public function checkMajorClassId($string) {
		$this->db->from('major_class');
		$this->db->where('mjr_class_id', $string);
		$query = $this->db->get();
		$b = true;
		if($query->num_rows() == 0) {
			$b =  false;
		}
		return $b; //bool	
	} //end checkMajorClassId()
	
	/**
	 * Callback: tests the Major Class Name, 
	 * makes sure it is unique
	 * 
	 * @param [string] $string = string
	 * 
	 * false: $string is unique, ok to insert
	 * true: $string is not unique, do not insert 
	 * 
	 * @return [boolean];
	 */
	public function checkMajorClassName($string) {
		$this->db->from('major_class');
		$this->db->where('mjr_class_name', $string);
		$query = $this->db->get();
		$b = true;
		if($query->num_rows() == 0) {
			$b = false;
		}
		return $b; //bool
	} //end checkMajorClassName();

	/**
	 * Delets a Major Class from the database
	 * 
	 * @param [int] $major_class_id = major class id;
	 * @return null
	 */
	public function deleteMajorClass($major_class_id) {
		$this->db->where('mjr_class_id', $major_class_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('major_class');

		return null; //null
	} //end deleteMajorClass();
	
	/**
	 * Returns All Major classes in an Array
	 * 
	 * @return [array] Major Class data
	 */
	public function getMajorClasses() {
		$this->db->from('major_class');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['major_class_id'] = $row['mjr_class_id']; //add alias 'major_class_id'
				$row['major_class_name'] = $row['mjr_class_name']; //add alias 'major_class_name'
				 
				$row['count'] = $this->getMajorClassCountItems($row['major_class_id']); //add count of items
				$data[$row['major_class_id']] = $row;	
			}
		}
		return $data; //array
	} //end getMajorClasses();
	
	/**
	 * Returns the (count) number of items for a speific 
	 * major class
	 * 
	 * @param [int] $major_class_id = major class id
	 * 
	 * @return [int] count of items
	 */
	public function getMajorClassCountItems($major_class_id) {
		$this->db->from('inventory');
		$this->db->where('mjr_class_id', $major_class_id);
		$count = $this->db->count_all_results();
		
		return $count; //int
	} //end getMajorClassCOuntItems();
	
	/**
	 * Returns Major Class Data,
	 *  
	 * @param [int] $major_class_id = major class id
	 * @return [array] = major_class_data
	 */
	public function getMajorClassData($major_class_id) {
		$this->db->from('major_class');
		$this->db->where('mjr_class_id', $major_class_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['major_class_id'] = $row['mjr_class_id']; //add alias 'major_class_id'
				$row['major_class_name'] = $row['mjr_class_name']; //add alias 'major_class_name'
				
				$row['item_count'] = $this->getMajorClassCountItems($row['major_class_id']); //add count of items
				$data = $row;
			}
		}
		return $data; //array
	} //end getMajorClassData();
	
	/**
	 * Returns the name of a Major Class item
	 *
	 * @param [int] $major_class_id = major class id
	 * 
	 * @return [string] = name of major class
	 */
	public function getMajorClassName($major_class_id) {
		$this->db->select('mjr_class_name');
		$this->db->from('major_class');
		$this->db->where('mjr_class_id', $major_class_id);
		$this->db->limit(1); //always limit one
		
		$query = $this->db->get();
		$name = "";
		if($query->num_rows() > 0) {
			$row = $query->row();
			$name = $row->mjr_class_name;
		}		
		return $name; //string
	} //end getMajorClassName();	
	
	/**
	 * Returns all of the web active Major Classes
	 *
	 * @return [array] = multi-din  array of Major Class Data
	 */
	public function getWebActiveMajorClasses() {
		$this->db->from('major_class');
		$this->db->where('active', 1);
		$this->db->where('show_web', 1);
		$this->db->where('element_url_name IS NOT NULL', null, true);
		//sub query to find major classes applied to the menu elements
		$sql = 'mjr_class_id NOT IN (SELECT menu_elements.element_type_id FROM menu_elements JOIN major_class on menu_elements.element_type_id = major_class.mjr_class_id WHERE menu_elements.element_type = 1)';
		$this->db->where($sql, null, true);
		
		$data = array();
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['major_class_id'] = $row['mjr_class_id']; //add alias 'major_class_id'
				$row['major_class_name'] = $row['mjr_class_name']; //add alias 'major_class_name'
				
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getWebActiveMajorClasses();
	
	/**
	 * Inserts a Major Class record
	 * 
	 * @param [arrays] $fields = column=>value
	 * @return [int] major_class_id;
	 */
	public function insertMajorClass($fields) {
		$this->db->insert('major_class', $fields);
		
		return $fields['mjr_class_id']; //int
	} //end insertMajorClass();
	
	/**
	 * Updates the major class with new data
	 * 
	 * @param [int] $id =  major_class_id
	 * @param [array] $fields = array of fields to update
	 * @return null
	 */
	public function updateMajorClass($id, $fields) {
		$this->db->where('mjr_class_id', $id);
		$this->db->limit(1); //always limit one
		$this->db->update('major_class', $fields);	
		
		return null; //null
	} //end updateMajorClass();
	
	/**
	 * AJAX call to update a major class
	 * 
	 * @param [int] $major_class_id = major class id
	 * @param [string] $column = column name
	 * @param [string] $value = value 
	 * 
	 * @return null
	 */
	public function AJAX_updateMajorClassField($major_class_id, $column, $value) {
		$fields = array();
			$fields[$column] = $value;
		$this->db->where('mjr_class_id', $major_class_id);	
		$this->db->update('major_class', $fields);
		
		return null;
		
	} //end AJAX_updateMajorClassField();

} //end Major_class_model();
?>