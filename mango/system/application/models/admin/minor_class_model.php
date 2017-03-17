<?php
class Minor_class_model extends Model {
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Callback to test if the Minor Class id is unique
	 * 
	 * @param [string] = $string
	 * false: $string is unique, ok to insert
	 * true: $string is not unique, do not insert 
	 * 
	 * @return [boolean]
	 */	
	public function checkMinorClassId($string) {
		$this->db->from('minor_class');
		$this->db->where('min_class_id', $string);
		$query = $this->db->get();
		$b = true;
		if($query->num_rows() == 0) {
			$b =  false;
		}
		return $b; //bool
	} //end checkMinorClassId();
	
	/**
	 * Callback to test if the Minor Class name is unique
	 * 
	 * @param [string] = $string
	 * false: $string is unique, ok to insert
	 * true: $string is not unique, do not insert 
	 * 
	 * @return [boolean]
	 */
	public function checkMinorClassName($string) {
		$this->db->from('minor_class');
		$this->db->where('min_class_name', $string);
		$query = $this->db->get();
		$b = true;
		if($query->num_rows() == 0) {
			$b = false;
		}
		return $b; //bool
	} //end checkMinorClassName();
	
	/**
	 * Deletes a Minor Class
	 * 
	 * @param [int] $minor_class_id = minor class id
	 * 
	 * @return null
	 */
	public function deleteMinorClass($minor_class_id) {
		$this->db->where('min_class_id', $minor_class_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('minor_class');

		return null;
	} //end deleteMinorClass();
	
	/**
	 * Returns all Minor Classes in database
	 * 
	 * @return [array] = multi-din  array of Minor classes 
	 */
	public function getMinorClasses() {
		$this->db->from('minor_class');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['minor_class_id'] = $row['min_class_id']; //add alias 'minor_class_id'
				$row['minor_class_name'] = $row['min_class_name']; //add alias 'minor_class_id'
				$row['count'] = $this->getMinorClassCountItems($row['minor_class_id']); //add count of items
				$data[$row['minor_class_id']] = $row;	
			}
		}
		return $data;
	} //end getMinorClasses();
	
	/**
	 * Returns a specific Minor Class Data
	 * 
	 * @param [int] $minor_class_id = minor_class_id
	 * 
	 * @return [array] = array of minor class data
	 */
	public function getMinorClassData($minor_class_id) {
		$this->db->from('minor_class');
		$this->db->where('min_class_id', $minor_class_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['minor_class_id'] = $row['min_class_id']; //add alias 'minor_class_id'
				$row['minor_class_name'] = $row['min_class_name']; //add alias 'minor_class_id'
				$row['item_count'] = $this->getMinorClassCountItems($row['minor_class_id']); //add count of items
				$data = $row;
			}
		}
		return $data; //array
	} //end getMinorClassData();
	
	/**
	 * Returns the count (number) of invenyor items
	 * that use this as a minor class
	 * 
	 * @param [int] $minor_class_id  = minor class id
	 * 
	 * @return [int] = count of items
	 */
	public function getMinorClassCountItems($minor_class_id) {
		$this->db->from('inventory');
		$this->db->where('min_class_id', $minor_class_id);
		$count = $this->db->count_all_results();
		return $count; //int
	} //end getMinorClassCountItems();
	
	/**
	 * Returns the minor classes name
	 *
	 * @param [int] $minor_class_id = minor class id
	 * 
	 * @return string
	 */
	public function getMinorClassName($minor_class_id) {
		$this->db->select('min_class_name');
		$this->db->from('minor_class');
		$this->db->where('min_class_id', $minor_class_id);
		$this->db->limit(1); //always limit one
		
		$query = $this->db->get();
		
		$name = ""; //string
		if($query->num_rows() > 0) { 
			$row = $query->row();
			$name = $row->min_class_name;
		}		
		return $name; //string
	} //end getMinorClassName();
	
	/**
	 * Inserts a Minor Class record
	 * 
	 * @param [array] $fields = column=>value
	 * 
	 * @return [int] minor class id
	 */
	public function insertMinorClass($fields) {
		$this->db->insert('minor_class', $fields);
		return $fields['min_class_id']; //int
	} //end insertMinorClass();
	
	/**
	 * Updates the minor class with new data
	 * 
	 * @param [int] $id = minor_class_id
	 * @param [array] $fields = Array of Fields
	 * 
	 * @return null
	 */
	public function updateMinorClass($id, $fields) {
		$this->db->where('min_class_id', $id);
		$this->db->limit(1);  //always limit one
		$this->db->update('minor_class', $fields);
		
		return null; //null
	} //updateMinorClass();
		
	/**
	 * AJAX call to update specific minor class
	 * 
	 * @param [int] $minor_class_id =  minor class
	 * @param [string] $column = column
	 * @param [string] $value = value;
	 * 
	 * @return null
	 */
	public function AJAX_updateMinorClassField($minor_class_id, $column, $value) {
		$fields = array();
			$fields[$column] = $value;
		$this->db->where('min_class_id', $minor_class_id);	
		$this->db->update('minor_class', $fields);
		
		return null; //null
	} //end AJAX_updateMinorClassField();
}
?>