<?php
/**
 * CLose Model,
 * 
 * Helps clone a record in the database.
 * There are limitations to cloning. See comments for more
 * details.
 * 
 * @author user
 *
 */
class Clone_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	
	}
	
	/**
	 * Clones a record from a database table. Requires the primary_key
	 * and table name to successfully clone a record.
	 * Will unset the primary_key to avaoid dups.
	 * 
	 * @param [int] $parent = parent_id (item_id)
	 * @param [int] $child = child_id (cloned_item_id)
	 * @param [string] $table = table name (any table with item_id in it)
	 * @param [string] $key = primary_kay for table 
	 * @param [bool] $image = is image table?
	 * 
	 * @return null
	 */
	public function cloneRecord($parent, $child, $table, $key, $image = false) {
		$this->db->from($table);
		$this->db->where('item_id', $parent);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//unset the primary_key,
				//If not unset, bad things happen... (dup records)...
				unset($row[$key]);
				if($image) {
					//print_r($_SERVER);
					$row['image_location'] = mysql_escape_string($row['image_location']);
					$path = $this->config->item('document_root');
					$main_path = str_replace($row['image_name'], '', $row['image_location']);
					
					$old_file = $path . $row['image_location'];
					$new_file = $path . $main_path . 'copy_' . $row['image_name'];
					//echo 'get_image: ' . $old_file . '<br />';
					//echo 'copy_image: ' . $new_file . '<br />';
					
					if(!copy($old_file, $new_file)) {
						echo 'bummer, no copied files';
					}
					$row['image_location'] = $main_path . 'copy_' . $row['image_name']; 
				}
				$row['item_id'] = $child;
				$this->db->insert($table, $row);
			}	
		}
		
		return null;
	} //end cloneRecord
	
	/**
	 * This updates a cloned item with it's parent information;
	 * Also sets default information for specific columns. We need 
	 * to unset two fields: item_id and item_number. If we do not
	 * unset these fields, they will cause problems. If item_id 
	 * is not unset it will cause a primary key expection, and 
	 * item_numer will confuse users.
 	 * 
	 * @param [int] $parent_data = parent item id
	 * @param [int] $child_id = cloned item id
	 * 
	 * @return null
	 */
	public function updateClone($parent_data, $child_id) {
		$number = $parent_data['item_number'];
		unset($parent_data['item_id']);
		unset($parent_data['item_number']);
		/*
		 * We need to update a few fields because this is a new item
		 * We should chnage the entry_date, add a note to the notes section
		 * and change the web_status;
		 * Cloned items should probably not be online by default
		 */
		$parent_data['item_notes'] .= ' This item was cloned from ' . $number;
		$parent_data['entry_date'] = date('Y-m-d');
		$parent_data['web_status'] = 0;
		
		$this->db->where('item_id', $child_id);
		$this->db->update('inventory', $parent_data);	

		return null;
	} //end updateClone();
	
	public function updateSuffix($clone_id) {
		$this->ci->load->model('inventory/inventory_model');
		$item = $this->ci->inventory_model->getInventoryData($clone_id);
		$num = explode("-", $item['item_number']);
		$suffix = $num[2];
		//update suffix
		$fields = array();
			$fields['suffix'] = $suffix;
		$this->db->where('item_id', $clone_id);
		$this->db->limit(1);
		$this->db->update('inventory', $fields);
	}
	/**
	 * Clones a diamond record, includes Color and Clarity
	 * 
	 * @param [int] $parent = parent item id
	 * @param [int] $child = cloned item id
	 * 
	 * @return null
	 */
	function cloneDiamonds($parent, $child) {
		$this->db->from('diamond_info');
		$this->db->where('item_id', $parent);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$old_d_id = $row['d_id'];
				unset($row['d_id']);
				$row['item_id'] = $child;
				$this->db->insert('diamond_info', $row);
				$new_d_id = $this->db->insert_id();
				
				//clone clarity and color for each diamond
				$this->cloneDiamondProperties('item_diamond_clarity', $parent, $old_d_id, $child, $new_d_id);
				$this->cloneDiamondProperties('item_diamond_color',$parent, $old_d_id, $child, $new_d_id);		
			}
		}
		
		return null;
	} //end cloneDiamonds
	
	/**
	 * Private function which should only be used in the cloneDiamonds method
	 * This clones the properties of a specific diamond into another diamond. 
	 * SHould only be used after cloning a diamond.
	 * 
	 * @param [string] $table = table name
	 * @param [int] $parent = parent item id;
	 * @param [int] $d_parent = parent diamond id
	 * @param [int] $child = new cloned item id;
	 * @param [int] $d_child = new cloned diamond id;
	 * 
	 * @return null
	 */
	private function cloneDiamondProperties($table, $parent, $d_parent, $child, $d_child) {
		$this->db->from($table);
		$this->db->where('diamond_id', $d_parent);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			if($table == 'item_diamond_clarity') {
				foreach($query->result_array() as $row) {
					$id = $row['item_clarity_id'];
					unset($row['item_clarity_id']);
					$row['diamond_id'] = $d_child;
					$row['item_id'] = $child;
					$this->db->insert('item_diamond_clarity', $row);
				}
			}
			else if ($table == 'item_diamond_color') {
				foreach($query->result_array() as $row) {
					$id = $row['item_color_id'];
					unset($row['item_color_id']);
					$row['diamond_id'] = $d_child;
					$row['item_id'] = $child;
					$this->db->insert('item_diamond_color', $row);
				}
			}
		}
		
	} //end CloneDiamondProperties();

} //end Clone_model();
?>