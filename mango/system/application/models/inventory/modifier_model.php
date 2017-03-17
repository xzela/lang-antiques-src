<?php
class Modifier_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();		
	}
	
	/**
	 * Applies the modifier to the item
	 *
	 * @param int $id = item id
	 * @param int $mod_id = modifier id
	 * 
	 * @return null
	 */
	function applyModifier($id, $mod_id) {
		$data = array('modifier_id' => $mod_id, 'item_id' => $id);
		$this->db->insert('item_modifier', $data);
	}
	
	function checkModifierNames($string) {
		$this->db->select('modifier_name');
		$this->db->where('modifier_name', $string);
		$this->db->from('modifiers');
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b;
	}
	
	/**
	 * Counts the number of modifiers that are
	 * applyed to a specific item
	 * 
	 * @param $id = item_id
	 * @return int
	 */
	function countModifiers($id) {
		$count = 0;
		$this->db->from('item_modifier');
		$this->db->where('item_id', $id);		
		$count = $this->db->count_all_results();
		return $count;
	}
	
	function deleteModifier($modifier_id) {
		$this->db->where('modifier_id', $modifier_id);
		$this->db->limit(1);
		$this->db->delete('modifiers');
		
		return null;
	}
	
	function getAllModifiers() {
		$this->db->from('modifiers');
		$this->db->order_by('modifier_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['count'] = $this->getModifierCount($row['modifier_id']);
				$data[$row['modifier_id']] = $row;
			}
		}
		return $data;
	}
	
	/**
	 * Returns all of the modifiers already applied to this item
	 *
	 * @param [int] $id = item_id
	 * 
	 * @return [array] = all of the applied modifiers
	 */
	function getAppliedModifiers($id) {
		$this->db->from('item_modifier');
		$this->db->join('modifiers', 'modifiers.modifier_id = item_modifier.modifier_id');
		$this->db->where('item_id', $id);
		$this->db->order_by('modifier_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}
	
	function getItemModifiers($item_id) {
		$this->db->from('item_modifier');
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
	
	function getItemsWithModifier($modifier_id) {
		$this->ci->load->model('inventory/inventory_model');
		$this->db->select('item_id');
		$this->db->where('modifier_id', $modifier_id);
		$this->db->from('item_modifier');
		
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
	function getKeyWordModifiers() {
		$data = array();
		$this->db->from('modifiers');
		$this->db->where('alt_keyword', 1);
		$this->db->order_by('modifier_name, keyword_name', 'ASC');
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}
	/**
	 * Returns all of the modifiers that can be applied
	 * to the item
	 *
	 * @param bool $active = active or not
	 * 
	 * @return [array]
	 */
	function getModifiers($active = true) {
		/*
		 * Returns all active modifiers
		 */
		$this->db->from('modifiers');
		$this->db->where('active', $active);
		$this->db->order_by('modifier_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
		
	}
	
	function getModifierCount($modifier_id) {
		$this->db->where('modifier_id', $modifier_id);
		$this->db->from('item_modifier');
		$data = 0;
		$query = $this->db->get();
		$count = $query->num_rows(); 
		if($count > 0) {
			$data = $count;
		}
		return $data;
	}
	
	function getModifierData($modifier_id) {
		$this->db->from('modifiers');
		$this->db->where('modifier_id', $modifier_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data =  $row;
			}
		}
		
		return $data;
	}
	
	/**
	 * Gets the modifier name
	 *
	 * @param [int] $modifier_id = modifier id
	 * 
	 * @return [string] = modifier name 
	 */
	function getModifierName($modifier_id) {
		$this->db->select('modifier_name');
		$this->db->from('modifiers');
		$this->db->where('modifier_id', $modifier_id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$name = $row->modifier_name;
			}
		}
		
		return $name;
	}
	
	public function getNonTopLevelWebActiveModifiers($parent_id = null) {
		$this->db->from('modifiers');
		$this->db->where('active', 1);
		$this->db->where('top_level !=', 1);
		$this->db->where('show_web', 1);
		$this->db->where('meta_description IS NOT NULL', null, true);
		$this->db->where('element_url_name IS NOT NULL', null, true);
		$sql1 = 'modifier_id NOT IN (SELECT menu_elements.element_type_id FROM menu_elements JOIN modifiers on menu_elements.element_type_id = modifiers.modifier_id WHERE menu_elements.element_type = 2)';
		$this->db->where($sql1, null, true);
		if($parent_id != null) {
			$sql2 = 'modifier_id NOT IN (SELECT menu_sub_elements.sub_element_type_id FROM menu_sub_elements WHERE parent_element_id = ' . $parent_id . ')';
			$this->db->where($sql2, null, true);
		}
		$this->db->order_by('modifier_name', 'ASC');
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}
	
	function getTopLevelModifiers() {
		$this->db->from('modifiers');
		$this->db->where('active', 1);
		$this->db->where('top_level', 1);
		$this->db->where('show_web', 1);
		$this->db->where('element_url_name IS NOT NULL', null, true);
		$sql = 'modifier_id NOT IN (SELECT menu_elements.element_type_id FROM menu_elements JOIN modifiers on menu_elements.element_type_id = modifiers.modifier_id WHERE menu_elements.element_type = 2)';
		$this->db->where($sql, null, true);
		$this->db->order_by('modifier_name', 'ASC');
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data;
	}
	
	function searchModifierNames($string) {
		$this->db->from('modifiers');
		$this->db->like('modifier_name',$string, 'after');
		$this->db->order_by('modifier_name', 'ASC');
		$this->db->limit(50);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}
	
	function insertModifier($fields) {
		$this->db->insert('modifiers', $fields);
		return $this->db->insert_id();
	}
	
	/**
	 * Updates a Modifier with new data;
	 * 
	 * @param [int] $id = modifier id
	 * @param [array] $fields = Array of fields and values
	 * 
	 * @return null
	 */
	function updateModifier($id, $fields) {
		$this->db->where('modifier_id', $id);
		$this->db->limit(1);
		$this->db->update('modifiers', $fields);
		
		return null;
	}
	

	
	

		

	

	


	


	
	



	

	

	

	
	/**
	 * Removes the Modifier that is applied to the item
	 *
	 * @param [int] $id = item id
	 * @param [int] $mod_id = modifier item
	 * 
	 * @return null
	 */
	function removeModifier($id, $mod_id) {
		$this->db->where('item_id', $id);
		$this->db->where('modifier_id', $mod_id);
		$this->db->delete('item_modifier');
	}
	

	
	function AJAX_updateModifierField($id, $column, $value) {
		$fields = array();
			$fields[$column] = $value;
		$this->db->where('modifier_id', $id);
		$this->db->update('modifiers', $fields);
	}
}
?>