<?php
/**
 * Modifier Model
 *
 * This model returns modifier data
 *
 * This only selects data, it should not Update, Delete, or Insert data.
 *
 * See Mango for that.
 *
 * @author user
 *
 */
class Modifier_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Return an array of modifiers
	 * for a specific item
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = multi-dem array of modifier data
	 */
	public function getItemModifiers($item_id) {
		$this->db->from('item_modifier');
		$this->db->join('modifiers', 'item_modifier.modifier_id = modifiers.modifier_id');
		$this->db->where('item_id', $item_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemModifiers();

	/**
	 * Returns data for a specific modifier
	 *
	 * @param [int] $modifier_id = modifier id
	 *
	 * @return [array] = array of modifier data
	 */
	public function getModifierData($modifier_id) {
		$this->db->from('modifiers');
		$this->db->where('modifier_id', $modifier_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data =  $row;
			}
		}

		return $data; //array
	} //end getModifierData();

	/**
	 * Returns all modifiers
	 *
	 * @return [array] = multi-dem array of modifiers
	 */
	public function getModifiers() {
		$this->db->from('modifiers');
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getModifiers();

	/**
	 * Returns all of the Active Modifiers of a specific item
	 *
	 * This is used for
	 *
	 * @param unknown_type $item_id
	 */
	public function getWebActiveItemModifiers($item_id) {
		$this->db->from('item_modifier');
		$this->db->join('modifiers', 'item_modifier.modifier_id = modifiers.modifier_id');
		$this->db->where('item_modifier.item_id', $item_id);
		$this->db->where('element_url_name IS NOT NULL', null, true);
		$this->db->where('element_url_name !=', '');
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getWebActiveItemModifiers();

} //end Modifier_model();
?>