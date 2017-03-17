<?php
class Jadeite_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance(); //allows access to external models;
	}
	
	/**
	 * Removes a jadeite from the database
	 * 
	 * @param [int] $item_id
	 * @param [int] $jadeite_id
	 * 
	 * @return NULL
	 */
	public function deleteJadeite($item_id, $jadeite_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('j_id', $jadeite_id);
		$this->db->delete('jadeite_info');
		
		return null; //null
	} //end deleteJadeite();
	
	/**
	 * Returns all of the jade applied to a specific
	 * item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dim array of jade data
	 */
	public function getItemJade($item_id) {
		$this->ci->load->model('inventory/gemstone_model');
		$this->db->from('jadeite_info');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['jade_name'] = $this->ci->gemstone_model->getGemstoneName($row['j_type_id']); 
				$data[$row['j_id']] = $row;
			}
		}
		return $data; //array
	} //end getItemJade()
	
	/**
	 * Returns a specific Jade
	 * 
	 * @param [int] $jadeite_id = jade id
	 * 
	 * @return [array] = array of jade data;
	 */
	public function getJadeiteData($jadeite_id) {
		$this->db->where('j_id', $jadeite_id);
		$this->db->from('jadeite_info');
		$query = $this->db->get(); 
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}
		return $data; //array;
	} //end getJadeiteData();
	
	/**
	 * Inserts a new Jade into the database
	 * 
	 * @param [array] $fields = array of fields
	 * 
	 * @return [int] = jade id
	 */
	public function insertJadeite($fields) {
		$this->db->insert('jadeite_info', $fields);
		return $this->db->insert_id(); //int		
	} //end insertJadeite();
	
	/**
	 * Updates a specific jade
	 *  
	 * @param [int] $item_id = item id
	 * @param [int] $jadeite_id = jade id
	 * @param [array] $fields = array of fields 
	 * 
	 * @return null
	 */
	public function updateItemJadeite($item_id, $jadeite_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->where('j_id', $jadeite_id);
		$this->db->update('jadeite_info', $fields);
		
		return null; //null
	} //end updateItemJadeite();
	
	/**
	 * An AJAX call to update a specific jadeite and field
	 * 
	 * @param [int] $jadeite_id = jade id
	 * @param [string] $field = column to update
	 * @param [string] $value = new value;
	 * 
	 * @return [string] = value
	 */
	public function AJAX_updateJadeiteField($jadeite_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('j_id', $jadeite_id);
		$this->db->limit(1);
		$this->db->update('jadeite_info', $data);
		
		return $value; //string
		
	} //end AJAX_updateJadeiteField();

} //end Jadeite_model();
?>