<?php
class Sync_model extends Model {
	
	public $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	function getPushData($item_id) {
		$this->ci->load->model('utils/push_model');
		$this->ci->load->model('inventory/gemstone_model');
		//test to see if the item was pushed
		$ignore_list = array('item_id', 'lang_id', 'fran_id');
		$b = false;
		$this->db->from('inventory');
		$this->db->where('item_id', $item_id);
		$this->db->limit(1);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$b = (bool)$row['push_state']; //cast a bool fool
				//because i'm not smart enough to store a bool in the database
				if($b) {//push data has been found
					//get the lang item where the lang_id=fran_id 
					$inventory_data = $this->ci->push_model->getLangInventoryData($row['item_id']);
					$data['inventory_sync_data'] = $this->ci->push_model->compareDataSets($row, $inventory_data, $ignore_list);
				}
			}
		}
		return $data;
	}	
	
	/**
	 * Syncs a spcific field with a value;
	 * 
	 * @param [int] $fran_id = frandb01:inventory:item_id
	 * @param [int] $lang_id = langdb01:inventory:item_id
	 * @param [string] $column = column name
	 * @param [string] $value = column value
	 * 
	 * @return null
	 */
	public function syncInventoryField($fran_id, $lang_id, $column, $value) {
		$field = array($column => $value);
		$this->db->where('item_id', $fran_id);
		$this->db->where('lang_id', $lang_id);
		$this->db->limit(1);
		$this->db->update('inventory', $field);
		
		return null;
	}
	
	/**
	 * Syncs all frandango inventory fields with mango inventory fields
	 * 
	 * @param [int] $fran_id = frandb01:inventory:item_id
	 * @param [int] $lang_id = landdb01:inventory:item_id
	 * @param [array] $lang_data = array with key values
	 *  
	 * @return null
	 */
	public function syncAllInventoryFields($fran_id, $lang_id, $lang_data) {
		$this->db->where('item_id', $fran_id);
		$this->db->where('lang_id', $lang_id);
		$this->db->limit(1);
		$this->db->update('inventory', $lang_data);
	}
}