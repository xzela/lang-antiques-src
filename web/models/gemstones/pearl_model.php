<?php
/**
 * Pearl Model
 * Returns data on Opals
 * 
 * This model should NOT Update, Delete, or Insert data.
 * It should only return data;
 * 
 * @author user
 *
 */
class Pearl_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Gets all of the Pearls applied to a specific
	 * item.
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of pearls
	 */
	public function getItemPearls($item_id) {
		$data = array();
		$this->db->from('pearl_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//sets generic keys
				$row['gemstone_id'] = $row['p_id'];
				$row['gemstone_type_id'] = $row['p_type_id'];
				$data[] = $row;
			}
		}		
		return $data; //array
	} //end getITmePearls();
	
	/**
	 * Returns simialr items based on current item
	 * 
	 * used to find similar items
	 * 
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of similar items;
	 */
	public function getItemPearlTypes($item_id) {
		$data = array();
		$this->db->distinct();
		$this->db->select('p_type_id');
		$this->db->from('pearl_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemPearlTypes

} //end Pearl_model();

?>