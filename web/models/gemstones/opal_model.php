<?php
/**
 * Opal Model
 * Returns data on Opals
 * 
 * This model should NOT Update, Delete, or Insert data.
 * It should only return data;
 * 
 * @author user
 *
 */
class Opal_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Returns all of the Opals applied to
	 * a specific item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of opal data;
	 */
	public function getItemOpals($item_id) {
		$data = array();
		$this->db->from('opal_info');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['gemstone_id'] = $row['o_id'];
				$row['gemstone_type_id'] = $row['o_type_id'];
				$data[] = $row;
			}
		}		
		return $data; //array
	} //end getItemOpals();
	
	
	/**
	 * Finds similar opals based on a specific
	 * item.
	 * 
	 * Used to find similar items
	 * 
	 * 
	 * @param [int] $item_id = item_id
	 * 
	 * @return [array] multi-dem array of similar items
	 */
	public function getItemOpalTypes($item_id) {
		$data = array();
		$this->db->distinct();
		$this->db->select('o_type_id');
		$this->db->from('opal_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemOpalTypes();

} //end Opal_model();

?>