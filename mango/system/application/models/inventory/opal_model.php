<?php
class Opal_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Removes a specific opal from the database
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $opal_id = opal id
	 * 
	 * @return null;
	 */
	public function deleteOpal($item_id, $opal_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('o_id', $opal_id);
		$this->db->delete('opal_info');
		
		return null; //null
	} //end deleteOpal(); 
	
	/**
	 * Returns all of the opals applied to a
	 * specific item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-din  array of opals
	 */
	public function getItemOpals($item_id) {
		$this->ci->load->model('inventory/gemstone_model');
		$this->db->from('opal_info');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['opal_name'] = $this->ci->gemstone_model->getGemstoneName($row['o_type_id']);
				$row['opal_shape'] = $this->ci->gemstone_model->getGemstoneCut($row['o_cut_id']);
				$data[$row['o_id']] = $row;
			}
		}
		
		return $data; //array;
	} //end getItemOpals();
	
	/**
	 * Returns data on a specific opal
	 * 
	 * @param [int] $opal_id
	 * 
	 * @return [array] = array of opal data
	 */
	public function getOpalData($opal_id) {
		$this->db->where('o_id', $opal_id);
		$this->db->from('opal_info');
		$query = $this->db->get(); 
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}
		return $data; //array
		
	} //end getOpalData();
	
	/**
	 * Inserts a new Opal into the database
	 * 
	 * @param [array] $fields = array of fields and values
	 * 
	 * @return [int] = opal id
	 */
	public function insertOpal($fields) {
		$this->db->insert('opal_info', $fields);
		
		return $this->db->insert_id(); //int
	}//end insertOpal();
	
	/**
	 * Updates an Opal
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $opal_id = opal id
	 * @param [array] $fields = array of fields and values
	 * 
	 * @return null;
	 */
	public function updateItemOpal($item_id, $opal_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->where('o_id', $opal_id);
		$this->db->update('opal_info', $fields);
		
		return null; //null
	} //end updateItemOpal();

	/**
	 * An AJAX call which updates a specific opal and opal field
	 * 
	 * @param [int] $opal_id = opal id
	 * @param [string] $field = column to update
	 * @param [string] $value = new value
	 * 
	 * @return [string] = value
	 */
	public function AJAX_updateOpalField($opal_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('o_id', $opal_id);
		$this->db->limit(1);
		$this->db->update('opal_info', $data);
		
		return $value; //string
		
	} //end AJAX_updateOpalField():
	
} //end Opal_model()
?>