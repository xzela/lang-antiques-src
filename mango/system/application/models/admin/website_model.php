<?php
/**
 * This controlls some of the aspects of the website
 * 
 * More specifically, the menus.
 * 
 * This allows users to update, delete, and create new menu items
 * 
 * 
 * @author user
 *
 */
class Website_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Inserts a new top level menu element
	 * 
	 * @param [array] $fields = array of fields and data
	 * 
	 * @return [int] = menu_element_id;
	 */
	public function addMenuElement($fields) {
		$this->db->insert('menu_elements', $fields);
		return $this->db->insert_id(); //int
	} //end addMenuElement();
	
	/**
	 * Inserts a new sub menu element.
	 * 
	 * @param [array] $fields = array of fields and data
	 * 
	 * @return [int] = sub_menu_element_id
	 */
	public function addSubMenuElement($fields) {
		$this->db->insert('menu_sub_elements', $fields);
		return $this->db->insert_id(); //int
	} //end addSubMenuElement();
	
	/**
	 * Removes a Top Level Menu element
	 * 
	 * @param [int] $element_id = id of menu element
	 * @param [int] $menu_id =  id of parent menu
	 * 
	 * @return null 
	 */
	public function deleteMenuElement($element_id, $menu_id) {
		$this->db->where('element_id', $element_id);
		$this->db->where('element_menu', $menu_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('menu_elements');
		
		return null; //null
	} //end deleteMenuElement();
	
	/**
	 * Removes a Sub Menu Element based on
	 * parent menu element id and sub menu element id.
	 * 
	 * @param [int] $sub_element_id = sub menu element id
	 * @param [int] $parent_element_id = parent id;
	 * 
	 * @return null
	 */
	public function deleteSubMenuElement($sub_element_id, $parent_element_id) {
		$this->db->where('parent_element_id', $parent_element_id);
		$this->db->where('sub_element_id', $sub_element_id);
		$this->db->delete('menu_sub_elements');

		return null; //null
	} //end deleteSubMenuElement();
	
	/**
	 * Returns data for a specific menu element
	 *  
	 * @param [int] $element_id = menu element id
	 * 
	 * @return [array] = array of menu element data
	 */
	public function getMenuElementData($element_id) {
		$this->db->from('menu_elements');
		$this->db->where('element_id', $element_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//element_types: 1=major class, 2=modifier, 3=special
				//element type of 3 means specical 
				if($row['element_type'] == 3) {
					$row['element_name'] = $row['special_name'];
				}
				else {
					//based on element type, get the elements name, 
					//pulled either from major class or modifier
					$row['element_name'] = $this->getMenuElementName($row['element_type_id'], $row['element_type']);
				}
				$data = $row;
			}
		}
		return $data; //array
		
	} //end getMenuElementData();
	
	/**
	 * Returns all of the menu elements for a specific
	 * menu
	 * 
	 * menu_id: 1=main, 2=jewelry periods
	 * The menu ids are hard coded (bad) @TODO fix
	 * 
	 * @param [int] $menu = menu id (hard coded)
	 * 
	 * @return [array] = multi-din  array of menu elements
	 */
	public function getMenuElements($menu = 1) {
		$this->db->from('menu_elements');
		$this->db->where('element_menu', $menu); //0=inactive, 1=main, 2=secondary, 3=etc..	
		$this->db->order_by('element_seq');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($row['element_type'] == 3) {
					$row['element_name'] = $row['special_name'];
				}
				else {
					$row['element_name'] = $this->getMenuElementName($row['element_type_id'], $row['element_type']);
				}				
				
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getMenuElements();
	
	/**
	 * Returns the string name of a menu element (parent or sub)
	 * 
	 * @param [int] $element_type_id = element type id (major_class_id OR modifier_id)
	 * @param [int] $element_type = type of element (major_class OR modifier)
	 * 		element_types: 1=major class, 2=modifier, 3=special
	 * @return [string] = string name
	 * 
	 */
	public function getMenuElementName($element_type_id, $element_type) {
		$string = '';
		if($element_type == 1) { //major class
			$this->ci->load->model('admin/major_class_model');
			$mjr = $this->ci->major_class_model->getMajorClassData($element_type_id);
			$string = $mjr['major_class_name'];
			
		}
		else if($element_type = 2) { //modifier
			$this->ci->load->model('inventory/modifier_model');
			$mod = $this->ci->modifier_model->getModifierData($element_type_id);
			$string = $mod['modifier_name'];
		}
		return $string; //string
	} //end getMenuElementName();
	
	/**
	 * Returns the child menu elements for a specific parent
	 * 
	 * @param [int] $element_id = menu_element_id
	 * 
	 * @return [array] = multi-din  array of child menu elements
	 * 
	 */
	public function getMenuSubElements($element_id) {
		$this->db->from('menu_sub_elements');
		$this->db->where('parent_element_id', $element_id);
		$this->db->order_by('sub_element_seq');
		$query = $this->db->get();
		$data = array();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//tests the child element type
				//element_types: 1=major class, 2=modifier, 3=special
				if($row['sub_element_type'] == 3) {
					$row['sub_element_name'] = $row['sub_special_name'];
				}
				else {
					//returns the string name of element.
					$row['sub_element_name'] = $this->getMenuElementName($row['sub_element_type_id'], $row['sub_element_type']);
				}
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getMenuSubElements()
	
	/**
	 * Returns all of the active Top Level Modifier typed
	 * menu elements.
	 * 
	 * @return [array] = multi-din  array of top level elements
	 */
	public function getTopLevelModifiers() {
		$this->ci->load->model('inventory/modifier_model');
		$data = array();
		$this->db->select('menu_elements.*');
		$this->db->from('menu_elements');
		$this->db->join('modifiers', 'menu_elements.element_type_id = modifiers.modifier_id');
		$this->db->where('element_type', 2); //element_type of 2: modifier
		$this->db->order_by('modifier_name', 'ASC');
		$query = $this->db->get();
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$row['modifier_data'] = $this->ci->modifier_model->getModifierData($row['element_type_id']);
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getTopLevelModifiers();
	
	/**
	 * Returns all of the Sub level Modifier typed 
	 * menu elements
	 * 
	 * @return [array] = multi-din  array of sub level menu elements 
	 */
	public function getSubLevelModifiers() {
		$this->ci->load->model('inventory/modifier_model');
		$data = array();
		$this->db->distinct();
		$this->db->select('modifiers.*, menu_sub_elements.sub_element_type_id');
		$this->db->from('modifiers');
		$this->db->join('menu_sub_elements', 'modifiers.modifier_id = menu_sub_elements.sub_element_type_id');
		$this->db->where('sub_element_type', 2);
		$this->db->order_by('modifier_name', 'ASC');
		
		$query = $this->db->get();
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				//gets the modifier data of the specific menu element
				$row['modifier_data'] = $this->ci->modifier_model->getModifierData($row['sub_element_type_id']);
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getSubLevelModifiers();
	
	/**
	 * Updates a menu item 
	 * 
	 * @param [int] $element_id = menu element id
	 * @param [array] $fields = array of fields and data
	 * 
	 * @return null;
	 */
	public function updateMenuElement($element_id, $fields) {
		$this->db->where('element_id', $element_id);
		$this->db->update('menu_elements', $fields);
		
		return null;
	} //end updateMenuElement();
	
	/**
	 * Updates the order of specific parent menu 
	 * 
	 * @param [int] $menu_id = menu id
	 * @param [array] $order = array of menu element ids and their order
	 * 
	 * @return null;
	 */
	public function updateMenuElementSeq($menu_id, $order) {
		$data = array();
		$i = 1;
		//loop through each element, update order;
		foreach($order as $element_id) {
			$data['element_seq'] = $i;
			$this->db->where('element_menu = ', $menu_id);
			$this->db->where('element_id = ', $element_id);
			$this->db->update('menu_elements', $data);
			$i++;
		}
		return null;
	} //end updateMenyElementSeq();
	
	/**
	 * Updates the order of specific sub menu
	 * 
	 * @param [int] $parent_id = parent menu element id
	 * @param $order = array of sub menu element ids and their order
	 * 
	 * @return null;
	 */
	public function updateSubMenuElementSeq($parent_id, $order) {
		$data = array();
		$i = 1;
		foreach($order as $element_id) {
			$data['sub_element_seq'] = $i;
			$this->db->where('parent_element_id = ', $parent_id);
			$this->db->where('sub_element_id = ', $element_id);
			$this->db->update('menu_sub_elements', $data);
			$i++;
		}
		return null;
	} //end updateSubMenuElementSeq();
	
	/**
	 * Update the status of a sub menu element,
	 * 
	 * 
	 * @param $element_id
	 * @param $status
	 */
	public function updateSubMenuElementStatus($element_id, $status) {
		$data = array();
			$data['sub_element_status'] = $status;
		$this->db->where('sub_element_id', $element_id);
		$this->db->limit(1); //always limit one
		$this->db->update('menu_sub_elements', $data);
		
		return null; //null
	} //end updateSubMenuElementStatus();
	
} //end Website_model();
?>