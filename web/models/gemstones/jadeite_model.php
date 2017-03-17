<?php
/**
 * Jadeite Model
 * Returns data on Jadeites
 * 
 * This model should NOT Update, Delete, or Insert data.
 * It should only return data;
 * 
 * 
 * @author user
 *
 */

class Jadeite_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Returns data about a specific jade
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of jade data
	 */
	public function getItemJadeite($item_id) {
		$data = array();
		$this->db->from('jadeite_info');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//sets generic key values
				$row['gemstone_id'] = $row['j_id'];
				$row['gemstone_type_id'] = $row['j_type_id'];
				$data[] = $row;
			}
		}		
		return $data; //array
	} //end getItemJadeite();
	
	/**
	 * Returns Jade Types.
	 * 
	 * Used for finding similar items
	 * 
	 * @param [int] $item_id
	 * 
	 * @return [array] = multi-dem array of similar items
	 */
	public function getItemJadeiteTypes($item_id) {
		$data = array();
		$this->db->distinct();
		$this->db->select('j_type_id');
		$this->db->from('jadeite_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemJadeiteTypes();

} //end Jadeite_model(); 

?>