<?php
/**
 * Returns Material Data
 * 
 * This does not Update, Delete, or Insert new data.
 * 
 * @author user
 *
 */
class Material_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Returns materials that have been
	 * applied to a specific item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of materials
	 */
	public function getItemMaterials($item_id) {
		$data = array();
		$this->db->from('item_material');
		$this->db->join('materials', 'item_material.material_id = materials.material_id');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	} //end getItemMaterials();
	
} //end Material_model();

?>