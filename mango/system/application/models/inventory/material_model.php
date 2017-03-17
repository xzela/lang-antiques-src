<?php
class Material_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();		
	}
	/**
	 * Applies a material to a item
	 *
	 * @param [int] $id = item id
	 * @param [int] $material_id = material id
	 * @param [string] $karat = karats 
	 * 
	 * @param [int] item_material_id (primary key)
	 * 
	 */
	function applyMaterial($fields) {		
		$this->db->insert('item_material', $fields);
		
		return $this->db->insert_id();
	}
	
	function checkMaterialNames($string) {
		$this->db->select('material_name');
		$this->db->where('material_name', $string);
		$this->db->from('materials');
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b;
	}
	
	/**
	 * Counts the number of materials that are
	 * applyed to a specific item
	 * 
	 * @param $id = item_id
	 * @return int
	 */
	function countMaterial($id) {
		$count = 0;
		$this->db->from('item_material');
		$this->db->where('item_id', $id);		
		$count = $this->db->count_all_results();
		return $count;
	}
	
	function deleteMaterial($material_id) {
		$this->db->where('material_id', $material_id);
		$this->db->limit(1);
		$this->db->delete('materials');
		return null;
	}
		
	function getAllMaterials() {
		$this->db->from('materials');
		$this->db->order_by('material_name', 'asc');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['count'] = $this->getMaterialCount($row['material_id']);
				$data[] = $row;
			}
		}
		return $data;
	}
	
	/**
	 * Returns all of the materials already applied to this item
	 *
	 * @param [int] $id = item id
	 * 
	 * @return [array] = materials
	 */
	function getAppliedMaterials($item_id) {
		$this->db->from('item_material');
		$this->db->join('materials', 'materials.material_id = item_material.material_id');
		$this->db->where('item_id', $item_id);
		$this->db->order_by('material_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['item_material_id']] = $row;
			}
		}
		return $data;
	}

	function getAppraisalMaterial($id) {
		$this->db->from('item_material');
		$this->db->join('materials', 'materials.material_id = item_material.material_id');
		$this->db->where('item_id', $id);
		$this->db->limit(1);
		$this->db->order_by('material_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;
		
	}
	
	function getItemMaterials($item_id) {
		$this->db->from('item_material');
		$this->db->where('item_id', $item_id);
		$data = array();
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data;
	}
	
	function getItemsWithMaterial($material_id) {
		$this->ci->load->model('inventory/inventory_model');
		$this->db->select('item_id');
		$this->db->where('material_id', $material_id);
		$this->db->from('item_material');
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row = $this->ci->inventory_model->getItemData($row['item_id']);
				$data[$row['item_id']] = $row;
			}
		}
		return $data;
	}
	
	function getMaterialCount($material_id) {
		$this->db->where('material_id', $material_id);
		$this->db->from('item_material');
		$data = 0;
		$query = $this->db->get();
		$count = $query->num_rows(); 
		if($count > 0) {
			$data = $count;
		}
		return $data;
	}
		
	function getMaterialData($material_id) {
		$this->db->where('material_id', $material_id);
		$this->db->from('materials');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;
	}
	
	/**
	 * Returns the name of a given material
	 *
	 * @param [int] $material_id = material id
	 * 
	 * @return [string] = material name
	 */
	function getMaterialName($material_id) {
		$this->db->select('material_name');
		$this->db->from('materials');
		$this->db->where('material_id', $material_id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$name = $row->material_name;
			}
		}
		
		return $name;
	}
	
	/**
	 * Returnals all of the active materials
	 *
	 * @param [bool] $active = active or not
	 * 
	 * @return [array]
	 */
	function getMaterials($active = true) {
		$this->db->from('materials');
		if ($active) {
			$this->db->where('active', $active);
		}
		$this->db->order_by('material_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
		
	}

	

	
	

	
	function insertMaterial($fields) {
		$this->db->insert('materials', $fields);
		return $this->db->insert_id();
	}
	
	function materialHasKarats($id) {
		$b = false;
		$this->db->from('materials');
		$this->db->where('material_id', $id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$b = $row['karats'];
			}
		}
		return $b;
	}
	/**
	 * Removes the material for an item
	 *
	 * @param [int] $id = item id
	 * @param [int] $material_id = material id
	 * 
	 * @return null
	 */
	function removeMaterial($item_material_id) {
		$this->db->where('item_material_id', $item_material_id);
		$this->db->delete('item_material');
		
		return null;
	}
			
	/**
	 * Updates the material with new data
	 * 
	 * @param [int] $id =  material id
	 * @param [array] $fields = array of fields and values
	 * 
	 * @return null
	 */
	function updateMaterial($id, $fields) {
		$this->db->where('material_id', $id);
		$this->db->limit(1);
		$this->db->update('materials', $fields);
		
		return null;
	}
	
	function AJAX_updateMaterialField($id, $column, $value) {
		$fields = array();
			$fields[$column] = $value;
		$this->db->where('material_id', $id);
		$this->db->update('materials', $fields);
	}
}
?>