<?php
class Pearl_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance(); //allows access to external models;
	}
	
	/**
	 * Removes a pearl from the databae
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $pearl_id =  pearl id
	 * 
	 * @return null
	 */
	public function deletePearl($item_id, $pearl_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('p_id', $pearl_id);
		$this->db->limit(1);
		$this->db->delete('pearl_info');
		
		return null; //null
	} //end deletePearl();
	
	/**
	 * Returns all of the pearsl applied to an item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = array of pearl data 
	 */
	public function getItemPearls($item_id) {
		$this->ci->load->model('inventort/gemstone_model');
		$this->db->from('pearl_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['pearl_name'] = $this->ci->gemstone_model->getGemstoneName($row['p_type_id']);
				$data[$row['p_id']] = $row;
			}
		}
		return $data; //array
		
	} //end getItemPearls();
	
	/**
	 * Returns all of the pearl data
	 * 
	 * @param [int] $pearl_id = pearl id
	 * 
	 * @return [array] = array of pearl data;
	 */
	public function getPearlData($pearl_id) {
		$this->db->where('p_id', $pearl_id);
		$this->db->from('pearl_info');
		$query = $this->db->get(); 
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}
		return $data; //array
	} //end getPearlData();
	
	/**
	 * Inserts a new pearl into the database
	 * 
	 * @param [array] $fields = array of fields
	 * 
	 * @return [int] = new p_id;
	 */
	public function insertPearl($fields) {
		$this->db->insert('pearl_info', $fields);
		return $this->db->insert_id(); //int
	} //end insertPearl();
	
	/**
	 * Update a specific pearl
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $pearl_id = pearl id
	 * @param [array] $fields = array of fields
	 * 
	 * @return null;
	 */
	public function updateItemPearl($item_id, $pearl_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->where('p_id', $pearl_id);
		$this->db->update('pearl_info', $fields);
		
		return null; //null
		
	} //end updateItemPearl();

	/**
	 * An AJAX call to update a feild
	 * 
	 * @param [int] $id = pearl id
	 * @param [string] $field = column to update
	 * @param [string] $value = value
	 * 
	 * @return [string] = value 
	 */
	public function AJAX_updatePearlField($pearl_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('p_id', $pearl_id);
		$this->db->limit(1);
		$this->db->update('pearl_info', $data);
		
		return $value; //string
	} //end AJAX_updatePearlField(); 
	
} //end Pearl_model();
?>