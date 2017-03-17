<?php

class Assemble_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Creates an Assemble item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [int] assemble_id
	 */
	public function createAssembleItem($item_id) {
		$field = array('parent_item_id' => $item_id);
		$this->db->insert('inventory_assemble_parents', $field);
		
		return $this->db->insert_id(); //int
		
	} //end createAssembleItem();
	
	/**
	 * Inserts a child into assembly data
	 * 
	 * @param [int] $assemble_id
	 * @param [int] $child_id
	 * 
	 * @return [int] = child insert id
	 */
	public function insertChildItem($assemble_id, $child_id) {
		$fields = array();
			$fields['assemble_id'] = $assemble_id;
			$fields['child_item_id'] = $child_id;
			
		$this->db->insert('inventory_assemble_children', $fields);
		
		return $this->db->insert_id(); //int
	} //end insertChildItem();
	
	/**
	 * Inserts a history note for
	 * 
	 * @param [int] $fields = array of fields
	 * 
	 * @return [int] = audit_id;  
	 */
	public function insertHistoryNote($fields) {
		$this->db->insert('inventory_audit', $fields);
		return $this->db->insert_id(); //int
	} //end insertHistoryNote();
	
	/**
	 * Returns specific assemble data
	 * 
	 * @param [int] $assemble_id = assemble id;
	 * 
	 * @return [array] = array of assemble data;
	 */
	public function getAssembleData($assemble_id) {
		$this->db->from('inventory_assemble_parents');
		$this->db->where('assemble_id', $assemble_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
		
	} //end getAssembleData();
	
	/**
	 * Returns the assembled id from the a parent item
	 * 
	 * @param [int] $parent_id = parent item id
	 * 
	 * @return [array] = array of assembly data 
	 */
	public function getAssembleIdFromParent($parent_id) {
		$this->db->from('inventory_assemble_parents');
		$this->db->where('parent_item_id', $parent_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array;
	} //end getAssembleIdFromParent();
	
	/**
	 * Returns the parent id from an assembled child
	 * 
	 * @param [int] $child_id = child item id
	 * 
	 * @return [array] = array of assembly data
	 */
	public function getAssembleIdFromChild($child_id) {
		$this->db->from('inventory_assemble_children');
		$this->db->where('child_item_id', $child_id);
		$this->db->order_by('child_item_id', 'DESC');
		$this->db->limit(1); //always limit on;
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			//@TODO convert this into $query->row_array();
			foreach ($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getAssembleIdFromChild();
	
	/**
	 * Finds the childen of an assembled item
	 * by its assemble id
	 * 
	 * @param [int] $assemble_id = assemble id
	 * @return [array] = array of children
	 */
	public function getChildrenAssemblyData($assemble_id) {
		$this->db->from('inventory_assemble_children');
		$this->db->where('assemble_id', $assemble_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['assemble_children_id']] = $row;
			}
		}
		return $data; //array;
	} //end getChildrenAssemblyData();
	
	/**
	 * Returns the parent assembly data.
	 * 
	 * @param [int] $assemble_id = parent id
	 * 
	 * @return [array] = array of parent assembly data
	 */
	public function getParentAssemblyData($assemble_id) {
		$this->db->from('inventory_assemble_parents');
		$this->db->where('assemble_id', $assemble_id);
		$data = array();
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
		
	} //end getParentAssemblyData()
	
	/**
	 * Removes a child assembled item from its parent
	 * 
	 * @param [int] $assemble_id
	 * @param [int] $child_id
	 */
	public function removeChildItem($assemble_id, $child_id) {
		$this->db->where('assemble_id', $assemble_id);
		$this->db->where('child_item_id', $child_id);
		$this->db->delete('inventory_assemble_children');
		
		return null;
	} //end removeChildItem();

} //end Assemble_model();

?>